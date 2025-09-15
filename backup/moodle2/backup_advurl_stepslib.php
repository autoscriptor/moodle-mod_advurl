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
 * Advanced URL backup structure step
 *
 * @package    mod_advurl
 * @copyright  2025 Waleed Alieldin <waleed@greystonecollege.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Advanced URL backup structure step
 *
 * @package    mod_advurl
 * @copyright  2025 Waleed Alieldin <waleed@greystonecollege.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_advurl_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define structure
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $advurl = new backup_nested_element('advurl', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'externalurl', 'display', 
            'showleave', 'showdescription', 'detect_youtube', 'timemodified'));

        // Build the tree.
        $advurl->set_source_table('advurl', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        $advurl->annotate_ids('course', 'course');

        // Define file annotations.
        $advurl->annotate_files('mod_advurl', 'intro', null);

        // Define user data if userinfo is included.
        if ($userinfo) {
            // Define reports nested element.
            $reports = new backup_nested_element('reports');
            $report = new backup_nested_element('report', array('id'), array(
                'advurlid', 'courseid', 'cmid', 'url', 'reportedby', 'reporttime', 
                'status', 'resolvedby', 'resolvedtime'));

            $advurl->add_child($reports);
            $reports->add_child($report);

            $report->set_source_table('advurl_reports', array('advurlid' => backup::VAR_PARENTID));
            $report->annotate_ids('user', 'reportedby');
            $report->annotate_ids('user', 'resolvedby');
        }

        // Return the root element (advurl), wrapped into standard activity structure.
        return $this->prepare_activity_structure($advurl);
    }
}
