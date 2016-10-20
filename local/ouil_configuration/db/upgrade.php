<?php
/**
 * local_ouil_configuration
 * This plugin will update configuration settings for various compenents
 *  for Open University of Israel
 */
    defined('MOODLE_INTERNAL') || die();

    require_once("$CFG->dirroot/lib/ddllib.php");
    require_once("$CFG->dirroot/local/ouil_configuration/db/upgradelib.php");
    /* Handle database updates
     * 
     */
     function xmldb_local_ouil_configuration_upgrade($oldversion=0) {

        global $DB, $CFG;
        $result = true;

        $dbman = $DB->get_manager();
    
       if ($oldversion < 2015110409) {
        	set_ouil_config_values();
        	upgrade_plugin_savepoint(true, 2015110409, 'local', 'ouil_configuration') ;
        }
        
        if ($oldversion < 2015110413) {
           $DB->execute('UPDATE `mdl_page` SET contentformat=?', array(1));
           upgrade_plugin_savepoint(true, 2015110413, 'local', 'ouil_configuration') ;
        }
        
        if ($oldversion < 2015110414) {
           $DB->execute('UPDATE `mdl_qtype_multichoice_options` SET answernumbering=?', array('none'));
           upgrade_plugin_savepoint(true, 2015110414, 'local', 'ouil_configuration') ;
        }
        
        return $result;
     }
