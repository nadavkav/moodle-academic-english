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
 *
 * @package     theme_enlight
 * @copyright   2015 Nephzat Dev Team,nephzat.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$numberofslides = theme_enlight_get_setting('numberofslides');
$autoslideshow = theme_enlight_get_setting('autoslideshow');
$slideinterval = theme_enlight_get_setting('slideinterval');
$slideinterval = intval($slideinterval);
$slideinterval = empty($slideinterval) ? 3000 : $slideinterval;

if ($numberofslides) {
?>

<div class="home-page-carousel">

  <div id="homepage-carousel" class="carousel slide">
    <ol class="carousel-indicators">
<?php 
    for ($s = 0; $s < $numberofslides; $s++) {
        $clstxt = ($s == "0") ? ' class="active"' : '';
?>
       <li data-target="#homepage-carousel" data-slide-to="<?php echo $s; ?>" <?php echo $clstxt; ?>></li>
<?php
    }
?>
    </ol>
    <div class="carousel-inner">
<?php
    for ($s1 = 1; $s1 <= $numberofslides; $s1++) {
        $clstxt2 = ($s1 == "1") ? ' active' : '';
        $slidecaption = theme_enlight_get_setting('slide' . $s1 . 'caption', true);
        $slideurl = theme_enlight_get_setting('slide' . $s1 . 'url');
        $slideurltext = theme_enlight_get_setting('slide' . $s1 . 'urltext');
        $slidedesc = theme_enlight_get_setting('slide' . $s1 . 'desc', 'format_html');
        $slideimg = theme_enlight_render_slideimg($s1, 'slide' . $s1 . 'image');

        $slidecaption = theme_enlight_lang($slidecaption);
        $slideurltext = theme_enlight_lang($slideurltext);
        $slidedesc = theme_enlight_lang($slidedesc);
?>
    
      <div class="item<?php echo $clstxt2; ?>" style="background-image: url(<?php echo $slideimg; ?>);">

<div class="item-inner container-fluid">
	<div class="carousel-content">
    	<h2><?php echo $slidecaption; ?></h2>
        <p><?php echo $slidedesc; ?></p>
        <div class="carousel-btn"><a href="<?php echo $slideurl; ?>">
		<?php echo $slideurltext; ?></a></div>
    </div>
</div>

      </div>
      
<?php
    }
?>
      
    </div>
    <a data-slide="prev" href="#homepage-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
    <a data-slide="next" href="#homepage-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
  </div>

</div>
<!--E.O.carousel-->
<?php
    if ($autoslideshow) {
?>
<script>
var interval = <?php echo $slideinterval; ?>;
  $(function(){
  	$('#homepage-carousel.carousel').carousel({
     	interval: interval
  	});
  });
 </script>
<?php
    }
}