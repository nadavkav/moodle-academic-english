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
 * this file contains the selectrss block itself
 *
 * File         block_selectrss.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * block_selectrss
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * */
class block_selectrss extends block_base {

    /**
     * initializes the block
     */
    public function init() {
        $this->title = get_string('selectrss', 'block_selectrss');
    }

    /**
     * Are you going to allow multiple instances of each block?
     * If yes, then it is assumed that the block WILL USE per-instance configuration
     * @return boolean
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Subclasses should override this and return true if the
     * subclass block has a settings.php file.
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Is each block of this type going to have instance-specific configuration?
     * Normally, this setting is controlled by {@link instance_allow_multiple()}: if multiple
     * instances are allowed, then each will surely need its own configuration. However, in some
     * cases it may be necessary to provide instance configuration to blocks that do not want to
     * allow multiple instances. In that case, make this function return true.
     * I stress again that this makes a difference ONLY if {@link instance_allow_multiple()} returns false.
     * @return boolean
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Which page types this block may appear on.
     *
     * @return array page-type prefix => true/false.
     */
    public function applicable_formats() {
        return array(
            'my' => false,
            'site-index' => true,
            'course-view' => true
        );
    }

    /**
     * Compare function to make sure we can sort items by itemtime while displaying
     */
    private function cmp($a, $b)
    {
        return strcmp($a->itemtime, $b->itemtime);
    }

    /**
     * Parent class version of this function simply returns NULL
     * This should be implemented by the derived class to return
     * the content object.
     *
     * @return stdClass
     */
    public function get_content() {
        global $DB;

        if (empty($this->instance)) {
            return $this->content;
        }

        $maxitems = get_config('block_selectrss', 'num_items');
        $showpartialcontent = 0;

        if ($this->config) {
            $maxitems = $this->config->num_items;
            $showpartialcontent = isset($this->config->show_partial_item_content) ?
                    $this->config->show_partial_item_content : 0;
        }

        $results = $DB->get_records('block_selectrss', array('publish' => 1, 'blockid' => $this->instance->id),
                '', '*', 0, $maxitems);
        $items = $DB->count_records('block_selectrss', array('blockid' => $this->instance->id));
        $this->content = new stdClass;

        // The items are most likely in the wrong order. - Fixing this
        if (count($results) > 1)
        {
            usort($results, array($this, 'cmp'));
            $results = array_reverse($results, true);
        }

        $this->content->text = '<ul class="list no-overflow">';
        foreach ($results as $result) {
            $itemhtml = '<div><a href="' . $result->url . '" target="blank" title="'
                    . $result->title . '">' . $result->title . '</a></div>';
            if ($showpartialcontent && !empty($result->content)) {
                $formatoptions = new stdClass();
                $formatoptions->para = false;
                $description = format_text($result->content, FORMAT_HTML, $formatoptions, $this->page->course->id);
                $itemhtml .= html_writer::start_tag('div', array('class' => 'description'));
                $itemhtml .= break_up_long_words($description, 30) . '...';
                $itemhtml .= html_writer::end_tag('div');
            }

            $this->content->text .= '<li>' . $itemhtml . '</li>';
        }
        $this->content->text .= '</ul>';

        if (has_capability('block/selectrss:managefeeds', $this->context)) {
            if ($items > 0) {
                $view = new moodle_url('/blocks/selectrss/view/viewfeed.php', array('blockid' => $this->instance->id));
                $this->content->footer = '<a href="' . $view->out() . '" target="self">'
                    . get_string('manageitems', 'block_selectrss') . '</a>';
            }
        }

        return $this->content;
    }

    /**
     * This function is called on your subclass right after an instance is loaded
     * Use this function to act on instance data just after it's loaded and before anything else is done
     * For instance: if your block will have different title's depending on location (site, course, blog, etc)
     */
    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->custom_title)) {
                $this->title = get_string('pluginname', 'block_selectrss');
            } else {
                $this->title = $this->config->custom_title;
            }
        }
    }

}
