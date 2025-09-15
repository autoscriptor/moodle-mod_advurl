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
 * Lists all instances of Advanced URL in a particular course.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/mod/advurl/lib.php');

$id = required_param('id', PARAM_INT); // Course id.

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);

require_course_login($course);

// Trigger course_module_instance_list_viewed event.
$params = [
    'context' => context_course::instance($course->id)
];

$event = \mod_advurl\event\course_module_instance_list_viewed::create($params);
$event->add_record_snapshot('course', $course);
$event->trigger();

$PAGE->set_url('/mod/advurl/index.php', ['id' => $id]);
$PAGE->set_title(get_string('modulenameplural', 'mod_advurl'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_advurl'));

// Get all advurl instances in this course.
if (!$cms = get_coursemodules_in_course('advurl', $course->id)) {
    echo $OUTPUT->notification(get_string('none'));    
    echo $OUTPUT->footer();
    exit;
}

// Display a table of instances.
$table = new html_table();
// Only show the name column to students; the external URL is hidden to prevent exposing the link.
$table->head  = [get_string('name')];
$table->align = ['left'];

foreach ($cms as $cm) {
    $advurl = $DB->get_record('advurl', ['id' => $cm->instance], '*', MUST_EXIST);
    $link = html_writer::link(new moodle_url('/mod/advurl/view.php', ['id' => $cm->id]),
        format_string($advurl->name));
    $table->data[] = [$link];
}

echo html_writer::table($table);
echo $OUTPUT->footer();