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
 * Label external API
 *
 * @package    mod_label
 * @category   external
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.3
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");
require dirname(__DIR__) . '/renderer.php';

/**
 * Label external functions
 *
 * @package    mod_label
 * @category   external
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.3
 */
class mod_poster_external extends external_api {

    /**
     * Describes the parameters for get_poster_blocks.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function get_poster_blocks_parameters() {
        return new external_function_parameters (
            array(
                'cmid' => new external_value(PARAM_INT, 'Course Module id'),
            )
        );
    }

    /**
     * Returns a list of labels in a provided list of courses.
     * If no list is provided all labels that the user can view will be returned.
     *
     * @param array $courseids course ids
     * @return array of warnings and labels
     * @since Moodle 3.3
     */
    public static function get_poster_blocks($cmid) {
        global $DB;
        
        $cm = get_coursemodule_from_id('poster', $cmid, 0, false, MUST_EXIST);
        $context = context_module::instance($cmid, MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
        $poster = $DB->get_record('poster', array('id' => $cm->instance), '*', MUST_EXIST);

        $page = new moodle_page();
        $page->set_url('/mod/poster/view.php', array('id' => $cm->id));
        $page->set_context($context);
        $page->set_cm($cm);
     
        require_login($course, true, $cm);
        require_capability('mod/poster:view', $page->context);
                
        $page->blocks->add_region('mod_poster-pre', true);
        $page->blocks->add_region('mod_poster-post', true);
        $page->blocks->load_blocks();
        $page->blocks->create_all_block_instances();
        
        //$renderer = $page->get_renderer('mod_poster');        
        $renderer = new mod_poster_renderer($page, RENDERER_TARGET_GENERAL);        
      
        $result = array(
            'cmid' => $cmid,
            //'blockshtml' => htmlspecialchars('<div id="test">TEST</div>')
            'blockshtml' => htmlspecialchars($renderer->render_postercontent($poster)),
            //'blockshtml' => print_r($page->blocks)
        );
        return $result;
    }

    /**
     * Describes the get_poster_blocks return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_poster_blocks_returns() {
        return new external_single_structure(
            array(
                'cmid' => new external_value(PARAM_INT, 'Course Module Id', true),
                'blockshtml' => new external_value(PARAM_TEXT, 'Blocks HTML Snippet', true)
            )
        );
    }
}
