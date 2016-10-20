<?php

defined('MOODLE_INTERNAL') || die();

class filter_h5p extends moodle_text_filter{
    public function filter($text , array $options=array()){
        global $PAGE,$DB;
      
        $re = '~<a\s[^>]*href="([^"]*(?:youtu.be|youtube.com)[^"]*)"[^>]*>([^>]*)</a>~is';
        preg_match($re, $text, $arrMaches);
        $strYutube=isset($arrMaches[0])?$arrMaches[0]:'';
        
         if (!empty($strYutube)){
            $sql='SELECT mdl_course_sections.`sequence`
            FROM `mdl_course_modules`
            LEFT JOIN `mdl_course_sections` ON (mdl_course_modules.`section`=mdl_course_sections.`id`)
            WHERE mdl_course_modules.id ='.$PAGE->cm->id;
            $objCPage = $DB->get_record_sql($sql);

            if (!empty($objCPage->sequence)){
                $arrIds = explode(',',$objCPage->sequence);

                $key = array_search($PAGE->cm->id,$arrIds);
                $nextPageId = $arrIds[$key+1];
                $sql="SELECT mdl_course_modules.id
                FROM `mdl_course_modules`
                LEFT JOIN `mdl_modules` ON (mdl_course_modules.`module`=mdl_modules.id)
                WHERE mdl_course_modules.id =".$nextPageId." AND mdl_modules.name='hvp' and mdl_course_modules.visible=1" ;
                $objRawResult = $DB->get_record_sql($sql);

                if (!empty($objRawResult)){
                    $reg='<iframe class="h5p-embed" src="'.$CFG->wwwroot.'/mod/hvp/view.php?id='.$objRawResult->id.'&isembedded=1" style="border:0;  width:100%; height:514px;" allowfullscreen="true" scrolling="no"></iframe>';
                    $text = str_replace($arrMaches[0],$reg,$text);
                    return $text; 
                }

            }
        }
         
        return  $text;
    }
}
