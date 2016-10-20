<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
	if (!is_siteadmin()) {
		return;
	}

	$settings = new admin_settingpage('local_ouil_lib', new lang_string('pluginname', 'local_ouil_lib'), 'local/ouil:ouil_lib');
	

	$settings->add(new admin_setting_configtext('guest_user_live_in_system',	new lang_string('guest_user_live_in_system',  'local_ouil_lib'),
			new lang_string('guest_user_live_in_system_desc',  'local_ouil_lib'),'43200'));
            
	$ADMIN->add('root', $settings);
}
