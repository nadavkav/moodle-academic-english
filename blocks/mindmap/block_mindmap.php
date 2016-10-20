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

class block_mindmap extends block_base {
    public function init() {
        $this->title = get_string('mindmap', 'block_mindmap');
    }


    public function get_content() {

        global $USER, $CFG, $COURSE;

        $this->content         = new stdClass;

        $this->content->text = '<p align="center"><a href="'.$CFG->wwwroot.
                               '/blocks/mindmap/view.php?courseid='.$COURSE->id.'&blckid='.$this->instance->id.'">'.
                               "<img src=\"$CFG->wwwroot/blocks/mindmap/icons/mmc_logo.gif\" alt=\"MindMap Course Button\">"
                               .'</a></p>';

        $this->content->footer = '';
        return $this->content;
    }

    public function instance_allow_config() {

        return false;
    }

    public function has_config() {
        return false;
    }

    public function config_save($data) {

        foreach ($data as $name => $value) {
            set_config($name, $value);
        }
        return true;
    }


    public function cron() {
        global $CFG;
        foreach (glob($CFG->dataroot."/temp/block_mindmap/*.mm") as $filename) {
            unlink($filename);
        }
    }
}