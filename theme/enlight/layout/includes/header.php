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

$surl = new moodle_url('/course/search.php');
$courserenderer = $PAGE->get_renderer('core', 'course');
$tcmenu = $courserenderer->top_course_menu();
$cmenuhide = theme_enlight_get_setting('cmenuhide');
$no = get_config('theme_enlight', 'patternselect');

?>

<header id="site-header">

    <div class="header-top">
        <div class="navbar  navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a data-target="#site-custom-menu" data-toggle="collapse" class="btn btn-navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div id="site-custom-menu" class="nav-collapse collapse">
                        <?php echo $OUTPUT->custom_menu(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-main">
        <div class="navbar navbar-fixed-top">
            <div class="logged-panel navigation">
                <div class="container-fluid">
                    <?php if (isloggedin()){?>
                        <div class="user-greeting">
                            <ul class="logged-panel__nav">
                                <li class="logged-panel__link">
                                    <a>
                                        <div class="user-thumb"><img src="<?php echo theme_enlight_theme_url();?>/images/user-thumb-default.png" alt=""></div>
                                        <?php echo get_string('hello','theme_enlight') ?> <?php echo $USER->firstname; ?>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="logged-panel__subnav">
                                        <li><a href="<?php echo new moodle_url('/mod/quiz/index_course_grades.php',array('id'=>5))?>">הציונים שלי</a></li>
                                        <li><a href="<?php echo new moodle_url('/badges/total_overview.php',array('courseid'=>5))?>">ההישגים שלי</a></li>
                                        <li><a href="<?php echo new moodle_url('/login/change_password.php')?>">שינוי סיסמא</a></li>
                                        <li><a href="<?php echo new moodle_url('/login/logout.php')?>"><?php echo get_string('logout')?></a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                        <?php } else { ?>
                        <div class="login-panel">
                            <div class="sign-in">
                                <a href="<?php echo new moodle_url('/login/index.php')?>" class="login-link"><i class="login-icon"></i>כניסה למערכת</a>
                            </div>
                            <div class="sign-up">
                                <span>לא רשומ/ה?</span>
                                <a href="<?php echo new moodle_url('/login/signup.php')?>" class="sign-up-button">הרשמו עכשיו</a>
                            </div>
                        </div>
                        <?php } ?>
                </div>
            </div>
            <div class="navbar-inner">
                <div id="sgkk" class="container-fluid">
                    <div id="site-user-menu" class="nav-collapse collapse pull-right navigation">
                        <a class="btn btn-navbar menu-close" data-toggle="collapse" data-target=".navigation">
                            <span class="icon-close"></span>
                        </a>
                        <div class="logged-panel--mobile">
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo theme_enlight_theme_url();?>/images/user-thumb-mobile-default.png" alt="">
                                    <?php echo get_string('hello','theme_enlight') ?> <?php echo $USER->firstname; ?>
                                    <i class="fa fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo new moodle_url('/mod/quiz/index_course_grades.php',array('id'=>5))?>">הציונים שלי</a></li>
                                    <li><a href="<?php echo new moodle_url('/badges/total_overview.php',array('courseid'=>5))?>">ההישגים שלי</a></li>
                                    <li><a href="<?php echo new moodle_url('/login/change_password.php')?>">שינוי סיסמא</a></li>
                                    <li><a href="<?php echo new moodle_url('/login/logout.php')?>"><?php echo get_string('logout')?></a></li>
                                </ul>
                            </div>

                        </div>
                        <ul class="nav pull-left">

                            <li><a href="<?php echo $CFG->wwwroot;?>"><?php echo get_string('home'); ?></a></li>
                            <?php
                            if (!$cmenuhide) {
                                ?>
                                <li class="dropdown hidden-desktop">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_string('courses'); ?><i class="fa fa-chevron-down"></i><span class="caretup"></span></a>
                                    <?php echo $tcmenu['topmmenu']; ?>
                                </li>
                                <?php /*
                                <li class="visible-desktop cr-link" id="cr_link">
                                <a href="<?php echo $CFG->wwwroot; ?>" ><?php echo get_string('courses'); ?>
                                <i class="fa fa-chevron-down"></i><span class="caretup"></span></a>
                                <?php echo $tcmenu['topcmenu']; ?>
                                </li>  
                                */ ?>


                                <?php
                            } else {
                                echo '<li><a href="'.new moodle_url('/course/index.php').'">'.get_string('courses').'</a></li>'."\n";
                            }
                            ?>
                            <?php
                            $abouturl=theme_enlight_get_setting('abouturl');

                            if (!empty($abouturl)){
                                if(!empty($PAGE->cm)&&$PAGE->cm->id==44){
                                    echo '<li><a class="active" href="'.$abouturl.'" target="about" >'.get_string('about','theme_enlight').'</a></li>'."\n";  

                                }else{
                                    echo '<li><a  href="'.$abouturl.'" target="about" >'.get_string('about','theme_enlight').'</a></li>'."\n";   
                                }


                            }

                            $supporturl= theme_enlight_get_setting('supporturl');
                            if (!empty($supporturl)){
                                if(!empty($PAGE->cm)&&$PAGE->cm->id==49){
                                    echo '<li><a class="active" href="'.$supporturl.'" target="_blank" >'.get_string('support','theme_enlight').'</a></li>'."\n";
                                }else{
                                    echo '<li><a href="'.$supporturl.'" target="_blank" >'.get_string('support','theme_enlight').'</a></li>'."\n";  
                                }
                            } 
                            //if (!isloggedin()){
                            //                                echo '<li><a href="'. new  moodle_url('/login/index.php').'" >'.get_string('enter','theme_enlight').'</a></li>'."\n";
                            //                            }
                            ?>
                        </ul>
                    </div>
                    <div class="clearfix hidden-desktop"></div>
                    <?php 
                    if ($PAGE->pagelayout!='page') 
                    { ?>
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <?php } else {?>
                        <a href="<?php echo new moodle_url('/course/view.php',array('id'=>$COURSE->id));?>" class="visible-phone return-link">
                            <i class="fa fa-chevron-right"></i>
                            <span>בחזרה לקורס</span>
                        </a>
                        <?php } ?>
                    <div class="header-logo">
                        <a class="brand logoP3" href="http://che.org.il/" title="המועצה להשכלה גבוהה" target="newtab"></a>
                        <a class="brand logoP2" href="http://www.openu.ac.il/" title="האוניברסיטה הפתוחה" target="newtab"></a>
                        <a class="brand  logoP1" href="http://onl.co.il/" title="אקדמייה מקוונת" target="newtab" data-pressed="false"></a>
                    </div>
                </div>
            </div>
        </div>



    </div>

</header>
<!--E.O.Header-->

<script>
    $(function(){
        <?php
        if (right_to_left()) {
            ?>

            var w =  $(".header-main #sgkk").width();
            var win = $(window).width();
            //yifatsh
            /*
            if(win>=980)
            {
            var ul_w =  $(".header-main #site-user-menu ul").width();
            var le = ( w-ul_w );
            // $('#cr_menu').css({"width":w+'px' , "right": '-'+le+'px' });
            $('#cr_menu').css({"width":'200px' , "right": '80px' });
            }
            */
            $(window).resize(function(){
                var w =  $(".header-main #sgkk").width();
                var win = $(window).width();
                if(win>=980)
                {
                    var ul_w =  $(".header-main #site-user-menu ul").width();
                    var le = ( w-ul_w );
                    //$('#cr_menu').css({"width":w+'px' , "right": '-'+le+'px' });
                    $('#cr_menu').css({"width":'200px' , "right": '80px' });  //yifatsh
                }
            });

            <?php
        } else {
            ?>
            var w =  $(".header-main #sgkk").width();
            var win = $(window).width();
            if(win>=980)
            {
                var ul_w =  $(".header-main #site-user-menu ul").width();
                var le = ( w-ul_w );
                $('#cr_menu').css({"width":w+'px' , "left": '-'+le+'px' });
            }

            $(window).resize(function(){
                var w =  $(".header-main #sgkk").width();
                var win = $(window).width();
                if(win>=980)
                {
                    var ul_w =  $(".header-main #site-user-menu ul").width();
                    var le = ( w-ul_w );
                    $('#cr_menu').css({"width":w+'px' , "left": '-'+le+'px' });
                }
            });

            <?php
        }
        ?>	

        $(".cr-link").mouseenter(function() {
            $(this).addClass("active");
            $(this).find(".custom-dropdown-menu").show();
        });

        $(".cr-link").mouseleave(function() {
            $(this).removeClass("active");
            $(this).find(".custom-dropdown-menu").hide();
        });

        $(".dropdown-menu a").on("touchstart", function() {
            document.location = $(this).attr("href");
        });

    });
</script>
