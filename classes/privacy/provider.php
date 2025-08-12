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

namespace mod_advurl\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider for the Advanced URL plugin.
 *
 * This plugin logs reports of broken links. The reports include the id of the user
 * making the report, the URL, and timestamps.
 *
 * @package    mod_advurl
 * @copyright  2025 Greystone College
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider {

    /**
     * Describe the personal data stored by the plugin.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table('advurl_reports', [
            'reportedby' => 'privacy:metadata:advurl_reports:reportedby',
            'reporttime' => 'privacy:metadata:advurl_reports:reporttime',
            'status' => 'privacy:metadata:advurl_reports:status',
        ], 'privacy:metadata:advurl_reports');
        return $collection;
    }

    /**
     * Get the list of contexts which contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist The list of contexts containing user information.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();
        $sql = "SELECT ctx.id
                  FROM {context} ctx
                  JOIN {advurl_reports} r ON r.cmid = ctx.instanceid
                 WHERE ctx.contextlevel = ? AND r.reportedby = ?";
        $params = [CONTEXT_MODULE, $userid];
        $contextlist->add_from_sql($sql, $params);
        return $contextlist;
    }

    /**
     * Export user data relating to the contexts specified.
     *
     * @param approved_contextlist $contextlist A list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }
            $reports = $DB->get_records('advurl_reports', ['reportedby' => $userid, 'cmid' => $context->instanceid]);
            if (!$reports) {
                continue;
            }
            $reportdata = [];
            foreach ($reports as $report) {
                $reportdata[] = [
                    'url' => $report->url,
                    'reporttime' => transform::datetime($report->reporttime),
                    'status' => $report->status,
                ];
            }
            writer::with_context($context)->export_data([], (object) ['reports' => $reportdata]);
        }
    }

    /**
     * Delete user information from the specified contexts.
     *
     * @param approved_contextlist $contextlist A list of contexts approved for deletion.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context->contextlevel == CONTEXT_MODULE) {
            $DB->delete_records('advurl_reports', ['cmid' => $context->instanceid]);
        }
    }

    /**
     * Delete user information for the given user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist A list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }
            $DB->delete_records('advurl_reports', ['cmid' => $context->instanceid, 'reportedby' => $userid]);
        }
    }
}