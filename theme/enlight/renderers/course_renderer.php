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
* @package    theme_enlight
* @copyright  2015 Nephzat Dev Team , nephzat.com
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
*/

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . "/course/renderer.php");

class theme_enlight_core_course_renderer extends core_course_renderer {

    public function new_courses() {
        global $CFG, $OUTPUT;

        $newcontent = '';
        $rcourseids = array();

        if ($ccc = get_courses('all', 'c.id DESC,c.sortorder ASC LIMIT 0,24', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $rcourseids[] = $cc->id;
            }
        }

        if (empty($rcourseids)) {
            return false;
        }

        $ncourseids = array_chunk($rcourseids, 6);

        $totalpcourse = count($ncourseids);
        $allcourse = new moodle_url('/course/index.php');

        $newheader = '<div class="frontpage-custom-blocks new-courses-block" id="New-Courses">
        <div class="bgtrans-overlay"><div>&nbsp;</div></div>
        <div class="container-fluid">
        <div class="titlebar">
        <h2 class="pull-left"><i class="fa fa-book"></i>'.get_string('newcourses', 'theme_enlight').'<a href="'.$allcourse.'">'
        .get_string('seeallcourses', 'theme_enlight').'</a></h2>
        <div class="pagenav slider-nav pull-right">
        <button class="slick-prev nav-item previous" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <button class="slick-next nav-item next" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div>
        <div class="row-fluid new_courses" data-crow="'.$totalpcourse.'">';

        $newfooter = '</div>
        </div>
        </div>';

        if (!empty($ncourseids)) {
            foreach ($ncourseids as $courseids) {
                $rowcontent = '<div>';
                $cnt = 0;
                foreach ($courseids as $courseid) {
                    $cnt++;
                    $course = get_course($courseid);
                    $noimgurl = $OUTPUT->pix_url('no-image', 'theme');

                    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                    if ($course instanceof stdClass) {
                        require_once($CFG->libdir. '/coursecatlib.php');
                        $course = new course_in_list($course);
                    }
                    $imgurl = '';

                    $summary = theme_enlight_strip_html_tags($course->summary);
                    $summary = theme_enlight_course_trim_char($summary, 75);

                    $context = context_course::instance($course->id);
                    $nostudents = count_role_users(5, $context);

                    foreach ($course->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                            '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                        if (!$isimage) {
                            $imgurl = $noimgurl;
                        }
                    }

                    if (empty($imgurl)) {
                        $imgurl = $noimgurl;
                    }

                    $coursehtml = '<div class="span2">
                    <div class="wrapper">
                    <div class="thumb"><a href="'.$courseurl.'"><img src="'.$imgurl.'" width="180" height="180" alt="'
                    .$course->fullname.'"></a></div>
                    <div class="info">
                    <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                    <p>'.$summary.'</p>
                    </div>
                    </div>
                    </div>';

                    $rowcontent .= $coursehtml;
                }

                $rowcontent .= '</div>';
                $newcontent .= $rowcontent;
            }
        }

        $newcourses = $newheader.$newcontent.$newfooter;
        /* Display the new courses in Home page */
        echo $newcourses;

    }

    public function popular_courses() {
        global $CFG, $OUTPUT;

        $popularcontent = '';

        $pcourses = $this->course_insights_home(1);    

        if (empty($pcourses)) {
            return false;
        }

        $astu = array();
        foreach ($pcourses as $pcourse) {
            $rcourseids[] = $pcourse['cid'];
            $astu[$pcourse['cid']] = $pcourse['students'];
        }

        $pcourseids = array_chunk($rcourseids, 6);
        $totalpcourse = count($pcourseids);
        $popularheader = '<div class="frontpage-custom-blocks popular-courses-block" id="Popular-Courses">
        <div class="bgtrans-overlay"><div>&nbsp;</div></div>
        <div class="container-fluid">
        <div class="titlebar">
        <h2 class="pull-left"><i class="fa fa-star"></i>'
        .get_string('popularcourses', 'theme_enlight').'</a></h2>
        <div class="pagenav slider-nav pull-right">
        <button class="slick-prev nav-item previous" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <button class="slick-next nav-item next" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div>
        <div class="row-fluid popular_courses" data-crow="'.$totalpcourse.'">';

        $popularfooter = '</div>
        </div>
        </div>';

        if (!empty($pcourseids)) {
            foreach ($pcourseids as $courseids) {
                $rowcontent = '<div>';
                $cnt = 0;
                foreach ($courseids as $courseid) {
                    $cnt++;
                    $course = get_course($courseid);
                    $noimgurl = $OUTPUT->pix_url('no-image', 'theme');

                    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                    if ($course instanceof stdClass) {
                        require_once($CFG->libdir. '/coursecatlib.php');
                        $course = new course_in_list($course);
                    }

                    $imgurl = '';

                    $summary = theme_enlight_strip_html_tags($course->summary);
                    $summary = theme_enlight_course_trim_char($summary, 75);

                    $context = context_course::instance($course->id);
                    $nostudents = count_role_users(5, $context);

                    foreach ($course->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                            '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                        if (!$isimage) {
                            $imgurl = $noimgurl;
                        }
                    }

                    if (empty($imgurl)) {
                        $imgurl = $noimgurl;
                    }

                    $coursehtml = ' <div class="span2">
                    <div class="wrapper">
                    <div class="thumb"><a href="'.$courseurl.'"><img src="'.
                    $imgurl.'" width="180" height="180" alt="'.$course->fullname.'"></a></div>
                    <div class="info">
                    <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                    <p>'.$summary.'</p>
                    </div>
                    </div>
                    </div>';

                    $rowcontent .= $coursehtml;
                }

                $rowcontent .= '</div>';
                $popularcontent .= $rowcontent;
            }
        }

        $popularcourses = $popularheader.$popularcontent.$popularfooter;
        /* Display the popular courses in Home page */
        echo $popularcourses;
    }

    public function list_categories() {
        global $CFG, $PAGE, $OUTPUT;
        $catcontent = '';
        require_once($CFG->libdir . '/coursecatlib.php');
        $rcoursecats = coursecat::make_categories_list();
        $noimgurl = $OUTPUT->pix_url('no-image', 'theme');
        $acoursecats = array_chunk($rcoursecats, 6, true);

        $tcount = count($acoursecats);

        $catheader = '<div class="frontpage-custom-blocks categories-block" id="Listcategories">
        <div class="container-fluid">
        <div class="titlebar">
        <h2 class="pull-left"><i class="fa fa-list"></i>'.get_string('categories').'</a></h2>
        <div class="pagenav slider-nav pull-right">
        <button class="slick-prev nav-item previous" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <button class="slick-next nav-item next" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div>
        <div class="row-fluid list_categories" data-crow="'.$tcount.'">';

        $catfooter = '</div>
        </div>
        </div>';

        if (!empty($acoursecats)) {
            foreach ($acoursecats as $coursecats) {
                $rowcontent = '<div>';

                foreach ($coursecats as $key => $value) {
                    $imgname = 'categoryimg'.$key;
                    $imgurl = $PAGE->theme->setting_file_url($imgname, $imgname);
                    $imgurl = empty($imgurl) ? $noimgurl : $imgurl;
                    $caturl = new moodle_url('/course/index.php', array('categoryid' => $key));
                    $cathtml = '<div class="span2">
                    <div class="wrapper">
                    <div class="thumb"><a href="'.$caturl.'"><img src="'.$imgurl.'" width="180" height="180" alt="'.$value.'"></a></div>
                    <div class="info">
                    <h6 class="title-text"><a href="'.$caturl.'">'.$value.'</a></h6>
                    </div>
                    </div>
                    </div>';

                    $rowcontent .= $cathtml;
                }

                $rowcontent .= '</div>';
                $catcontent .= $rowcontent;
            }
        }

        $catcourses = $catheader.$catcontent.$catfooter;
        echo $catcourses;
    }

    public function course_insights_home ($f = 0) {
        $courses = 0;
        $teachers = 0;
        $students = 0;
        $acourses = array();
        $astu = array();
        $atea = array();

        /* Get all courses */
        if ($ccc = get_courses('all', 'c.sortorder ASC', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $courses++;

                $context = context_course::instance($cc->id);

                /* count no of teachers */
                $noteachers = count_role_users(3, $context);
                $teachers = $teachers + $noteachers;
                /* count no of students */
                $nostudents = count_role_users(5, $context);
                $students = $students + $nostudents;
                $acourses[] = array('cid' => $cc->id, 'students' => $nostudents,
                    'teachers' => $noteachers
                );
                $astu[] = $nostudents;
                $atea[] = $noteachers;
            }
        }

        if ($f == "1") {
            array_multisort($astu, SORT_DESC, $atea, SORT_DESC, $acourses);
            $acourses = array_slice($acourses, 0, 24, true);
            return $acourses;
        }

        return compact('courses', 'teachers', 'students');
    }

    public function course_category($category) {
        global $CFG;

        $clayout = get_config('theme_enlight', 'courselayout');

        if ($clayout == "layout1") {
            $cpage = $this->custom_course_page($category);
            return $cpage;
        } else if ($clayout == "layout2") {
            $cpage = $this->custom_course_page2($category);
            return $cpage;
        } else {
            return parent::course_category($category);
        }

    }

    private function custom_course_page2($category) {
        global $OUTPUT, $PAGE, $CFG, $DB, $USER;
        require_once($CFG->libdir. '/coursecatlib.php');

        $output = '';

        $page = optional_param('page', '0', PARAM_INT);
        $categoryid = optional_param('categoryid', null, PARAM_INT);
        $ctype = optional_param('ctype', null, PARAM_TEXT);

        // Course page title.
        require_once($CFG->libdir. '/coursecatlib.php');
        $coursecat = coursecat::get(is_object($category) ? $category->id : $category);
        $site = get_site();

        if (!$coursecat->id) {
            if (coursecat::count_all() == 1) {
                // There exists only one category in the system, do not display link to it.
                $coursecat = coursecat::get_default();
                $strfulllistofcourses = get_string('fulllistofcourses');
                $this->page->set_title("$site->shortname: $strfulllistofcourses");
            } else {
                $strcategories = get_string('categories');
                $this->page->set_title("$site->shortname: $strcategories");
            }
        } else {
            $this->page->set_title("$site->shortname: ". $coursecat->get_formatted_name());
        }
        // Course page title.

        $displaylist = coursecat::make_categories_list();

        $seg = $page;
        $perpage = '8';
        $baseurl = new moodle_url('/course/index.php', array("categoryid" => $categoryid,
            "ctype" => $ctype)
        );

        $offset  = $seg * $perpage;

        $catid = (empty($categoryid)) ? 'all' : $categoryid;

        $sortstr = (empty($ctype) || $ctype == "asc") ? 'c.sortorder ASC' : 'c.sortorder DESC';

        $mycourses = theme_enlight_get_courses_page1($catid, $sortstr, 'c.id,
            c.category,c.shortname,c.fullname,c.visible,c.sortorder,c.idnumber ,c.startdate,c.groupmode,c.groupmodeforce,c.cacherev',
            $totalcount, $offset, $perpage);

        $paging = $OUTPUT->paging_bar($totalcount, $page, $perpage, $baseurl);
        $topmnu = $this->category_menu2($totalcount);

        $header = '<div id="custom-page">
        <div class="container-fluid">
        <div class="site-custom-blocks custom-listing-01">';

        $menustr = '<div class="titlebar">
        <h3>'.get_string('courses').'</h3>
        '.$topmnu.'
        </div>';

        $footer = '</div>
        </div>
        </div>';

        $coursestr = $this->display_course2($mycourses);

        $output .= $header.$menustr.$coursestr.$paging.$footer;

        return $output;
    }

    private function custom_course_page($category) {
        global $CFG;
        require_once($CFG->libdir. '/coursecatlib.php');
        $coursecat = coursecat::get(is_object($category) ? $category->id : $category);
        $site = get_site();
        $categoryid = optional_param('categoryid', 0, PARAM_INT);

        if (!$coursecat->id) {
            if (coursecat::count_all() == 1) {
                // There exists only one category in the system, do not display link to it.
                $coursecat = coursecat::get_default();
                $strfulllistofcourses = get_string('fulllistofcourses');
                $this->page->set_title("$site->shortname: $strfulllistofcourses");
            } else {
                $strcategories = get_string('categories');
                $this->page->set_title("$site->shortname: $strcategories");
            }
        } else {
            $this->page->set_title("$site->shortname: ". $coursecat->get_formatted_name());
        }

        $list = coursecat::make_categories_list();

        if ($coursecat->id) {
            $list = array($coursecat->id => $list[$coursecat->id]);
        }

        $addnew = '';
        $context = get_category_or_system_context($coursecat->id);
        if (has_capability('moodle/course:create', $context)) {
            // Print link to create a new course, for the 1st available category.
            if ($coursecat->id) {
                $url = new moodle_url('/course/edit.php', array('category' => $coursecat->id, 'returnto' => 'category'));
            } else {
                $url = new moodle_url('/course/edit.php', array('category' => $CFG->defaultrequestcategory,
                    'returnto' => 'topcat'));
            }
            $addnew = $this->single_button($url, get_string('addnewcourse'), 'get');
        }

        $output = '';
        $srchurl = new moodle_url('/course/search.php');

        $output .= '<div class="site-custom-search">
        <div class="site-custom-search-wrap">
        <form action="'.$srchurl.'" method="get">
        <div class="form-fields">
        <input type="text" value=""  name="search" placeholder="Type the course name here...">
        <div class="action-btn"><i class="fa fa-search"></i><input type="submit" value="Search"></div>
        </div>
        </form>
        </div>
        </div>';

        foreach ($list as $catid => $catname) {
            if ($categoryid > 0) {
                $output .= $this->load_course_list_all($catid, $catname);
            } else {
                $output .= $this->load_course_list($catid, $catname);
            }
        }

        return $output;

    }

    private function load_course_list_all ($catid, $catname) {
        global $CFG, $OUTPUT;
        $rcourseids = array();
        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        if ($categoryid > 0) {
            $lmstr = 'LIMIT 0,24';
        } else {
            $lmstr = '';
        }

        if ($ccc = get_courses($catid, 'c.id DESC,c.sortorder ASC '.$lmstr, 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $rcourseids[] = $cc->id;
            }

            if (empty($rcourseids)) {
                return '';
            }

            $lcourseids = array_chunk($rcourseids, 6);
            $totallcourse = count($lcourseids);

            $clstxt = 'list_courses'.$catid;
            $idtxt = 'List-Courses'.$catid;
            $caturl = new moodle_url("/course/index.php", array("categoryid" => $catid));

            $listbtn = '<div class="pagenav slider-nav btnwrap">
            <button class="slick-prev nav-item previous" type="button">
            <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i>
            </button>
            <button class="slick-next nav-item next" type="button">
            <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i>
            </button>
            </div>';

            $listbtn = ( $totallcourse > 1 ) ? $listbtn : '';

            $listheader = '<div class="site-custom-blocks custom-listing" id="'.$idtxt.'">
            <div class="titlebar">
            <h3>'.$catname.'</h3>
            '.$listbtn.'
            <div class="clearfix"></div>
            </div>
            <div class="'.$clstxt.'">';

            $listfooter = '</div>
            </div>';

            $listcontent = '';

            if (!empty($lcourseids)) {
                foreach ($lcourseids as $courseids) {
                    $rowcontent = '<div>';
                    $cnt = 0;
                    $innerrow = '<div class="row-fluid">';
                    foreach ($courseids as $courseid) {
                        $cnt++;
                        $course = get_course($courseid);
                        $noimgurl = $OUTPUT->pix_url('no-image', 'theme');

                        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                        if ($course instanceof stdClass) {
                            require_once($CFG->libdir. '/coursecatlib.php');
                            $course = new course_in_list($course);
                        }

                        $imgurl = '';

                        $summary = theme_enlight_strip_html_tags($course->summary);
                        $summary = theme_enlight_course_trim_char($summary, 75);

                        $context = context_course::instance($course->id);
                        $nostudents = count_role_users(5, $context);
                        $noteachers = count_role_users(3, $context);

                        foreach ($course->get_course_overviewfiles() as $file) {
                            $isimage = $file->is_valid_image();
                            $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);

                            if (!$isimage) {
                                $imgurl = $noimgurl;
                            }
                        }

                        if (empty($imgurl)) {
                            $imgurl = $noimgurl;
                        }

                        $stlang = ($nostudents > 1) ? 'students' : 'defaultcoursestudent';
                        $telang = ($noteachers > 1) ? 'teachers' : 'defaultcourseteacher';

                        $coursehtml = '<div class="span4">
                        <div class="box">
                        <div class="box-thumb">
                        <a href="'.$courseurl.'"><img src="'.$imgurl.'" alt="'.$course->fullname.'"></a>
                        </div>
                        <div class="box-content">
                        <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                        <hr>
                        <div class="stat-info">
                        <span title="'.$noteachers.' '.get_string($telang).'"><i class="fa fa-user"></i>'.$noteachers.'</span>
                        <span title="'.$nostudents.' '.get_string($stlang).'"><i class="fa fa-group"></i>'.$nostudents.'</span>
                        <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="more-link"><a href="'.$courseurl.'">'.
                        get_string('knowmore', 'theme_enlight').' <i class="fa fa-arrow-right"></i></a></div>
                        </div>
                        </div>
                        </div>';

                        $innerrow .= $coursehtml;
                        if ($cnt > 0 && $cnt % 3 == "0") {
                            $innerrow .= '</div><div class="row-fluid">';
                        }
                    }
                    $innerrow .= '</div>';
                    $rowcontent .= $innerrow;
                    $rowcontent .= '</div>';
                    $listcontent .= $rowcontent;
                }
            }

            $slrtl = (right_to_left()) ? ' , '."\n".'rtl: true' : '';

            $scpt = '';

            if (!empty($listbtn) ) {
                $scpt = '<script>
                $(function(){
                $(".'.$clstxt.'").slick({
                arrows:true ,
                swipe:false,
                prevArrow:\'#'.$idtxt.' .pagenav .slick-prev\',
                nextArrow: \'#'.$idtxt.' .pagenav .slick-next\' '.$slrtl.'
                });
                });
                </script>';
            }

            $listcourses = $listheader.$listcontent.$listfooter."<br/>".$scpt;
            return $listcourses;

        } else {
            return '';
        }

    }

    private function load_course_list($catid, $catname) {
        global $CFG, $OUTPUT;
        $rcourseids = array();
        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        if ($categoryid > 0) {
            $lmstr = 'LIMIT 0,24';
        } else {
            $lmstr = '';
        }

        if ($ccc = get_courses($catid, 'c.id DESC,c.sortorder ASC '.$lmstr, 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $rcourseids[] = $cc->id;
            }

            if (empty($rcourseids)) {
                return '';
            }

            $lcourseids = array_chunk($rcourseids, 3);
            $totallcourse = count($lcourseids);

            $clstxt = 'list_courses'.$catid;
            $idtxt = 'List-Courses'.$catid;
            $caturl = new moodle_url("/course/index.php", array("categoryid" => $catid));

            $listbtn = '<div class="pagenav slider-nav btnwrap">
            <a href="'.$caturl.'">'.get_string('viewall', 'theme_enlight').'</a>
            <button class="slick-prev nav-item previous" type="button">
            <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
            <button class="slick-next nav-item next" type="button">
            <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
            </div>';

            $listbtn = ( $totallcourse > 1 ) ? $listbtn : '';

            if ($categoryid > 0) {
                $listbtn = '';
            }

            $listheader = '<div class="site-custom-blocks custom-listing" id="'.$idtxt.'">
            <div class="titlebar">
            <h3>'.$catname.'</h3>
            '.$listbtn.'
            <div class="clearfix"></div>
            </div>
            <div class="row-fluid '.$clstxt.'">';

            $listfooter = '</div>
            </div>';

            $listcontent = '';

            if (!empty($lcourseids)) {
                foreach ($lcourseids as $courseids) {
                    $rowcontent = '<div>';
                    $cnt = 0;

                    foreach ($courseids as $courseid) {
                        $cnt++;
                        $course = get_course($courseid);
                        $noimgurl = $OUTPUT->pix_url('no-image', 'theme');

                        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                        if ($course instanceof stdClass) {
                            require_once($CFG->libdir. '/coursecatlib.php');
                            $course = new course_in_list($course);
                        }

                        $imgurl = '';

                        $summary = theme_enlight_strip_html_tags($course->summary);
                        $summary = theme_enlight_course_trim_char($summary, 75);

                        $context = context_course::instance($course->id);
                        $nostudents = count_role_users(5, $context);
                        $noteachers = count_role_users(3, $context);

                        foreach ($course->get_course_overviewfiles() as $file) {
                            $isimage = $file->is_valid_image();
                            $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                            if (!$isimage) {
                                $imgurl = $noimgurl;
                            }
                        }

                        if (empty($imgurl)) {
                            $imgurl = $noimgurl;
                        }
                        $stlang = ($nostudents > 1) ? 'students' : 'defaultcoursestudent';
                        $telang = ($noteachers > 1) ? 'teachers' : 'defaultcourseteacher';

                        $coursehtml = '<div class="span4">
                        <div class="box">
                        <div class="box-thumb">
                        <a href="'.$courseurl.'"><img src="'.$imgurl.'" alt="'.$course->fullname.'"></a>
                        </div>
                        <div class="box-content">
                        <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                        <hr>
                        <div class="stat-info">
                        <span title="'.$noteachers.' '.get_string($telang).'"><i class="fa fa-user"></i>'.$noteachers.'</span>
                        <span title="'.$nostudents.' '.get_string($stlang).'"><i class="fa fa-group"></i>'.$nostudents.'</span>
                        <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="more-link"><a href="'.$courseurl.'">'.get_string('knowmore','theme_enlight').' <i class="fa fa-arrow-right"></i></a></div>
                        </div>
                        </div>
                        </div>';

                        $rowcontent .= $coursehtml;
                    }
                    $chtmlend = '</div>';

                    $rowcontent .= '</div>';
                    $listcontent .= $rowcontent;
                }
            }

            $slrtl = (right_to_left()) ? ' , '."\n".'rtl: true' : '';

            $scpt = '';

            if (!empty($listbtn) && $categoryid == "0" ) {
                $scpt = '<script>
                $(function(){
                $(".'.$clstxt.'").slick({
                arrows:true ,
                swipe:false,
                prevArrow:\'#'.$idtxt.' .pagenav .slick-prev\',
                nextArrow: \'#'.$idtxt.' .pagenav .slick-next\' '.$slrtl.'
                });
                });
                </script>';

            }

            $listcourses = $listheader.$listcontent.$listfooter."<br/>".$scpt;
            return $listcourses;

        } else {
            return '';
        }

    }

    public function top_course_menu() {
        global $CFG, $OUTPUT, $DB;
        require_once($CFG->libdir. '/coursecatlib.php');
        $list = coursecat::make_categories_list();
        $mclist = array();
        $sql = "SELECT a.category , a.cnt from (
        SELECT category , count(category) as cnt FROM {course}
        WHERE category != '0' and visible = ?
        group by category
        order by rand()) as a
        order by a.cnt desc limit 0,4";
        $params = array('1');
        $result = $DB->get_records_sql($sql, $params);
        if ($result) {
            foreach ($result as $rowcat) {
                $mclist[] = $rowcat->category;
            }
        }
        $rcourseids = array();
        foreach ($mclist as $catid) {
            $cname = coursecat::get($catid, MUST_EXIST, true)->get_formatted_name();
            $menuheader = '<div class="cols">
            <h6>'.$cname.'</h6>
            <ul>'."\n";
            $menufooter = '</ul>
            </div>'."\n";
            $mmenuheader='';
            /*
            $mmenuheader = '<li class="dropdown-submenu">
            <a href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle">'.$cname.'</a>
            <ul class="dropdown-menu">';
            $mmenufooter = '</ul>
            </li>';
            */

            $menuitems = '';
            //if ($ccc = get_courses($catid, 'c.id DESC,c.sortorder ASC LIMIT 0,6', 'c.id,c.shortname,c.fullname,c.visible')) {
            if ($ccc = get_courses($catid, 'c.sortorder ASC LIMIT 0,6', 'c.id,c.shortname,c.fullname,c.visible')) {
                foreach ($ccc as $cc) {
                    if ($cc->visible == "0" || $cc->id == "1") {
                        continue;
                    }
                    $courseurl = new moodle_url("/course/view.php", array("id" => $cc->id));
                    $menuitems .= '<li><a href="'.$courseurl.'">'.$cc->fullname.'</a></li>'."\n";
                }
                /*
                if (!empty($menuitems)) {
                $rcourseids[$catid] = array("desk" => $menuheader.$menuitems.$menufooter,
                "mobile" => $mmenuheader.$menuitems.$mmenufooter
                );
                }

                */
                if (!empty($menuitems)) {
                    $rcourseids[$catid] = array("desk" => $menuheader.$menuitems.$menufooter,
                        "mobile" => $mmenuheader.$menuitems
                    );
                }



            }
        }
        $mcourseids = array_slice($rcourseids, 0, 4);
        $strcourse = $mstrcourse = '';
        foreach ($mcourseids as $ctid => $marr) {
            $strcourse .= $marr["desk"]."\n";
            $mstrcourse .= $marr["mobile"]."\n";
        }

        $courseaurl = new moodle_url('/course/index.php');
        $topcmenu = '<div class="custom-dropdown-menu" id="cr_menu" style="display:none;">
        <div class="cols-wrap">'.$strcourse.'
        <div class="clearfix"></div>
        </div>
        </div>';
        /*
        $topmmenu = '<ul class="dropdown-menu">
        '.$mstrcourse.'<li>
        <a href="'.$courseaurl.'">'.get_string('viewall', 'theme_enlight').'</a>
        </li>
        </ul>';
        */


        $topmmenu = '<ul class="dropdown-menu">
        '.$mstrcourse.'</ul>';

        return compact('topcmenu', 'topmmenu');
    }

    private function category_menu2($count) {
        global $OUTPUT, $PAGE, $CFG, $DB, $USER;

        $page = optional_param('page', '0', PARAM_INT);
        $categoryid = optional_param('categoryid', null, PARAM_INT);
        $ctype = optional_param('ctype', null, PARAM_TEXT);
        $displaylist = coursecat::make_categories_list();

        if (empty($count)) {
            $countstr = '<p>No course available</p>';
        } else if ($count == "1") {
            $countstr = '<p>'.$count.' course</p>';
        } else if ($count > 1) {
            $countstr = '<p>'.$count.' course(s)</p>';
        }

        $options = $options1 = '';

        foreach ($displaylist as $cid => $cval) {
            $ctxt = ($categoryid == $cid) ? ' selected="selected" ' : '';
            $options .= "<option value='$cid'$ctxt>$cval</option>\n";
        }

        $dlist = array("asc" => "Asc", "desc" => "Desc");
        foreach ($dlist as $ct => $ctval) {
            $ctxt1 = ($ctype == $ct) ? ' selected="selected" ' : '';
            $options1 .= "<option value='$ct'$ctxt1>$ctval</option>\n";
        }

        $courseurl = new moodle_url("/course/index.php");

        $html = '<div class="theme-filters">
        <form action="'.$courseurl.'" name="frmcourse" method="post" id="frmcrs">
        <select name="categoryid">
        <option value="">Categories</option>
        '.$options.'
        </select>
        <select name="ctype">
        <option value="">Sort</option>
        '.$options1.'
        </select>
        '.$countstr.'
        </form>
        </div>';

        return $html;
    }

    private function display_course2 ($mycourses) {
        global $OUTPUT, $CFG;
        require_once($CFG->dirroot.'/course/lib.php');
        $rcourses = array_chunk($mycourses, 4);
        $output = '';
        $noimgurl = $OUTPUT->pix_url('no-image', 'theme');
        foreach ($rcourses as $rowcourse) {
            $coursestr = '<div class="row-fluid">';

            foreach ($rowcourse as $course) {

                $courseurl = new moodle_url('/course/view.php', array('id' => $course->id ));

                if ($course instanceof stdClass) {
                    require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $imgurl = '';

                $summary = theme_enlight_strip_html_tags($course->summary);
                $summary = theme_enlight_course_trim_char($summary, 75);

                $context = context_course::instance($course->id);
                $nostudents = count_role_users(5, $context);
                $noteachers = count_role_users(3, $context);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) {
                        $imgurl = $noimgurl;
                    }
                }

                if (empty($imgurl)) {
                    $imgurl = $noimgurl;
                }

                $activities = get_array_of_activities($course->id);
                $countact = count($activities);

                $stlang = ($nostudents > 1) ? 'students' : 'defaultcoursestudent';
                $telang = ($noteachers > 1) ? 'teachers' : 'defaultcourseteacher';
                $aclang = ($countact > 1) ? 'activities' : 'activity';

                $coursestr .= '<div class="span3">
                <div class="box">
                <div class="box-thumb">
                <a href="'.$courseurl.'"><img src="'.$imgurl.'" alt="img252-01.jpg"></a>
                </div>
                <div class="box-content">
                <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                <hr>
                <p class="shortdesc">'.$summary.'</p>
                <hr>
                <div class="stat-info">
                <span title="'.$noteachers.' '.get_string($telang).'"><i class="fa fa-user"></i>'.$noteachers.'</span>
                <span title="'.$countact.' '.get_string($aclang).'"><i class="fa fa-clock-o"></i>'.$countact.'</span>
                <span title="'.$nostudents.' '.get_string($stlang).'"><i class="fa fa-group"></i>'.$nostudents.'</span>
                <div class="clearfix"></div>
                </div>
                </div>
                </div>
                </div>';
            }

            $coursestr .= '</div>';

            $output .= $coursestr;
        }

        return $output;

    }


    public function show_site_courses() {
        global $CFG, $OUTPUT;

        $popularcontent = '';

        //$pcourses = $this->course_insights_home(1);
        $pcourses=array();

        if ($ccc = get_courses('all', 'c.sortorder ASC LIMIT 0,24', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $pcourses[] = $cc->id;
            }
        }




        if (empty($pcourses)) {
            return false;
        }

        //$astu = array();
        foreach ($pcourses as $pcourse) {
            $rcourseids[] = $pcourse;
            //  $astu[$pcourse['cid']] = $pcourse['students'];
        }

        $pcourseids = array_chunk($rcourseids, 6);
        $totalpcourse = count($pcourseids);
        $bgimage ='<img src="'. $CFG->wwwroot.'/theme/enlight/pix/home/Icon_Courses.jpg'.'" alt="'.get_string('courseentery', 'theme_enlight').'" >' ;
        $popularheader = '<div class="frontpage-custom-blocks popular-courses-block" id="Popular-Courses">
        <div class="coursetitlebar"><h4>'.$bgimage.get_string('courseentery', 'theme_enlight').'</h4></div>
        <div class="bgtrans-overlay"><div>&nbsp;</div></div>
        <div class="container-fluid">
        <div class="titlebar">
        <h2 class="pull-left"><i class="fa fa-star"></i>'
        .get_string('popularcourses', 'theme_enlight').'</a></h2>
        <div class="pagenav slider-nav pull-right">
        <button class="slick-prev nav-item previous" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <button class="slick-next nav-item next" type="button">
        <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-left"></i></button>
        <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div>
        <div class="row-fluid courses_list" data-crow="'.$totalpcourse.'">';

        $popularfooter = '</div>
        </div>
        </div>';

        if (!empty($pcourseids)) {
            foreach ($pcourseids as $courseids) {
                $rowcontent = '<div>';
                $cnt = 0;
                foreach ($courseids as $courseid) {
                    $cnt++;
                    $course = get_course($courseid);
                    $noimgurl = $OUTPUT->pix_url('no-image', 'theme');

                    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                    if ($course instanceof stdClass) {
                        require_once($CFG->libdir. '/coursecatlib.php');
                        $course = new course_in_list($course);
                    }

                    $imgurl = '';
                    $summary =   $course->summary;
                    //$summary = theme_enlight_strip_html_tags($course->summary);
                    //$summary = theme_enlight_course_trim_char($summary, 75);

                    $context = context_course::instance($course->id);
                    $nostudents = count_role_users(5, $context);

                    foreach ($course->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                            '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                        if (!$isimage) {
                            $imgurl = $noimgurl;
                        }
                    }

                    if (empty($imgurl)) {
                        $imgurl = $noimgurl;
                    }
                    /*
                    $coursehtml = ' <div class="span2">
                    <div class="wrapper">
                    <div class="thumb"><a href="'.$courseurl.'"><img src="'.
                    $imgurl.'"  alt="'.$course->fullname.'"  class="frontpagecourse"  ></a></div>
                    <div class="info">
                    <h6 class="title-text"><a href="'.$courseurl.'">'.$course->fullname.'</a></h6>
                    <p>'.$summary.'</p>
                    </div>
                    </div>
                    </div>';
                    */
                    $coursehtml = ' <div class="span2">
                    <div class="wrapper">
                    <div class="thumb"><a href="'.$courseurl.'"><img src="'.
                    $imgurl.'"  alt="'.$course->fullname.'"  class="frontpagecourse"  ></a></div>
                    <div class="info">'.$summary.'</div>
                    </div>
                    </div>';
                    $rowcontent .= $coursehtml;
                }

                $rowcontent .= '</div>';
                $popularcontent .= $rowcontent;
            }
        }

        $popularcourses = $popularheader.$popularcontent.$popularfooter;
        /* Display the popular courses in Home page */
        echo $popularcourses;
    }


    public function custom_course_frontpage($category) {
        global $OUTPUT, $PAGE, $CFG, $DB, $USER;
        require_once($CFG->libdir. '/coursecatlib.php');

        $output = '';

        $page = optional_param('page', '0', PARAM_INT);
        $categoryid = optional_param('categoryid', null, PARAM_INT);
        $ctype = optional_param('ctype', null, PARAM_TEXT);

        // Course page title.
        require_once($CFG->libdir. '/coursecatlib.php');
        $coursecat = coursecat::get(is_object($category) ? $category->id : $category);
        $site = get_site();

        if (!$coursecat->id) {
            if (coursecat::count_all() == 1) {
                // There exists only one category in the system, do not display link to it.
                $coursecat = coursecat::get_default();
                $strfulllistofcourses = get_string('fulllistofcourses');
                $this->page->set_title("$site->shortname: $strfulllistofcourses");
            } else {
                $strcategories = get_string('categories');
                $this->page->set_title("$site->shortname: $strcategories");
            }
        } else {
            $this->page->set_title("$site->shortname: ". $coursecat->get_formatted_name());
        }
        // Course page title.

        $displaylist = coursecat::make_categories_list();

        $seg = $page;
        $perpage = '8';
        $baseurl = new moodle_url('/course/index.php', array("categoryid" => $categoryid,
            "ctype" => $ctype)
        );

        $offset  = $seg * $perpage;

        $catid = (empty($categoryid)) ? 'all' : $categoryid;

        $sortstr =  'c.sortorder ASC';

        $mycourses = theme_enlight_get_courses_page1($catid, $sortstr, 'c.id,
            c.category,c.shortname,c.fullname,c.visible,c.sortorder,c.idnumber ,c.startdate,c.groupmode,c.groupmodeforce,c.cacherev',
            $totalcount, $offset, $perpage);

        $paging = $OUTPUT->paging_bar($totalcount, $page, $perpage, $baseurl);
        $topmnu = $this->category_menu2($totalcount);

        $header = '<div id="custom-page">
        <div class="site-custom-blocks custom-listing-01">';

        // $menustr = '<div class="titlebar">
        //         <h3>'.get_string('courses').'</h3>
        //         '.$topmnu.'
        //      </div>';

        $footer = '</div>
        </div>';

        $coursestr='';
        $coursestr = $this->display_course_frontpage($mycourses);

        $output .= $header.$coursestr.$footer;

        return $output;
    }

    private function display_course_frontpage ($mycourses) {
        global $OUTPUT, $CFG;


        $totalpcourse = count($mycourses);
        $popularheader = '<div class="courses">
        <h2 class="courses__title">' . get_string('course-select', 'theme_enlight') . '</h2>
        <div class="courses__list">';
        $popularfooter = '</div></div>';

        //display courses 
        require_once($CFG->dirroot.'/course/lib.php');
        $rcourses = array_chunk($mycourses, 4);
        $output = '';
        $noimgurl = $OUTPUT->pix_url('no-image', 'theme');
        foreach ($rcourses as $rowcourse) {
            $coursestr = '';

            foreach ($rowcourse as $course) {

                if (isloggedin()){
                    $courseurl = new moodle_url('/course/view.php', array('id' => $course->id ));
                }else{
                    $courseurl = new moodle_url('/login/index.php');
                }


                if ($course instanceof stdClass) {
                    require_once($CFG->libdir. '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $imgurl = '';

                //$summary = theme_enlight_strip_html_tags($course->summary);
                //$summary = theme_enlight_course_trim_char($summary, 75);
                $summary = $course->summary;

                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) {
                        $imgurl = $noimgurl;
                    }
                }

                if (empty($imgurl)) {
                    $imgurl = $noimgurl;
                }

                $boolIsOpened = (bool) get_user_preferences('usercourses_'.$course->id);
                $coursestr .=  '<a href="'.$courseurl.'" class="course">';

                if ($boolIsOpened){
                    $coursestr .='<div class="course__status">בלמידה</div>';    
                }
                $coursestr .='<div class="course__title"><h4>'.$course->fullname.'</h4></div>
                <div class="course__info"><div class="course__desc">'.$summary.'</div></div>
                </a>';
            }

            $coursestr .= '';

            $output .= $coursestr;
        }

        return $popularheader.$output.$popularfooter;

    }



}
