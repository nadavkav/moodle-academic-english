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
* Section_modules_navbar block caps.
*
* @package    block_section_modules_navbar
* @copyright  Lea Cohen <leac@ort.org.il>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die();

class block_section_modules_navbar extends block_base {

    function init() {
        $this->title = 'ניווט ביחידה';
    }

    function get_content() {
        global $OUTPUT;  
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->files = array();
        $this->content->first = array();
        $this->content->icons = array();
        // $this->content->text = 'sdf sdsf sd';
        $this->content->footer = ' ';


        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);

        // don't show block in main course page
        if (empty($this->page->cm->id)) {
            $this->content = '';
            return $this->content;
        }


        // Get current activity's section
        $modinfo = get_fast_modinfo($this->page->course);


        // Get all activities in given section
        if (!empty($modinfo->sections[$this->page->cm->sectionnum])) { 
            $this->listActivities($modinfo, $this->page->cm->sectionnum);
        }

        // Use cookies to save last section number before user navigated to common/hidden section 11
        // and display a link to that section when in section 11. (11 is hardcoded for project isteam!)
        //     $saved_sectionnum = 0;
        //        if ($this->page->cm->sectionnum != 11) {
        //            setcookie('moodle_smnav_section', $this->page->cm->sectionnum, time() + (86400 * 30), "/");
        //            // Always add activities from the 11th section.
        //            // TODO: Add a settings panel for defining which section holds the hidden activities
        //            //  $this->listActivities($modinfo, 11);  
        //            return $this->content;
        //        } else {
        //            /* Save a cookie of the last section the user was in, so able to return to it.
        //            * Do so only in sections 1-10 */
        //            $saved_sectionnum = $_COOKIE['moodle_smnav_section'];
        //
        //            $sectionmodnumbers = $modinfo->sections[$saved_sectionnum];
        //            $section_firstmodinfo = $modinfo->cms[$sectionmodnumbers[0]];
        //            if (isset($section_firstmodinfo) AND $section_firstmodinfo->uservisible
        //            AND !empty($section_firstmodinfo->url)
        //            ) {
        //                $url = $section_firstmodinfo->url;
        //                // Get a link to the course's frontpage.
        //                $sectionname = get_section_name($this->page->course, $saved_sectionnum);
        //                //$icon = '<img src="' . $icon = $OUTPUT->pix_url('icon', 'page') . '" class="icon" alt="" />&nbsp;';
        //                $this->content->items[] = '<a title="' . $sectionname . '" href="' . $url->out(false) . '" class="back-to-section">' /*. $icon */ . get_string('back_to_step', 'block_section_modules_navbar') . ': ' . $sectionname . '</a>';
        //            }
        //        }

        $contents ="<ul class='lesson-links'>";

        //files
        foreach($this->content->files as $itemCounter => $file){
            $contents.='<li class="r'.$itemCounter.'"><div class="column c1"><h5>'.$file.'</h5></div></li>';
        }
        //first page
        foreach($this->content->first as $itemCounter => $item){
            $contents.='<li class="r'.$itemCounter.'"><div class="column c1"><h5>'.$item.'</h5></div></li>';
        }
        $contents.="</ul>";



        //second div with scroller
        $contents.= "<div class='scroller'>";
        $contents.="<ul class='unlist'>";
        foreach($this->content->items as $itemCounter => $item){
            $contents.='<li class="r'.$itemCounter.'"><div class="column c1">'.$item.'</div></li>';
        }
        $contents.="</ul>";
        $contents.="</div>";

        $this->content->text=$contents."<style>.block_section_modules_navbar{ display:block !important;} </style>";



        return $this->content;

    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => true,
            'site' => true,
            'site-index' => true,
            'course-view' => true,
            'course-view-social' => true,
            'mod' => true,
            'mod-quiz' => true);
    }

    public function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return false;
    }

    public function cron() {
        mtrace("Hey, my cron script is running");

        // do something

        return true;
    }

    public function getThumbUrl($course_id,$sectionNum,$cm,$arr_parser) {
        global $CFG;
        $arrCourses=array(
            "3"=>"BES",
            "2"=>"MTA",
            "5"=>"TBA",
            "4"=>"TBB"
        );
        $name = $cm->get_formatted_name();
        $lesson=0;
               
        if (strpos($name, 'מבוא')!== false){
            $lesson=0;
        }else{
            $lesson=str_replace('שיעור','',$name);
            $lesson=trim($lesson);
        }

        $thumbUrl='ENG_'.$arrCourses[$course_id].'_'.$sectionNum.'_'.$lesson.".jpg"; 
        $time=isset($arr_parser[$arrCourses[$course_id]][$sectionNum][$lesson])?$arr_parser[$arrCourses[$course_id]][$sectionNum][$lesson]:'';

        $arrResult=array();

        if (file_exists($CFG->libdir."/../"."theme/enlight/images/english_thumbs_new/".$thumbUrl)){
            $arrResult=array(
                "url"=>'/theme/enlight/images/english_thumbs_new/'.$thumbUrl,
                "time"=>$time['duration']
            ); 
        }
        return $arrResult;
    }

    public function isSeen($course,$sectionNum,$cm) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$sectionNum])) {
            return false;
        }

        // Generate array with count of activities in this section:
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$sectionNum] as $cmid) {
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
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                    $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {

                        if ($cmid==$cm->id){

                            $complete++;
                        }
                    }
                }

            }
        }

        if($complete>0){
            return true;
        }

        return false;

    }

    public function listActivities($modinfo, $sectionNum) {
        global $PAGE, $DB, $COURSE,$CFG;
        $completioninfo = new completion_info($COURSE);
         
        $cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'core', 'string');
        if (!$arr_parser = $cache->get('thumbmails')) {
            $CSVName=$CFG->libdir."/.."."/theme/enlight/csv/video_durations.csv";   
            $arr_parser =csv_to_array($CSVName); 
            $cache->set('thumbmails',$arr_parser);
        } 
          
        $arr_result=array();
        foreach($arr_parser as $row){
            $arrTmp2=array();
            $arrTmp3=array();

            $arrTmp = explode('_', $row['ar']);

            if(count($arrTmp) == 4 && is_numeric($arrTmp[2]) && is_numeric($arrTmp[3])){

                $arrTmp3['duration']=$row['duration'];
                $arrTmp3['seconds']=$row['seconds'];
                $arr_result[$arrTmp[1]][$arrTmp[2]][$arrTmp[3]] = $arrTmp3;
            }

        }


        //   $this->content->items[] = '<a class="lesson-link" href="#"><i class="lesson-link__icon icon-download"></i> מאמר להורדה (PDF)</a>';
        //   $this->content->items[] = '<a class="lesson-link" href="#"><i class="lesson-link__icon icon-route"></i> מסלול למידה מומלץ</a>';  
        $firstPage=1;
        foreach ($modinfo->sections[$sectionNum] as $cmid) {
            $cm = $modinfo->cms[$cmid];
            if ($cm->modname=='hvp'||!$cm->visible){
                continue;
            }
            if (!$cm->uservisible) {
                continue;
            }

            if ($cm->modname == 'resource'&&isset($cm->url)&&$cm->visible){
                $this->content->files[] = '<a class="lesson-link" href="'.$cm->url.'"><i class="lesson-link__icon icon-download"></i> מאמר להורדה (PDF)</a>';
                continue;
            }

            $content = $cm->get_formatted_content(array('overflowdiv' => true, 'noclean' => true));
            $instancename = $cm->get_formatted_name();

            // Display page's intro (including video tumbnail and other metadata) inside menu entry
            if ($cm->modname == 'page') {

                if ($firstPage==1){
                    $this->content->first[] = '<a class="lesson-link" href="'.$cm->url.'"><i class="lesson-link__icon icon-route"></i> מסלול למידה מומלץ</a>';
                    $firstPage=0;
                    continue;  
                }

                $page = $DB->get_record('page', array('id'=>$cm->instance));
                $contextmodule = context_module::instance($cm->id);
                $pageintro = file_rewrite_pluginfile_urls($page->intro, 'pluginfile.php', $contextmodule->id, 'mod_page', 'intro', NULL);
            }

            $complete=0;
            $completiondata = $completioninfo->get_data($cm, true);
            if ($completiondata->completionstate == COMPLETION_COMPLETE ||
            $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                $complete++;
            } 

            $icon='';
            if ($cm->modname=='page'){
                // $pageintro = $icon . $instancename." fff".$complete;
                $arrResult=$this->getThumbUrl($COURSE->id,$sectionNum,$cm,$arr_result);


                if (!empty($arrResult['url'])){
                    $icon = '<div class="activity__image">
                    <div class="activity__duration">'.$arrResult['time'].'</div>
                    <img src="'.$arrResult['url'].'" alt="">
                    </div>';
                }
            }
            if ($cm->modname=='quiz') {
                $pageintro = '<div class="activity__text activity__text--quiz">';
            } else {
                $pageintro = '<div class="activity__text">';
            }
            if ($this->isSeen($COURSE,$sectionNum,$cm)) {
                $pageintro .= '<i class="seen-icon"></i>';
            }
            $pageintro .= '<h3>' . $instancename . '</h3>';
            if ($cm->modname=='quiz'){
                $objQuiz = $DB->get_record('quiz',array('id'=>$cm->instance)) ;
                //  $pageintro.=get_string('gradingmethod', 'quiz',quiz_get_grading_option_name($objQuiz->grademethod));
            }
            $pageintro .= '<p></p>';
            $pageintro .= '</div>';
            $pageintro .= $icon;

            // end party

            if (!($url = $cm->url)) {
            } else {
                $linkcss = $cm->visible ? '' : ' class="dimmed" ';
                // Accessibility: incidental image - should be empty Alt text
                //$icon = '<img src="' . $cm->get_icon_url() . '" class="icon" alt="" />&nbsp;';
                //  $currentclass = $this->page->url == $cm->url ? 'class="activity current"' : 'class="activity"';     
                
                $devicetype = core_useragent::get_device_type();
                if($devicetype !== 'mobile') {
                    $currentclass = $this->page->url == $cm->url ? 'class="activity current"' : 'class="activity"';
                }else{
                    $currentclass = $this->page->url == $cm->url ? 'class="activity "' : 'class="activity"';  
                }
                  //$this->content->items[] = '<a title="' . $cm->modplural . '" ' . $linkcss . ' ' . $cm->extra . ' href="' .
                //    $url . '"' . $currentclass . ' >' . $icon . $instancename . '</a>';
                $this->content->items[] = '<a title="' . $cm->modplural . '" ' . $linkcss . ' ' . $cm->extra . ' href="' .
                $url . '"' . $currentclass . ' >' . $pageintro . '</a>';
            }
        }
    }
}
