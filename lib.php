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
 * Library of functions and constants for the Advanced URL module.
 *
 * This file contains general utility functions used by the module.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds an Advanced URL instance.
 *
 * @param stdClass $data
 * @param mod_advurl_mod_form $mform
 * @return int new instance id
 */
// For backward compatibility, also provide the old function name
if (!function_exists('advurl_add_instance')) {
    function advurl_add_instance($data, $mform = null) {
        return mod_advurl_add_instance($data, $mform);
    }
}

function mod_advurl_add_instance($data, $mform = null) {
    global $DB;
    $data->timemodified = time();
    // Convert checkbox values.
    $data->showleave = empty($data->showleave) ? 0 : 1;
    // Insert into database.
    $id = $DB->insert_record('advurl', $data);
    return $id;
}

/**
 * Updates an Advanced URL instance.
 *
 * @param stdClass $data
 * @param mod_advurl_mod_form $mform
 * @return bool success
 */
// For backward compatibility, also provide the old function name
if (!function_exists('advurl_update_instance')) {
    function advurl_update_instance($data, $mform = null) {
        return mod_advurl_update_instance($data, $mform);
    }
}

function mod_advurl_update_instance($data, $mform = null) {
    global $DB;
    $data->timemodified = time();
    $data->id = $data->instance;
    // Convert checkbox.
    $data->showleave = empty($data->showleave) ? 0 : 1;
    return $DB->update_record('advurl', $data);
}

/**
 * Deletes an Advanced URL instance.
 *
 * @param int $id
 * @return bool success
 */
// For backward compatibility, also provide the old function name
if (!function_exists('advurl_delete_instance')) {
    function advurl_delete_instance($id) {
        return mod_advurl_delete_instance($id);
    }
}

function mod_advurl_delete_instance($id) {
    global $DB;
    if (!$advurl = $DB->get_record('advurl', ['id' => $id])) {
        return false;
    }
    // Delete dependent broken link reports.
    $DB->delete_records('advurl_reports', ['advurlid' => $id]);
    // Delete the activity instance.
    $DB->delete_records('advurl', ['id' => $id]);
    return true;
}

/**
 * Returns a small object with summary information about what a
 * user sees when they click on the activity in the course page.
 *
 * @param cm_info $cm Course-module
 * @return cached_cm_info|null
 */
// For backward compatibility, also provide the old function name
if (!function_exists('advurl_get_coursemodule_info')) {
    function advurl_get_coursemodule_info($cm) {
        return mod_advurl_get_coursemodule_info($cm);
    }
}

function mod_advurl_get_coursemodule_info($cm) {
    global $DB;
    $info = new cached_cm_info();
    // Fetch the full advurl record.  We only load what we need to build the course page summary.
    $advurl = $DB->get_record('advurl', ['id' => $cm->instance], '*', MUST_EXIST);
    $info->name = format_string($advurl->name, true);

    // Only display the description on the course page if the course module setting requests it and an intro exists.
    if (!empty($cm->showdescription) && !empty($advurl->intro)) {
        // Use Moodle's helper to format the module introduction.  This takes care of contexts,
        // files and filters without incurring heavy processing or causing caching deadlocks.
        $info->content = format_module_intro('advurl', $advurl, $cm->id, false);
    } else {
        $info->content = '';
    }

    return $info;
}

/**
 * Indicates plugin support for various Moodle features.
 *
 * @param string $feature Feature constant
 * @return mixed True if supported, null if unknown
 */
// For backward compatibility, also provide the old function name
if (!function_exists('advurl_supports')) {
    function advurl_supports($feature) {
        return mod_advurl_supports($feature);
    }
}

function mod_advurl_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            // This module stores an introduction and uses standard intro elements.
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            // We provide a flag to display the description on the course page.
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            // Completion is marked when the user views the activity.
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            // No grading for this module.
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        default:
            return null;
    }
}

/**
 * Define standard attachment behaviour (none for URLs).
 *
 * @param int $contextid
 * @param array $args
 * @param bool $forcedownload
 * @return void
 */
function mod_advurl_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    // There are no files associated with this module.
    send_file_not_found();
}

/**
 * Extends the course navigation with Advanced URL dashboard link.
 *
 * @param global_navigation $navigation
 * @param navigation_node $advurlnode
 * @param context $context
 */
function mod_advurl_extend_navigation_course($navigation, $course, $context) {
    global $PAGE;
    
    // Only show for users with viewreports capability
    if (has_capability('mod/advurl:viewreports', $context)) {
        $dashboardurl = new moodle_url('/mod/advurl/dashboard.php', ['courseid' => $course->id]);
        $navigation->add(
            get_string('dashboard', 'mod_advurl'),
            $dashboardurl,
            navigation_node::TYPE_CUSTOM,
            null,
            'advurldashboard',
            new pix_icon('i/externallink', '')
        );
    }
}

// Icon mapping functions removed - using custom SVG icon instead

/**
 * Detect if a URL is a YouTube video URL.
 *
 * @param string $url The URL to check
 * @return array|false Array with video ID if YouTube, false otherwise
 */
function mod_advurl_is_youtube_url($url) {
    $patterns = [
        '/youtube\.com\/watch\?v=([^&]+)/',
        '/youtu\.be\/([^?]+)/',
        '/youtube\.com\/embed\/([^?]+)/',
        '/m\.youtube\.com\/watch\?v=([^&]+)/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return [
                'type' => 'youtube',
                'video_id' => $matches[1],
                'embed_url' => 'https://www.youtube.com/embed/' . $matches[1]
            ];
        }
    }
    
    return false;
}

/**
 * Generate YouTube embed HTML.
 *
 * @param string $video_id YouTube video ID
 * @param string $title Video title for accessibility
 * @return string HTML for YouTube embed
 */
function mod_advurl_generate_youtube_embed($video_id, $title = '') {
    // Use YouTube's responsive embed URL with parameters to eliminate letterboxing
    $embed_url = 'https://www.youtube.com/embed/' . $video_id . '?rel=0&modestbranding=1&showinfo=0';
    $title_attr = !empty($title) ? ' title="' . s($title) . '"' : '';
    
    // Add responsive CSS using inline method that works across Moodle versions
    global $PAGE;
    $PAGE->requires->js_init_code("
        var style = document.createElement('style');
        style.textContent = `
            .advurl-youtube-embed {
                position: relative;
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                background: #000;
                border-radius: 8px;
                overflow: hidden;
            }
            .advurl-youtube-embed iframe {
                display: block;
                width: 100%;
                height: auto;
                min-height: 400px;
                border: none;
                aspect-ratio: 16/9;
            }
            @media (min-width: 768px) {
                .advurl-youtube-embed {
                    max-width: 75%;
                }
            }
            @media (min-width: 1200px) {
                .advurl-youtube-embed {
                    max-width: 60%;
                }
            }
        `;
        document.head.appendChild(style);
    ");
    
    return html_writer::div(
        html_writer::tag('iframe', '', [
            'src' => $embed_url,
            'width' => '100%',
            'height' => '400',
            'frameborder' => '0',
            'allowfullscreen' => 'allowfullscreen',
            'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
            'loading' => 'lazy',
            'style' => 'border: none; aspect-ratio: 16/9;'
        ]),
        'advurl-youtube-embed'
    );
}