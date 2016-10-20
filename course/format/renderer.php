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
* Base renderer for outputting course formats.
*
* @package core
* @copyright 2012 Dan Poltawski
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
* @since Moodle 2.3
*/

defined('MOODLE_INTERNAL') || die();


/**
* This is a convenience renderer which can be used by section based formats
* to reduce code duplication. It is not necessary for all course formats to
* use this and its likely to change in future releases.
*
* @package core
* @copyright 2012 Dan Poltawski
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
* @since Moodle 2.3
*/
abstract class format_section_renderer_base extends plugin_renderer_base {

    /** @var contains instance of core course renderer */
    protected $courserenderer;

    /**
    * Constructor method, calls the parent constructor
    *
    * @param moodle_page $page
    * @param string $target one of rendering target constants
    */
    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);
        $this->courserenderer = $this->page->get_renderer('core', 'course');
    }

    /**
    * Generate the starting container html for a list of sections
    * @return string HTML to output.
    */
    abstract protected function start_section_list();

    /**
    * Generate the closing container html for a list of sections
    * @return string HTML to output.
    */
    abstract protected function end_section_list();

    /**
    * Generate the title for this section page
    * @return string the page title
    */
    abstract protected function page_title();

    /**
    * Generate the section title
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course The course entry from DB
    * @return string HTML to output.
    */
    public function section_title($section, $course) {
        $title = get_section_name($course, $section);
        $url = course_get_url($course, $section->section, array('navigation' => true));
        if ($url) {
            $title = html_writer::link($url, $title);
        }
        return $title;
    }

    /**
    * Generate the content to displayed on the right part of a section
    * before course modules are included
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course The course entry from DB
    * @param bool $onsectionpage true if being printed on a section page
    * @return string HTML to output.
    */
    protected function section_right_content($section, $course, $onsectionpage) {
        $o = $this->output->spacer();

        if ($section->section != 0) {
            $controls = $this->section_edit_controls($course, $section, $onsectionpage);
            if (!empty($controls)) {
                $o = implode('<br />', $controls);
            }
        }

        return $o;
    }

    /**
    * Generate the content to displayed on the left part of a section
    * before course modules are included
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course The course entry from DB
    * @param bool $onsectionpage true if being printed on a section page
    * @return string HTML to output.
    */
    protected function section_left_content($section, $course, $onsectionpage) {
        $o = $this->output->spacer();

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (course_get_format($course)->is_section_current($section)) {
                $o = get_accesshide(get_string('currentsection', 'format_'.$course->format));
            }
        }

        return $o;
    }

    /**
    * Generate the display of the header part of a section before
    * course modules are included
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course The course entry from DB
    * @param bool $onsectionpage true if being printed on a single-section page
    * @param int $sectionreturn The section to return to after an action
    * @return string HTML to output.
    */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn=null) {
        global $PAGE;

        $o = '';
        $currenttext = '';
        $sectionstyle = '';

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } else if (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }

        $o.= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
            'class' => 'section main clearfix'.$sectionstyle, 'role'=>'region',
            'aria-label'=> get_section_name($course, $section)));

        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));

        // When not on a section page, we display the section titles except the general section if null
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));

        // When on a section page, we only display the general section title, if title is not the default one
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));

        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }
        $o.= $this->output->heading($this->section_title($section, $course), 3, 'sectionname' . $classes);

        $o.= html_writer::start_tag('div', array('class' => 'summary'));
        $o.= $this->format_summary_text($section);

        $context = context_course::instance($course->id);
        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {
            $url = new moodle_url('/course/editsection.php', array('id'=>$section->id, 'sr'=>$sectionreturn));
            $o.= html_writer::link($url,
                html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/settings'),
                    'class' => 'iconsmall edit', 'alt' => get_string('edit'))),
                array('title' => get_string('editsummary')));
        }
        $o.= html_writer::end_tag('div');

        $o .= $this->section_availability_message($section,
            has_capability('moodle/course:viewhiddensections', $context));

        return $o;
    }

    /**
    * Generate the display of the footer part of a section
    *
    * @return string HTML to output.
    */
    protected function section_footer() {
        $o = html_writer::end_tag('div');
        $o.= html_writer::end_tag('li');

        return $o;
    }

    /**
    * Generate the edit controls of a section
    *
    * @param stdClass $course The course entry from DB
    * @param stdClass $section The course_section entry from DB
    * @param bool $onsectionpage true if being printed on a section page
    * @return array of links with edit controls
    */
    protected function section_edit_controls($course, $section, $onsectionpage = false) {
        global $PAGE;

        if (!$PAGE->user_is_editing()) {
            return array();
        }

        $coursecontext = context_course::instance($course->id);
        $isstealth = isset($course->numsections) && ($section->section > $course->numsections);

        if ($onsectionpage) {
            $baseurl = course_get_url($course, $section->section);
        } else {
            $baseurl = course_get_url($course);
        }
        $baseurl->param('sesskey', sesskey());

        $controls = array();

        $url = clone($baseurl);
        if (!$isstealth && has_capability('moodle/course:sectionvisibility', $coursecontext)) {
            if ($section->visible) { // Show the hide/show eye.
                $strhidefromothers = get_string('hidefromothers', 'format_'.$course->format);
                $url->param('hide', $section->section);
                $controls[] = html_writer::link($url,
                    html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/hide'),
                        'class' => 'icon hide', 'alt' => $strhidefromothers)),
                    array('title' => $strhidefromothers, 'class' => 'editing_showhide'));
            } else {
                $strshowfromothers = get_string('showfromothers', 'format_'.$course->format);
                $url->param('show',  $section->section);
                $controls[] = html_writer::link($url,
                    html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/show'),
                        'class' => 'icon hide', 'alt' => $strshowfromothers)),
                    array('title' => $strshowfromothers, 'class' => 'editing_showhide'));
            }
        }

        if (course_can_delete_section($course, $section)) {
            if (get_string_manager()->string_exists('deletesection', 'format_'.$course->format)) {
                $strdelete = get_string('deletesection', 'format_'.$course->format);
            } else {
                $strdelete = get_string('deletesection');
            }
            $url = new moodle_url('/course/editsection.php', array('id' => $section->id,
                'sr' => $onsectionpage ? $section->section : 0, 'delete' => 1));
            $controls[] = html_writer::link($url,
                html_writer::empty_tag('img', array('src' => $this->output->pix_url('t/delete'),
                    'class' => 'icon delete', 'alt' => $strdelete)),
                array('title' => $strdelete));
        }

        if (!$isstealth && !$onsectionpage && has_capability('moodle/course:movesections', $coursecontext)) {
            $url = clone($baseurl);
            if ($section->section > 1) { // Add a arrow to move section up.
                $url->param('section', $section->section);
                $url->param('move', -1);
                $strmoveup = get_string('moveup');

                $controls[] = html_writer::link($url,
                    html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/up'),
                        'class' => 'icon up', 'alt' => $strmoveup)),
                    array('title' => $strmoveup, 'class' => 'moveup'));
            }

            $url = clone($baseurl);
            if ($section->section < $course->numsections) { // Add a arrow to move section down.
                $url->param('section', $section->section);
                $url->param('move', 1);
                $strmovedown =  get_string('movedown');

                $controls[] = html_writer::link($url,
                    html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/down'),
                        'class' => 'icon down', 'alt' => $strmovedown)),
                    array('title' => $strmovedown, 'class' => 'movedown'));
            }
        }

        return $controls;
    }

    /**
    * If user seen one lesson
    * 
    * @param mixed $section
    * @param mixed $course
    * @param mixed $mods
    */

    protected function isSeen($section, $course, $mods){
        global $USER;
        $modinfo = get_fast_modinfo($course,$USER->id);
        if (empty($modinfo->sections[$section->section])) {
            return false;
        }

        // Generate array with count of activities in this section:
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course,$USER->id);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->modname == 'label') {
                // Labels are special (not interesting for students)!
                continue;
            }

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true,$USER->id);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                    $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                    }
                }

            }
        }

        if($complete>0){
            return true;
        }

        return false;

    }

    /**
    * If user finished Section
    * 
    * @param mixed $section
    * @param mixed $course
    */

    protected function hasBadge($section,$course){  
        global $USER,$DB;      
        $badges = badges_get_user_badges($USER->id, $course->id, 0, 2);
        if (!empty($badges)){
            foreach ($badges as $badge) {
                $objBadge = new badge($badge->id);
                foreach ($objBadge->get_criteria() as $criteria) {
                    if (get_class($criteria) == 'award_criteria_activity') {
                        foreach($criteria->params as $param) {
                            $cm = $DB->get_record('course_modules', array('id' => $param['module']));
                            if ($section->section ==$cm->section ){
                                return  true;
                            }
                        }
                    }  
                }

            }
        }
        return false;
    }

    /**
    * Generate a summary of a section for display on the 'coruse index page'
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course The course entry from DB
    * @param array    $mods (argument not used)
    * @return string HTML to output.
    */
    protected function section_summary($section, $course, $mods) {
        global $USER;
        $classattr = 'unit';
        $linkclasses = '';

        // Link section title to section's first module. (nadavkav)
        $modinfo = get_fast_modinfo($course,$USER->id);
        $sectionmodnumbers = $modinfo->sections[$section->section];
        foreach($sectionmodnumbers as $pageid){
            $section_firstmodinfo = $modinfo->cms[$pageid];  
            if ($section_firstmodinfo->modname!='resource'){
                break;
            }
        }


        if (isset($section_firstmodinfo) AND $section_firstmodinfo->uservisible
        AND !empty($section_firstmodinfo->url)) {
            $url = $section_firstmodinfo->url;
        } else {
            $url = course_get_url($course, $section->section);
        }


        // If section is hidden then display grey section link
        if (!$section->visible) {
            $classattr .= ' hidden';
            $linkclasses .= ' dimmed_text';
        } else if (course_get_format($course)->is_section_current($section)) {
            $classattr .= ' current';
        }

        $isSeen = $this->isSeen($section, $course, $mods);
        $hasBadge = $this->hasBadge($section, $course);

        if ($hasBadge) {
            $classattr .= ' unit--complete';
        }else
            if ($isSeen) {
                $classattr .= ' unit--learning';
            }

            $title = get_section_name($course, $section);
        $o = '';
        $o .= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
            'class' => $classattr, 'role'=>'region', 'aria-label'=> $title));
        $o .= html_writer::start_tag('a', array('href' => $url."&f=1"));

        if ($hasBadge) {
            $o .= html_writer::start_tag('div', array('class' => 'flag'));
            $o .= html_writer::start_tag('div', array('class' => 'flag__image'));
            $o .= html_writer::tag('img', '', array('src' => theme_enlight_theme_url().'/images/achievement-icon.png'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('h6', 'הסתיים!', array('class' => 'flag__text'));
            $o .= html_writer::end_tag('div');
        }else if ($isSeen) {
            $o .= html_writer::start_tag('div', array('class' => 'flag'));
            $o .= html_writer::start_tag('div', array('class' => 'flag__image'));
            $o .= html_writer::tag('img', '', array('src' => theme_enlight_theme_url().'/images/courses-corner.png'));
            $o .= html_writer::end_tag('div');
            $o .= html_writer::tag('h6', 'בלמידה', array('class' => 'flag__text'));
            $o .= html_writer::end_tag('div');
        }

        $o .= html_writer::start_tag('div', array('class' => 'row-fluid'));
        $o .= html_writer::start_tag('div', array('class' => 'span8'));

        // $o .= html_writer::start_tag('div', array('class' => 'left side'));
        // $o .= html_writer::start_tag('div', array('class' => 'content'));

        // if ($section->uservisible) {
        //     $title = html_writer::tag('a', $title,
        //         array('href' => $url."&f=1", 'class' => $linkclasses));
        // }

        $o .= $this->output->heading($title, 4, 'unit__title');
        $o.= html_writer::start_tag('div', array('class' => 'summarytext'));
        $o.= html_writer::end_tag('div');
        $o.= $this->section_activity_summary($section, $course, null);

        $context = context_course::instance($course->id);
        $o .= $this->section_availability_message($section,
            has_capability('moodle/course:viewhiddensections', $context));

        $o .= html_writer::end_tag('div');
        $o .= html_writer::start_tag('div', array('class' => 'span4'));
        $o .= html_writer::start_tag('div', array('class' => 'unit__num'));
        $o .= html_writer::tag('div', $section->section);
        $o .= html_writer::tag('span', 'יחידה');
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('div');
        $o .= html_writer::end_tag('a');
        $o .= html_writer::end_tag('li');

        return $o;
    }

    /**
    * Generate a summary of the activites in a section
    *
    * @param stdClass $section The course_section entry from DB
    * @param stdClass $course the course record from DB
    * @param array    $mods (argument not used)
    * @return string HTML to output.
    */
    protected function section_activity_summary($section, $course, $mods) {
        global $USER;
        $modinfo = get_fast_modinfo($course,$USER->id);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section:
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->modname == 'label') {
                // Labels are special (not interesting for students)!
                continue;
            } 

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                    $sectionmods[$thismod->modname]['total_view']=0;
                    $sectionmods[$thismod->modname]['total_completed']=0;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $sectionmods[$thismod->modname]['total_view']++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                    $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                        $sectionmods[$thismod->modname]['total_completed']++;
                    }
                }

            }
        }

        if (empty($sectionmods)) { 
            // No sections
            return '';
        }

        // Output section activities summary:
        $o = html_writer::start_tag('div', array('class' => 'unit__info'));
        $o.= html_writer::start_tag('ul');
        $o.= html_writer::start_tag('li');
        $o.= html_writer::start_tag('h6');
        $o.= html_writer::tag('i', '', array('class' => 'unit-icon unit-icon--classes'));
        foreach ($sectionmods as $modtype => $mod) {  
            $o.= html_writer::start_tag('span', array('class' => 'activity-count'));
            if($modtype=='page'){
                if ($mod['total_completed']){
                    $o.= '| <b>'.$mod['total_completed']."/".$mod['total_view']. '</b> ';
                }else{
                    $o.= '| <b>'.$mod['total_view']. '</b> ';   
                }
                $o.= 'שיעורים';    
            }else if ($modtype=='quiz'){
                if ($mod['total_completed']){
                    $o.= ' | <b>'.$mod['total_completed']."/".$mod['total_view']. '</b> '; 
                }else{
                    $o.= ' | <b>'.$mod['total_view']. '</b> '; 
                }
                $o.= 'בוחן';
            }

            $o.= html_writer::end_tag('span');
        }
        $o.= html_writer::end_tag('h6');
        $o.= html_writer::end_tag('li');

        // Output section completion data
        $o.= html_writer::start_tag('li');
        //$o.= html_writer::tag('i', '', array('class' => 'unit-icon unit-icon--duration'));
       // $o.= html_writer::start_tag('span', array('class' => 'activity-count'));
      //  $o.= '| זמן למידה משוער ';
//        $summary=$this->format_summary_text($section);
//        $summary=str_replace(array('<br />','<p>','</p>'),'',$summary);
//        $o.= '<b>'.$summary.' שעות</b> ';
//$o.= html_writer::end_tag('span');
        $o.= html_writer::end_tag('li');

        $o.= html_writer::end_tag('ul');
        $o.= html_writer::end_tag('div');

        //TODO Additional details about yahida
        return $o;
    }

    /**
    * If section is not visible, display the message about that ('Not available
    * until...', that sort of thing). Otherwise, returns blank.
    *
    * For users with the ability to view hidden sections, it shows the
    * information even though you can view the section and also may include
    * slightly fuller information (so that teachers can tell when sections
    * are going to be unavailable etc). This logic is the same as for
    * activities.
    *
    * @param stdClass $section The course_section entry from DB
    * @param bool $canviewhidden True if user can view hidden sections
    * @return string HTML to output
    */
    protected function section_availability_message($section, $canviewhidden) {
        global $CFG;
        $o = '';
        if (!$section->uservisible) {
            // Note: We only get to this function if availableinfo is non-empty,
            // so there is definitely something to print.
            $formattedinfo = \core_availability\info::format_info(
                $section->availableinfo, $section->course);
            $o .= html_writer::div($formattedinfo, 'availabilityinfo');
        } else if ($canviewhidden && !empty($CFG->enableavailability) && $section->visible) {
            $ci = new \core_availability\info_section($section);
            $fullinfo = $ci->get_full_information();
            if ($fullinfo) {
                $formattedinfo = \core_availability\info::format_info(
                    $fullinfo, $section->course);
                $o .= html_writer::div($formattedinfo, 'availabilityinfo');
            }
        }
        return $o;
    }

    /**
    * Show if something is on on the course clipboard (moving around)
    *
    * @param stdClass $course The course entry from DB
    * @param int $sectionno The section number in the coruse which is being dsiplayed
    * @return string HTML to output.
    */
    protected function course_activity_clipboard($course, $sectionno = null) {
        global $USER;

        $o = '';
        // If currently moving a file then show the current clipboard.
        if (ismoving($course->id)) {
            $url = new moodle_url('/course/mod.php',
                array('sesskey' => sesskey(),
                    'cancelcopy' => true,
                    'sr' => $sectionno,
                )
            );

            $o.= html_writer::start_tag('div', array('class' => 'clipboard'));
            $o.= strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
            $o.= ' ('.html_writer::link($url, get_string('cancel')).')';
            $o.= html_writer::end_tag('div');
        }

        return $o;
    }

    /**
    * Generate next/previous section links for naviation
    *
    * @param stdClass $course The course entry from DB
    * @param array $sections The course_sections entries from the DB
    * @param int $sectionno The section number in the coruse which is being dsiplayed
    * @return array associative array with previous and next section link
    */
    protected function get_nav_links($course, $sections, $sectionno) {
        // FIXME: This is really evil and should by using the navigation API.
        $course = course_get_format($course)->get_course();
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
        or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $back = $sectionno - 1;
        while ($back > 0 and empty($links['previous'])) {
            if ($canviewhidden || $sections[$back]->uservisible) {
                $params = array();
                if (!$sections[$back]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslink = html_writer::tag('span', $this->output->larrow(), array('class' => 'larrow'));
                $previouslink .= get_section_name($course, $sections[$back]);
                $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
            }
            $back--;
        }

        $forward = $sectionno + 1;
        while ($forward <= $course->numsections and empty($links['next'])) {
            if ($canviewhidden || $sections[$forward]->uservisible) {
                $params = array();
                if (!$sections[$forward]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlink = get_section_name($course, $sections[$forward]);
                $nextlink .= html_writer::tag('span', $this->output->rarrow(), array('class' => 'rarrow'));
                $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
            }
            $forward++;
        }

        return $links;
    }

    /**
    * Generate the header html of a stealth section
    *
    * @param int $sectionno The section number in the coruse which is being dsiplayed
    * @return string HTML to output.
    */
    protected function stealth_section_header($sectionno) {
        $o = '';
        $o.= html_writer::start_tag('li', array('id' => 'section-'.$sectionno, 'class' => 'section main clearfix orphaned hidden'));
        $o.= html_writer::tag('div', '', array('class' => 'left side'));
        $course = course_get_format($this->page->course)->get_course();
        $section = course_get_format($this->page->course)->get_section($sectionno);
        $rightcontent = $this->section_right_content($section, $course, false);
        $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));
        $o.= $this->output->heading(get_string('orphanedactivitiesinsectionno', '', $sectionno), 3, 'sectionname');
        return $o;
    }

    /**
    * Generate footer html of a stealth section
    *
    * @return string HTML to output.
    */
    protected function stealth_section_footer() {
        $o = html_writer::end_tag('div');
        $o.= html_writer::end_tag('li');
        return $o;
    }

    /**
    * Generate the html for a hidden section
    *
    * @param int $sectionno The section number in the coruse which is being dsiplayed
    * @param int|stdClass $courseorid The course to get the section name for (object or just course id)
    * @return string HTML to output.
    */
    protected function section_hidden($sectionno, $courseorid = null) {
        if ($courseorid) {
            $sectionname = get_section_name($courseorid, $sectionno);
            $strnotavailable = get_string('notavailablecourse', '', $sectionname);
        } else {
            $strnotavailable = get_string('notavailable');
        }

        $o = '';
        $o.= html_writer::start_tag('li', array('id' => 'section-'.$sectionno, 'class' => 'section main clearfix hidden'));
        $o.= html_writer::tag('div', '', array('class' => 'left side'));
        $o.= html_writer::tag('div', '', array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));
        $o.= html_writer::tag('div', $strnotavailable);
        $o.= html_writer::end_tag('div');
        $o.= html_writer::end_tag('li');
        return $o;
    }

    /**
    * Generate the html for the 'Jump to' menu on a single section page.
    *
    * @param stdClass $course The course entry from DB
    * @param array $sections The course_sections entries from the DB
    * @param $displaysection the current displayed section number.
    *
    * @return string HTML to output.
    */
    protected function section_nav_selection($course, $sections, $displaysection) {
        global $CFG;
        $o = '';
        $sectionmenu = array();
        $sectionmenu[course_get_url($course)->out(false)] = get_string('maincoursepage');
        $modinfo = get_fast_modinfo($course);
        $section = 1;
        while ($section <= $course->numsections) {
            $thissection = $modinfo->get_section_info($section);
            $showsection = $thissection->uservisible or !$course->hiddensections;
            if (($showsection) && ($section != $displaysection) && ($url = course_get_url($course, $section))) {
                $sectionmenu[$url->out(false)] = get_section_name($course, $section);
            }
            $section++;
        }

        $select = new url_select($sectionmenu, '', array('' => get_string('jumpto')));
        $select->class = 'jumpmenu';
        $select->formid = 'sectionmenu';
        $o .= $this->output->render($select);

        return $o;
    }

    /**
    * Output the html for a single section page .
    *
    * @param stdClass $course The course entry from DB
    * @param array $sections (argument not used)
    * @param array $mods (argument not used)
    * @param array $modnames (argument not used)
    * @param array $modnamesused (argument not used)
    * @param int $displaysection The section number in the course which is being displayed
    */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }


        if (!$sectioninfo->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection, $course->id);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or !empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
            echo $this->start_section_list();

            echo $this->section_header($thissection, $course, true, $displaysection);
            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section'));

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = '';
        $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation navigationtitle'));
        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        // Title attributes
        $classes = 'sectionname';
        if (!$thissection->visible) {
            $classes .= ' dimmed_text';
        }
        $sectiontitle .= $this->output->heading(get_section_name($course, $displaysection), 3, $classes);

        $sectiontitle .= html_writer::end_tag('div');
        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
        echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection),
            array('class' => 'mdl-align'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');
    }

    /**
    * Output the html for a multiple section page
    *
    * @param stdClass $course The course entry from DB
    * @param array $sections (argument not used)
    * @param array $mods (argument not used)
    * @param array $modnames (argument not used)
    * @param array $modnamesused (argument not used)
    */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
        global $PAGE,$DB,$USER;

        $modinfo = get_fast_modinfo($course,$USER->id);
        $course = course_get_format($course)->get_course();

        $context = context_course::instance($course->id);
        // Title with completion help icon.
        $completioninfo = new completion_info($course,$USER->id);
        echo $completioninfo->display_help_icon();
       // echo $this->output->heading($this->page_title(), 2, 'accesshide');

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, 0);

        // Now the list of sections..
        echo $this->start_section_list();

        foreach ($modinfo->get_section_info_all() as $section => $thissection) {
            if ($section == 0) {
                // 0-section is displayed a little different then the others
                //if ($thissection->summary or !empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
                //                    echo $this->section_header($thissection, $course, false, 0);
                //                    echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                //                    echo $this->courserenderer->course_section_add_cm_control($course, 0, 0);
                //                    echo $this->section_footer();
                //                   
                //                }

                // First item
                echo '<li  role="region" class="unit unit--first" id="section-0">
                <a target="_blank" href="'.(new moodle_url('/mod/book/view.php',array('id'=>424))).'">
                <div class="row-fluid">
                <div class="span8">
                <h4>מאגר אסטרטגיות למידה</h4>
                <h5>סרטונים קצרים וממוקדים המסבירים את הטכניקות העיקריות להבנת הנקרא של טקסטים</h5>
                </div>
                <div class="span4">
                <div class="unit--first__image"></div>
                </div>
                </div>
                <div class="tooltip">
                <p>אסטרטגיות למידה נכונות הן המפתח להצלחה <span>התחילו את הלמידה כאן</span></p>
                </div>
                </a>
                </li>';

                //TO DO BADGE
                // Display all user badges in course. (nadavkav)
                //  if ($badges = badges_get_user_badges($USER->id, $course->id, 0, 2)) {
                //                    $output = $this->page->get_renderer('core', 'badges');
                //                    echo html_writer::start_tag('div', array('class' => 'badges'));
                //                    echo $output->print_badges_list($badges, $USER->id, true);
                //                    echo html_writer::end_tag('div');
                //                } else {
                //                    echo get_string('nothingtodisplay', 'block_badges');
                //                }

                continue;
            }



            if ($section > $course->numsections) {
                // activities inside this section are 'orphaned', this section will be printed as 'stealth' below
                continue;
            }

            if (!$thissection->visible){
                continue;
            }
            // Show the section if the user is permitted to access it, OR if it's not available
            // but there is some available info text which explains the reason & should display.
            $showsection = $thissection->uservisible ||
            ($thissection->visible && !$thissection->available &&
                !empty($thissection->availableinfo));
            if (!$showsection) {
                // If the hiddensections option is set to 'show hidden sections in collapsed
                // form', then display the hidden section message - UNLESS the section is
                // hidden by the availability system, which is set to hide the reason.
                if (!$course->hiddensections && $thissection->available) {
                    echo $this->section_hidden($section, $course->id);
                }

                continue;
            }

            if (!$PAGE->user_is_editing() && $course->coursedisplay == COURSE_DISPLAY_MULTIPAGE) {
                // Display section summary only.
                echo  $this->section_summary($thissection, $course, null);
            } else {
                echo $this->section_header($thissection, $course, false, 0);
                if ($thissection->uservisible) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                    echo $this->courserenderer->course_section_add_cm_control($course, $section, 0);
                }
                echo $this->section_footer();
            }
        }


        //  print_r($objBohan);
        if (!empty($modinfo->sections[0][1])){

            // Last item
            echo '<li role="region" class="units__quiz" id="section-0">
            <div class="row-fluid">
            <div class="span6">
            <img src="'.theme_enlight_theme_url().'/images/quiz-icon.png" alt="">
            </div>
            <div class="span6">
            <h4>מבחן לדוגמה</h4>';

            echo '<a class="button units__quiz__button" href="/mod/quiz/view.php?id='.$modinfo->sections[0][1].'">התחילו מבחן</a>
            </div>
            </div>
            </li>';

        }


        if ($PAGE->user_is_editing() and has_capability('moodle/course:update', $context)) {
            // Print stealth sections if present.
            foreach ($modinfo->get_section_info_all() as $section => $thissection) {
                if ($section <= $course->numsections or empty($modinfo->sections[$section])) {
                    // this is not stealth section or it is empty
                    continue;
                }
                echo $this->stealth_section_header($section);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                echo $this->stealth_section_footer();
            }

            echo $this->end_section_list();

            echo html_writer::start_tag('div', array('id' => 'changenumsections', 'class' => 'mdl-right'));

            // Increase number of sections.
            $straddsection = get_string('increasesections', 'moodle');
            $url = new moodle_url('/course/changenumsections.php',
                array('courseid' => $course->id,
                    'increase' => true,
                    'sesskey' => sesskey()));
            $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
            echo html_writer::link($url, $icon.get_accesshide($straddsection), array('class' => 'increase-sections'));

            if ($course->numsections > 0) {
                // Reduce number of sections sections.
                $strremovesection = get_string('reducesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                        'increase' => false,
                        'sesskey' => sesskey()));
                $icon = $this->output->pix_icon('t/switch_minus', $strremovesection);
                echo html_writer::link($url, $icon.get_accesshide($strremovesection), array('class' => 'reduce-sections'));
            }

            echo html_writer::end_tag('div');
        } else {
            echo $this->end_section_list();
        }
    }

    /**
    * Generate html for a section summary text
    *
    * @param stdClass $section The course_section entry from DB
    * @return string HTML to output.
    */
    protected function format_summary_text($section) {
        $context = context_course::instance($section->course);
        $summarytext = file_rewrite_pluginfile_urls($section->summary, 'pluginfile.php',
            $context->id, 'course', 'section', $section->id);

        $options = new stdClass();
        $options->noclean = false;
        $options->overflowdiv = false;
        $options->newlines = false;
        return format_text($summarytext, $section->summaryformat, $options);
    }
}
