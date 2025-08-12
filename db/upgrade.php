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
 * Upgrade script for the Advanced URL module.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Executes upgrades between plugin versions.
 *
 * @param int $oldversion
 * @return bool always true
 */
function xmldb_advurl_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Automatically generated Moodle v3.11.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2025072801) {
        // Add new field showdescription to the advurl table.
        $table = new xmldb_table('advurl');
        $field = new xmldb_field('showdescription', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'showleave');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Bump the version to record the upgrade.
        upgrade_mod_savepoint(true, 2025072801, 'advurl');
    }

    if ($oldversion < 2025072802) {
        // No structural changes required in this upgrade, but bump the version to record the new code.
        upgrade_mod_savepoint(true, 2025072802, 'advurl');
    }

    if ($oldversion < 2025072803) {
        // No DB change, just bump version for code updates.
        upgrade_mod_savepoint(true, 2025072803, 'advurl');
    }

    if ($oldversion < 2025072804) {
        // No structural change needed; bump for code updates (supports function and improved course page display).
        upgrade_mod_savepoint(true, 2025072804, 'advurl');
    }

    if ($oldversion < 2025072805) {
        // No DB changes; bump for disclaimer label update and email notification.
        upgrade_mod_savepoint(true, 2025072805, 'advurl');
    }

    if ($oldversion < 2025072806) {
        // Remove iframe embedding, update disclaimer, and change link to button.
        // Display method deprecated but database field kept for backwards compatibility.
        upgrade_mod_savepoint(true, 2025072806, 'advurl');
    }

    if ($oldversion < 2025072807) {
        // Add course settings table and dashboard functionality.
        // Create advurl_course_settings table for course-level email configuration.
        $table = new xmldb_table('advurl_course_settings');
        if (!$dbman->table_exists($table)) {
            // Create the table structure
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('reportemail', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            
            // Add keys and indexes (avoid naming conflicts)
            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $table->add_key('courseid_fk', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
            // Note: Foreign key already ensures uniqueness, no need for separate unique index
            
            $dbman->create_table($table);
        }
        upgrade_mod_savepoint(true, 2025072807, 'advurl');
    }

    if ($oldversion < 2025072808) {
        // Ensure the course settings table exists (fix for missing table creation).
        $table = new xmldb_table('advurl_course_settings');
        if (!$dbman->table_exists($table)) {
            // Create the table structure
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('reportemail', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            
            // Add keys and indexes (avoid naming conflicts)
            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $table->add_key('courseid_fk', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
            // Note: Foreign key already ensures uniqueness, no need for separate unique index
            
            $dbman->create_table($table);
        }
        upgrade_mod_savepoint(true, 2025072808, 'advurl');
    }

    if ($oldversion < 2025072809) {
        // Final fix: Remove unique index to avoid collision with foreign key.
        // The foreign key already ensures one record per course.
        upgrade_mod_savepoint(true, 2025072809, 'advurl');
    }

    if ($oldversion < 2025072810) {
        // Make disclaimer dynamic using site name instead of hardcoded institution name.
        upgrade_mod_savepoint(true, 2025072810, 'advurl');
    }

    if ($oldversion < 2025072811) {
        // Switch to using Moodle's built-in external link icon.
        upgrade_mod_savepoint(true, 2025072811, 'advurl');
    }

    if ($oldversion < 2025072812) {
        // Fix: Ensure pix directory exists for plugin validation.
        upgrade_mod_savepoint(true, 2025072812, 'advurl');
    }

    if ($oldversion < 2025072813) {
        // Add custom SVG icon for Advanced URL activity.
        upgrade_mod_savepoint(true, 2025072813, 'advurl');
    }

    if ($oldversion < 2025072814) {
        // Add broken link reports table to dashboard with status management.
        upgrade_mod_savepoint(true, 2025072814, 'advurl');
    }

    if ($oldversion < 2025072815) {
        // Add YouTube detection and embedding functionality.
        $table = new xmldb_table('advurl');
        $field = new xmldb_field('detect_youtube', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'showdescription');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2025072815, 'advurl');
    }

    if ($oldversion < 2025072900) {
        // Version 1.0.0 stable release.
        upgrade_mod_savepoint(true, 2025072900, 'advurl');
    }

    if ($oldversion < 2025072901) {
        // Fix function names to comply with frankenstyle coding standards.
        upgrade_mod_savepoint(true, 2025072901, 'advurl');
    }

    return true;
}