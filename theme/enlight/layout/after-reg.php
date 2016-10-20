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
global $USERCUSTOM;
$username = urlencode($USERCUSTOM->username);
$username = str_replace('.', '%2E', $username);
$urlConfirm = $CFG->wwwroot .'/login/confirm.php?data='. $USERCUSTOM->secret .'/'. $username;
$firstname = fullname($USERCUSTOM);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
    <head>
        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
        <?php echo $OUTPUT->standard_head_html() ?>
        <?php echo custom_css(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body <?php echo $OUTPUT->body_attributes(); ?>>

        <?php echo $OUTPUT->standard_top_of_body_html() ?>

        <?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

        <div id="page" class="after-reg-page">
            <div class="after-reg" id="page-content">
                <div class="after-reg__header">
                    <img src="<?php echo theme_enlight_theme_url(); ?>/images/after-reg-header.png" alt="">
                </div>
                <section class="after-reg__content" id="region-main">
                    <div class="container-fluid">
                        <div role="main">
                            <span id="maincontent"></span>
                            <div class="box generalbox" id="notice">

                                <h3> תודה על הרשמתך לאתר אנגלית אקדמית.  </h3>
                                <h4>
                                    בדקות הקרובות יישלח אליך מייל לאישור ההרשמה.
                                    
                                    לימוד נעים!
                                </h4>           
                                
                            </div>
                        </div>


                    </div>
                </section>
            </div>

            <div style="display: none;">
                <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();     
                echo $OUTPUT->course_content_footer();
                ?>
            </div>
        </div>

        <?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>

    </body>
</html>
