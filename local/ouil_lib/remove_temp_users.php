<?php
define('CLI_SCRIPT', true); /* this is a command line script */
require_once('../../config.php');
/* remove all temp users*/



global $DB;

$timestamp=microtime(true);
$timestamp = round($timestamp);
$D=date('D, d M Y H:i:s',$timestamp);
 echo "<BR>Today: ".$D; 
$guest_user_live_in_system=$CFG->guest_user_live_in_system;
echo "<BR> guest_user_live_in_system = ".$CFG->guest_user_live_in_system;



$queryQuiz = "select id  from mdl_quiz";

$quizes =  $DB->get_records_sql($queryQuiz,null);


echo "<BR> remove quiz attemps";
require_once($CFG->dirroot.'/mod/quiz/lib.php');
foreach($quizes as $quiz) {
	quiz_delete_all_attempts($quiz);
}


$usernameHead="guest";
$queryUser = "select *  from mdl_user  where username like '%".$usernameHead."_%' and firstname='AnonFirst'  and lastname='AnonLast' and email='fakemail@fakemail.co.il' ";


$resources_users  = $DB->get_records_sql($queryUser,null);

foreach($resources_users as $duser) {
	echo "<BR> remove user : ".$duser->id." ".$duser->username;
	$userstart =intval(substr($duser->username , 6));
	
	$D=date('D, d M Y H:i:s',$userstart );
	
	$timepass=$timestamp-$userstart;
	if ($timepass>$guest_user_live_in_system){
		echo "  delete user ";
		delete_user($duser);
		$DB->delete_records('user', array('id' => $duser->id,
				'deleted'=>1 ));
	}

}


echo "<BR>  finish ";


?>
