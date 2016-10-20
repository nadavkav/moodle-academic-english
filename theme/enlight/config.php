<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
* @package    theme_enlight
* @copyright  2015 Nephzat Dev Team , nephzat.com
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
*/

$THEME->name = 'enlight';

$THEME->doctype = 'html5';
$THEME->parents = array('bootstrapbase');
//$THEME->sheets = array('custom', 'enlight', 'font-awesome.min');
$THEME->sheets = array('custom', 'font-awesome.min');
$no = get_config('theme_enlight', 'patternselect');
if ($no==5) {
    $THEME->sheets[] = 'enlight-5';
}else{
    $THEME->sheets[] = 'enlight';
}
$THEME->sheets[] = 'academic-english';
$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();
$THEME->enable_dock = true;
$THEME->editor_sheets = array();
//$no = get_config('theme_enlight', 'patternselect');
$clayout = get_config('theme_enlight', 'courselayout');

if ($no) {
    $THEME->sheets[] = 'pattern-'.$no;
} else {
    $THEME->sheets[] = 'pattern-default';
}

$coursecat = (!empty($clayout)) ? 'columns3-'.$clayout.'.php' : 'columns3-default.php';

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_enlight_process_css';

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default.

    'base' => array(
        'file' => 'columns3.php',
        'regions' => array(),
    ),
    'signup' => array(
        'file' => 'signup.php',
        'regions' => array(),
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    'coursecategory' => array(
        'file' => $coursecat ,
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'columns3.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),
    'embeddedhv5' => array(
        'file' => 'embeddedhv5.php',
        'regions' => array()
    ),
    'forgotpassword' => array(
        'file' => 'forgot-password.php',
        'regions' => array()
    ),
    'course' => array(
        'file' => 'course.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'page' => array(
        'file' => 'page.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ), 
    'singlepage' => array(
        'file' => 'singlepage.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    'quiz' => array(
        'file' => 'quiz.php',
        'regions' => array('side-pre', 'side-post','content-menu'),
        'defaultregion' => 'side-pre',
    ),
    'quiz_first' => array(
        'file' => 'quiz_first.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    'quiz_summary' => array(
        'file' => 'quiz_summary.php',
        'regions' => array('side-pre', 'side-post','content-menu'),
        'defaultregion' => 'side-pre',
    ),
    'quiz_review' => array(
        'file' => 'quiz_summary.php',
        'regions' => array('side-pre', 'side-post','content-menu'),
        'defaultregion' => 'side-pre',
    ),
    'courseadmin' => array(
        'file' => 'courseadmin.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'about_page' => array(
        'file' => 'about_page.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'strategy_page' => array(
        'file' => 'strategy_page.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'help_page' => array(
        'file' => 'help_page.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'after-reg' => array(
        'file' => 'after-reg.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),  
    'book' => array(
        'file' => 'book.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),      
    'admin' => array(
        'file' => 'columns3.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'badges' => array(
        'file' => 'badges.php',
        'regions' => array(),
    )
    ,
    'grades' => array(
        'file' => 'grades.php',
        'regions' => array(),
    ),
    'logout' => array(
        'file' => 'logout.php',
        'regions' => array(),
    ),
    'simpletext' => array(
        'file' => 'simpletext.php',
        'regions' => array(),
    ),
    'popup' => array(
        'file' => 'popup.php',
        'regions' => array(),
    )

);

$THEME->blockrtlmanipulations = array(
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
);

if (core_useragent::is_ie() && !core_useragent::check_ie_version('9.0')) {
    $THEME->javascripts[] = 'respond';
}

