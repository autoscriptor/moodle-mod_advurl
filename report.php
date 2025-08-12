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
 * Handles submission of broken link reports for the Advanced URL plugin.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT); // Course module id.

$cm = get_coursemodule_from_id('advurl', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$advurl = $DB->get_record('advurl', ['id' => $cm->instance], '*', MUST_EXIST);

require_course_login($course, false, $cm);

// Check permission to report.
require_capability('mod/advurl:reportbroken', context_module::instance($cm->id));

// Insert a new record into advurl_reports.
$report = new stdClass();
$report->advurlid   = $advurl->id;
$report->courseid   = $course->id;
$report->cmid       = $cm->id;
$report->url        = $advurl->externalurl;
$report->reportedby = $USER->id;
$report->reporttime = time();
$report->status     = 'open';

$DB->insert_record('advurl_reports', $report);

// Compose and send an email notification about the broken link.
require_once($CFG->libdir . '/moodlelib.php');

// Get course settings for report email
$coursesettings = $DB->get_record('advurl_course_settings', ['courseid' => $course->id]);
if (empty($coursesettings->reportemail)) {
    // No email configured, redirect with error
    redirect($returnurl, 'No report email configured for this course.', 3);
}

// Build a recipient object for the configured email. Use a dummy ID so email_to_user will still send.
$recipient = new stdClass();
$recipient->id        = -1;
$recipient->email     = $coursesettings->reportemail;
$recipient->firstname = 'Course';
$recipient->lastname  = 'Administrator';
$recipient->maildisplay = 1;
$recipient->emailstop   = 0;
$recipient->deleted     = 0;
$recipient->suspended   = 0;
$recipient->confirmed   = 1;
$recipient->auth        = 'manual';
$recipient->lang        = current_language();

// Use Moodle's noreply user as the sender.
$from = \core_user::get_noreply_user();

// Get site name with fallback for email
$sitename = !empty($SITE->fullname) ? $SITE->fullname : get_string('institution', 'core');

// Subject and body for the email.
$subject = 'Broken link report: ' . format_string($advurl->name);
$body  = "A broken link has been reported in {$sitename} Moodle.\n\n";
$body .= "Course: {$course->fullname} (ID: {$course->id})\n";
$body .= "Activity: {$advurl->name}\n";
$body .= "External URL: {$advurl->externalurl}\n";
$body .= "Course module ID: {$cm->id}\n";
$body .= "Reported by: {$USER->firstname} {$USER->lastname} (ID: {$USER->id}, Email: {$USER->email})\n";
$body .= "Report time: " . userdate($report->reporttime) . "\n\n";
$body .= "Please check the link and update or remove it if necessary.";

// HTML version of the email body.
$bodyhtml = text_to_html($body, false, false, true);

// Send the email; ignore any errors.
email_to_user($recipient, $from, $subject, $body, $bodyhtml);

// Redirect back to the activity page with a thank‑you message.
$returnurl = new moodle_url('/mod/advurl/view.php', ['id' => $cm->id]);
redirect($returnurl, get_string('reportthankyou', 'mod_advurl'), 3);