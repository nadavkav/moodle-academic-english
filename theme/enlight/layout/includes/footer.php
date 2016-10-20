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
?>
  
<footer id="site-footer">
  
  <div class="footer-main">
      <div class="footer-main-wrap">
        <div class="partner-logos">
          <h6>פותח והופק על ידי:</h6>
          <ul>
            <li><a href="http://telem.openu.ac.il/" target="_blank"><img src="<?php echo theme_enlight_theme_url(); ?>/images/logo1.png" alt=""></a></li>
            <li><a href="http://academic.openu.ac.il/english/Pages/default.aspx" target="_blank"><img src="<?php echo theme_enlight_theme_url(); ?>/images/logo2.png" alt=""></a></li>
          </ul>
        </div>
        <div class="copyright">
          <p><?php echo $copyright;  ?></p>
        </div>
    </div>
       
  </div>
  
  <!-- <div class="footer-bottom">
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
  </div> -->
  
</footer>
<!--E.O.Footer-->

<?php  echo $OUTPUT->standard_end_of_body_html() ?>

<script src="<?php echo $CFG->wwwroot.'/theme/enlight/javascript/theme.js'; ?>" type="text/javascript"></script>
