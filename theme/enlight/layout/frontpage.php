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
$no = get_config('theme_enlight', 'patternselect');

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

$PAGE->requires->js('/theme/enlight/javascript/bootstrap-carousel.js');
$PAGE->requires->js('/theme/enlight/javascript/bootstrap-transition.js');
$courserenderer = $PAGE->get_renderer('core', 'course');

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <?php echo custom_css(); ?>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no">
     
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>
<!--Custom theme header-->
<div class="page-wrapper">
<?php
require_once(dirname(__FILE__) . '/includes/top-banner.php');
?>
<!--Custom theme slider-->
   
<link rel="stylesheet" href="<?php echo theme_enlight_theme_url(); ?>/style/slick.css" />
<script src="<?php echo theme_enlight_theme_url(); ?>/javascript/slick.js"></script>


<!--New Courses-->
<?php 
//$courserenderer->new_courses(); ?> 
<!--E.O.New Courses-->

<!--Categories-->
<?php 
//$courserenderer->list_categories(); ?> 
<!--E.O.Categories-->
  
<!--Popular Courses-->
<?php

if ($no!=5){
	$courserenderer->popular_courses(); 
}else{

$courserenderer = $PAGE->get_renderer('core', 'course');
$content = $courserenderer->custom_course_frontpage(1); 
echo $content;
}
?>

<?php if (!isloggedin()){?>
<!-- Advantages -->

<div class="advantages">
    <h2 class="advantages__title">כל הכלים להצלחה- במקום אחד</h2>
    <div class="advantages__list">
        <div class="advantage">
            <div class="advantage__inner">
                <div class="advantage__img">
                    <a data-url="https://www.youtube.com/embed/96HHmILhyrE" class="advantage__link_old"><img src="<?php echo theme_enlight_theme_url(); ?>/images/advantage1.png" alt=""></a>
                </div>
                <div class="advantage__text">
                    <h3>מאות סרטוני וידאו</h3>
                    <h4>סרטוני וידאו שילוו אתכם צעד צעד </h4>
                    <h4>בלמידה עצמית בקצב שלכם, </h4>
                    <h4><b>בזמן שמתאים לכם, מכל מכשיר, בכל מקום.</b></h4>
                </div>
            </div>
        </div>
        <div class="advantage">
            <div class="advantage__inner">
                <div class="advantage__img">
                    <a data-url="https://www.youtube.com/embed/K926HAKRFvw" class="advantage__link_old"><img src="<?php echo theme_enlight_theme_url(); ?>/images/advantage2.png" alt=""></a>
                </div>
                <div class="advantage__text">
                    <h3>מעקב למידה אישי</h3>
                    <p>תרגולים ומבחנים לתרגול חוזר</p>
                    <p>כדי שתדעו <b>בדיוק למה לצפות</b> במבחן </p>
                </div>
            </div>
        </div>
        <div class="advantage">
            <div class="advantage__inner">
                <div class="advantage__img">
                    <a data-url="https://www.youtube.com/embed/yWRmWnPNkqU" class="advantage__link_old"><img src="<?php echo theme_enlight_theme_url(); ?>/images/advantage3.png" alt=""></a>
                </div>
                <div class="advantage__text">
                    <h3>מאגר אסטרטגיות למידה</h3>
                    <p>מאגר סרטונים קצרים וממוקדים המלמדים אסטרטגיות למידה (Learning Strategies) שיסיעו ללומדים בהבנת הנקרא (Reading Comprehension)</p>
                </div>
            </div>
        </div>
    </div>
    <div class="advantages__footer">
        <a class="button button--blue" href="<?php echo new moodle_url('/login/index.php')?>"><?php echo get_string('start-study','theme_enlight')?></a>
    </div>
</div>

<!-- E.O.Advantages -->
<?php } ?>
<!--E.O.Popular Courses-->

<div class="frontpage-siteinfo">
	<div class="siteinfo-bgoverlay">
		<div class="container-fluid">
<?php
$msp1title = theme_enlight_get_setting('mspot1title', 'format_text');
$msp1title = theme_enlight_lang($msp1title);
$msp1desc = theme_enlight_get_setting('mspot1desc', 'format_text');
$msp1desc = theme_enlight_lang($msp1desc);
echo '<h1>'.$msp1title.'</h1>';
echo '<p>'.$msp1desc.'</p>';
?>
   </div>
  </div>
</div>
<!-- Marketing Spot 1 -->

<!--Testimonials-->
<?php echo theme_enlight_tmonials(); ?>
<!--E.O.Testimonials-->

<?php
$msp2title = theme_enlight_get_setting('mspot2title', 'format_text');
$msp2title = theme_enlight_lang($msp2title);
$msp2desc = theme_enlight_get_setting('mspot2desc', 'format_text');
$msp2desc = theme_enlight_lang($msp2desc);
$msp2url = theme_enlight_get_setting('mspot2url');
$msp2urltxt = theme_enlight_get_setting('mspot2urltext', 'format_text');
$msp2urltxt = theme_enlight_lang($msp2urltxt);
?>
<div class="jumbo-viewall">
 <div class="container-fluid">
     <div class="inner-wrap">
         <div class="desc-wrap">
                <h2><?php echo $msp2title; ?></h2>
                <p><?php echo $msp2desc; ?></p>
            </div>
		<a href='<?php echo $msp2url; ?>' class="btn-jumbo"><?php echo $msp2urltxt; ?></a>
			</div>
 </div>
</div>
<!-- Marketing Spot 2 -->

</div>
<div id="page" class="container-fluid" style="display:none;">
    <header id="page-header" class="clearfix">
        <?php echo $html->heading; ?>
        <div id="page-navbar" class="clearfix">
            <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
        </div>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>
    <div id="page-content" class="row-fluid">

        <div id="<?php echo $regionbsid ?>" class="span9">
					<?php
                        echo $OUTPUT->course_content_header();
                        echo $OUTPUT->main_content();
                        echo $OUTPUT->course_content_footer();
?>
        </div>
				<?php echo $OUTPUT->blocks('side-pre', 'span3'); ?>

    </div>
</div>

<?php
if (right_to_left()) {
?>

<script>
$(function(){
	$(".new_courses").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#New-Courses .pagenav .slick-prev',
		nextArrow: '#New-Courses .pagenav .slick-next',
		rtl:true
	});
				
	var prow = $(".new_courses").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#New-Courses .pagenav").hide();
	}
	
	$(".list_categories").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#Listcategories .pagenav .slick-prev',
		nextArrow: '#Listcategories .pagenav .slick-next',
		rtl:true
	});
				
	var prow = $(".list_categories").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#Listcategories .pagenav").hide();
	}
	
				
	$(".popular_courses").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#Popular-Courses .pagenav .slick-prev',
		nextArrow: '#Popular-Courses .pagenav .slick-next',
		rtl:true
	});
				
	var prow = $(".popular_courses").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#Popular-Courses .pagenav").hide();
	}
	
	$("#Carouseltestimonials.carousel").carousel({
     	interval: 5000
  	});
				
});	
</script>	

<?php
} else {
?>
<script>
$(function(){
	$(".new_courses").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#New-Courses .pagenav .slick-prev',
		nextArrow: '#New-Courses .pagenav .slick-next'
	});
				
	var prow = $(".new_courses").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#New-Courses .pagenav").hide();
	}
	
	$(".list_categories").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#Listcategories .pagenav .slick-prev',
		nextArrow: '#Listcategories .pagenav .slick-next'
	});
				
	var prow = $(".list_categories").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#Listcategories .pagenav").hide();
	}
	
				
	$(".popular_courses").slick({
		arrows:true ,
		swipe:false,
		prevArrow:'#Popular-Courses .pagenav .slick-prev',
		nextArrow: '#Popular-Courses .pagenav .slick-next'
	});
				
	var prow = $(".popular_courses").attr("data-crow");
	prow = parseInt(prow);
	if(prow < 2)
	{
	  $("#Popular-Courses .pagenav").hide();
	}
	
	$("#Carouseltestimonials.carousel").carousel({
     	interval: 5000
  	});

});	
</script>

<?php
}

require_once(dirname(__FILE__) . '/includes/footer.php');  ?>   
<!--Custom theme footer-->

<script src="<?php echo theme_enlight_theme_url(); ?>/javascript/custom.js"></script>
<!--<script>
    $(document).ready(function() {

        $(".advantage__link").on("click", function() {
            var popup = new Popup( $(this).data("url") );
            popup.init();
        });

        toggleHeader();

        $(".fselect").each(function() {
            selectEmulation($(this));
        });

    });
</script>-->
</body>
</html>
