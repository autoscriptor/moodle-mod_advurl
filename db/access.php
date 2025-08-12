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
 * Capability definitions for the advanced URL module.
 *
 * @package   mod_advurl
 * @category  access
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'mod/advurl:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities',
    ],
    'mod/advurl:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'guest'    => CAP_ALLOW,
            'student'  => CAP_ALLOW,
            'teacher'  => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'  => CAP_ALLOW,
        ],
    ],
    // Permission to submit a broken link report.
    'mod/advurl:reportbroken' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'student'  => CAP_ALLOW,
            'teacher'  => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'  => CAP_ALLOW,
        ],
    ],
    // Permission to view broken link reports within a course.
    'mod/advurl:viewreports' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'teacher'  => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'  => CAP_ALLOW,
        ],
    ],
];