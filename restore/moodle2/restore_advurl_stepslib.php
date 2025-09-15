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
 * Advanced URL restore structure step
 *
 * @package    mod_advurl
 * @copyright  2025 Waleed Alieldin <waleed@greystonecollege.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Advanced URL restore structure step
 *
 * @package    mod_advurl
 * @copyright  2025 Waleed Alieldin <waleed@greystonecollege.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_advurl_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define structure
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('advurl', '/activity/advurl');
        
        // Add user data paths if userinfo is included.
        if ($userinfo) {
            $paths[] = new restore_path_element('advurl_report', '/activity/advurl/reports/report');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process an Advanced URL restore.
     *
     * @param object $data The data in object form
     * @return void
     */
    protected function process_advurl($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the advurl record.
        $newitemid = $DB->insert_record('advurl', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Process an Advanced URL report restore.
     *
     * @param object $data The data in object form
     * @return void
     */
    protected function process_advurl_report($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->advurlid = $this->get_new_parentid('advurl');
        $data->courseid = $this->get_courseid();
        $data->cmid = $this->get_mappingid('course_module', $data->cmid);
        $data->reportedby = $this->get_mappingid('user', $data->reportedby);
        $data->resolvedby = $this->get_mappingid('user', $data->resolvedby);

        $data->reporttime = $this->apply_date_offset($data->reporttime);
        if ($data->resolvedtime) {
            $data->resolvedtime = $this->apply_date_offset($data->resolvedtime);
        }

        $newitemid = $DB->insert_record('advurl_reports', $data);
        $this->set_mapping('advurl_report', $oldid, $newitemid);
    }

    /**
     * Post-execute actions
     *
     * @return void
     */
    protected function after_execute() {
        // Add advurl related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_advurl', 'intro', null);
    }
}
