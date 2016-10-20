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
 * Utility class implementation.
 *
 * File         util.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_selectrss;

/**
 * block_selectrss\util
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class util {

    /**
     * Clean feeds.
     */
    public static function clean_feeds() {
        global $DB;

        // Remove old records.
        $timestamp = time() - (int)get_config('block_selectrss', 'autocleanafter');
        $DB->delete_records_select('block_selectrss', 'timecreated < ?', array($timestamp));

        // If we have MORE than max_keep_items, remove those as well.
        $maxkeepitems = (int)get_config('block_selectrss', 'max_keep_items');
        if ($maxkeepitems > 0) {
            $blockids = $DB->get_fieldset_sql('SELECT blockid FROM {block_selectrss} GROUP BY blockid');
            foreach ($blockids as $blockid) {
                // We only clean out published items.
                $where = 'blockid = ? AND publish = ? ORDER BY publish DESC, itemtime DESC';
                $idlist = $DB->get_fieldset_select('block_selectrss', 'id', $where, array($blockid, 1));
                // If count exceeds max items to keep, remove them.
                if (count($idlist) > $maxkeepitems) {
                    $idstoremove = array_slice($idlist, $maxkeepitems);
                    $DB->delete_records_list('block_selectrss', 'id', $idstoremove);
                }
            }
        }
    }

    /**
     * synchronizes feeds
     */
    public static function synchronize_feeds() {
        global $DB, $CFG;
        require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');
        // ...
        $instances = $DB->get_records('block_instances', array('blockname' => 'selectrss'));
        $notifyadmin = false;
        foreach ($instances as $instance) {
            // Unencode config.
            $instance->configdata = unserialize(base64_decode($instance->configdata));
            // Rss feeds zit in rss_feeds.
            $rss = new \moodle_simplepie;
            // LOAD FEEDS.
            $rss->set_feed_url($instance->configdata->rss_feeds);
            $rss->init();

            $n = 0;
            // PROCESS FEEDS.
            foreach ($rss->get_items() as $key => $item) {
                // We'll only insert if the record does *not* exist yet.
                $record = $DB->get_record('block_selectrss', array('url' => $item->get_permalink()));
                if (!$record) {
                    $n++;
                    self::add_feed_item($item, $instance->configdata->default_publish, $instance->id);
                    if (!(bool)$instance->configdata->default_publish) {
                        $notifyadmin = true;
                    }
                }
            }
        }

        // If complete, notify ADMIN.
        if ($notifyadmin) {
            $supportuser = \core_user::get_support_user();
            $recipient = get_admin();
            $a = new \stdClass();
            $a->sitename = get_site()->fullname;
            $a->siteurl = $CFG->wwwroot;
            $a->from = fullname($supportuser);
            $a->fullname = fullname($recipient);
            $messagehtml = get_string('messagehtml', 'block_selectrss', $a);
            $messagetext = format_text_email($messagehtml, FORMAT_HTML);
            $subject = get_string('messagesubject', 'block_selectrss', $n);
            email_to_user($recipient, $supportuser, $subject, $messagetext, $messagehtml);
        }
    }

    /**
     * Removes all feed items, given a block id.
     *
     * @param int $blockid blockid or 0 to remove all.
     * @return void
     */
    public static function remove_feeds($blockid = 0) {
        global $DB;
        if ($blockid > 0) {
            $DB->delete_records('block_selectrss', array('blockid' => $blockid));
        } else {
            $DB->delete_records('block_selectrss');
        }
    }

    /**
     * Add a feed item to a block.
     *
     * @param \SimplePie_Item $item simple pie item
     * @param int $publish 0 means not published, 1 means published
     * @param int $blockid block instance id
     * @return void
     */
    public static function add_feed_item($item, $publish, $blockid) {
        global $DB;
        $record = new \stdClass();
        $record->url = $item->get_permalink();
        $record->title = $item->get_title();
        $record->content = $item->get_content();
        $record->publish = $publish;
        $record->itemdate = $item->get_date();
        $record->itemtime = $item->get_date('U');
        $record->blockid = $blockid;
        $record->exist = md5($record->url);
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;

        return $DB->insert_record('block_selectrss', $record, true);
    }

    /**
     * Format a self-removing message
     *
     * @param string $message
     */
    public static function format_message($message) {
        echo '<div id="block-selectrss-message">' . $message . '</div><script>'
              . 'setTimeout(function(){var el = document.getElementById(\'block-selectrss-message\');'
                . 'el.parentNode.removeChild(el);}, 3000);'
              . '</script>';
    }

}