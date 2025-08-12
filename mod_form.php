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
 * Form for creating and editing Advanced URL instances.
 *
 * @package   mod_advurl
 * @copyright 2025 Greystone College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_advurl_mod_form extends moodleform_mod {

    /**
     * Defines the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        // General section.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Name.
        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Introductory text and format.
        $this->standard_intro_elements();

        // External URL.
        $mform->addElement('url', 'externalurl', get_string('externalurl', 'mod_advurl'), ['size' => '64'], ['usefilepicker' => false]);
        $mform->addRule('externalurl', null, 'required', null, 'client');
        $mform->setType('externalurl', PARAM_URL);
        $mform->addHelpButton('externalurl', 'externalurl', 'mod_advurl');

        // Display method is deprecated - always opens in new tab
        // Keeping display field in database for backwards compatibility

        // YouTube detection checkbox.
        $mform->addElement('advcheckbox', 'detect_youtube', get_string('detect_youtube', 'mod_advurl'));
        $mform->setDefault('detect_youtube', 0);
        $mform->addHelpButton('detect_youtube', 'detect_youtube', 'mod_advurl');

        // Leaving notice checkbox.
        $mform->addElement('advcheckbox', 'showleave', get_string('showleave', 'mod_advurl'));
        $mform->setDefault('showleave', 1);
        $mform->addHelpButton('showleave', 'showleave', 'mod_advurl');

        // Note: We rely on Moodle's built‑in course module setting to show the description on the course page.

        // Standard course module settings.
        $this->standard_coursemodule_elements();

        // Standard buttons (save/cancel).
        $this->add_action_buttons();
    }

    /**
     * Data preprocessing for the form.
     *
     * @param array $defaultvalues
     */
    public function data_preprocessing(&$defaultvalues) {
        parent::data_preprocessing($defaultvalues);
        // Ensure our checkboxes are set properly.
        if (!isset($defaultvalues['showleave'])) {
            $defaultvalues['showleave'] = 1; // Default to checked
        }
        if (!isset($defaultvalues['detect_youtube'])) {
            $defaultvalues['detect_youtube'] = 0; // Default to unchecked
        }
    }

    /**
     * Add custom validation rules.
     *
     * @param array $data
     * @param array $files
     * @return array An array of errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        // Validate URL.
        if (!empty($data['externalurl']) && !filter_var($data['externalurl'], FILTER_VALIDATE_URL)) {
            $errors['externalurl'] = get_string('invalidurl', 'error');
        }
        return $errors;
    }
}