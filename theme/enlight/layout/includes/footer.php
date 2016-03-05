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
$copyright = theme_enlight_get_setting('copyright');
$copyright = theme_enlight_lang($copyright);
$fb1title = theme_enlight_get_setting('footerbtitle1', 'format_text');
$fb1title = theme_enlight_lang($fb1title);
$fb2title = theme_enlight_get_setting('footerbtitle2', 'format_text');
$fb2title = theme_enlight_lang($fb2title);
$fb3title = theme_enlight_get_setting('footerbtitle3', 'format_text');
$fb3title = theme_enlight_lang($fb3title);
$fb4title = theme_enlight_get_setting('footerbtitle4', 'format_text');
$fb4title = theme_enlight_lang($fb4title);



$no = get_config('theme_enlight', 'patternselect');
if ($no!=5) {
?>

<footer id="site-footer">

	<div class="footer-main">
  	<div class="bgtrans-overlay"></div><!--Overlay transparent bg layer-->
    <div class="container-fluid footer-main-wrap">
      <div class="row-fluid">
        <div class="span3">
        	<h6><?php echo $fb1title; ?></h6>
          <div class="footer-links">
          	<ul>
             <?php echo theme_enlight_generate_links('footerblink1'); ?>
            </ul>
          </div>
        </div>
        <div class="span3">
        	<h6><?php echo $fb2title; ?></h6>
          <div class="footer-links">
          	<ul>
            <?php echo theme_enlight_generate_links('footerblink2'); ?>
            </ul>
          </div>
        </div>
        <div class="span3">
        	<h6><?php echo $fb3title; ?></h6>
          <div class="footer-links">
          	<ul>
             <?php echo theme_enlight_generate_links('footerblink3'); ?>
            </ul>
          </div>
        </div>
        <div class="span3">
          <h6><?php echo $fb4title; ?></h6>
          <div class="social-media">
          	<ul>
                <?php echo theme_enlight_social_links(); ?>
            </ul>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
	<div class="footer-bottom">
  	<div class="container">
      <p><?php echo $copyright; ?></p>
    </div>
  </div>
  
</footer>
<!--E.O.Footer-->

<?php

}else{


?>

<footer id="site-footer">
	
	<div class="footer-main">
  	  <div class="container-fluid footer-main-wrap">
        <div class="row-fluid">
         <?php
         	echo theme_enlight_get_partners_logoes();
         ?>
          
      </div>
    </div>
        <?php echo '<span class="guestuserera" >';
        if (!isloggedin()){
            //echo "not login ";
            $lo = new moodle_url('/login/index.php');
            echo '<a href="'.$lo.'">login</a>';
        }
        else{
            echo $USER->username;
            $lo = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
            echo '<a href="'.$lo.'"> Logout</a>';
        }
        echo '</span>';
        ?>
  </div>
  
	<div class="footer-bottom">
  	<div class="container">
      <p><?php
      		 
            echo '<a class="secretlogin" href="'.new moodle_url("/login/index.php").'" >'.
            get_string('login').'</a>';
            echo $copyright;
            $lo = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
            echo '<a class="secretlogin" href="'.$lo.'" >'.
            		get_string('logout').'</a>';
        ?>
        </p>
    </div>
  </div>
  
</footer>
<!--E.O.Footer-->
<?php 
}



?>
<?php  echo $OUTPUT->standard_end_of_body_html() ?>

<script src="<?php echo $CFG->wwwroot.'/theme/enlight/javascript/theme.js'; ?>" type="text/javascript"></script>
