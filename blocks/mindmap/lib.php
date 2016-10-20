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
  * Mindmap Course - Block which displays course content as interactive,
  * personalized mindmap
  *
  * @package    block_mindmap
  * @copyright  2014 onwards Łukasz Sanokowski, Barbara Dębska
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */

function block_mindmap_create_file($filename, $path, $contextblock) {

    global $CFG, $COURSE, $USER, $DB, $PAGE;

    require_once($CFG->dirroot.'/lib/filelib.php');
    require_once($CFG->dirroot.'/lib/datalib.php');

    $modinfo = get_fast_modinfo($COURSE);
    $context = context_course::instance($COURSE->id);
    $course = $DB->get_record('course', array('id' => $COURSE->id), '*', MUST_EXIST);
    $course = course_get_format($course)->get_course();
    $coursesections = get_fast_modinfo($COURSE->id, 0)->get_section_info_all();

    $content  = "<map version=\"0.8.1\">\n";
    $content .= "\t<node TEXT=\"    ".str_replace('"', '', $COURSE->fullname)."  \" LINK=\"".$CFG->wwwroot.'/course/view.php?id='.
    $COURSE->id."\">\n";
    $content .= "\t<font BOLD=\"true\" SIZE=\"15\"/>\n";
    $sectionsvisible = 0;
    $hiddensections = $DB->get_field('course_format_options', 'value', array('courseid' => $COURSE->id ,
    'name' => 'hiddensections'));

    foreach ($coursesections as $section) {
        if ($section->section <= $course->numsections) {
            if ( ($section->visible == 1) OR has_capability('moodle/course:viewhiddensections', $context)
            OR $hiddensections == 0  ) {
                if (!empty($section->sequence) OR !empty($section->summary)) {
                        $sectionsvisible++;
                }
            }
        }
    }
    $currentsection = 0;
    foreach ($coursesections as $section) {
        if ($section->section <= $course->numsections) {
            if ($section->visible == 1 or has_capability('moodle/course:viewhiddensections', $context) or $hiddensections == 0 ) {
                if (($currentsection <= $sectionsvisible) && (!empty($section->sequence) OR !empty($section->summary))) {
                    if ( $section->visible == 0 AND !has_capability('moodle/course:viewhiddensections', $context)) {
                        if ($hiddensections == 0) {
                            $content .= block_mindmap_print_section($section, $sectionsvisible, 1);
                            $currentsection++;
                            continue;
                        }
                    } else {
                        $content .= block_mindmap_print_section($section, $sectionsvisible, 0);
                        $currentsection++;
                    }
                }
            }
        }
    }
    $content .= "\t</node>\n";
    $content .= "</map>\n";
    $content = block_mindmap_change_chars($content);

    $file = fopen($path.$filename.".mm", "w");
    fwrite($file, $content);
    fclose($file);

}

function block_mindmap_print_section($section, $sectionsvisible, $notavailable) {
    global $COURSE, $CFG, $DB;
    if ($section->section < $sectionsvisible / 2) {
        $leftorright = "left";
    } else {
        $leftorright = "right";
    }
    // First half of sections will be on left side of map, second half on right side.

    if (!empty($section->name)) {
        $title = $section->name;
    } else {
        $title = str_replace('"', '', trim(strip_tags($section->summary)));
        $shortened = 0;
        if (strlen($title) > 120) {
            $title = substr($title, 0, 120); $shortened = 1;
        }
            $words = explode(" ", $title);
            $title = implode(" ", array_splice($words, 0, 8));
        if ($shortened == 1) {
            $title .= " (..)";
        }
    }
    if ($notavailable) {
        $content = "\t\t\t\t<node TEXT=\"".get_string('notavailable', 'core')."\" POSITION=\"".$leftorright."\"></node>\n";
    } else {
        $content  = "\t\t\t\t<node TEXT=\"".$section->section.". ".$title."\" POSITION=\"".$leftorright."\" LINK=\"".$CFG->wwwroot.
        '/course/view.php?id='.$COURSE->id."#section-".$section->section."\" >\n";
        // Trim removes newlines so structure of map file is clean.
        $content .= block_mindmap_pamfs($section);
        $content .= "\t\t\t\t<font NAME=\"SansSerif\" SIZE=\"14\"/>\n";
        $content .= "\t\t</node>\n";
    }
    return $content;

}

function block_mindmap_pamfs($section) { // Function print_all_modules_from_section.
    global $COURSE;
    $content = "";
    $sectionmods = explode (",", $section->sequence);
    $modinfo = get_fast_modinfo($COURSE);

    foreach ($sectionmods as $modnumber) {
        $content .= block_mindmap_print_module($modnumber, $modinfo);
    }
    return $content;
}

function block_mindmap_print_module($modnumber, $modinfo) {

    global $DB, $CFG, $COURSE;
    $mods = get_fast_modinfo($COURSE->id)->get_cms();

    if (!empty($mods[$modnumber])) {
            $mod = $mods[$modnumber];
    } else {
        return "";
    }
    $content = "";

    if (isset($modinfo->cms[$modnumber])) {

        if (!$modinfo->cms[$modnumber]->uservisible &&
            (empty($modinfo->cms[$modnumber]->showavailability) || empty($modinfo->cms[$modnumber]->availableinfo))) {
            return "";
        }
    }

    if (!$cm = $DB->get_record('course_modules', array('id' => $modnumber))) {
        return "";
    }

    $modname = $DB->get_field('modules', 'name', array('id' => $cm->module));
    if (!file_exists($CFG->dirroot.'/mod/'.$modname.'/lib.php')) {
        return true; // Check if module is installed.
    }

    $modcontext = context_module::instance($modnumber);
    $canviewhidden = has_capability('moodle/course:viewhiddenactivities', $modcontext);
    $accessiblebutdim = false;
    $conditionalhidden = '';
    if (!empty($CFG->enableavailability)) {
        $conditionalhidden = $mod->availablefrom > time() ||
        ($mod->availableuntil && $mod->availableuntil < time()) ||
        count($mod->conditionsgrade) > 0 ||
        count($mod->conditionscompletion) > 0;
    }

    $hascapabilityviewhiddenactivities = has_capability('moodle/course:viewhiddenactivities',
                        context_course::instance($mod->course));

            $accessiblebutdim = (!$mod->visible || $conditionalhidden) &&
                (!$mod->uservisible || $hascapabilityviewhiddenactivities);

    $name = $DB->get_field('modules', 'name', array('id' => $cm->module));

    switch ($name) {

        case "resource":
            $content .= block_mindmap_print_resource($cm, $modname, $accessiblebutdim, $modinfo->cms[$modnumber]->uservisible,
            $hascapabilityviewhiddenactivities);
        break;

        default:
            if ($name == "label") {
                break;
            }
            $content .= block_mindmap_print_fa($cm, $modname, $accessiblebutdim, $modinfo->cms[$modnumber]->uservisible,
            $hascapabilityviewhiddenactivities);
    }

    return $content;
}

function block_mindmap_print_fa($cm, $type, $accessiblebutdim, $uservisible, $hascapabilityviewhiddenactivities) {
    global $COURSE, $USER, $CFG, $DB;
    require_once($CFG->dirroot.'/lib/filelib.php');
    require_once($CFG->libdir . '/gradelib.php');
    require_once($CFG->libdir . '/conditionlib.php');
    $content = "";
    $module = $DB->get_record($type, array('id' => $cm->instance));
    if ($accessiblebutdim) {
        $color = ' COLOR="#C0C0C0" ';
    } else {
        $color = '';
    }
    $grade = grade_get_grades($COURSE->id, 'mod', $type, $cm->instance, $USER->id);
    $keywords = "";
    if (!empty($module->content)) {
        $keywords = block_mindmap_print_lesson_page($module->content);
    }
    $coloricontimeleft = new stdClass();
    $coloricontimeleft = block_mindmap_grade_icon($grade, $cm);
    $colortemp = $color;
    $icontemp = null;
    $timeleftnodetemp = null;
    if (!empty($coloricontimeleft->color)) {
        $colortemp = $coloricontimeleft->color;
    }
    if (!empty($coloricontimeleft->icon)) {
        $icontemp = $coloricontimeleft->icon;
    }
    if (!empty($coloricontimeleft->timeleftnode)) {
        $timeleftnodetemp = $coloricontimeleft->timeleftnode;
    }

    $folditem = '';
    if (block_mindmap_fold_item($grade, $keywords) != "") {
        $folditem = block_mindmap_fold_item($grade, $keywords);
    } else {
        if (!empty($coloricontimeleft->timeleftnode)) {
            $folditem = " FOLDED=\"true\" ";
        }
    }
    $content .= "\t\t\t<node ID=\"".$cm->id."\"".
    $colortemp." TEXT=\"".block_mindmap_print_mod_icon($type).str_replace('"', '', $module->name)."\" ".
    block_mindmap_get_link_to_module($type, $cm->id, $accessiblebutdim, $uservisible)." ".
    $folditem
    .">\n";
    $content .= $icontemp;

    if ($type == 'lesson') {
        $content .= block_mindmap_get_lesson_dependency($cm);
        $content .= block_mindmap_print_lesson_pages($cm, $accessiblebutdim, $hascapabilityviewhiddenactivities);
    }

    if ($type == 'book' && !$accessiblebutdim) {
        require_once($CFG->dirroot.'/mod/book/locallib.php');
        $book   = $DB->get_record('book', array('id' => $cm->instance), '*', MUST_EXIST);
        $chapters = book_preload_chapters($book);
        foreach ($chapters as $chapter) {
            if ($chapter->subchapter == 0) {
                $content .= block_mindmap_print_book_chapter($chapter, $chapters, $cm);
            }
        }

    }

    $content .= block_mindmap_print_conditional_availability_arrows($cm);
    $content .= "\t\t\t\t\t\t\t<font NAME=\"SansSerif\" SIZE=\"13\" ".block_mindmap_check_if_visited($cm, null)." />\n";
    if (!empty($keywords)) {
        $content .= $keywords;
    }
    if ($timeleftnodetemp) {
        $content .= "\t\t\t\t<node TEXT=\"".$timeleftnodetemp ."\"><font NAME=\"SansSerif\" SIZE=\"12\"/></node>\n";
    }
    $content .= "\t\t\t</node>\n";
    return $content;
}

function block_mindmap_print_book_chapter($chapter, $chapters, $cm) {

    global $DB, $CFG;
    $chaptercontent = $DB->get_field('book_chapters', 'content', array('bookid' => $cm->instance, 'pagenum' => $chapter->pagenum ));
    $content = '';
    $content .= "\t\t\t\t\t\t<node TEXT=\"".str_replace('"', '', $chapter->title)."\" LINK=\"".$CFG->wwwroot.
    "/mod/book/view.php?id=".$cm->id."&amp;chapterid=".$chapter->id."\"><font NAME=\"SansSerif\" ".
    block_mindmap_check_if_visited($cm, $chapter->id)." SIZE=\"12\" />".block_mindmap_print_lesson_page($chaptercontent);
    if (!empty ($chapter->subchapters)) {
        foreach ($chapter->subchapters as $subchapter) {
                $content .= block_mindmap_print_book_chapter($chapters[$subchapter], 0, $cm);
        }
    }
    $content .= "</node>\n";
    return $content;
}

function block_mindmap_print_resource($cm, $type, $accessiblebutdim, $uservisible) {
    global $DB, $COURSE;
    $content = "";
    $module = $DB->get_record($type , array('id' => $cm->instance));
    $content .= "\t\t\t<node TEXT=\"".block_mindmap_print_mod_icon($type).str_replace('"', '', $module->name)."\" ".
    block_mindmap_get_link_to_module("resource", $cm->id, $accessiblebutdim, $uservisible)." >\n";
    $content .= block_mindmap_print_conditional_availability_arrows($cm);
    $content .= "\t\t\t\t\t\t\t<font NAME=\"SansSerif\" SIZE=\"13\" ".block_mindmap_check_if_visited($cm, null)." />\n";
    $content .= "\t\t\t</node>\n";
    return $content;
}

function block_mindmap_check_if_visited($cm, $lessonorbookpagenumber) { // If not visisted, font of module will be bolded.
    global $USER, $COURSE, $DB;
    $content = "";
    if (empty($lessonorbookpagenumber)) { // If current module is not lesson page and not book page.
        if (!$DB->get_field('log', 'time', array('course' => $COURSE->id, 'userid' => $USER->id, 'cmid' => $cm->id),
        IGNORE_MULTIPLE)) {
            $content = " BOLD=\"true\" ";
        }
    } else { // If it is lesson page.
        if (!$DB->get_field('log', 'time', array('course' => $COURSE->id, 'userid' => $USER->id, 'cmid' => $cm->id,
        'info' => $lessonorbookpagenumber), IGNORE_MULTIPLE)) {
            $content = " BOLD=\"true\" ";
        }
    }

    return $content;
}

function block_mindmap_get_link_to_module($modulename, $moduleid, $accessiblebutdim, $uservisible) {

    if ($accessiblebutdim && empty($uservisible)) {
        return "";
    }
    global $CFG;
    $content = "LINK=\"".$CFG->wwwroot."/mod/".$modulename."/view.php?id=".$moduleid."\" ";
    return $content;
}

function block_mindmap_get_lesson_dependency($cm) { // Function included only in lesson module, for lesson module dependency only.
    global $COURSE, $DB;
    $output = "";
    $instance = 0;
    $instance = $DB->get_field('course_modules', 'instance', array('id' => $cm->id, 'course' => $COURSE->id));
    $dependentof = $DB->get_field('lesson', 'dependency', array('id' => $instance));
    if ($dependentof == 0) {
        return "";
    }
    $lessonmoduleid = $DB->get_field('modules', 'id' , array('name' => 'lesson'));
    $dependentofid  = $DB->get_field('course_modules', 'id' , array('instance' => $dependentof, 'course' => $COURSE->id,
    'module' => $lessonmoduleid));
    if ($dependentofid == "") { // If option "dependent of" is set to "none", but was set to correct module before.
        return "";
    }
    $output = "\t\t\t\t<arrowlink COLOR=\"#0080c0\" DESTINATION=\"".$dependentofid."\" ENDARROW=\"Default\" />\n";
    return $output;
}

function block_mindmap_print_lesson_pages($cm, $accessiblebutdim, $hascapabilityviewhiddenactivities) {
    global $DB, $CFG, $USER, $COURSE;
    if ($accessiblebutdim) {
        return "";
    }
    require_once($CFG->dirroot.'/mod/lesson/locallib.php');
    $output = "";
    $lesson = new lesson($DB->get_record('lesson', array('id' => $cm->instance), '*', MUST_EXIST));
    $displayleft   = $DB->get_field('lesson', 'displayleft',   array('course' => $cm->course, 'id' => $cm->instance));
    $displayleftif = $DB->get_field('lesson', 'displayleftif', array('course' => $cm->course, 'id' => $cm->instance));
    if ($displayleft == 0) { // If displayleft is swithed off, stop here.
        return "";
    }
    $available = $DB->get_field('lesson', 'available', array('course' => $cm->course, 'id' => $cm->instance ));
    $deadline  = $DB->get_field('lesson', 'deadline', array('course' => $cm->course, 'id' => $cm->instance ));
    if ($available != 0) {
        if (time() < $available) {
            return "";
        }
    }
    if ($deadline != 0) {
        if (time() > $deadline) {
            return "";
        }
    }

    if ($displayleftif != 0) {
        $params = array ("userid" => $USER->id, "lessonid" => $cm->instance);
        if (!$maxgrade = $DB->get_record_sql('SELECT userid, MAX(grade) AS maxgrade FROM {lesson_grades} WHERE userid = :userid
        AND lessonid = :lessonid GROUP BY userid', $params)) {
            return "";
        }
        if ($maxgrade->maxgrade < $lesson->displayleftif) {
            return "";
        }
    }

    if ($dependentlesson = $DB->get_record('lesson', array('id' => $lesson->dependency))) {
            $conditions = unserialize($lesson->conditions);
            $errors = array();

        if ($conditions->timespent) {
                $timespent = false;
            if ($attempttimes = $DB->get_records('lesson_timer', array("userid" => $USER->id,
            "lessonid" => $dependentlesson->id))) {
                foreach ($attempttimes as $attempttime) {
                    $duration = $attempttime->lessontime - $attempttime->starttime;
                    if ($conditions->timespent < $duration / 60) {
                            $timespent = true;
                    }
                }
            }
            if (!$timespent) {
                    $errors[] = get_string('timespenterror', 'lesson', $conditions->timespent);
            }
        }

        if ($conditions->gradebetterthan) {
                $gradebetterthan = false;
            if ($studentgrades = $DB->get_records('lesson_grades', array("userid" => $USER->id,
            "lessonid" => $dependentlesson->id))) {
                foreach ($studentgrades as $studentgrade) {
                    if ($studentgrade->grade >= $conditions->gradebetterthan) {
                        $gradebetterthan = true;
                    }
                }
            }
            if (!$gradebetterthan) {
                    $errors[] = get_string('gradebetterthanerror', 'lesson', $conditions->gradebetterthan);
            }
        }

        if ($conditions->completed) {
            if (!$DB->count_records('lesson_grades', array('userid' => $USER->id, 'lessonid' => $dependentlesson->id))) {
                    $errors[] = get_string('completederror', 'lesson');
            }
        }

        if (!empty($errors) && !$hascapabilityviewhiddenactivities) {
            return "";
        }
    }

    if (!($pages = $DB->get_records('lesson_pages', array('lessonid' => $cm->instance)))) {
            return "";
            print_error('cannotfindpages', 'lesson');

    }

        $orderedpages = array();
        $lastpageid = 0;

    while (true) {
        foreach ($pages as $page) {
            if ((int)$page->prevpageid === (int)$lastpageid) {
                    $orderedpages[$page->id] = $page;
                    unset($pages[$page->id]);
                    $lastpageid = $page->id;
                if ((int)$page->nextpageid === 0) {
                        break 2;
                } else {
                        break 1;
                }
            }
        }
    }

    foreach ($orderedpages as $page) {
        if ($page->display == 1) {
            if ($page->title != "") {
                $output .= "\t\t\t\t\t\t<node TEXT=\"".str_replace('"', '', $page->title)."\" LINK=\"".
                $CFG->wwwroot."/mod/lesson/view.php?id=".$cm->id."&amp;pageid=".$page->id.
                "\"><font NAME=\"SansSerif\" ".block_mindmap_check_if_visited($cm, $page->id)." SIZE=\"12\" />"
                .block_mindmap_print_lesson_page($page->contents)
                ."</node>\n";
            }
        }
    }
    return $output;
}

function block_mindmap_array_iunique($topics) { // This function works as array_unique($match, SORT_STRING);
                                                // but is case insensitive.

    $ltopics = array_map('strtolower', $topics);
    $cleanedtopics = array_unique($ltopics);

    foreach ($topics as $key => $value) {
        if (!isset($cleanedtopics[$key])) {
            unset($topics[$key]);
        }
    }

    return $topics;
}

function block_mindmap_print_lesson_page($pagecontent) { // It could be named 'print_glossary_keywords'.

    global $COURSE, $CFG;
    $output = "";
    $options = new object();
    $context = context_course::instance($COURSE->id);
    $text = '';
    $pagecontent = file_rewrite_pluginfile_urls($pagecontent, 'pluginfile.php', $context->id, 'block_mindmap', 'content', null);
    $text = format_text($pagecontent, FORMAT_MOODLE, $options, $COURSE->id);
    preg_match_all('/<a href="'.preg_quote($CFG->wwwroot, '/').'\/mod\/glossary\/showentry\.php\?.*?">.*?<\/a>/i', $text, $matches);
    foreach ($matches as $match) {
        $match = block_mindmap_array_iunique($match);
        foreach ($match as $mat) {
            preg_match('/href="([^"]*)"/i', $mat, $url);
            $output .= "\t\t\t\t\t\t\t<node TEXT=\"".str_replace('"', '', trim(strip_tags($mat)))."\" LINK=\"".$url[1].
            "\"><font NAME=\"SansSerif\" SIZE=\"11\"/></node>\n";
        }
    }

    return $output;
}

function block_mindmap_print_conditional_availability_arrows($cm) {
    global $CFG, $DB, $COURSE;
    if ($CFG->enableavailability != 1) {
        return "";
    }
    require_once($CFG->libdir.'/conditionlib.php');
    $output = "";
    $modinfo = get_fast_modinfo($COURSE);

    $conditions = $DB->get_records_sql($sql = "
    SELECT
    cma.id as cmaid, gi.*,cma.sourcecmid,cma.requiredcompletion,cma.gradeitemid,
    cma.grademin as conditiongrademin, cma.grademax as conditiongrademax
    FROM
    {course_modules_availability} cma
    LEFT JOIN {grade_items} gi ON gi.id=cma.gradeitemid
    WHERE
    coursemoduleid=?", array($cm->id));

    foreach ($conditions as $condition) {
        $moduleid = $DB->get_field('modules', 'id', array('name' => $condition->itemmodule));
        $dependentofid = $DB->get_field('course_modules', 'id', array('instance' => $condition->iteminstance,
        'course' => $COURSE->id, 'module' => $moduleid));
        if (isset($modinfo->cms[$dependentofid])) {

            if (!$modinfo->cms[$dependentofid]->uservisible &&
                    (empty($modinfo->cms[$dependentofid]->showavailability) ||
                      empty($modinfo->cms[$dependentofid]->availableinfo))) {

                   continue;
            }
        }

         $output .= "\t\t\t\t<arrowlink COLOR=\"#0080c0\" DESTINATION=\"".$dependentofid."\" ENDARROW=\"Default\" />\n";
    }

    return $output;
}

function block_mindmap_print_mod_icon($type) {
    global $CFG;
    if (file_exists($CFG->dirroot.'/mod/'.$type.'/pix/icon.gif')) {
        $output = '&lt;html&gt;&lt;img src=&quot;'.$CFG->wwwroot.'/mod/'.$type.'/pix/icon.gif&quot;&gt;';
    } else {
        $output = '&lt;html&gt;&lt;img src=&quot;'.$CFG->wwwroot.'/mod/'.$type.'/pix/icon.png&quot;&gt;';
    }

    return $output;
}

function block_mindmap_grade_icon($grade, $cm) { // If $textocolor=true then return color="TEXT", if not then return only icon.
    global $USER;
    $colorbad     = ' COLOR = "#ff0033"';
    $colorok      = ' COLOR = "#009900"';
    $colorwarning = ' COLOR = "#FF9900"';
    $output = new stdClass();
    $context = context_module::instance($cm->id);
    if ( empty($grade->items[0]->name) ) { // Check if module grading is enabled.
        return "";
    }
    $timeleft = '';
    $constraints = new stdClass();
    $constraints = block_mindmap_get_particular_module_time_constraints($cm); // Check particular module time constraints
                                                                              // and choose those stricter.
    if ($constraints->from > $cm->availablefrom) {
        $cm->availablefrom  = $constraints->from;
    }
    if (( ($constraints->to != 0) AND ($constraints->to < $cm->availableuntil)) OR ($cm->availableuntil == 0)) {
        $cm->availableuntil = $constraints->to;
    }
    if ($cm->availablefrom != 0 && $cm->availableuntil != 0) {
        $totaltime = $cm->availableuntil - $cm->availablefrom;
        $timeleftraw = $cm->availableuntil - time();
        if ($timeleftraw < $totaltime * 0.2) { // If less than 20% of total time left.
            $timeleft = block_mindmap_time_to_dhm($timeleftraw);
        }

    } else if ($cm->availableuntil != 0) {
        $timeleftraw = ($cm->availableuntil - time() );
        if ($timeleftraw < 604800) {// If there is less than 7 days left.
            $timeleft = block_mindmap_time_to_dhm($timeleftraw);
        }
    }

    if ($grade->items[0]->gradepass > 0) { // If gradepass is defined.
        if ($grade->items[0]->grades[$USER->id]->grade >= $grade->items[0]->gradepass) { // If grade is >= gradepass.
                    $output->icon = "\t\t\t\t<icon BUILTIN=\"button_ok\"/>\n"; // Print icon "ok".
                    $output->color = $colorok;
        } else { // If grade is < gradepass.
            if ( !is_null($grade->items[0]->grades[$USER->id]->grade) ) { // If grade was made.
                if ($timeleft) { // Print icon "warning".
                    $output->icon = "\t\t\t\t<icon BUILTIN=\"messagebox_warning\"/>\n";
                }
                $output->icon .= "\t\t\t\t<icon BUILTIN=\"button_cancel\"/>\n"; // Print icon "cancel".
                $output->color = $colorbad;
                $output->timeleftnode = $timeleft;
            } else if ($timeleft) {
                        $output->icon = "\t\t\t\t<icon BUILTIN=\"messagebox_warning\"/>\n"; // If student has not made any attempts
                                                                                            // and time is running out.
                        $output->color = $colorwarning;
                        $output->timeleftnode = $timeleft;
            }
        }
    } else if ($grade->items[0]->grademax > 0) { // If gradepass is not defined and grademax is > 0.
        if ( ( ($grade->items[0]->grades[$USER->id]->grade) / ($grade->items[0]->grademax) ) >= 0.5 ) {
            // If grade is >= than 1/2 of grademax
                $output->icon = "\t\t\t\t<icon BUILTIN=\"button_ok\"/>\n"; // Print icon "ok".
                $output->color = $colorok;
        } else {
            if ( !is_null($grade->items[0]->grades[$USER->id]->grade) ) { // If grade is < than 1/2 * grademax print.
                if ($timeleft) {
                    $output->icon = "\t\t\t\t<icon BUILTIN=\"messagebox_warning\"/>\n"; // Print icon "warning".
                }
                    $output->icon .= "\t\t\t\t<icon BUILTIN=\"button_cancel\"/>\n";
                    $output->color = $colorbad;
                    $output->timeleftnode = $timeleft;
            } else if ($timeleft) {
                        $output->icon = "\t\t\t\t<icon BUILTIN=\"messagebox_warning\"/>\n";
                        // If student has not made any attempts and time is running out.
                        $output->color = $colorwarning;
                        $output->timeleftnode = $timeleft;
            }

        }
    }

    $feedbacknode = block_mindmap_print_feedback_node($grade);
    if (!empty($feedbacknode)) {
        $output->icon .= $feedbacknode;
    }
    $gradenode = block_mindmap_print_grade_node($grade);
    if (!empty($gradenode)) {
        $output->icon .= $gradenode;
    }
    return $output;
}

function block_mindmap_get_particular_module_time_constraints($cm) {
    global $DB, $COURSE, $USER;
    $name = $DB->get_field('modules', 'name', array('id' => $cm->module));
    $params = array ("courseid" => $COURSE->id, "id" => $cm->instance, "userid" => $USER->id);
    $constraints = new stdClass();
    $constraints->from = null;
    $constraints->to = null;
    $return = new stdClass();
    switch ($name) {
        case "assign":
            $return = $DB->get_record_sql('SELECT allowsubmissionsfromdate, duedate FROM {assign} WHERE course = :courseid
            AND id = :id' , $params);
            if (!empty($return->allowsubmissionsfromdate)) {
                $constraints->from = $return->allowsubmissionsfromdate;
            }
            if (!empty($return->duedate)) {
                $constraints->to   = $return->duedate;
            }
        break;

        case "assignment":
            $return = $DB->get_record_sql('SELECT timeavailable, timedue FROM {assignment} WHERE course = :courseid
            AND id = :id' , $params);
            if (!empty($return->timeavailable)) {
                $constraints->from = $return->timeavailable;
            }
            if (!empty($return->timedue)) {
                $constraints->to   = $return->timedue;
            }
        break;

        case "choice":
            $return = $DB->get_record_sql('SELECT timeopen, timeclose FROM {choice} WHERE course = :courseid
            AND id = :id' , $params);
            if (!empty($return->timeopen)) {
                $constraints->from = $return->timeopen;
            }
            if (!empty($return->timeclose)) {
                $constraints->to   = $return->timeclose;
            }
        break;

        case "data":
            $return = $DB->get_record_sql('SELECT timeavailablefrom, timeavailableto FROM {data} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->timeavailablefrom;
            $constraints->to   = $return->timeavailableto;
        break;

        case "forum":
            $return = $DB->get_record_sql('SELECT assesstimestart, assesstimefinish FROM {forum} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->assesstimestart;
            $constraints->to   = $return->assesstimefinish;
        break;

        case "glossary":
            $return = $DB->get_record_sql('SELECT assesstimestart , assesstimefinish FROM {glossary} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->assesstimestart;
            $constraints->to   = $return->assesstimefinish;
        break;

        case "hotpot":
            $return = $DB->get_record_sql('SELECT timeopen , timeclose FROM {hotpot} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->timeopen;
            $constraints->to   = $return->timeclose;
        break;

        case "lesson":
            $return = $DB->get_record_sql('SELECT available , deadline FROM {lesson} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->available;
            $constraints->to   = $return->deadline;
        break;

        case "quiz":
            $return = $DB->get_record_sql('SELECT timeopen , timeclose FROM {quiz} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->timeopen;
            $constraints->to   = $return->timeclose;
        break;

        case "scorm":
            $return = $DB->get_record_sql('SELECT timeopen , timeclose FROM {scorm} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->timeopen;
            $constraints->to   = $return->timeclose;
        break;

        case "feedback":
            $return = $DB->get_record_sql('SELECT timeopen , timeclose FROM {feedback} WHERE course = :courseid
            AND id = :id' , $params);
            $constraints->from = $return->timeopen;
            $constraints->to   = $return->timeclose;
        break;
    }
    return($constraints);
}

function block_mindmap_time_to_dhm ($seconds) {

    if ($seconds <= 0) {
        return get_string('deadlinehaspassed', 'block_mindmap');
    }
    $return1 = explode (":", gmdate ('H:i', $seconds));
    $days = floor ($seconds / 86400);
    if ($days > 1) {
        return get_string('thereare', 'block_mindmap').' '. $days .  get_string('days', 'block_mindmap')  .''. $return1[0] .
        get_string('hours', 'block_mindmap') . $return1[1] . get_string('minutes', 'block_mindmap');
    } else if ($days > 0) {
        return get_string('thereare', 'block_mindmap').' '. $days .  get_string('day', 'block_mindmap')  .''. $return1[0] .
        get_string('hours', 'block_mindmap') .' ' . $return1[1] . get_string('minutes', 'block_mindmap');
    }

    return get_string('thereare', 'block_mindmap').' '. $return1[0] . get_string('hours', 'block_mindmap') . $return1[1] .
    get_string('minutes', 'block_mindmap');
}

function block_mindmap_print_grade_node($grade) {
    global $USER;
    $content = '';
    if (empty($grade->items)) {
        return $content;
    }
    if ($grade->items[0]->grades[$USER->id]->str_long_grade == "-" ) {
        return $content;
    }
    $content .= "\t\t\t\t\t<node TEXT=\"".get_string('grade', 'block_mindmap')." ".
    str_replace('"', '', $grade->items[0]->grades[$USER->id]->str_long_grade)."\">\n";
    $content .= "\t\t\t\t\t</node>\n";
    return $content;
}

function block_mindmap_print_feedback_node($grade) {
    global $USER;
    $content = '';
    if (empty($grade->items[0]->grades[$USER->id]->str_feedback)) {
        return $content;
    }
    $content .= "\t\t\t\t\t<node TEXT=\"".get_string('feedback', 'block_mindmap').' '.
    strip_tags(str_replace('"', '', $grade->items[0]->grades[$USER->id]->str_feedback))."\">\n";
    $content .= "\t\t\t\t\t</node>\n";
    return $content;
}

function block_mindmap_change_chars($content) { // This function changes polish national characters into html entities.
    $characters = array (
        "ą" => "&#261;",
        "Ą" => "&#260;",
        "ć" => "&#263;",
        "Ć" => "&#262;",
        "ę" => "&#281;",
        "Ę" => "&#280;",
        "ł" => "&#322;",
        "Ł" => "&#321;",
        "ń" => "&#324;",
        "Ń" => "&#323;",
        "ó" => "&#243;",
        "Ó" => "&#211;",
        "ś" => "&#347;",
        "Ś" => "&#346;",
        "ż" => "&#380;",
        "Ż" => "&#379;",
        "ź" => "&#378;",
        "Ź" => "&#377;",
    );
    foreach ($characters as $key => $character) {
        $content = str_replace($key, $character, $content);
    }
    return $content;
}

function block_mindmap_fold_item($grade, $keywords) { // If there is grade node here, then fold this item,
                                                      // or in resource if there is a keyword from glossary, then fold item.
    $content = "";
    if (block_mindmap_print_grade_node($grade) != "") {
        $content .= " FOLDED=\"true\"";
    } else if (!empty($keywords)) {
        $content .= " FOLDED=\"true\" ";
    }
    return $content;
}

function block_mindmap_print_page($mindmap, $return = false) {
    global $CFG, $COURSE;
    include_once($CFG->dirroot.'/lib/filelib.php');

    $output .= '<div class="mindmap displaydate">'.userdate($mindmap->displaydate).'</div>';

    $output .= print_box_start('generalbox', '', true);
    $output .= clean_text($mindmap->displaytext);
    $fileurl = get_file_url($COURSE->id.'/'.$mindmap->filename);
    $output .= '<br /><a href="'.$fileurl.'">'.get_string('viewfile', 'block_mindmap').'</a>';
    $output .= print_box_end(true);

    if ($mindmap->displaypicture) {
        $images = block_mindmap_images();
        $output .= print_box_start('generalbox', '', true);
        $output .= $images[$mindmap->picture];
        $output .= '<br />'.$mindmap->description;
        $output .= print_box_end(true);
    }

    $output .= print_box_start('generalbox', '', true);
    $output .= get_string('animal', 'block_mindmap'). $mindmap->animal .'<br />';
    $output .= get_string('location', 'block_mindmap'). $mindmap->location;
    $output .= print_box_end(true);

    if ($return) {
        return $output;
    } else {
        print $output;
    }
}