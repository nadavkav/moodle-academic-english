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
* @package   theme_enlight
* @copyright 2015 Nephzat Dev Team,nephzat.com
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

// Get the HTML for the settings bits.
global $DB,$COURSE;

$html = theme_enlight_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

$boolIsOpened = (bool) get_user_preferences('usercourses_'.$COURSE->id);
set_user_preference('usercourses_'.$COURSE->id,true);
                                                                             

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
    <head>
        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
        <?php echo $OUTPUT->standard_head_html() ?>
        <?php echo custom_css(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <?php require_once(dirname(__FILE__) . '/includes/bodyclasses.php');
    ?>
    <body <?php echo $OUTPUT->body_attributes($bodyclasses); ?>>
    
        <?php echo $OUTPUT->standard_top_of_body_html() ?>

        <?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

        <div id="page" class="course">

            <?php /*
            <header id="page-header" class="clearfix">
            <div class="container-fluid">
            <?php echo $html->heading; ?>
            <div id="page-navbar" class="clearfix">
            <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
            </div>
            <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
            </div>
            </div>
            </header>
            */?>
            <header class="course__header">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span6">
                            <h1 class="course__title"><?php echo $COURSE->shortname?></h1>
                        </div>
                        <div class="span6">
                            <div class="course__icon">
                                <img src="<?php echo theme_enlight_theme_url(); ?>/images/course-icon-default.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <section class="course__content">
                <div class="course__about <?php echo $boolIsOpened?'':'open'?>"> 
                <?php echo $OUTPUT->page_heading_button(); ?> 
                    <div class="container-fluid">
                        <?php 
                        $objSections= $DB->get_record('course_sections',array('course'=>$COURSE->id,'section'=>'0'));
                        ?> 
                        <h5><?php echo $objSections->name ?></h5>
                        <div class="row-fluid">
                            <?php
                            $arrSummary = explode('###',$objSections->summary);
                            if (!empty($arrSummary[1])) { ?>

                            <div class="span6">
                                <div class="about-button about-show">מידע על הקורס <i class="fa fa-chevron-thin-down"></i></div>
                                <div class="about-video">
                                    <?php echo $arrSummary[1]; ?>
                                   <!-- <img src="/theme/enlight/images/advantage1.png">-->
                                </div>
                            </div>

                            <div class="span6">
                                <div class="about-text">
                                    <?php echo $arrSummary[0]; ?>
                                </div>
                            </div>

                            <?php } else if (!empty($arrSummary[0])) { ?>

                            <div class="span12">
                                <div class="about-button about-show">מידע על הקורס <i class="fa fa-chevron-thin-down"></i></div>
                                <div class="about-text">
                                    <?php echo $arrSummary[0]; ?>
                                </div>
                            </div>

                            <?php } ?>
                            
                        </div>
                    </div>
                    <div class="about-button about-close">סגור<i class="fa fa-chevron-thin-up"></i></div>
                </div>
                <?php 
                $objInstanceLabel= $DB->get_record('course_modules',array('course'=>$COURSE->id,'module'=>12,'visible'=>true));
                
                if (!empty($objInstanceLabel->instance)){
                $objLabel= $DB->get_record('label',array('id'=>$objInstanceLabel->instance));
                }
                ?>
                <div class="units">
                    <div class="container-fluid">
                        <div class="row-fluid">
                            <div class="span3">  
                                <?php 
                                $sectionsNumers = $DB->count_records_select('course_sections','course = ? and visible=1',array($COURSE->id) );
                                $budgesNumbers=0;
                                $badges = badges_get_user_badges($USER->id, $COURSE->id, 0, 2);
                                if (!empty($badges)) {
                                foreach ($badges as $badge) {
                                    $objBadge = new badge($badge->id);
                                    foreach ($objBadge->get_criteria() as $criteria) {
                                        if (get_class($criteria) == 'award_criteria_activity') {
                                            if (!empty($criteria->params)){
                                                foreach($criteria->params as $param) {
                                                    $cm = $DB->get_record('course_modules', array('id' => $param['module']));
                                                    $budgesNumbers++;
                                                    }
                                                }
                                            }  
                                        }

                                    }
                                } 
                                ?>

                                <div class="achievements">
                                    <div class="achievements__num">
                                        <span><?php echo $budgesNumbers;?></span>/<span><?php echo $sectionsNumers-1?></span>
                                    </div>
                                    הישגים שצברתי
                                </div>
                            </div>
                            <div class="span9">
                                <div class="units__title">
                                    <?php
                                    if (!empty($objLabel->intro)){
                                        echo  $objLabel->intro;
                                    }                                     
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- <ul class="units__list">
                        <li class="unit unit--first">
                        <div class="row-fluid">
                        <div class="span8">
                        <h4>מאגר אסטרטגיות למידה</h4>
                        <p>סרטונים קצרים וממוקדים המסבירים את הטכניקות העיקריות להבנת הנקרא של טקסטים</p>
                        </div>
                        <div class="span4">
                        <div class="unit--first__image"></div>
                        </div>
                        </div>
                        <div class="tooltip">
                        <p>אסטרטגיות למידה נכונות הן המפתח להצלחה <span>התחילו את הלמידה כאן</span></p>
                        </div>
                        </li>
                        <li class="unit unit--complete">
                        <div class="flag">
                        <div class="flag__image">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/achievement-icon.png" alt="">
                        </div>
                        <div class="flag__text">הסתיים!</div>
                        </div>
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">Space is a good investment</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        1
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit unit--learning">
                        <div class="flag">
                        <div class="flag__image">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/unit-corner.png" alt="">
                        </div>
                        <div class="flag__text">בלמידה</div>
                        </div>
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">Celebrating a unique event</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        2
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit unit--learning">
                        <div class="flag">
                        <div class="flag__image">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/unit-corner.png" alt="">
                        </div>
                        <div class="flag__text">בלמידה</div>
                        </div>
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title"> Gerald Durrell</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        33
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">John Glenn</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        4
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">The impact of the internet</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        5
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">Getting an education</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        6
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">

                        <h5 class="unit__title">More than a doll</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        7
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">The royal game</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        8
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">Alternative medicine</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        9
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="unit">
                        <div class="row-fluid">
                        <div class="span8">
                        <h5 class="unit__title">Alternative medicine</h5>
                        <div class="unit__info">
                        <ul>
                        <li>
                        <i class="unit-icon unit-icon--classes"></i>
                        | <b>2/2</b> שיעורים | <b>2/2</b> תרגולים
                        </li>
                        <li>
                        <i class="unit-icon unit-icon--duration"></i>
                        | זמן למידה משוער <b>3 שעות</b>
                        </li>
                        </ul>
                        </div>
                        </div>
                        <div class="span4">
                        <div class="unit__num">
                        10
                        <span>יחידה</span>
                        </div>
                        </div>
                        </div>
                        </li>
                        <li class="units__quiz">
                        <div class="row-fluid">
                        <div class="span6">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/quiz-icon.png" alt="">
                        </div>
                        <div class="span6">
                        <h4>מבחן לדוגמה</h4>
                        <p><i class="unit-icon unit-icon--duration"></i> | זמן מבחן משוער <b>3 שעות</b></p>
                        <a class="button units__quiz__button">התחילו מבחן</a>
                        </div>
                        </div>
                        </li>
                        </ul> -->
                        <section id="region-main">
                            <?php
                            echo $OUTPUT->course_content_header();
                            echo $OUTPUT->main_content();
                            echo $OUTPUT->course_content_footer();
                            ?>
                        </section>
                    </div>
                </div>
            </section>

            <div class="container-fluid">
            </div>

        </div>

        <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
        <script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js"></script>
    </body>
</html>
