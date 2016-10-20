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
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<div id="page" class="sign-up">
    <div class="form-tabs">
        <ul>
            <li><a href="<?php echo $CFG->httpswwwroot; ?>/login/index.php">התחברות</a></li>
            <li class="active">יצירת חשבון</li>
        </ul>
    </div>

    <div class="sign-up__content">
        <div class="sign-up__header"><h2><?php echo get_string('registration','theme_enlight'); ?></h2></div>
        <h4>פרטים אישיים</h4>
        <?php
        echo $OUTPUT->course_content_header();
        echo $OUTPUT->main_content();
        echo $OUTPUT->course_content_footer();
        ?>
    </div>

</div>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>

<script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js" /></script>
<script>
    $(document).ready(function() {
        // signup
        $("#id_profile_field_academicinstitute ~ ul.select-list").on("click", function() {
            var selected = this.getElementsByClassName("selected")[0].dataset.optionValue,
                $otherField = $("#fitem_id_profile_field_otherinstitute"),
                $role = $("#fgroup_id_profile_field_academicrole_grp");
            
            if (selected === "-1") {
                $otherField.slideDown(200);
                $role.slideUp(200);
            } else if (selected == "0") {
                $otherField.slideUp(200);
                $role.slideUp(200)
            } else {
                $otherField.slideUp(200);
                $role.slideDown(200)
            }
        });

        $(".sign-up .mform .fpassword").append("<div class='tip-icon'>?</div>");

        var passwordLabel = 'בסיסמא צריכים להיות לפחות 6 תוים ולכל היותר 20. אפשר להשתמש באותיות באנגלית, ספרוֹת ותוים מיוחדים כרצונך.';
        $(".sign-up .mform .fpassword").append(`<div class='tip'>${passwordLabel}</div>`);

        var $tip = $(".tip");
        $(".tip-icon").hover(function() {
            $tip.css("top", $(this).position().top - (30 + $tip.height()));
            $tip.addClass("show");
        }, function() {
            $tip.css("top", "-100px");
            $tip.removeClass("show");
        });
    });
</script>

</body>
</html>
