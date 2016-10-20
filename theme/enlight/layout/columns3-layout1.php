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

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<script type="text/javascript" src="<?php echo $CFG->wwwroot."/theme/enlight/javascript/slick.js"; ?>"> </script>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot."/theme/enlight/style/slick.css"; ?>" />

<div id="custom-page">
  <div class="container-fluid">
    <div class="row-fluid">

        <div class="span3">
            <div class="custom-side-block">
                <div class="cbheader">
                    <h2><?php echo get_string('Categories','theme_enlight');?></h2>
                </div>
	            <?php echo theme_enlight_category_menu(); ?>
            </div>
        </div>
            
			 <div class="span9">
				  <?php
                    echo $OUTPUT->main_content();
                    ?>
              </div> 

    </div>
  </div>
</div>


<script>
$(function(){
	$(".vall").click(function(){
		var dh = $(this).attr("data-hide");
		var ds = $(this).attr("data-show");
		var dm = $(this).attr("data-main");
		$(dh).slideToggle();
		$(ds).slideToggle();
		$(dm+' .pagenav button').slideToggle();
		$(this).hide();
		
	});
});
</script>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>

</body>
</html>
