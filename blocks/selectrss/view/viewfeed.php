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
 * Script to let a user edit the properties of a particular RSS feed.
 *
 * File         block_selectrss.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');
require_once($CFG->libdir . '/formslib.php');

require_login();

if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$blockid = required_param('blockid', PARAM_INT);
$action = optional_param('action', '', PARAM_RAW);

$urlparams = array('blockid' => $blockid);

$blockcontext = \context_block::instance($blockid);
// Require capability!
require_capability('block/selectrss:managefeeds', $blockcontext);

// Load / set course context (in-context / navigation).
$coursecontext = $blockcontext->get_course_context(false);
$PAGE->set_context($coursecontext);
$PAGE->set_course($DB->get_record('course', array('id' => $coursecontext->instanceid)));

$PAGE->set_url('/blocks/selectrss/view/viewfeed.php', $urlparams);

$strviewfeed = get_string('viewfeed', 'block_selectrss');

$PAGE->set_title($strviewfeed);
$PAGE->set_heading($strviewfeed);
$PAGE->set_pagelayout('standard');

$PAGE->navbar->add(get_string('pluginname', 'block_selectrss') . ' ' . get_string('viewfeeds', 'block_selectrss'));

if (!empty($action)) {
    $itemid = required_param('itemid', PARAM_INT);
    switch ($action) {
        case 'publish':
            require_sesskey();
            $record = $DB->get_record('block_selectrss', array('id' => $itemid), '*', MUST_EXIST);
            $record->publish = 1;
            $DB->update_record('block_selectrss', $record);
            $SESSION->block_selectrss_msg = get_string('msg:published', 'block_selectrss');
            redirect($PAGE->url);
            break;
        case 'unpublish':
            require_sesskey();
            $record = $DB->get_record('block_selectrss', array('id' => $itemid), '*', MUST_EXIST);
            $record->publish = 0;
            $DB->update_record('block_selectrss', $record);
            $SESSION->block_selectrss_msg = get_string('msg:unpublished', 'block_selectrss');
            redirect($PAGE->url);
            break;
    }
}

echo $OUTPUT->header();
echo '<div class="block-selectrss-container">';

if (isset($SESSION->block_selectrss_msg)) {
    echo \block_selectrss\util::format_message($SESSION->block_selectrss_msg);
    unset($SESSION->block_selectrss_msg);
}

$table = new html_table();
$table->head = array(
    get_string('status', 'block_selectrss'),
    get_string('date', 'block_selectrss'),
    get_string('title', 'block_selectrss'),
    get_string('source', 'block_selectrss'),
    get_string('blockid', 'block_selectrss'));
$table->colclasses = array('leftalign name', 'leftalign id', 'leftalign description', 'leftalign size', 'centeralign source');
$table->id = 'selectrssfeeds';
$table->attributes['class'] = 'admintable generaltable';

$results = $DB->get_records('block_selectrss', array('blockid' => $blockid), 'publish DESC, itemtime DESC', '*', 0, 0);

foreach ($results as $item) {
    $params = array('blockid' => $blockid, 'itemid' => $item->id, 'sesskey' => sesskey());
    $purl = new moodle_url('/blocks/selectrss/view/viewfeed.php', $params);
    $status = (((bool)$item->publish) ? get_string('active', 'block_selectrss') : get_string('inactive', 'block_selectrss'));
    $action = (((bool)$item->publish) ? ('<a href="' . $purl->out(true, array('action' => 'unpublish')) . '" class="red">'
        . get_string('unpublish', 'block_selectrss') . '</a>') : '<a href="' . $purl->out(true, array('action' => 'publish'))
        . '" class="green">' . get_string('publish', 'block_selectrss') . '</a>');
    $action = ' (' . $action . ')';
    $item = array(
        $status . $action,
        $item->itemdate,
        '<a href="' . $item->url . '" target="_blank">' . $item->title . '</a>',
        ((strlen($item->url) > 40) ? substr($item->url, 0, 40) . '...' : $item->url),
        $item->blockid
    );
    $data[] = $row = new html_table_row($item);
}

$table->data = $data;

echo html_writer::table($table);

echo '</div>';
echo $OUTPUT->footer();