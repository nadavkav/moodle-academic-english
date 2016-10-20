<?php
$currentSection;

$courseId = $COURSE->id; 
$lesson=-1;
if (strpos($PAGE->cm->name, 'מבוא')!== false||strpos($PAGE->cm->name, 'מסלול הלמידה')!== false) {
   $lesson=0;  
}else if (strpos($PAGE->cm->name, 'שיעור')!== false) {
    $lesson=str_replace('שיעור','',$PAGE->cm->name);
    $lesson=trim($lesson); 
} 


$CSVName=$CFG->libdir."/.."."/theme/enlight/csv/strategics.csv";    

$arr_parser =csv_to_array_by_course_lesson_section($CSVName, $courseId, $lesson, $currentSection);

if ($arr_parser){
?>
<div class="lesson-content__strategies">
    <h4 class="lesson-content__strategies__title">לצפייה באסטרטגיות הלמידה שבשיעור:</h4>
    <div class="lesson-content__strategies__body">
        <ul>
            <?php            
            foreach($arr_parser as $stValue){
                if ($stValue){
                    ?>
                    <li class="lesson-content__strategy">
                        <a href="<?php echo new moodle_url('/mod/book/view.php',array('id'=>424,'chapterid'=>$stValue['id']))?>" target="_blank" class="lesson-content__strategy__icon icon-<?php echo $stValue['file'];?>"></a>
                        <div class="lesson-content__strategy__name"><?php echo ucwords($stValue['name'])?></div>
                    </li>

                    <?php } }?>
        </ul>
    </div>
</div>
<?php }?>
