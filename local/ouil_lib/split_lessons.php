<?php

require_once('../../config.php');
require_once('../../course/modlib.php');
require_once('../../course/lib.php');


global $USER ,$PAGE; 
$currentuser =  $USER->id;
$context = $usercontext = context_user::instance($currentuser, MUST_EXIST);

$PAGE->set_context($context);
$PAGE->set_pagelayout('mypublic');
$PAGE->set_pagetype('user-profile');
$PAGE->blocks->add_region('content');
$PAGE->set_title(" split lessons");
$PAGE->set_heading("split lessons");
$PAGE->set_url('/mod/local/ouil_lib/split_lessons.php', null);
echo $OUTPUT->header();
global $CFG;



function course_create_sections_if_missing_lesson_to_page($courseorid, $sections , $name) {
    global $DB;
    if (!is_array($sections)) {
        $sections = array($sections);
    }
    $existing = array_keys(get_fast_modinfo($courseorid)->get_section_info_all());
    if (is_object($courseorid)) {
        $courseorid = $courseorid->id;
    }
    $coursechanged = false;
    foreach ($sections as $sectionnum) {
        if (!in_array($sectionnum, $existing)) {
            $cw = new stdClass();
            $cw->course   = $courseorid;
            $cw->section  = $sectionnum;
	    $cw->name  = $name;
            $cw->summary  = '';
            $cw->summaryformat = FORMAT_HTML;
            $cw->sequence = '';
            $id = $DB->insert_record("course_sections", $cw);
            $coursechanged = true;
        }
    }
    if ($coursechanged) {
        rebuild_course_cache($courseorid, true);
    }
    return $coursechanged;
}



 function get_course_module_data( $course,$sectionnum ){
	global $DB;

	$data = new stdClass();
	$data->course = $course->id;
	$data->section = $sectionnum ;
	echo "<br> sectionnum  data ->= ".$sectionnum;
	$data->modulename = "page";
	$data->visible=1;
	

	$data->groupingid =0;
	$data->groupmembersonly = 0;
	$data->cmidnumber  = '';
	$data->cmidnumber = 0;
	$data->completion = 0;
	$data->completiongradeitemnumber = null;
	$data->completionview = 0;
	$data->completionexpected = 0;

	//TO DO need to copy and update
	if (empty($CFG->enableavailability)) {
		$data->availability = null;
	}

	$data->instance = 0; // Set to 0 for now, going to create it soon (next step)

	//$data->add          =  $this->module;
	$data->return           = 0; //must be false if this is an add, go back to course view on cancel

	//just throw ouput to the screen
	
	return $data;

}




function copy_lesson_pages( $lesson,$course,$pagemod   ){
	global $DB;
	$lessonid=$lesson->id;
	$lesson_name = $lesson->name;
	$sectionid=$DB->get_field_sql("SELECT MAX(section) FROM mdl_course_sections where course=:course_id",array('course_id'=>$course->id));
	$sectionid++;
	echo "<BR> sectionid = ".$sectionid;



	$lesson_pages = $DB->get_records('lesson_pages', array('lessonid'=>$lessonid)); 
	
	
		
		
		
	
	foreach( $lesson_pages  as $lesson_page ){
	
	$data = get_course_module_data($course,$sectionid);
	 
	//$source_page = $this->get_source_page();
	 
	//if(!$source_page){
//		throw new copycourse_exception(" page instance for module " . $this->oldmoduleid . " does not exists ");
	//	return;
	//}
	//$sourcemodule = $data->id;
	 
	$data->module = $pagemod;
	$data->id = '';
	$data->instance='';
	$data->coursemodule='';
	
	/** Add page fields **/
	$data->name = $lesson_page->title;
	$data->intro ='';
	$data->introformat = 1;
	$data->content = $lesson_page->contents;
	//$data->contentformat =1;
	$data->legacyfiles =  0;
	//$data->legacyfileslast = $source_page->legacyfileslast;
	    
	//$data->revision = $source_page->revision;
	$data->timemodified =time(); ;
	
	
	
		$data->displayoptions 	= $DB->get_field('config_plugins', 'value', array('name' => 'displayoptions', 'plugin' => 'page'));
		$data->display 			= $DB->get_field('config_plugins', 'value', array('name' => 'display', 'plugin' => 'page'));
		$data->printheading 	= $DB->get_field('config_plugins', 'value', array('name' => 'printheading', 'plugin' => 'page'));
		$data->printintro		= $DB->get_field('config_plugins', 'value', array('name' => 'printintro', 'plugin' => 'page'));
		$data->popupwidth 		= $DB->get_field('config_plugins', 'value', array('name' => 'popupwidth', 'plugin' => 'page')); 
		$data->popupheight 		=  $DB->get_field('config_plugins', 'value', array('name' => 'popupheight', 'plugin' => 'page'));

	
	//}
	
	
	
	 
		 
	//$draftid_editor = 0;
	//file_prepare_draft_area($draftid_editor, null, null, null, null, array('subdirs'=>true));
	 
	//$data->introeditor = array('text'=> '<p>' . $data->intro . '</p>', 'format'=>FORMAT_HTML, 'itemid'=>$draftid_editor);
	$data->files = null;
	 
 
	course_create_sections_if_missing_lesson_to_page($course, $sectionid,$lesson->name);
	$data_cm =  add_moduleinfo($data, $course, null);
	$moduleinfo =0;
	
	
	
	
	}//$lesson_pages 
	
	
	
	
}





function course_split_lession($courseid,$page_mod) {
	echo "<BR>haddle course =  ".$courseid;
	global  $DB;
	if (!$course = $DB->get_record('course', array('id'=>$courseid))) {
		print_error('invalidcourseid');
	}


	if ( $lessons = get_all_instances_in_course("lesson", $course)) {

		foreach ($lessons  as $lesson){

		$name = $lesson->name;
		copy_lesson_pages($lesson,$course,$page_mod->id );
	
		}
	
	}



	$section_max=$DB->get_field_sql("SELECT MAX(section) FROM mdl_course_sections where course=:course_id",array('course_id'=>$course->id));

	$course_format_options= "select id from {course_format_options} where format=:format and name=:name and courseid=:courseid";
	$param =array();
        $param['format']='topics';
        $param['name']='numsections';
        $param['courseid']=$course->id;

 	$numsections_obj=$DB->get_record_sql( $course_format_options,$param);
	if ($numsections_obj){
 		$numsections_obj->value=$section_max;
 		$DB->update_record('course_format_options', $numsections_obj);
	}

	


}


require_login();
if (!is_siteadmin()) {
	die;
}else{



$runquery=false;
$courseid= optional_param('course_id', 0,PARAM_ALPHANUM);
$run= optional_param('run', "no",PARAM_ALPHANUM);
$multipleCourse=optional_param('multipleCourse', "no",PARAM_ALPHANUM);

 
 echo '<form action="split_lessons.php" method="get">';
  echo'<input type="HIDDEN" name="run" value="yes">';
 echo"<table>";
  echo'<TR><TD>course_id</TD><TD><input type="text" name="course_id"></TD></TR>';
  echo'<TR><TD>Run on all Course </TD><TD><select name="multipleCourse"> <option value="no" selected >no</option><option value="yes"  >yes</option>';
  echo'</TD></TR>';
  echo'<TR><TD>submit</TD><TD><input type="submit" value="Split Lessons"></TD></TR>';
 echo"</table>";
 echo "</form>";


if ($run=="yes"){


$page_mod = $DB->get_record('modules', array('name'=>'page'));
echo "<BR>siteid =".SITEID;

if ($multipleCourse=="yes"){

	$courses = $DB->get_records('course', null); 
	foreach ($courses  as $course){
		if (SITEID!==$course->id){
		echo "<BR> run on course ".$course->id;
		 course_split_lession($course->id,$page_mod) ;
		}
	}
}else{
	if (($courseid>0) && (SITEID!==$courseid) ){
		echo "<BR> run on course ".$courseid;
		course_split_lession($courseid,$page_mod);
	}
}

}

}

?>
