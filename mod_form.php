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
 * @package     mod_poster
 * @copyright   2015 David Mudrak <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Defines the poster instance settings form
 */
class mod_poster_mod_form extends moodleform_mod {

    /**
     * Defines the fields of the form
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Start the general form section.
        $mform->addElement('header', 'general', get_string('general', 'core_form'));

        // Add the poster name field.
        $mform->addElement('text', 'name', get_string('postername', 'mod_poster'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', 'core', 255), 'maxlength', 255, 'client');

        // Add the instruction/description field.
        $this->add_intro_editor();

        // Add the show description at the view page field.
        $mform->addElement('advcheckbox', 'showdescriptionview', get_string('showdescriptionview', 'mod_poster'));
        $mform->addHelpButton('showdescriptionview', 'showdescriptionview', 'mod_poster');

        // Add standard elements.
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}