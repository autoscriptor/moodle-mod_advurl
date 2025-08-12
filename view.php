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
 * View page for Advanced URL activity.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/completionlib.php');

$id = optional_param('id', 0, PARAM_INT);       // Course module ID.
$n  = optional_param('n', 0, PARAM_INT);        // Instance ID.

if ($id) {
    $cm = get_coursemodule_from_id('advurl', $id, 0, false, MUST_EXIST);
    $course = get_course($cm->course);
    $advurl = $DB->get_record('advurl', ['id' => $cm->instance], '*', MUST_EXIST);
} else if ($n) {
    $advurl = $DB->get_record('advurl', ['id' => $n], '*', MUST_EXIST);
    $course = get_course($advurl->course);
    $cm = get_coursemodule_from_instance('advurl', $advurl->id, $course->id, false, MUST_EXIST);
} else {
    print_error('invalidaccessparameter');
}

require_course_login($course, true, $cm);
// Log the view event and mark activity viewed for completion.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Set up page.
$PAGE->set_url('/mod/advurl/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($advurl->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Do not print the introduction here. Moodle displays the description via the
// activity header/card on the view page automatically. Printing it again here
// would lead to duplication.

// Check if this is a YouTube video that should be embedded
$youtube_info = null;
$is_youtube_embedded = false;

if (!empty($advurl->detect_youtube)) {
    $youtube_info = mod_advurl_is_youtube_url($advurl->externalurl);
    if ($youtube_info) {
        $is_youtube_embedded = true;
    }
}

// Show external content notice if configured.
if (!empty($advurl->showleave)) {
    // Get site name with fallback
    $sitename = !empty($SITE->fullname) ? $SITE->fullname : get_string('institution', 'core');
    
    if ($is_youtube_embedded) {
        // Use YouTube-specific disclaimer
        $noticemsg = get_string('leavewarning_youtube', 'mod_advurl', $sitename);
    } else {
        // Use regular disclaimer
        $noticemsg = get_string('leavewarning_desc', 'mod_advurl', $sitename);
    }
    echo $OUTPUT->notification($noticemsg, 'info');
}

// Create button container for both actions
echo html_writer::start_div('advurl-buttons mb-3');

// Open in new tab button (always show as fallback)
$url = $advurl->externalurl;
$openbutton = html_writer::tag('a', get_string('openinnewtab', 'mod_advurl'), [
    'href' => $url,
    'target' => '_blank',
    'class' => 'btn btn-primary',
    'role' => 'button'
]);
echo html_writer::div($openbutton, 'advurl-openlink d-inline-block mr-2');

            // Report broken link button (if user has permission)
            if (has_capability('mod/advurl:reportbroken', $PAGE->context)) {
                $reporturl = new moodle_url('/mod/advurl/report.php', ['id' => $cm->id]);
                $reportbutton = html_writer::tag('a', get_string('reportbrokenlink', 'mod_advurl'), [
                    'href' => $reporturl,
                    'class' => 'btn btn-secondary',
                    'role' => 'button'
                ]);
                echo html_writer::div($reportbutton, 'advurl-reportlink d-inline-block');
            }

echo html_writer::end_div();

// Display content based on type
if ($is_youtube_embedded) {
    // Display embedded YouTube video
    echo html_writer::start_div('advurl-youtube-container mt-3');
    echo mod_advurl_generate_youtube_embed($youtube_info['video_id'], format_string($advurl->name));
    echo html_writer::end_div();
} else {
    // For non-YouTube or non-embedded content, no additional display needed
    // The "Open in New Tab" button is sufficient
}

// Add a horizontal rule and disclaimer at the end of the page.
echo html_writer::empty_tag('hr');
echo html_writer::tag('p', get_string('advurlcopyright', 'mod_advurl'), ['class' => 'small text-muted']);

echo $OUTPUT->footer();