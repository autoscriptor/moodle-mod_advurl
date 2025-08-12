<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Dashboard for Advanced URL settings and reports management.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);
require_capability('mod/advurl:viewreports', $context);

$PAGE->set_url('/mod/advurl/dashboard.php', ['courseid' => $courseid]);
$PAGE->set_title(get_string('dashboard', 'mod_advurl'));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// Handle form submission
if ($data = data_submitted() && confirm_sesskey()) {
    $reportemail = optional_param('reportemail', '', PARAM_EMAIL);
    
    // Validate email if provided
    if (!empty($reportemail) && !filter_var($reportemail, FILTER_VALIDATE_EMAIL)) {
        $error = get_string('invalidemail', 'mod_advurl');
    } else {
        // Save or update settings
        $settings = $DB->get_record('advurl_course_settings', ['courseid' => $courseid]);
        
        if ($settings) {
            // Update existing record
            $settings->reportemail = $reportemail;
            $settings->timemodified = time();
            $DB->update_record('advurl_course_settings', $settings);
        } else {
            // Create new record
            $settings = new stdClass();
            $settings->courseid = $courseid;
            $settings->reportemail = $reportemail;
            $settings->timemodified = time();
            $DB->insert_record('advurl_course_settings', $settings);
        }
        
        $success = get_string('settingssaved', 'mod_advurl');
    }
}

// Handle report status updates
$action = optional_param('action', '', PARAM_ALPHA);
$reportid = optional_param('reportid', 0, PARAM_INT);

if ($action && $reportid && confirm_sesskey()) {
    $report = $DB->get_record('advurl_reports', ['id' => $reportid, 'courseid' => $courseid]);
    
    if ($report) {
        $report->status = '';
        $report->resolvedby = $USER->id;
        $report->resolvedtime = time();
        
        switch ($action) {
            case 'resolve':
                $report->status = 'resolved';
                break;
            case 'falsepositive':
                $report->status = 'false_positive';
                break;
            case 'reopen':
                $report->status = 'open';
                $report->resolvedby = null;
                $report->resolvedtime = null;
                break;
        }
        
        if ($report->status) {
            $DB->update_record('advurl_reports', $report);
            $success = get_string('status_updated', 'mod_advurl');
        }
    }
}

// Get current settings
$currentsettings = $DB->get_record('advurl_course_settings', ['courseid' => $courseid]);
$currentemail = $currentsettings ? $currentsettings->reportemail : '';

echo $OUTPUT->header();

// Display success/error messages
if (isset($success)) {
    echo $OUTPUT->notification($success, 'success');
}
if (isset($error)) {
    echo $OUTPUT->notification($error, 'error');
}

// Settings Section
echo $OUTPUT->heading(get_string('dashboard', 'mod_advurl'), 2);

echo html_writer::start_div('advurl-dashboard-settings');
echo html_writer::tag('h3', get_string('reportemail', 'mod_advurl'), ['class' => 'mb-3']);

// Settings form
echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'advurl-settings-form']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);

// Email field
$emailfield = html_writer::tag('input', '', [
    'type' => 'email',
    'name' => 'reportemail',
    'value' => s($currentemail),
    'class' => 'form-control',
    'placeholder' => 'Enter email address',
    'size' => '50'
]);

echo html_writer::start_div('form-group');
echo html_writer::tag('label', get_string('reportemail', 'mod_advurl'), ['for' => 'reportemail', 'class' => 'form-label']);
echo $emailfield;
echo html_writer::tag('small', get_string('reportemail_help', 'mod_advurl'), ['class' => 'form-text text-muted']);
echo html_writer::end_div();

// Save button
$savebutton = html_writer::tag('button', get_string('savesettings', 'mod_advurl'), [
    'type' => 'submit',
    'class' => 'btn btn-primary'
]);
echo html_writer::div($savebutton, 'form-group');

echo html_writer::end_tag('form');
echo html_writer::end_div();

// Reports Section
echo html_writer::start_div('advurl-dashboard-reports mt-4');
echo html_writer::tag('h3', get_string('reports', 'mod_advurl'), ['class' => 'mb-3']);

// Get reports for this course
$reports = $DB->get_records_sql("
    SELECT r.*, a.name as activity_name, u.firstname, u.lastname
    FROM {advurl_reports} r
    JOIN {advurl} a ON r.advurlid = a.id
    JOIN {user} u ON r.reportedby = u.id
    WHERE r.courseid = ?
    ORDER BY r.reporttime DESC
", [$courseid]);

if (empty($reports)) {
    echo html_writer::tag('p', get_string('no_reports', 'mod_advurl'), ['class' => 'text-muted']);
} else {
    // Create table
    $table = new html_table();
    $table->head = [
        get_string('activity', 'mod_advurl'),
        get_string('reportedurl', 'mod_advurl'),
        get_string('reportedby', 'mod_advurl'),
        get_string('reportdate', 'mod_advurl'),
        get_string('status', 'mod_advurl'),
        get_string('actions', 'mod_advurl')
    ];
    $table->align = ['left', 'left', 'left', 'left', 'center', 'center'];
    $table->size = ['20%', '25%', '15%', '15%', '10%', '15%'];

    foreach ($reports as $report) {
        // Status badge
        $statusclass = '';
        $statustext = '';
        switch ($report->status) {
            case 'open':
                $statusclass = 'badge badge-danger';
                $statustext = get_string('status_open', 'mod_advurl');
                break;
            case 'resolved':
                $statusclass = 'badge badge-success';
                $statustext = get_string('status_resolved', 'mod_advurl');
                break;
            case 'false_positive':
                $statusclass = 'badge badge-secondary';
                $statustext = get_string('status_false_positive', 'mod_advurl');
                break;
        }
        $statusbadge = html_writer::tag('span', $statustext, ['class' => $statusclass]);

        // Action buttons
        $actions = '';
        if ($report->status === 'open') {
            $resolveurl = new moodle_url('/mod/advurl/dashboard.php', [
                'courseid' => $courseid,
                'action' => 'resolve',
                'reportid' => $report->id,
                'sesskey' => sesskey()
            ]);
            $falsepositiveurl = new moodle_url('/mod/advurl/dashboard.php', [
                'courseid' => $courseid,
                'action' => 'falsepositive',
                'reportid' => $report->id,
                'sesskey' => sesskey()
            ]);
            
            $actions .= html_writer::link($resolveurl, get_string('mark_resolved', 'mod_advurl'), 
                ['class' => 'btn btn-sm btn-success mr-1']);
            $actions .= html_writer::link($falsepositiveurl, get_string('mark_false_positive', 'mod_advurl'), 
                ['class' => 'btn btn-sm btn-secondary']);
        } else {
            $reopenurl = new moodle_url('/mod/advurl/dashboard.php', [
                'courseid' => $courseid,
                'action' => 'reopen',
                'reportid' => $report->id,
                'sesskey' => sesskey()
            ]);
            $actions .= html_writer::link($reopenurl, get_string('reopen_report', 'mod_advurl'), 
                ['class' => 'btn btn-sm btn-warning']);
        }

        $table->data[] = [
            format_string($report->activity_name),
            html_writer::link($report->url, $report->url, ['target' => '_blank']),
            fullname($report),
            userdate($report->reporttime),
            $statusbadge,
            $actions
        ];
    }

    echo html_writer::table($table);
}

echo html_writer::end_div();

echo $OUTPUT->footer();
