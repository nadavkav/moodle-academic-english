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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_enlight
 * @copyright  2015 Nephzat Dev Team, www.nephzat.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_enlight_core_renderer extends theme_bootstrapbase_core_renderer {

    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG, $OUTPUT;

        $signup = '';

        if (($CFG->registerauth == 'email') || !empty($CFG->registerauth)) {
            $signup .= '<li><a href="'.new moodle_url("/login/signup.php").'">'.
            get_string('signup', 'theme_enlight').'</a></li>'."\n";
        }

        if (!isloggedin()) {
            return '<li><a href="'.new moodle_url("/login/index.php").'" title="'.get_string('login').'">'.
            get_string('login').'<span class="caretup"></span></a></li>'.$signup;
        }

        if (isguestuser()) {
            return '<li><a href="'.new moodle_url("/login/index.php").'" title="'.get_string('loggedinasguest').'">'.
            get_string('login').' ('.get_string('guest').') '.'<span class="caretup"></span></a></li>'.$signup;
        }

        if ($CFG->branch > "27") {
            require_once($CFG->dirroot . '/user/lib.php');

            if (is_null($user)) {
                $user = $USER;
            }

            // Get some navigation opts.
            $opts = user_get_user_navigation_info($user, $this->page, $this->page->course);
            $usertextcontents = $opts->metadata['userfullname'];

            $listr = '';
            foreach ($opts->navitems as $key => $value) {
                if ($value->itemtype == "link") {
                    $pix = null;
                    if (isset($value->pix) && !empty($value->pix)) {
                        $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                    } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                        $value->title = html_writer::img(
                            $value->imgsrc,
                            $value->title,
                            array('class' => 'iconsmall')
                        ) . $value->title;
                    }

                    // $imgurl = $OUTPUT->pix_url($value->pix.'_white');
                    $imgurl = $OUTPUT->pix_url($value->pix.'_gray');
/*                    $iurl = get_headers($imgurl, 1);

                    if (strpos( $iurl[0], "404" ) !== false) {
                        $imgurl = $OUTPUT->pix_url($value->pix);
                    }*/

                    $imgsrc = "<img src='$imgurl'>";

                    $listr .= '<li><a href="'.$value->url.'">'.$imgsrc.$value->title.'</a></li>';
                }
            }
            $uname = $opts->metadata['userfullname'];

            $content = '<li class="dropdown">
			<a class="dropdown-toggle"
			data-toggle="dropdown"
			href="#">
			'.$uname.'
			<i class="fa fa-chevron-down"></i><span class="caretup"></span>
			</a>
			<ul class="dropdown-menu">
			'.$listr.'
			</ul>
			</li>';

        } else {
            $uname = fullname($USER, true);
            $dlink = new moodle_url("/my");
            $plink = new moodle_url("/user/profile.php", array("id" => $USER->id));
            $lo = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));

            $content = '<li class="dropdown">
			<a class="dropdown-toggle"
			data-toggle="dropdown"
			href="#">
			'.$uname.'
			<i class="fa fa-chevron-down"></i><span class="caretup"></span>
			</a>
			<ul class="dropdown-menu">
			<li><a href="'.$dlink.'">Dashboard</a></li>
			<li><a href="'.$plink.'">Profile</a></li>
			<li><a href="'.$lo.'">Logout</a></li>
			</ul>
			</li>';

        }
        return $content;
    }

    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0 ) {
        static $submenucount = 0;

        if ($menunode->has_children()) {

            if ($level == 1) {
                $dropdowntype = 'dropdown';
            } else {
                $dropdowntype = 'dropdown-submenu';
            }

            $content = html_writer::start_tag('li', array('class' => $dropdowntype));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }
            $linkattributes = array(
                'href' => $url,
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'title' => $menunode->get_title(),
            );
            $content .= html_writer::start_tag('a', $linkattributes);
            $content .= $menunode->get_text();
            if ($level == 1) {
                $content .= '<i class="fa fa-chevron-down"></i>';
            }
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem.
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()));
        }
        return $content;
    }  
    
    

    /**
     * Outputs the courses menu
     * @return custom_menu object
     */
    public function custom_menu_courses() {
        global $CFG;

        $coursemenu = new custom_menu();

        //$hasdisplaymycourses = $this->get_setting('displaymycourses');
        if (isloggedin() && !isguestuser()) {
            $mycoursetitle = 'module';//= $this->get_setting('mycoursetitle');
            if ($mycoursetitle == 'module') {
                $branchtitle = get_string('mymodules', 'theme_enlight');
            } else if ($mycoursetitle == 'unit') {
                $branchtitle = get_string('myunits', 'theme_enlight');
            } else if ($mycoursetitle == 'class') {
                $branchtitle = get_string('myclasses', 'theme_enlight');
            } else {
                $branchtitle = get_string('mycourses', 'theme_enlight');
            }
            //$branchlabel = '<i class="fa fa-briefcase"></i>' . $branchtitle;
            $branchlabel = $branchtitle;
            $branchurl = new moodle_url('');
            $branchsort = 200;

            $branch = $coursemenu->add($branchlabel, $branchurl, $branchtitle, $branchsort);

            $hometext = get_string('myhome');
            $homelabel = html_writer::tag('i', '', array('class' => 'fa fa-home')).html_writer::tag('span', ' '.$hometext);
            $branch->add($homelabel, new moodle_url('/my/index.php'), $hometext);

            // Get 'My courses' sort preference from admin config.
            if (!$sortorder = $CFG->navsortmycoursessort) {
                $sortorder = 'sortorder';
            }

            // Retrieve courses and add them to the menu when they are visible
            $numcourses = 0;
            if ($courses = enrol_get_my_courses(NULL, $sortorder . ' ASC')) {
                foreach ($courses as $course) {
                    if ($course->visible) {  // hide courses beginning with * form students  hanna 29/7/15
                        if (has_capability('moodle/course:update', context_system::instance()) or (!(mb_substr($course->fullname,0,1) == "*") ) ) {
                            $branch->add('<i class="fa fa-graduation-cap"></i>' . format_string($course->fullname), new moodle_url('/course/view.php?id=' . $course->id), format_string($course->shortname));
                            $numcourses += 1;
                        }  // end if has capability or ! *
                    } else if (has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                        $branchtitle = format_string($course->shortname);
                        $branchlabel = '<span class="dimmed_text"><i class="fa fa-eye-slash"></i>' . format_string($course->fullname) . '</span>';
                        $branchurl = new moodle_url('/course/view.php', array('id' =>$course->id));
                        $branch->add($branchlabel, $branchurl, $branchtitle);
                        $numcourses += 1;
                    }
                }
            }
            if ($numcourses == 0 || empty($courses)) {
                $noenrolments = get_string('noenrolments', 'theme_essential');
                $branch->add('<em>' . $noenrolments . '</em>', new moodle_url('#'), $noenrolments);
            }
        }

        $content = '<ul class="nav">';
        foreach ($coursemenu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
        return $content.'</ul>';
    }

}
