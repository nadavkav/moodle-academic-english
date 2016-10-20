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
  * Mindmap Course - Block which displays course content as interactive,
  * personalized mindmap
  *
  * @package    block_mindmap
  * @copyright  2014 onwards Łukasz Sanokowski, Barbara Dębska
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */

require_once('../../config.php');
global $CFG, $USER, $COURSE, $DB, $PAGE;

require_once($CFG->libdir.'/weblib.php');
require_once($CFG->libdir.'/filelib.php');
require_once('lib.php');

$courseid = required_param('courseid', PARAM_INT);
$blockinstanceid = required_param('blckid', PARAM_INT);
$context = context_block::instance($blockinstanceid);

if (! $course = $DB->get_record('course', array("id" => $courseid))) {
    print_error(get_string('invalidcourse', 'block_mindmap', $courseid));
}

require_login($course);

require_capability('block/mindmap:view', $context);


$site = get_site();

    $PAGE->set_url('/blocks/mindmap/view.php', array('courseid' => $courseid, 'blckid' => $blockinstanceid));
    $PAGE->set_heading($site->fullname);
    $PAGE->set_title($course->shortname.': '.get_string('pluginname', 'block_mindmap'));
    $PAGE->set_pagelayout('frametop');
    $PAGE->navbar->add('<a href="'.$CFG->wwwroot.'/course/view.php?id='.$courseid.'"></a>
                        <a href="'.$CFG->wwwroot.'/blocks/mindmap/view.php?courseid='.$courseid.'&blckid='.
                        $blockinstanceid.'">Mindmap</a>');

    echo $OUTPUT->header();


$filename = substr(md5($COURSE->id.'_'.mt_rand()), 0, 14);
if (!file_exists("$CFG->dataroot/temp/block_mindmap/")) {
    mkdir("$CFG->dataroot/temp/block_mindmap/", 0700);
}
$pathlocal = "$CFG->dataroot/temp/block_mindmap/";
$url = "$CFG->wwwroot/blocks/mindmap/file.php?filename=$filename&courseid=$COURSE->id&blockinstanceid=$blockinstanceid";

block_mindmap_create_file($filename, $pathlocal, $context);

echo '<div id="flashcontent" style="height:700px">  </div>';
echo '<script type="text/javascript" src="freemindflashbrowser/flashobject.js"></script>';
require_once("freemindflashbrowser/display.php");

echo "<br><a href =\"".$url."\">".get_string('downloadmapfile', 'block_mindmap')."</a>";
add_to_log($course->id, "mindmap", "view", "blocks/timestat/index.php?id=$course->id", $course->id);
echo $OUTPUT->footer();