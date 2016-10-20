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

<div id="page" class="strategies-page">

<section id="region-main" class="strategies">
        <div class="strategies__header">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span7">
                        <div class="strategies__title">
                            <h2>מאגר אסטרטגיות הלמידה</h2>
                            <p>מאגר סרטונים קצרים וממוקדים המלמדים אסטרטגיות למידה (Learning Strategies) שיסיעו ללומדים בהבנת הנקרא (Reading Comprehension) של טקסטים אקדמיים באנגלית.</p>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="strategies__icon">
                            <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategies-header-icon.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="strategies__content">
            <div class="container-fluid">
                <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
                ?>
              
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-1.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Parts of speech
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-2.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Sentence structure
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-3.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Research
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-4.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Cause & result
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-5.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Comparison & cotrast
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-6.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Sequence of events
                    </div>
                </div>
                <div class="strategy">
                    <div class="strategy__icon">
                        <img src="<?php echo theme_enlight_theme_url(); ?>/images/strategy-icon-7.png" alt="">
                    </div>
                    <div class="strategy__text">
                        <span class="strategy__text__duration"><i class="play-icon"></i> 03:25</span> Listing
                    </div>
                </div>
               
            </div>
        </div>
        <?php
       // echo $OUTPUT->course_content_header();
//        echo $OUTPUT->main_content();
//        echo $OUTPUT->course_content_footer();
        ?>
    </section>
</div>
<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
</body>
</html>
