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
 * The one column layout.
 *
 * @package   theme_enlight
 * @copyright 2015 Nephzat Dev Team,nephzat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_enlight_get_html_for_settings($OUTPUT, $PAGE);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
     <?php echo custom_css(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <style type="text/css">
    
    table#explaincaps tbody > tr:nth-child(2n+1) > td, table#defineroletable tbody > tr:nth-child(2n+1) > td, table.grading-report tbody > tr:nth-child(2n+1) > td, table#listdirectories tbody > tr:nth-child(2n+1) > td, table.rolecaps tbody > tr:nth-child(2n+1) > td, table.userenrolment tbody > tr:nth-child(2n+1) > td, table#form tbody > tr:nth-child(2n+1) > td, form#movecourses table tbody > tr:nth-child(2n+1) > td, #page-admin-course-index .editcourse tbody > tr:nth-child(2n+1) > td, .forumheaderlist tbody > tr:nth-child(2n+1) > td, table.flexible tbody > tr:nth-child(2n+1) > td, .generaltable tbody > tr:nth-child(2n+1) > td, table#explaincaps tbody > tr:nth-child(2n+1) > th, table#defineroletable tbody > tr:nth-child(2n+1) > th, table.grading-report tbody > tr:nth-child(2n+1) > th, table#listdirectories tbody > tr:nth-child(2n+1) > th, table.rolecaps tbody > tr:nth-child(2n+1) > th, table.userenrolment tbody > tr:nth-child(2n+1) > th, table#form tbody > tr:nth-child(2n+1) > th, form#movecourses table tbody > tr:nth-child(2n+1) > th, #page-admin-course-index .editcourse tbody > tr:nth-child(2n+1) > th, .forumheaderlist tbody > tr:nth-child(2n+1) > th, table.flexible tbody > tr:nth-child(2n+1) > th, .generaltable tbody > tr:nth-child(2n+1) > th{
        background-color: #f9f9f9;
    }
    </style>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<div id="page" class="achievements-page">
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
    */ ?>


    <section id="region-main">
        <div class="achievements">
            <header class="achievements__header">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="achievements__title">
                                <h2>הציונים שלי</h2>
                            </div>
                        </div>
                        <div class="span8">
                            <div class="achievements__icon">
                                <img src="<?php echo theme_enlight_theme_url(); ?>/images/achievements-header-icon.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="achievements__content">
                <!-- <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span4 pull-right achievements__courses">
                            <ul>
                                <li><a data-course-id="5" href="#">טרום בסיסי א׳</a></li>
                                <li><a data-course-id="4" href="#">טרום בסיסי ב׳</a></li>
                                <li><a data-course-id="3" href="#">בסיסי</a></li>
                                <li><a data-course-id="2" href="#">מתקדמים א׳</a></li>
                            </ul>
                        </div>
                        <div class="span8 achievements__body">
                            <div class="achievements__list">
                                <ul>
                                    <li class="achievement">
                                        <div class="achievement__image">1</div>
                                        <div class="achievement__text">אות הצטיינות על השלמת יחידה 1</div>
                                    </li>
                                    <li class="achievement">
                                        <div class="achievement__image">2</div>
                                        <div class="achievement__text">אות הצטיינות על השלמת יחידה 2</div>
                                    </li>
                                    <li class="achievement">
                                        <div class="achievement__image">3</div>
                                        <div class="achievement__text">אות הצטיינות על השלמת יחידה 3</div>
                                    </li>
                                    <li class="achievement">
                                        <div class="achievement__image">4</div>
                                        <div class="achievement__text">אות הצטיינות על השלמת יחידה 4</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> -->
                <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
                
                
                ?>
            </div>
        </div>
    </section>

</div>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
<script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js"></script>
</body>
</html>
