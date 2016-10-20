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
* @package    theme_enlight
* @copyright  2015 Nephzat Dev Team , nephzat.com
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
*/

/**
* Load the Jquery and migration files
* Load the our theme js file
*
*/
function theme_enlight_page_init(moodle_page $page) {
    global $CFG;
    $page->requires->jquery();
    $page->requires->jquery_plugin('migrate');
}

/**
* Loads the CSS Styles and replace the background images.
* If background image not available in the settings take the default images.
*
* @param $css
* @param $theme
* @return string
*/

function theme_enlight_process_css($css, $theme) {
    global $OUTPUT, $CFG;
    // Set BG Images.
    $bgimgs = array('testimonialsbg' => 'testimonialsbg.jpg', 'footbgimg' => 'footbgimg.jpg',
        'newcoursesbg' => 'newcoursesbg.jpg', 'popularcoursesbg' => 'popularcoursesbg.jpg'
        , 'aboutbg' => 'aboutbg.png', 'loginbg' => 'loginbg.jpg'
    );
    foreach ($bgimgs as $bgimg => $bgimgname) {
        $setting = $bgimg;
        $bgimage = $theme->setting_file_url($setting, $setting);
        if (empty($bgimage)) {
            $bgimage = $CFG->wwwroot.'/theme/enlight/pix/home/'.$bgimgname;
        }
        $css = theme_enlight_set_bgimg($css, $bgimage, $setting);
    }

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_enlight_set_customcss($css, $customcss);

    $css = theme_enlight_set_fontwww($css);
    return $css;
}

/**
* Loads the CSS and set the background images.
*
* @param string $css 
* @param string $bgimage
* @param string $setting
* @return string
*/
function theme_enlight_set_bgimg($css, $bgimage, $setting) {
    $tag = '[[setting:' . $setting . ']]';
    if (!($bgimage)) {
        $replacement = 'none';
    } else {
        $replacement = 'url(\'' . $bgimage . '\')';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

/**
* Serves any files associated with the theme settings.
*
* @param stdClass $course
* @param stdClass $cm
* @param context $context
* @param string $filearea
* @param array $args
* @param bool $forcedownload
* @param array $options
* @return bool
*/
function theme_enlight_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;
    $bgimgs = array('testimonialsbg', 'footbgimg', 'newcoursesbg', 'popularcoursesbg', 'aboutbg', 'loginbg');

    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {

        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'footerlogo') {
            return $theme->setting_file_serve('footerlogo', $args, $forcedownload, $options);
        } else if ($filearea === 'style') {
            theme_enlight_serve_css($args[1]);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
        } else if (preg_match("/slide[1-9][0-9]*image/", $filearea) !== false) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if (in_array($filearea, $bgimgs)) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
* Serves CSS for image file updated to styles.
*
* @param string $filename
* @return string
*/
function theme_enlight_serve_css($filename) {
    global $CFG;
    if (!empty($CFG->themedir)) {
        $thestylepath = $CFG->themedir . '/enlight/style/';
    } else {
        $thestylepath = $CFG->dirroot . '/theme/enlight/style/';
    }
    $thesheet = $thestylepath . $filename;

    /* http://css-tricks.com/snippets/php/intelligent-php-cache-control/ - rather than /lib/csslib.php as it is a static file who's
    contents should only change if it is rebuilt.  But! There should be no difference with TDM on so will see for the moment if
    that decision is a factor. */

    $etagfile = md5_file($thesheet);
    // File.
    $lastmodified = filemtime($thesheet);
    // Header.
    $ifmodifiedsince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
    $etagheader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    if ((($ifmodifiedsince) && (strtotime($ifmodifiedsince) == $lastmodified)) || $etagheader == $etagfile) {
        theme_enlight_send_unmodified($lastmodified, $etagfile);
    }
    theme_enlight_send_cached_css($thestylepath, $filename, $lastmodified, $etagfile);
}

// Set browser cache used in php header.
function theme_enlight_send_unmodified($lastmodified, $etag) {
    $lifetime = 60 * 60 * 24 * 60;
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: text/css; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

// Cached css.
function theme_enlight_send_cached_css($path, $filename, $lastmodified, $etag) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/configonlylib.php'); // For min_enable_zlib_compression().
    // 60 days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($path . $filename));
    }

    readfile($path . $filename);
    die;
}


/**
* Adds any custom CSS to the CSS before it is cached.
*
* @param string $css The original CSS.
* @param string $customcss The custom CSS to add.
* @return string The CSS which now contains our custom CSS.
*/
function theme_enlight_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
* Returns an object containing HTML for the areas affected by settings.
*
* Do not add Clean specific logic in here, child themes should be able to
* rely on that function just by declaring settings with similar names.
*
* @param renderer_base $output Pass in $OUTPUT.
* @param moodle_page $page Pass in $PAGE.
* @return stdClass An object with the following properties:
*      - navbarclass A CSS class to use on the navbar. By default ''.
*      - heading HTML to use for the heading. A logo if one is selected or the default heading.
*      - footnote HTML to use as a footnote. By default ''.
*/
function theme_enlight_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}

/**
* Loads the CSS Styles and put the font path
*
* @param $css
* @return string
*/
function theme_enlight_set_fontwww($css) {
    global $CFG, $PAGE;
    if (empty($CFG->themewww)) {
        $themewww = $CFG->wwwroot."/theme";
    } else {
        $themewww = $CFG->themewww;
    }

    $tag = '[[setting:fontwww]]';
    $theme = theme_config::load('enlight');
    $css = str_replace($tag, $themewww.'/enlight/fonts/', $css);
    return $css;
}

function theme_enlight_get_logo_url($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }

    $logo = $theme->setting_file_url('logo', 'logo');
    $logo = empty($logo) ? $OUTPUT->pix_url('home/logo', 'theme') : $logo;
    return $logo;
}

function theme_enlight_render_slideimg($p, $sliname) {
    global $PAGE, $OUTPUT;

    $nos = theme_enlight_get_setting('numberofslides');
    $i = $p % 3;
    $slideimage = $OUTPUT->pix_url('home/slide'.$i, 'theme');

    if (theme_enlight_get_setting($sliname)) {
        $slideimage = $PAGE->theme->setting_file_url($sliname, $sliname);
        return $slideimage;
    }
    return $slideimage;
}

function theme_enlight_get_setting($setting, $format = false) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/weblib.php');
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }
    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
* Render the current theme url
*
* @param void
* @return string
*/
function theme_enlight_theme_url() {
    global $CFG, $PAGE;
    $themeurl = $CFG->wwwroot.'/theme/'. $PAGE->theme->name;
    return $themeurl;
}

/**
* Display Footer Block Custom Links
* @param string $menu_name Footer block link name.
* @return string The Footer links are return.
*/

function theme_enlight_generate_links($menuname = '') {
    global $CFG, $PAGE;
    $htmlstr = '';
    $menustr = theme_enlight_get_setting($menuname);
    $menusettings = explode("\n", $menustr);
    foreach ($menusettings as $menukey => $menuval) {
        $expset = explode("|", $menuval);
        list($ltxt, $lurl) = $expset;
        $ltxt = trim($ltxt);
        $ltxt = theme_enlight_lang($ltxt);
        $lurl = trim($lurl);
        if (empty($ltxt)) {
            continue;
        }
        if (empty($lurl)) {
            $lurl = 'javascript:void(0);';
        }
        $pos = strpos($lurl, 'http');
        if ($pos === false) {
            $lurl = new moodle_url($lurl);
        }
        $htmlstr .= '<li><a href="'.$lurl.'">'.$ltxt.'</a></li>'."\n";
    }
    return $htmlstr;
}

/**
* Display Footer block Social Media links.
* 
* @return string The Footer Social Media links are return.
*/
function theme_enlight_social_links() {
    global $CFG;
    $totalicons = 4;
    $htmlstr = '';
    for ($i = 1; $i <= 4; $i++) {
        $iconenable = theme_enlight_get_setting('siconenable'.$i);
        $icon = theme_enlight_get_setting('socialicon'.$i);
        $iconcolor = theme_enlight_get_setting('siconbgc'.$i);
        $iconurl = theme_enlight_get_setting('siconurl'.$i);
        $iconstr = '';
        $iconsty = (empty($iconcolor)) ? '' : ' style="background: '.$iconcolor.';"';
        if ($iconenable == "1") {
            $iconstr = '<li class="media0'.$i.'"'.$iconsty.'><a href="'.$iconurl.'"><i class="fa fa-'.$icon.'"></i></a></li>'."\n";
            $htmlstr .= $iconstr;
        }
    }
    return $htmlstr;
}

/**
* Remove the html special tags from course content.
* This function used in course home page.
*
* @param string $text
* @return string
*/
function theme_enlight_strip_html_tags( $text ) {
    $text = preg_replace(
        array(
            // Remove invisible content.
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before and after blocks.
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text
    );
    return strip_tags( $text );
}

/**
* Cut the Course content.
*
* @param $str
* @param $n
* @param $end_char
* @return string
*/
function theme_enlight_course_trim_char($str, $n = 500, $endchar = '&#8230;') {
    if (strlen($str) < $n) {
        return $str;
    }

    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
    if (strlen($str) <= $n) {
        return $str;
    }

    $out = "";
    $small = substr($str, 0, $n);
    $out = $small.$endchar;
    return $out;
}

function theme_enlight_tmonials() {
    global $OUTPUT, $PAGE;

    $toggletmonial = theme_enlight_get_setting('toggletmonial');
    if (!$toggletmonial) {
        return '';
    }
    $strtmonial = '';
    $numberoftmonials = theme_enlight_get_setting('numberoftmonials');
    $atestimonials = array();
    $nouserimg = $OUTPUT->pix_url('no-user', 'theme');
    $titems = '';
    $indicators = '';

    for ($i = 1; $i <= $numberoftmonials; $i++) {
        $testimonial = theme_enlight_get_setting('tmonial'.$i.'text', 'format_text');
        $uname = theme_enlight_get_setting('tmonial'.$i.'uname');
        $uimg = $PAGE->theme->setting_file_url('tmonial'.$i.'img', 'tmonial'.$i.'img');
        $uimg = empty($uimg) ? $nouserimg : $uimg;

        if (!empty($testimonial) && !empty($uname) ) {
            $clstxt = ($i == 1) ? 'active ' : '';
            $titems .= '<div class="'.$clstxt.'item">
            <div class="item-content">
            <p>"'.$testimonial.'"</p>
            <div class="user-info">
            <div class="thumb"><img src="'.$uimg.'" width="128" height="128" alt="'.$uname.'"></div>
            <h6>'.$uname.'</h6>
            </div>
            </div>
            </div>';

            $clstxt1 = ($i == 1) ? ' class="active"' : '';
            $nodeno = $i - 1;
            $indicators .= '<li data-target="#Carousel-testimonials" data-slide-to="'.$nodeno.'" '.$clstxt1.'></li>';
        }
    }

    $strtestimonial = '<div class="frontpage-testimonials">
    <div class="bgtrans-overlay"></div>
    <div class="container-fluid">
    <div class="row-fluid">
    <div class="span8 offset2">
    <div id="Carouseltestimonials" class="carousel slide">
    <ol class="carousel-indicators" style="display:none;">
    '.$indicators.'
    </ol>
    <div class="carousel-inner">
    '.$titems.'
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>';

    return $strtestimonial;
}

function theme_enlight_category_menu() {
    global $CFG, $PAGE;
    $categoryid = optional_param('categoryid', null, PARAM_INT);
    $category = coursecat::get($categoryid);
    $html = '';
    if ($category === null) {
        $selectedparents = array();
        $selectedcategory = null;
    } else {
        $selectedparents = $category->get_parents();
        $selectedparents[] = $category->id;
        $selectedcategory = $category->id;
    }

    $catatlevel = \core_course\management\helper::get_expanded_categories('');
    $catatlevel[] = array_shift($selectedparents);
    $catatlevel = array_unique($catatlevel);

    require_once($CFG->libdir. '/coursecatlib.php');
    $listing = coursecat::get(0)->get_children();
    $html .= '<ul class="nav">';
    foreach ($listing as $listitem) {
        $subcategories = array();
        if (in_array($listitem->id, $catatlevel)) {
            $subcategories = $listitem->get_children();
        }
        $html .= theme_enlight_category_menu_item(
            $listitem,
            $subcategories,
            $listitem->get_children_count(),
            $selectedcategory,
            $selectedparents
        );
    }
    $html .= '</ul>';
    return $html;
}

function theme_enlight_category_menu_item(coursecat $category, array $subcategories, $totalsubcategories,
    $selectedcategory = null, $selectedcategories = array()) {

    $viewcaturl = new moodle_url('/course/index.php', array('categoryid' => $category->id));
    $text = $category->get_formatted_name();
    $isexpandable = ($totalsubcategories > 0);
    $isexpanded = (!empty($subcategories));
    $activecategory = ($selectedcategory === $category->id);
    $dataexpanded = $isexpanded ? ' data-expanded = "1" ' : ' data-expanded = "0"';

    if ($isexpanded) {
        $cls = $activecategory ? 'has-children expanded' : 'has-children';
    } else if ($isexpandable) {
        $cls = 'has-children';
    } else {
        $cls = $activecategory ? 'expanded' : '';
    }

    $html = '<li class="'.$cls.'"'.$dataexpanded.'>';
    $html .= '<a href="'.$viewcaturl.'">'.$text.'</a>';

    if (!empty($subcategories)) {
        $html .= '<ul class="nav childnav">';

        $catatlevel = \core_course\management\helper::get_expanded_categories($category->path);
        $catatlevel[] = array_shift($selectedcategories);
        $catatlevel = array_unique($catatlevel);

        foreach ($subcategories as $listitem) {
            $childcategories = (in_array($listitem->id, $catatlevel)) ? $listitem->get_children() : array();
            $html .= theme_enlight_category_menu_item(
                $listitem,
                $childcategories,
                $listitem->get_children_count(),
                $selectedcategory,
                $selectedcategories
            );
        }

        $html .= '</ul>';
    }
    $html .= '</li>';

    return $html;
}

function theme_enlight_get_courses_page1($categoryid="all", $sort="c.sortorder ASC", $fields="c.*",
    &$totalcount, $limitfrom="", $limitnum="") {

    global $USER, $CFG, $DB;
    $params = array();

    $categoryselect = "";
    if ($categoryid !== "all" && is_numeric($categoryid)) {
        $categoryselect = "WHERE c.category = :catid and c.visible = :visible and c.id != '1' ";
        $params['catid'] = $categoryid;
        $params['visible'] = '1';
    } else {
        $categoryselect = "WHERE  c.visible = :visible and c.id != '1' ";
        $params['visible'] = '1';
    }

    $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
    $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
    $params['contextlevel'] = CONTEXT_COURSE;

    $totalcount = 0;
    if (!$limitfrom) {
        $limitfrom = 0;
    }
    $visiblecourses = array();

    $sql = "SELECT $fields $ccselect
    FROM {course} c
    $ccjoin
    $categoryselect
    ORDER BY $sort";

    // Pull out all course matching the cat.
    $rs = $DB->get_recordset_sql($sql, $params);
    // Iteration will have to be done inside loop to keep track of the limitfrom and limitnum.
    foreach ($rs as $course) {
        context_helper::preload_from_record($course);
        if ($course->visible <= 0) {
            // For hidden courses, require visibility check.
            if (has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                $totalcount++;
                if ($totalcount > $limitfrom && (!$limitnum or count($visiblecourses) < $limitnum)) {
                    $visiblecourses [$course->id] = $course;
                }
            }
        } else {
            $totalcount++;
            if ($totalcount > $limitfrom && (!$limitnum or count($visiblecourses) < $limitnum)) {
                $visiblecourses [$course->id] = $course;
            }
        }
    }
    $rs->close();
    return $visiblecourses;

}

function theme_enlight_lang($key='') {
    $pos = strpos($key, 'lang:');
    if ($pos !== false) {
        list($l, $k) = explode(":", $key);
        $v = get_string($k, 'theme_enlight');
        return $v;
    } else {
        return $key;
    }

}




function theme_enlight_get_exta_logoes_url_old($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }

    $logo2 = $theme->setting_file_url('logo2', 'logo2');
    $logo2url = theme_enlight_get_setting('logo2url');	
    $logo2name = theme_enlight_get_setting('logo2name');
    $logo2url=false;
    if (empty($logo2)){
        $logo2='';
    }else{
        if (!empty($logo2url)){
            $logo2='<img src="'.$logo2.'" alt="'.$logo2name.'"   width="137" hegiht="77" class="img-responsive" >';
            $logo2='<a class="brand" href="'. $logo2url.'" title="'.$logo2name.'"  target="newtab"  >'.$logo2.'</a>';
        }else{
            $logo2='<img src="'.$logo2.'" alt="'.$logo2name.'" class="imgbrand   img-responsive">';
        }
        $logo2='<div class="span3">	'.$logo2.'</div>';
    }
    $logo3='';

    $logo3 = $theme->setting_file_url('logo3', 'logo3');
    $logo3url = theme_enlight_get_setting('logo3url');
    $logo3name = theme_enlight_get_setting('logo3name');
    $logo3url=false;
    if (empty($logo3)){
        $logo3='';
    }else{

        if (!empty($logo3url)){
            $logo3='<img src="'.$logo3.'" alt="'.$logo3name.'"  width="194" hegiht="77"   class="img-responsive" >';
            $logo3='<a class="brand" href="'. $logo3url.'" title="'.$logo3name.'" target="newtab"   >'.$logo3.'</a>';
        }else{
            $logo3='<img src="'.$logo3.'" alt="'.$logo3name.'" class="imgbrand" >';
        }
        $logo3='<div class="span3">	'.$logo3.'</div>';
    }

    $logo4='';

    $logo4 = $theme->setting_file_url('logo4', 'logo4');
    $logo4url = theme_enlight_get_setting('logo4url');
    $logo4name = theme_enlight_get_setting('logo4name');
    $logo4url=false;
    if (empty($logo4)){
        $logo4='';
    }else{

        if (!empty($logo4url)){
            $logo4='<img src="'.$logo4.'" alt="'.$logo4name.'"   width="114" hegiht="77"  class="img-responsive" >';
            $logo4='<a class="brand" href="'. $logo4url.'" title="'.$logo4name.'" target="newtab" >'.$logo4.'</a>';
        }else{
            $logo4='<img src="'.$logo4.'" alt="'.$logo4name.'" class="imgbrand" >';
        }
        $logo1='<div class="span3">	'.$logo4.'</div>';
    }
    $logo ='<div  class="headerlogoes" width ="300px">'.$logo2.$logo3.$logo4.'</div>';
    return $logo;
}


function theme_enlight_get_partners_logoes($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }

    $partners_title=theme_enlight_get_setting('partnerheader');
    if(empty($partners_title)){
        $partners_title="";
    }else{
        //$partners_title='<div class="span3 partnerheader"><h6>'.$partners_title.'</h6></div>';
        $partners_title='<h6>'.$partners_title.'</h6><br>';
    }


    $logo1 = $theme->setting_file_url('partner1logo', 'partner1logo');
    $logo1url = theme_enlight_get_setting('partner1url');
    $logo1name = theme_enlight_get_setting('partner1name');
    if (empty($logo1)){
        $logo1='';
    }else{
        $logo1='<img  class="partner1"  src="'.$logo1.'" alt="'.$logo1name.'">';
        if (!empty($logo1url)){
            $logo1='<a  href="'. $logo1url.'" title="'.$logo1name.'" target="newtab" >'.$logo1.'</a>';
        }
        //	$logo1='<div class="partner1">	'.$logo1.'</div>';

    }
    $logo2='';
    $logo2 = $theme->setting_file_url('partner2logo', 'partner2logo');
    $logo2url = theme_enlight_get_setting('partner2url');
    $logo2name = theme_enlight_get_setting('partner2name');
    if (empty($logo2)){
        $logo2='';
    }else{
        $logo2='<img class="partner2"  src="'.$logo2.'" alt="'.$logo2name.'">';
        if (!empty($logo2url)){
            $logo2='<a  href="'. $logo2url.'" title="'.$logo2name.'" target="newtab" >'.$logo2.'</a>';
        }
        //$logo2='<div class="partner2">'.$logo2.'</div>';
    }


    $logo3='';
    $logo3 = $theme->setting_file_url('partner3logo', 'partner3logo');
    $logo3url = theme_enlight_get_setting('partner3url');
    $logo3name = theme_enlight_get_setting('partner3name');
    if (empty($logo3)){
        $logo3='';
    }else{
        $logo3='<img  class="partner3" src="'.$logo3.'" alt="'.$logo3name.'">';
        if (!empty($logo3url)){
            $logo3='<a  href="'. $logo3url.'" title="'.$logo3name.'" target="newtab" >'.$logo3.'</a>';
        }
        //$logo3='<div class="span3">	<div class="footer-links">'.$logo3.'</div></div>';
    }
    $logo4='';
    $logo4 = $theme->setting_file_url('partner4logo', 'partner4logo');
    $logo4url = theme_enlight_get_setting('partner4url');
    $logo4name = theme_enlight_get_setting('partner4name');
    if (empty($logo4)){
        $logo4='';
    }else{
        $logo4='<img src="'.$logo4.'" alt="'.$logo4name.'">';
        if (!empty($logo4url)){
            $logo4='<a  href="'. $logo4url.'" title="'.$logo4name.'" target="newtab" >'.$logo4.'</a>';
        }
        $logo4='<div class="span3">	<div class="footer-links">'.$logo4.'</div></div>';
    }

    $logo5='';
    $logo5 = $theme->setting_file_url('partner5logo', 'partner5logo');
    $logo5url = theme_enlight_get_setting('partner5url');
    $logo5name = theme_enlight_get_setting('partner5name');
    if (empty($logo5)){
        $logo5='';
    }else{
        $logo5='<img src="'.$logo5.'" alt="'.$logo5name.'">';
        if (!empty($logo5url)){
            $logo5='<a  href="'. $logo5url.'" title="'.$logo5name.'" target="newtab" >.$logo5.</a>';
        }
        $logo5='<div class="span3">	<div class="footer-links">'.$logo5.'</div></div>';
    }

    $logo ='<div class="partner_footer">'.$partners_title.$logo1.$logo2.$logo3.$logo4.$logo5.'</div>';
    return $logo;
}

function theme_enlight_get_exta_logoes_url($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlight');
    }

    $logo2 = $theme->setting_file_url('logo2', 'logo2');
    $logo2url = theme_enlight_get_setting('logo2url');
    $logo2name = theme_enlight_get_setting('logo2name');

    if (empty($logo2)){
        $logo2='';
    }else{
        if (!empty($logo2url)){
            //$logo2='<div class="logoP1" >';
            $logo2='<a class="brand  logoP1 " href="'. $logo2url.'" title="'.$logo2name.'"  target="newtab"  ></a>';
        }else{
            //	$logo2='<img src="'.$logo2.'" alt="'.$logo2name.'" class="imgbrand   img-responsive">';
        }
    }

    $logo3 = $theme->setting_file_url('logo3', 'logo3');
    $logo3url = theme_enlight_get_setting('logo3url');
    $logo3name = theme_enlight_get_setting('logo3name');
    if (empty($logo3)){
        $logo3='';
    }else{

        if (!empty($logo3url)){
            $logo3='<a class="brand logoP2" href="'. $logo3url.'" title="'.$logo3name.'" target="newtab"   ></a>';
        }else{
            $logo3='<img src="'.$logo3.'" alt="'.$logo3name.'" class="imgbrand" >';
        }
    }



    $logo4 = $theme->setting_file_url('logo4', 'logo4');
    $logo4url = theme_enlight_get_setting('logo4url');
    $logo4name = theme_enlight_get_setting('logo4name');

    if (empty($logo4)){
        $logo4='';
    }else{

        if (!empty($logo4url)){
            $logo4='<a class="brand logoP3" href="'. $logo4url.'" title="'.$logo4name.'" target="newtab" ></a>';
        }else{
            $logo4='<img src="'.$logo4.'" alt="'.$logo4name.'" class="imgbrand" >';
        }
    }

    $logo = $logo4.$logo3.$logo2;
    return $logo;
}

//temporrary 
function custom_css(){
    return '<link rel="stylesheet" href="'.theme_enlight_theme_url().'/style/custom.css?ver='.rand(1,100).'" />';
}

function csv_to_array($filename, $delimiter=','){
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $arr_parser = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $arr_parser[] = array_combine($header, $row);
        }
        fclose($handle);
    }     

    return $arr_parser;

}

function csv_to_array_by_course_lesson_section($filename, $course, $lesson, $section, $delimiter=','){

    $arrIconData = array(
        "Dictionary"=>array("id"=>54, "name"=>"Dictionary", "file"=>"8"),
        "Pre Reading"=>array("id"=>55, "name"=>"Pre Reading", "file"=>"5"),
        "Main idea VS topic"=>array("id"=>56, "name"=>"Main idea VS topic", "file"=>"1"),
        "Ideas & supporting details"=>array("id"=>58, "name"=>"Ideas & supporting details", "file"=>"7"),
        "Purpose"=>array("id"=>59, "name"=>"Purpose", "file"=>"10"),
        "Reference Words"=>array("id"=>60, "name"=>"Reference Words", "file"=>"14"),
        "Listing"=>array("id"=>61, "name"=>"Listing", "file"=>"6"),
        "Sequence of events"=>array("id"=>62, "name"=>"Sequence of events", "file"=>"4"),
        "Comparison & Contrast"=>array("id"=>63, "name"=>"Comparison & Contrast", "file"=>"9"),
        "Cause & Result"=>array("id"=>64, "name"=>"Cause & Result", "file"=>"2"),
        "Research"=>array("id"=>65, "name"=>"Research", "file"=>"3"),
        "Sentence Structure"=>array("id"=>66, "name"=>"Sentence Structure", "file"=>"11"),
        "Parts of Speech"=>array("id"=>67, "name"=>"Parts of Speech", "file"=>"12"),
    );

    $cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'core', 'string');


    if (!$arr_parser = $cache->get('strategics')) {

        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $arr_parser = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $arr_parser[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $cache->set('strategics',$arr_parser);
    }  

    if (!empty($arr_parser)){

        $arr_tmp = array();    
        foreach($arr_parser as $item){
            if($item['course'] == $course && $item['section'] == $section && $item['lesson'] == $lesson){
                $arr_tmp = $item;
            }          
        }

        if (!empty($arr_tmp)){
            unset($arr_tmp['lesson']); unset($arr_tmp['section']); unset($arr_tmp['course']); 

            $arrResult = array();
            foreach($arr_tmp as $name=>$val){
                if($val == 1){
                    $arrResult[] = $arrIconData[$name];       
                }
            }

            return $arrResult;

        }else return FALSE; 

    }else return FALSE;

}

function getCurrentSection(){
    global $PAGE;
    $currentSection=0;
    $modinfo = get_fast_modinfo($PAGE->course);
    foreach ($modinfo->get_section_info_all() as $section => $thissection) {
        if ($section == 0) {
            continue;   
        }
        $showsection = $thissection->uservisible ||
        ($thissection->visible && !$thissection->available &&
            !empty($thissection->availableinfo));
        if (!$showsection) {
            continue;
        }
        $sectionmodnumbers = $modinfo->sections[$thissection->section];
        if (in_array($PAGE->cm->id,$sectionmodnumbers)){
            $currentSection=$section;
        }
    }

    return $currentSection;

}
