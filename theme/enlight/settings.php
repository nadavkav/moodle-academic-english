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
 * @copyright 2015 Nephzat Dev Team, nephzat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
$settings = null;

if (is_siteadmin()) {

    $ADMIN->add('themes', new admin_category('theme_enlight', 'Enlight'));

    /* General Settings */
    $temp = new admin_settingpage('theme_enlight_general', get_string('themegeneralsettings', 'theme_enlight'));

    $name = 'theme_enlight/patternselect';
    $title = get_string('patternselect' , 'theme_enlight');
    $description = get_string('patternselectdesc', 'theme_enlight');
    $default = 'default';
    $choices = array(
        'default' => 'Blue',
        '1' => 'Green',
        '2' => 'Lavender',
        '3' => 'Red',
        '4' => 'Purple',
    	'5' => 'Openu'
    );

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $pimg = array();
    global $CFG;

    $cp = $CFG->wwwroot.'/theme/enlight/pix/color/';
    $pimg = array($cp.'default.jpg', $cp.'pattern-1.jpg', $cp.'pattern-2.jpg',
            $cp.'pattern-3.jpg', $cp.'pattern-4.jpg',$cp.'pattern-5.jpg'
    );

    $themepattern = '<ul class="thumbnails theme-color-schemes">
	 <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[0].'" alt="default" width="100" height="100" />
        <h6>Blue</h6>
		</div>
	</li>
	 <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[1].'" alt="pattern1" width="100" height="100" />
       <h6>Green</h6>
		</div>
	 </li>
     <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[2].'" alt="pattern2" width="100" height="100" />
       <h6>Lavender</h6>
		</div>
	</li>
	  <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[3].'" alt="pattern3" width="100" height="100" />
	  <h6>Red</h6>
		</div>
	</li>
      <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[4].'" alt="pattern4" width="100" height="100" />
       <h6>Purple</h6>
		</div>
	  </li>
      <li class="span2">
	   <div class="thumbnail">
      <img src="'.$pimg[5].'" alt="pattern5" width="100" height="100" />
       <h6>Openu</h6>
		</div>
	  </li>
</ul>';

    $temp->add(new admin_setting_heading('theme_enlight_patternheading', '', $themepattern));

    /* Course Layout */
    $name = 'theme_enlight/courselayout';
    $title = get_string('courselayout' , 'theme_enlight');
    $description = get_string('courselayoutdesc', 'theme_enlight');
    $default = 'layout1';
    $choices = array(
        'default' => 'Default',
        'layout1' => get_string('layout', 'theme_enlight').' 1',
        'layout2' => get_string('layout', 'theme_enlight').' 2'
    );

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Course Layout */

    // Hide the course menu.
    $name = 'theme_enlight/cmenuhide';
    $title = get_string('cmenuhide', 'theme_enlight');
    $description = get_string('cmenuhidedesc', 'theme_enlight');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /* Login BG Image file setting */
    $name = 'theme_enlight/loginbg';
    $title = get_string('loginbg', 'theme_enlight');
    $description = get_string('loginbgdesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbg');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Logo file setting.
    $name = 'theme_enlight/logo';
    $title = get_string('logo', 'theme_enlight');
    $description = get_string('logodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    
    // Logo2 file setting.
    $name = 'theme_enlight/logo';
    $title = get_string('logo', 'theme_enlight');
    $description = get_string('logodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo2 file setting. yifatsh
    $name = 'theme_enlight/logo2';
    $title = get_string('logo2', 'theme_enlight');
    $description = get_string('logodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo2 Url.
    $name = 'theme_enlight/logo2url';
    $title = get_string('logo2url', 'theme_enlight');
    $description = get_string('logo2url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo 2 name.
    $name = 'theme_enlight/logo2name';
    $title = get_string('logo2name', 'theme_enlight');
    $description = get_string('logo2name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
       
    
    // Logo3 file setting. yifatsh
    $name = 'theme_enlight/logo3';
    $title = get_string('logo3', 'theme_enlight');
    $description = get_string('logodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo3');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo3 Url.
    $name = 'theme_enlight/logo3url';
    $title = get_string('logo3url', 'theme_enlight');
    $description = get_string('logo3url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo 3 name.
    $name = 'theme_enlight/logo3name';
    $title = get_string('logo3name', 'theme_enlight');
    $description = get_string('logo3name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo4 file setting. yifatsh
    $name = 'theme_enlight/logo4';
    $title = get_string('logo4', 'theme_enlight');
    $description = get_string('logodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo4');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo4 Url.
    $name = 'theme_enlight/logo4url';
    $title = get_string('logo4url', 'theme_enlight');
    $description = get_string('logo4url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Logo 4 name.
    $name = 'theme_enlight/logo4name';
    $title = get_string('logo4name', 'theme_enlight');
    $description = get_string('logo4name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    
    // Custom CSS file.
    $name = 'theme_enlight/customcss';
    $title = get_string('customcss', 'theme_enlight');
    $description = get_string('customcssdesc', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    // About Url.
    $name = 'theme_enlight/abouturl';
    $title = get_string('about', 'theme_enlight');
    $description = get_string('abouturldesc', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // support Url.
    $name = 'theme_enlight/supporturl';
    $title = get_string('support', 'theme_enlight');
    $description = get_string('supporturldesc', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    
    

    $ADMIN->add('theme_enlight', $temp);
    // General settings end.

    /* Footer Settings start */
    $temp = new admin_settingpage('theme_enlight_footer', get_string('footerheading', 'theme_enlight'));

    /* Footer Block1 */
    $name = 'theme_enlight_footerblock1heading';
    $heading = get_string('footerblock', 'theme_enlight').' 1 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlight/footerbtitle1';
    $title = get_string('footerblock', 'theme_enlight').' 1 - '.get_string('title', 'theme_enlight');
    $description = get_string('footerbtitledesc', 'theme_enlight');
    $default = 'lang:information';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_enlight/footerblink1';
    $title = get_string('footerblock', 'theme_enlight').' 1 - '.get_string('links', 'theme_enlight');
    $description = get_string('footerblink_desc', 'theme_enlight', array('blockno' => '1'));
    $default = get_string('footerblink1default', 'theme_enlight');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Footer Block1 */

    /* Footer Block2*/
    $name = 'theme_enlight_footerblock2heading';
    $heading = get_string('footerblock', 'theme_enlight').' 2 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlight/footerbtitle2';
    $title = get_string('footerblock', 'theme_enlight').' 2 - '.get_string('title', 'theme_enlight');
    $description = get_string('footerbtitledesc', 'theme_enlight');
    $default = 'lang:community';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_enlight/footerblink2';
    $title = get_string('footerblock', 'theme_enlight').' 2 - '.get_string('links', 'theme_enlight');
    $description = get_string('footerblink_desc', 'theme_enlight', array('blockno' => '2'));
    $default = get_string('footerblink2default', 'theme_enlight');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Footer Block2 */

    /* Footer Block3 */

    $name = 'theme_enlight_footerblock3heading';
    $heading = get_string('footerblock', 'theme_enlight').' 3 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlight/footerbtitle3';
    $title = get_string('footerblock', 'theme_enlight').' 3 - '.get_string('title', 'theme_enlight');
    $description = get_string('footerbtitledesc', 'theme_enlight');
    $default = 'lang:ourorg';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_enlight/footerblink3';
    $title = get_string('footerblock', 'theme_enlight').' 3 - '.get_string('links', 'theme_enlight');
    $description = get_string('footerblink_desc', 'theme_enlight', array('blockno' => '3'));
    $default = get_string('footerblink3default', 'theme_enlight');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Footer Block3 */

    /* Footer Block4 */
    $name = 'theme_enlight_footerblock4heading';
    $heading = get_string('footerblock', 'theme_enlight').' 4 ';
    $information = get_string('socialmediadesc', 'theme_enlight');
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlight/footerbtitle4';
    $title = get_string('footerblock', 'theme_enlight').' 4 - '.get_string('title', 'theme_enlight');
    $description = get_string('footerbtitledesc', 'theme_enlight');
    $default = 'lang:followuson';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Enable / Disable social media icon 1.
    $name = 'theme_enlight/siconenable1';
    $title = get_string('enable', 'theme_enlight').' '.get_string('socialicon', 'theme_enlight').' 1 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 1 - name.
    $name = 'theme_enlight/socialicon1';
    $title = get_string('socialicon', 'theme_enlight').' 1 ';
    $description = get_string('socialicondesc', 'theme_enlight');
    $default = get_string('socialicon1default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 1 - Background color.
    $name = 'theme_enlight/siconbgc1';
    $title = get_string('socialicon', 'theme_enlight').' 1 '.get_string('bgcolor', 'theme_enlight');
    $description = get_string('siconbgcdesc', 'theme_enlight');
    $default = get_string('siconbgc1default', 'theme_enlight');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social Media Icon Url 1.
    $name = 'theme_enlight/siconurl1';
    $title = get_string('socialicon', 'theme_enlight').' 1 '.get_string('url', 'theme_enlight');
    $description = get_string('siconurldesc', 'theme_enlight');
    $default = get_string('siconurl1default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 2.
    $name = 'theme_enlight/siconenable2';
    $title = get_string('enable', 'theme_enlight').' '.get_string('socialicon', 'theme_enlight').' 2 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 2 - name.
    $name = 'theme_enlight/socialicon2';
    $title = get_string('socialicon', 'theme_enlight').' 2 ';
    $description = get_string('socialicondesc', 'theme_enlight');
    $default = get_string('socialicon2default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 2 - Background color.
    $name = 'theme_enlight/siconbgc2';
    $title = get_string('socialicon', 'theme_enlight').' 2 '.get_string('bgcolor', 'theme_enlight');
    $description = get_string('siconbgcdesc', 'theme_enlight');
    $default = get_string('siconbgc2default', 'theme_enlight');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social Media Icon Url 2.
    $name = 'theme_enlight/siconurl2';
    $title = get_string('socialicon', 'theme_enlight').' 2 '.get_string('url', 'theme_enlight');
    $description = get_string('siconurldesc', 'theme_enlight');
    $default = get_string('siconurl2default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 3.
    $name = 'theme_enlight/siconenable3';
    $title = get_string('enable', 'theme_enlight').' '.get_string('socialicon', 'theme_enlight').' 3 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 3 - name.
    $name = 'theme_enlight/socialicon3';
    $title = get_string('socialicon', 'theme_enlight').' 3 ';
    $description = get_string('socialicondesc', 'theme_enlight');
    $default = get_string('socialicon3default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 3 - Background color.
    $name = 'theme_enlight/siconbgc3';
    $title = get_string('socialicon', 'theme_enlight').' 3 '.get_string('bgcolor', 'theme_enlight');
    $description = get_string('siconbgcdesc', 'theme_enlight');
    $default = get_string('siconbgc3default', 'theme_enlight');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social Media Icon Url 3.
    $name = 'theme_enlight/siconurl3';
    $title = get_string('socialicon', 'theme_enlight').' 3 '.get_string('url', 'theme_enlight');
    $description = get_string('siconurldesc', 'theme_enlight');
    $default = get_string('siconurl3default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 4.
    $name = 'theme_enlight/siconenable4';
    $title = get_string('enable', 'theme_enlight').' '.get_string('socialicon', 'theme_enlight').' 4 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 4 - name.
    $name = 'theme_enlight/socialicon4';
    $title = get_string('socialicon', 'theme_enlight').' 4 ';
    $description = get_string('socialicondesc', 'theme_enlight');
    $default = get_string('socialicon4default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social media icon 4 - Background color.
    $name = 'theme_enlight/siconbgc4';
    $title = get_string('socialicon', 'theme_enlight').' 4 '.get_string('bgcolor', 'theme_enlight');
    $description = get_string('siconbgcdesc', 'theme_enlight');
    $default = get_string('siconbgc4default', 'theme_enlight');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Social Media Icon Url 4.
    $name = 'theme_enlight/siconurl4';
    $title = get_string('socialicon', 'theme_enlight').' 4 '.get_string('url', 'theme_enlight');
    $description = get_string('siconurldesc', 'theme_enlight');
    $default = get_string('siconurl4default', 'theme_enlight');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
    /* Footer Block4 */

    // Copyright.
    $name = 'theme_enlight_copyrightheading';
    $heading = get_string('copyrightheading', 'theme_enlight');
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    // Copyright setting.
    $name = 'theme_enlight/copyright';
    $title = get_string('copyright', 'theme_enlight');
    $description = get_string('copyrightdesc', 'theme_enlight');
    $default = 'lang:copyrightdefault';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    
    
    ///yifatsh add partners logo
    
    $name = 'theme_enlight/partnerheader';
    $title = get_string('partnerheaderDesc', 'theme_enlight');
    $description = get_string('partnerheaderDesc', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    // Partner 1 file setting. yifatsh
    $name = 'theme_enlight/partner1logo';
    $title = get_string('partner1logo', 'theme_enlight');
    $description = get_string('parnterlogodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'partner1logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 1 name.
    $name = 'theme_enlight/partner1name';
    $title = get_string('partner1name', 'theme_enlight');
    $description = get_string('partner1name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 1 url.
    $name = 'theme_enlight/partner1url';
    $title = get_string('partner1url', 'theme_enlight');
    $description = get_string('partner1url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    

    // Partner 2 file setting. yifatsh
    $name = 'theme_enlight/partner2logo';
    $title = get_string('partner2logo', 'theme_enlight');
    $description = get_string('parnterlogodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'partner2logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 2 name.
    $name = 'theme_enlight/partner2name';
    $title = get_string('partner2name', 'theme_enlight');
    $description = get_string('partner1name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 2 url.
    $name = 'theme_enlight/partner2url';
    $title = get_string('partner2url', 'theme_enlight');
    $description = get_string('partner2url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    
    // Partner 3 file setting. yifatsh
    $name = 'theme_enlight/partner3logo';
    $title = get_string('partner3logo', 'theme_enlight');
    $description = get_string('parnterlogodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'partner3logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 3 name.
    $name = 'theme_enlight/partner3name';
    $title = get_string('partner3name', 'theme_enlight');
    $description = get_string('partner3name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 3 url.
    $name = 'theme_enlight/partner3url';
    $title = get_string('partner3url', 'theme_enlight');
    $description = get_string('partner3url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    //Partner 4 file setting. yifatsh
    $name = 'theme_enlight/partner4logo';
    $title = get_string('partner4logo', 'theme_enlight');
    $description = get_string('parnterlogodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'partner4logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 4 name.
    $name = 'theme_enlight/partner4name';
    $title = get_string('partner4name', 'theme_enlight');
    $description = get_string('partner4name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 4 url.
    $name = 'theme_enlight/partner4url';
    $title = get_string('partner4url', 'theme_enlight');
    $description = get_string('partner4url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    //Partner 5 file setting. yifatsh
    $name = 'theme_enlight/partner5logo';
    $title = get_string('partner5logo', 'theme_enlight');
    $description = get_string('parnterlogodesc', 'theme_enlight');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'partner5logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 5 name.
    $name = 'theme_enlight/partner5name';
    $title = get_string('partner5name', 'theme_enlight');
    $description = get_string('partner5name', 'theme_enlight');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // partner 5 url.
    $name = 'theme_enlight/partner5url';
    $title = get_string('partner5url', 'theme_enlight');
    $description = get_string('partner5url', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    
    
    
    $ADMIN->add('theme_enlight', $temp);
    /* Footer Settings end */

    /* Front Page Settings */
    $temp = new admin_settingpage('theme_enlight_frontpage', get_string('frontpageheading', 'theme_enlight'));

    /* Marketing Spot 1*/
    $name = 'theme_enlight_mspot1heading';
    $heading = get_string('marketingspot', 'theme_enlight').' 1 ('.get_string('aboutustxt', 'theme_enlight').')';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);
    // Marketing Spot 1 Title.
    $name = 'theme_enlight/mspot1title';
    $title = get_string('marketingspot', 'theme_enlight').' 1 - '.get_string('title', 'theme_enlight');
    $description = get_string('mspottitledesc', 'theme_enlight', array('msno' => '1'));
    $default = 'lang:aboutus';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Marketing Spot 1 Description.
    $name = 'theme_enlight/mspot1desc';
    $title = get_string('marketingspot', 'theme_enlight').' 1 - '.get_string('description');
    $description = get_string('mspotdescdesc', 'theme_enlight', array('msno' => '1'));
    $default = 'lang:aboutusdesc';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Marketing Spot 1*/

    /* Marketing Spot 2*/
    $name = 'theme_enlight_mspot2heading';
    $heading = get_string('marketingspot', 'theme_enlight').' 2 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);
    // Marketing Spot 2 Title.
    $name = 'theme_enlight/mspot2title';
    $title = get_string('marketingspot', 'theme_enlight').' 2 - '.get_string('title', 'theme_enlight');
    $description = get_string('mspottitledesc', 'theme_enlight', array('msno' => '2'));
    $default = 'lang:learnanytime';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    // Marketing Spot 2 Description.
    $name = 'theme_enlight/mspot2desc';
    $title = get_string('marketingspot', 'theme_enlight').' 2 - '.get_string('description');
    $description = get_string('mspotdescdesc', 'theme_enlight', array('msno' => '2'));
    $default = 'lang:learnanytimedesc';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    // Marketing Spot 2 Link.
    $name = 'theme_enlight/mspot2url';
    $title = get_string('marketingspot', 'theme_enlight').' 2 - '.get_string('link', 'theme_enlight');
    $description = get_string('mspot2urldesc', 'theme_enlight');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    // Marketing Spot 2 Link Text.
    $name = 'theme_enlight/mspot2urltext';
    $title = get_string('marketingspot', 'theme_enlight').' 2 - '.get_string('link', 'theme_enlight').' '
        .get_string('text', 'theme_enlight');
    $description = get_string('mspot2urltxtdesc', 'theme_enlight', array('msno' => '2'));
    $default = 'lang:viewallcourses';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    /* Marketing Spot 2*/

    $ADMIN->add('theme_enlight', $temp);
    /* Front Page Settings End */

    /* Slideshow Settings Start */

    $temp = new admin_settingpage('theme_enlight_slideshow', get_string('slideshowheading', 'theme_enlight'));
    $temp->add(new admin_setting_heading('theme_enlight_slideshow', get_string('slideshowheadingsub', 'theme_enlight'),
    format_text(get_string('slideshowdesc', 'theme_enlight'), FORMAT_MARKDOWN)));

    // Auto Scroll.
    $name = 'theme_enlight/autoslideshow';
    $title = get_string('autoslideshow', 'theme_enlight');
    $description = get_string('autoslideshowdesc', 'theme_enlight');
    $yes = get_string('yes');
    $no = get_string('no');
    $default = 1;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Slide Show Interval.
    $name = 'theme_enlight/slideinterval';
    $title = get_string('slideinterval', 'theme_enlight');
    $description = get_string('slideintervaldesc', 'theme_enlight');
    $default = 3000;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Number of slides.
    $name = 'theme_enlight/numberofslides';
    $title = get_string('numberofslides', 'theme_enlight');
    $description = get_string('numberofslidesdesc', 'theme_enlight');
    $default = 3;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
    );
    $temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $numberofslides = get_config('theme_enlight', 'numberofslides');
    for ($i = 1; $i <= $numberofslides; $i++) {
        // This is the descriptor for Slide One.
        $name = 'theme_enlight/slide' . $i . 'info';
        $heading = get_string('slideno', 'theme_enlight', array('slide' => $i));
        $information = get_string('slidenodesc', 'theme_enlight', array('slide' => $i));
        $setting = new admin_setting_heading($name, $heading, $information);
        $temp->add($setting);

        // Slide Image.
        $name = 'theme_enlight/slide' . $i . 'image';
        $title = get_string('slideimage', 'theme_enlight');
        $description = get_string('slideimagedesc', 'theme_enlight');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide' . $i . 'image');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Caption.
        $name = 'theme_enlight/slide' . $i . 'caption';
        $title = get_string('slidecaption', 'theme_enlight');
        $description = get_string('slidecaptiondesc', 'theme_enlight');
        $default = 'lang:slidecaptiondefault';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Link text.
        $name = 'theme_enlight/slide' . $i . 'urltext';
        $title = get_string('slideurltext', 'theme_enlight');
        $description = get_string('slideurltextdesc', 'theme_enlight');
        $default = 'lang:knowmore';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Url.
        $name = 'theme_enlight/slide' . $i . 'url';
        $title = get_string('slideurl', 'theme_enlight');
        $description = get_string('slideurldesc', 'theme_enlight');
        $default = 'http://www.example.com/';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Description Text.
        $name = 'theme_enlight/slide' . $i . 'desc';
        $title = get_string('slidedesc', 'theme_enlight');
        $description = get_string('slidedesctext', 'theme_enlight');
        $default = 'lang:slidedescdefault';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

    }

    $ADMIN->add('theme_enlight', $temp);
    /* Slideshow Settings End*/

    /*Testimonials Start*/
    $temp = new admin_settingpage('theme_enlight_tmonialhead', get_string('tmonialheading', 'theme_enlight'));

    // Display Slideshow.
    $name = 'theme_enlight/toggletmonial';
    $title = get_string('toggletmonial', 'theme_enlight');
    $description = get_string('toggletmonialdesc', 'theme_enlight');
    $yes = get_string('yes');
    $no = get_string('no');
    $default = 1;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_enlight/numberoftmonials';
    $title = get_string('numberoftmonials', 'theme_enlight');
    $description = get_string('numberoftmonials_desc', 'theme_enlight');
    $default = 4;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
        13 => '13',
        14 => '14',
        15 => '15',
        16 => '16'
    );
    $temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));
    // No of testimonials.
    $numberoftmonials = get_config('theme_enlight', 'numberoftmonials');

    for ($i = 1; $i <= $numberoftmonials; $i++) {
        // Testimonial Heading.
        $name = 'theme_enlight/testimonialno' . $i . 'info';
        $heading = get_string('testimonialno', 'theme_enlight', array('tmonial' => $i));
        $information = get_string('testimonialnodesc', 'theme_enlight', array('tmonial' => $i));
        $setting = new admin_setting_heading($name, $heading, $information);
        $temp->add($setting);

        // User Name.
        $name = 'theme_enlight/tmonial' . $i . 'uname';
        $title = get_string('tmonialuname', 'theme_enlight');
        $description = get_string('tmonialunamedesc', 'theme_enlight');
        $default = ($i == 1) ? get_string('tmonialuname_default', 'theme_enlight') : '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Testimonial Image.
        $name = 'theme_enlight/tmonial'. $i . 'img';
        $title = get_string('tmonialimg', 'theme_enlight');
        $description = get_string('tmonialimgdesc', 'theme_enlight');
        $default = 'tmonial'. $i . 'img';
        $setting = new admin_setting_configstoredfile($name , $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Testimonial text.
        $name = 'theme_enlight/tmonial' . $i . 'text';
        $title = get_string('tmonialtext', 'theme_enlight');
        $description = get_string('tmonialtextdesc', 'theme_enlight');
        $default = ($i == 1) ? get_string('tmonialtext_default', 'theme_enlight') : '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

    }

    $ADMIN->add('theme_enlight', $temp);
    /*Testimonials End*/

    /* Category Image */
    $temp = new admin_settingpage('theme_enlight_categoryimg', get_string('categoryimgheading', 'theme_enlight'));
    $temp->add(new admin_setting_heading('theme_enlight_categoryimg', get_string('categoryimgheadingsub', 'theme_enlight'),
    format_text(get_string('categoryimgdesc', 'theme_enlight'), FORMAT_MARKDOWN)));
    // Get all category IDs and their pretty names.
    require_once($CFG->libdir . '/coursecatlib.php');
    $coursecats = coursecat::make_categories_list();

    // Go through all categories and create the necessary settings.
    foreach ($coursecats as $key => $value) {
        // Category Icons for each category.
        $name = 'theme_enlight/categoryimg';
        $title = $value;
        $description = get_string('categoryimgcategory', 'theme_enlight', array('category' => $value));
        $default = 'categoryimg'.$key;
        $setting = new admin_setting_configstoredfile($name . $key, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);
    }
    unset($coursecats);

    $ADMIN->add('theme_enlight', $temp);
    /* Category Image */

}