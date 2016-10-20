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
 * Badge overview page
 *
 * @package    core
 * @subpackage badges
 * @copyright  2012 onwards Totara Learning Solutions Ltd {@link http://www.totaralms.com/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Yuliya Bozhko <yuliya.bozhko@totaralms.com>
 */

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once($CFG->libdir . '/badgeslib.php');

$courseid = optional_param('courseid',5, PARAM_INT);

require_login();

if (empty($CFG->enablebadges)) {
    print_error('badgesdisabled', 'badges');
}

$currenturl = new moodle_url('/badges/total_overview.php', array('courseid' => $courseid));
$context = context_course::instance($courseid);

$course = get_course($courseid);

$PAGE->set_context($context);
$PAGE->set_url($currenturl);
$PAGE->set_heading($course->fullname);
$PAGE->set_title($course->fullname);
$PAGE->set_pagelayout('badges');
$PAGE->navbar->add($course->fullname);

require_capability('moodle/badges:viewbadges', $context);

echo $OUTPUT->header();

$courses = enrol_get_my_courses();
$courselist = array(); 
foreach ($courses as $c) {
    $curl = new moodle_url('/badges/total_overview.php', array('courseid' => $c->id));
    $courselist[$c->id] = '<a href="'.$curl.'" data-course-id="'.$c->id.'">'.$c->fullname.'</a>';
}
     
echo html_writer::start_div('container-fluid');
    echo html_writer::start_div('row-fluid');
        echo html_writer::start_div('span3 pull-right achievements__courses');
        echo html_writer::alist($courselist);
        echo html_writer::end_div();

        echo html_writer::start_div('span9 achievements__body');
        if ($badges = badges_get_user_badges($USER->id, $courseid)) {
            $output = $PAGE->get_renderer('core', 'badges');
            echo $output->print_badges_list($badges, $USER->id, true);
           // echo $output->print_badges_list_type($badges, $USER->id, true, false ,BADGE_TYPE_COURSE);
           // echo $output->print_badges_list_type($badges, $USER->id, true,fase,BADGE_TYPE_SITE);
        } else {
            // echo get_string('nothingtodisplay', 'block_badges');
            echo html_writer::start_div('achievements--no');
            echo html_writer::img(theme_enlight_theme_url().'/images/no-achievement.png');
            echo html_writer::tag('h3', 'לא צברת עדיין הישגים לקורס זה');
            echo html_writer::tag('a', 'לעמוד הקורס', array('href' => '/', 'class' => 'achievements__button'));
            echo html_writer::end_div();
        }

        echo html_writer::end_div();
    echo html_writer::end_div();
echo html_writer::end_div();

echo $OUTPUT->footer();
