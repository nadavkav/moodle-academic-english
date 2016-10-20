<?php
defined('MOODLE_INTERNAL') || die;


function set_ouil_config_values() {
	global $DB;
	
	//authloginviaemail
	$config_object = $DB->get_record('config', array( 'name'=>'authloginviaemail'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'authloginviaemail';
		$config_plugin->value= true;
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value= true;
		$DB->update_record('config', $config_object);
	}
	
	//rememberusername
	$config_object = $DB->get_record('config', array( 'name'=>'rememberusername'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'rememberusername';
		$config_plugin->value= 'yes';
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value= 'yes';
		$DB->update_record('config', $config_object);
	}

	
	//sessiontimeout
	$config_object = $DB->get_record('config', array( 'name'=>'sessiontimeout'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'sessiontimeout';
		$config_plugin->value= 2592000;
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value=2592000;
		$DB->update_record('config', $config_object);
	}
	
	
	
	//extendedusernamechars
	$config_object = $DB->get_record('config', array( 'name'=>'extendedusernamechars'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'extendedusernamechars';
		$config_plugin->value= true;
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value=true;
		$DB->update_record('config', $config_object);
	}
	
	//guestloginbutton
	$config_object = $DB->get_record('config', array( 'name'=>'guestloginbutton'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'guestloginbutton';
		$config_plugin->value= false;
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value=false;
		$DB->update_record('config', $config_object);
	}
	
	
	//sitepolicyguest
	$config_object = $DB->get_record('config', array( 'name'=>'sitepolicyguest'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'sitepolicyguest';
		$config_plugin->value= 'link to disclaimer';
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value='link to disclaimer';
		$DB->update_record('config', $config_object);
	}
	
	
	
	//sitepolicy
	$config_object = $DB->get_record('config', array( 'name'=>'sitepolicy'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'sitepolicy';
		$config_plugin->value= 'link to disclaimer' ;
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value='link to disclaimer' ;
		$DB->update_record('config', $config_object);
	}
	
	
	
	
	
	
	
	

/*
	$config_object = $DB->get_record('config', array( 'name'=>'defaulthomepage'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'defaulthomepage';
		$config_plugin->value= '0';
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value= '0';
		$DB->update_record('config', $config_object);
	}





	$config_object = $DB->get_record('config', array( 'name'=>'lang'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'lang';
		$config_plugin->value= 'he';
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value= 'he';
		$DB->update_record('config', $config_object);
	}



	$config_object = $DB->get_record('config', array( 'name'=>'theme'));
	if(!$config_object){
		$config_plugin = new stdClass();
		$config_plugin->name = 'theme';
		$config_plugin->value= 'enlight';
		$DB->insert_record('config', $config_plugin);
	}else{
		$config_object->value= 'enlight';
		$DB->update_record('config', $config_object);
	}










    $instance = $DB->get_record('config_plugins', array('name' => 'toolbar', 'plugin' => 'editor_atto'), 'id');
    
    if(	isset($instance->id)	){
    $DB->set_field('config_plugins', 'value',
    		'style1 = title, bold, italic
LIST = unorderedlist, orderedlist
links = link
files = image, media, managefiles
style2 = underline, strike, subscript, superscript
align = align
indent = indent
INSERT = equation, charmap, TABLE, clear
UNDO = UNDO
accessibility = accessibilitychecker, accessibilityhelper
other = html',
    		array('id' => $instance->id));
    }

*/

    
}

	



