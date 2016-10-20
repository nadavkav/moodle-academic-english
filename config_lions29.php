<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'lions29';
$CFG->dbuser    = 'lions29';
$CFG->dbpass    = 'lions29';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
);

$CFG->wwwroot   = 'http://lions29.moodlembl.dev.openu.ac.il';
$CFG->dataroot  = 'C:\\wamp\\www\\moodledata';
$CFG->admin     = 'admin';
$CFG->session_path = '/tmp/';
$CFG->directorypermissions = 0777;
$CFG->enviroment = 'dev';
$CFG->session_save_path='/tmp'; //yifatsh issueid:2790 parasmter declare session path
$CFG->session_file_save_path='/tmp'; //yifatsh issueid:4074 paramter declare session path
$CFG->directorypermissions = 0777;

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
