<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
	if (!has_capability('local/ouil:configuration', context_system::instance()))
		return;
/*
	$settings = new admin_settingpage('local_ouil_configuration', new lang_string('pluginname', 'local_ouil_configuration'), 'local/ouil:config');
	
	$settings->add(new admin_setting_configtextarea(
			'allowedfiletype',
			new lang_string('acceptedfiletypes', 'local_ouil_configuration'),
			new lang_string('acceptedfiletypes', 'local_ouil_configuration'),
			get_string('defaulttypes', 'local_ouil_configuration'),
			PARAM_RAW, 100));
	
	$settings->add(new admin_setting_configtextarea(
			'migrationcourses', 
			new lang_string('migrationcourses',  'local_ouil_configuration'),
			new lang_string('migrationcourses',  'local_ouil_configuration'),
			'',
			PARAM_TEXT,100 ));

	$ADMIN->add('root', $settings);
*/
}
