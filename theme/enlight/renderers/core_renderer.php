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

                    $imgurl = $OUTPUT->pix_url($value->pix.'_white');
                    $iurl = get_headers($imgurl, 1);

                    if (strpos( $iurl[0], "404" ) !== false) {
                        $imgurl = $OUTPUT->pix_url($value->pix);
                    }

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

}