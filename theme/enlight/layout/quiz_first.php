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
$html = theme_enlight_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
    <head>
        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
        <?php echo $OUTPUT->standard_head_html() ?>
        <?php echo custom_css(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <?php
        require_once(dirname(__FILE__) . '/includes/bodyclasses.php');
        $bodyclasses[] = 'lesson-layout';
    ?>

    <body <?php echo $OUTPUT->body_attributes($bodyclasses); ?>>
        <?php echo $OUTPUT->standard_top_of_body_html() ?>
        <?php  require_once(dirname(__FILE__) . '/includes/header.php');

        $currentSection='';
        $modinfo = get_fast_modinfo($PAGE->course);  
        $arrSectionsUrls=array();
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {
            if ($section == 0) {
                continue;   
            }
            $showsection = $thissection->uservisible ||
            ($thissection->visible && !$thissection->available &&
                !empty($thissection->availableinfo));
            if (!$showsection) {
                continue;
            }
            $sectionmodnumbers = $modinfo->sections[$thissection->section];
            if (in_array($PAGE->cm->id,$sectionmodnumbers)){
                $currentSection=$section;
            }

            foreach($sectionmodnumbers as $pageid){
                $section_firstmodinfo = $modinfo->cms[$pageid];  
                if ($section_firstmodinfo->modname!='resource'){
                    break;
                }
            }

            if (isset($section_firstmodinfo) AND $section_firstmodinfo->uservisible
            AND !empty($section_firstmodinfo->url)) {
                $url = $section_firstmodinfo->url;
            } else {
                $url = course_get_url($PAGE->course, $thissection->section);
            }
            $arrSectionsUrls[$thissection->section]=$url;
        }
        ?>
        <div id="page" class="lesson-page">
            <header id="page-header" class="breadcrumbs">
                <nav class="container-fluid">
                    <ul class="breadcrumbs-nav">
                        <li>
                            <a href="<?php echo new moodle_url('/course/view.php',array('id'=>$PAGE->course->id));?>">
                                <h4><i class="breadcrumbs-nav__icon"></i>
                                <?php echo $PAGE->course->shortname; ?></h4>
                            </a>
                        </li>
                        <li><a href="javascript:void(0)"><h4><?php echo $currentSection!=0?"יחידה ".$currentSection:" יחידות";?><i class="breadcrumbs-subnav__icon"></i></h4></a>
                            <ul class="breadcrumbs-subnav">
                                <?php 
                                foreach($arrSectionsUrls as $sectionCurrent =>$url){
                                    ?>
                                    <li><a href="<?php echo $url;?>">יחידה <?php echo $sectionCurrent?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </header>

            <div class="show-lesson-list">ניווט ביחידה <i class="fa fa-chevron-down"></i></div>
            <div class="container-fluid">
                <div id="page-content" class="row-fluid lesson">
                    <?php 
                    $strClassContent='span8';
                    if ($currentSection==0){
                        $strClassContent='span12';  
                    }
                    ?>
                    <div id="<?php echo $regionbsid ?>" class="<?php echo $strClassContent?> lesson-content">
                        <section id="region-main" class="quiz-first">
                            <img src="<?php echo theme_enlight_theme_url(); ?>/images/examination-icon.png" alt="" class="quiz-first__image">
                            <?php
                            echo $OUTPUT->course_content_header();
                            echo $OUTPUT->main_content();
                            echo $OUTPUT->course_content_footer();
                            echo $OUTPUT->blocks('content-menu','quiz-nav'); 
                            ?>
                        </section>
                    </div>
                    <?php 

                    if ($currentSection!=0){
                        echo $OUTPUT->blocks('side-pre', 'span4 lesson-list');
                        echo $OUTPUT->blocks('side-post', 'span4 lesson-list');
                    }
                    ?>
                </div>
            </div>

        </div>
        <script src="<?php echo theme_enlight_theme_url(); ?>/javascript/jquery.scrollbar.min.js"></script>
        <script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js"></script>
        <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>

        <!--<script>
            $(document).ready(function() {
                $('.list-block .content').scrollbar();
                $(".show-lesson-list").on("click touchstart", toggleLessonNav);

                $(".strategy__icon").on("click touchstart", function() {
                    var popup = new Popup( $(this).data("url") );
                    popup.init();
                });

                $.browser.device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));

                if ($.browser.device && getGETParam("f")) {
                    toggleLessonNav();
                }
            });

            function toggleLessonNav() {
                $(".show-lesson-list").toggleClass("open");
                $(".lesson-list").toggleClass("open");
            }

            function getGETParam(key) {
                var val,
                tmp = [];

                var params = window.location.search.substr(1).split("&");
                for (var i = 0; i < params.length; i++) {
                    tmp = params[i].split("=");
                    if (tmp[0] === key) val = decodeURIComponent(tmp[1]);
                }

                return val;
            }

        </script>-->

    </body>
</html>
