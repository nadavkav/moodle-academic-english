<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle_ou_academic-english-lions29';
$CFG->dbuser    = 'root';
$CFG->dbpass    = 'nature';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
);

$CFG->wwwroot   = 'http://localhost/moodle-academic-english';
$CFG->dataroot  = '/var/moodledata-moodleou-academic-english-moodle29stable-lions29';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

//$CFG->themedesignermode = 1; // 1= theme Designer mode
$CFG->cachejs = false;
$CFG->yuicomboloading = false;
//$CFG->yuiloglevel = 'debug';
$CFG->debug = 32767;

// auto user login session
//$CFG->forcelogin = true;

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
