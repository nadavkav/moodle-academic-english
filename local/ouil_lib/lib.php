<?php

	function login_temp_user(){
	global $PAGE;
//	if((isguestuser() || (!isloggedin())) &&($PAGE->pagelayout!='frontpage')  && ($PAGE->course->id!=SITEID)  ){
	if(!isloggedin() ){
		global $CFG;
		require_once($CFG->dirroot.'/user/lib.php');
		require_once($CFG->dirroot.'/user/profile/lib.php');
	
		$useruniqe=microtime(true);
		$newuser = new stdClass();
		$newuser->auth='manual';
		$newuser->confirmed=1;
		$newuser->policyagreed=0;
		$newuser->deleted=0;
		$newuser->suspended=0;
		$newuser->mnethostid=1;
		$newuser->username='guest_'.$useruniqe;
		$newuser->PASSWORD='Guest_'.$useruniqe.'!';
		$newuser->idnumber='0011';
		$newuser->firstname='AnonFirst';
		$newuser->lastname='AnonLast';
		$newuser->email='fakemail@fakemail.co.il';
		$newuser->emailstop=0;
		$newuser->icq='';
		$newuser->skype='';
		$newuser->yahoo='';
		$newuser->aim='';
		$newuser->msn='';
		$newuser->phone1='';
		$newuser->phone2='';
		$newuser->institution='';
		$newuser->department='';
		$newuser->address='';
		$newuser->city='';
		$newuser->country='';
		$newuser->lang='he';
		$newuser->calendartype='gregorian';
		$newuser->theme='';
		$newuser->timezone=99;
		$newuser->firstaccess=0;
		$newuser->lastaccess=0;
		$newuser->lastlogin=0;
		$newuser->currentlogin=0;
		$newuser->lastip='';
		$newuser->secret='';
		$newuser->picture=0;
		$newuser->url='';
		$newuser->description=null;
		$newuser->descriptionformat=1;
		$newuser->mailformat=1;
		$newuser->maildigest=0;
		$newuser->maildisplay=2;
		$newuser->autosubscribe=0;
		$newuser->trackforums=0;
		$newuser->timecreated=null;
		$newuser->timemodified=null;
		$newuser->trustbitmask=0;
		$newuser->imagealt=null;
		$newuser->lastnamephonetic=null;
		$newuser->firstnamephonetic=null;
		$newuser->middlename=null;
		$newuser->alternatename=null;
	
		$newuser->id = user_create_user($newuser, false, false);
		profile_save_data($newuser);
	
		if ($USER = get_complete_user_data('username', 'guest_'.$useruniqe)) {
			complete_user_login($USER);
		}
	
	
	} //guest user
	
}
//login_temp_user();

?>