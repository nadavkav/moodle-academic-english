<?php
require_once("$CFG->dirroot/lib/ddllib.php");
require_once("$CFG->dirroot/local/ouil_configuration/db/upgradelib.php");
/**
 *local_ouil_configuration_install
 * This plugin is for general OUIL configurations
 *
 */
function xmldb_local_ouil_configuration_install() {
	set_ouil_config_values(); 
 }
