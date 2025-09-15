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
 * English language strings for the advanced URL plugin.
 *
 * @package   mod_advurl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Advanced URL';
$string['modulename'] = 'Advanced URL';
$string['modulenameplural'] = 'Advanced URLs';
$string['advurl:addinstance'] = 'Add a new Advanced URL';
$string['advurl:view'] = 'View Advanced URL';
$string['advurl:reportbroken'] = 'Report broken link';
$string['advurl:viewreports'] = 'View broken link reports';

$string['externalurl'] = 'External URL';
$string['externalurl_help'] = 'The URL of the external resource you want to link to.';
// Display method strings deprecated - always opens in new tab
$string['showleave'] = 'Show Disclaimer';
$string['showleave_help'] = 'If enabled, a disclaimer will be displayed to inform users that the subsequent content is provided by a third‑party website.';
$string['leavewarning'] = 'External content notice';
$string['leavewarning_desc'] = 'You are about to open an external website that is not maintained or controlled by {$a}. If the new page does not load or appears broken, please click "Report Broken Link" to let us know.';
$string['continue'] = 'Continue';
$string['reportbrokenlink'] = 'Report Broken Link';
$string['reportthankyou'] = 'Thank you. Your report has been submitted and our team has been notified.';
$string['brokenlinkdialog'] = 'The link appears to be broken?';
$string['brokenlinksubmit'] = 'Submit report';
$string['privacy:metadata'] = 'The Advanced URL plugin stores user reports of broken links.';

// Privacy metadata strings
$string['privacy:metadata:advurl_reports'] = 'Information about broken link reports submitted by users.';
$string['privacy:metadata:advurl_reports:reportedby'] = 'The ID of the user who reported the broken link.';
$string['privacy:metadata:advurl_reports:reporttime'] = 'The timestamp when the broken link was reported.';
$string['privacy:metadata:advurl_reports:status'] = 'The current status of the broken link report (open, resolved, or false positive).';
$string['openinnewtab'] = 'Open Link in a New Tab';
// Deprecated: Display description on course page – we now rely on Moodle’s built‑in course module setting.
// $string['displaydescription'] = 'Display description on course page';
// $string['displaydescription_help'] = 'If enabled, the description will appear on the course page underneath the activity name.';

// Disclaimer footer for the view page.
$string['advurlcopyright'] = 'Advanced URL tool is designed by Walid Alieldin. All rights Reserved walid.alieldin@gmail.com.';

// Dashboard strings
$string['dashboard'] = 'Advanced URL Dashboard';
$string['dashboard_help'] = 'Manage Advanced URL settings and view broken link reports for this course.';
$string['reportemail'] = 'Broken Link Report Email';
$string['reportemail_help'] = 'Email address that will receive broken link reports for all Advanced URL activities in this course. Leave empty to disable the "Report Broken Link" functionality. Only teachers and administrators can access this setting.';
$string['savesettings'] = 'Save Settings';
$string['settingssaved'] = 'Settings saved successfully.';
$string['invalidemail'] = 'Please enter a valid email address.';

// URL validation messages
$string['externalurl_required'] = 'Please enter a complete URL (e.g., https://www.example.com)';

// Reports table strings
$string['reports'] = 'Broken Link Reports';
$string['reports_help'] = 'View and manage broken link reports for this course.';
$string['activity'] = 'Activity';
$string['reportedurl'] = 'Reported URL';
$string['reportedby'] = 'Reported By';
$string['reportdate'] = 'Report Date';
$string['status'] = 'Status';
$string['actions'] = 'Actions';
$string['status_open'] = 'Open';
$string['status_resolved'] = 'Resolved';
$string['status_false_positive'] = 'False Positive';
$string['mark_resolved'] = 'Mark Resolved';
$string['mark_false_positive'] = 'Mark False Positive';
$string['reopen_report'] = 'Reopen Report';
$string['no_reports'] = 'No broken link reports found for this course.';
$string['status_updated'] = 'Report status updated successfully.';
$string['confirm_status_change'] = 'Are you sure you want to change the status of this report?';

// YouTube detection strings
$string['detect_youtube'] = 'Detect YouTube links';
$string['detect_youtube_help'] = 'If enabled, YouTube videos will be embedded directly on the page. Other links will open in a new tab as usual.';
$string['leavewarning_youtube'] = 'The YouTube video displayed below is from an external source and may not be maintained or controlled by {$a}. If the video does not load or play properly, you can click "Open in New Tab" to view it on YouTube, or click "Report Broken Link" to let us know.';
$string['youtube_not_found'] = 'YouTube video not found. Opening in new tab instead.';
$string['youtube_embed_failed'] = 'Unable to embed video. Opening in new tab instead.';