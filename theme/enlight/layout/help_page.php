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

 <?php require_once(dirname(__FILE__) . '/includes/bodyclasses.php');
    ?>
    <body <?php echo $OUTPUT->body_attributes($bodyclasses); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<div id="page" class="help-page">
    <section id="region-main">
        <div class="help">
            <header class="help__header">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="help__title">
                                <h3>איך נוכל לעזור?</h3>
                                <h5>נשמח לעזור לכם בכל עת, ברשותכם מאגר שאלות נפוצות או מוקד טלפוני לתמיכה טכנית</h5>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="help__icon">
                                <img src="<?php echo theme_enlight_theme_url(); ?>/images/help-header-icon.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
            ?>
        </div>
        
        <?php  /* pop up contact form
        <div id="help-popup" class="help-popup__form">
            <h2>תמיכה טכנית טופס פניה</h2>
            <p>אנא מלאו את השדות הבאים בשילוב פירוט הפניה ונשוב אליכם בהקדם</p>
            <form action="#" class="form">
                <div class="form__row">
                    <label for="">שם פרטי ומשפחה</label>
                    <input type="text">
                </div>
                <div class="form__row">
                    <label for="">טלפון</label>
                    <input type="text">
                </div>
                <div class="form__row">
                    <label for="">דוא״ל</label>
                    <input type="text">
                </div>
                <div class="form__row">
                    <label for="">פרטי הפניה</label>
                    <textarea name="" id="" cols="30" rows="10"></textarea>
                </div>
                <div class="form__row">
                    <label for="">אימות תווים</label>
                    <input type="text" class="captcha-input">
                    <div class="captcha">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/captcha-default.png" alt="">
                    </div>
                </div>
                <button class="form__button">שלחו פניה</button>
            </form>
        </div>
        */ 
        ?>
      
    </section>
</div>
<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
<script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js"></script>
</body>
</html>
