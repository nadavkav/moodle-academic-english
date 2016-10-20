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
use \block_selectrss\util;

/**
 * block_selectrss_edit_form
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * */
class block_selectrss_edit_form extends block_edit_form {

    /**
     * Override this to create any form fields specific to this type of block.
     * @param object $mform the form being built.
     */
    protected function specific_definition($mform) {
        global $CFG;

        require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');

        $maxitems = get_config('block_selectrss', 'max_get_num_items');
        if ($maxitems === false) {
            $maxitems = 10;
        }

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_selectrss'));
        $mform->setType('config_custom_title', PARAM_TEXT);

        $temp = (explode("\n", get_config('block_selectrss', 'rss_feeds')));
        $rssfeeds = array();
        foreach ($temp as $feed) {
            $rssfeeds[trim($feed)] = trim($feed);
        }

        $select = $mform->addElement('select', 'config_rss_feeds', get_string('rss_feeds', 'block_selectrss'), $rssfeeds, '');
        $select->setMultiple(true);

        $mform->addElement('text', 'config_num_items', get_string('num_items', 'block_selectrss'));
        $mform->setType('config_num_items', PARAM_INT);

        $mform->addElement('advcheckbox', 'config_default_publish', get_string('default_publish', 'block_selectrss'),
                '', null, array(0, 1));
        $mform->setType('config_default_publish', PARAM_INT);
        $mform->setDefault('config_default_publish', 0);

        $mform->addElement('selectyesno', 'config_show_partial_item_content',
                get_string('clientshowpartialcontentlabel', 'block_selectrss'));
        $mform->setDefault('config_show_partial_item_content', 0);
    }

    /**
     * Return submitted data if properly submitted or returns NULL if validation fails or
     * if there is no submitted data.
     *
     * note: $slashed param removed
     *
     * @return object submitted data; NULL if not valid or not submitted or cancelled
     */
    public function get_data() {
        global $DB;

        if ($data = parent::get_data()) {
            $results = $DB->count_records('block_selectrss', array('blockid' => $this->block->instance->id));

            if (isset($data->config_rss_feeds) && !empty($data->config_rss_feeds)) {
                if (!$results) {
                    $this->add_feed_items($data->config_rss_feeds, $data->config_default_publish);
                }

                if (isset($this->block->config->rss_feeds) && $this->block->config->rss_feeds != $data->config_rss_feeds) {
                    util::remove_feeds($this->block->instance->id);
                    $this->add_feed_items($data->config_rss_feeds, $data->config_default_publish);
                }
            } else {
                util::remove_feeds($this->block->instance->id);
            }

            return $data;
        }
    }

    /**
     * Creates new array of the selected rss feeds
     *
     * @param array $selection the selection of rss feeds
     * @return array selected rss feed(s)
     */
    protected function get_feeds($selection = array()) {
        $configfeeds = array_map('trim', (explode("\n", get_config('block_selectrss', 'rss_feeds'))));
        $feeds = array_intersect($selection, $configfeeds);

        $rss = new moodle_simplepie;
        $rss->set_feed_url($feeds);
        $rss->init();

        return $rss;
    }

    /**
     * Insert all rss items from the selected rss feeds
     *
     * @param array $selection the selected rss feed(s)
     * @param int $defaultpublish publish by default? (0 or 1)
     * @return int last inserted id
     */
    protected function add_feed_items($selection, $defaultpublish = 0) {
        foreach ($this->get_feeds($selection)->get_items() as $key => $item) {
            $lastinsertid[] = util::add_feed_item($item, $defaultpublish, $this->block->instance->id);
        }
        return $lastinsertid;
    }

}
