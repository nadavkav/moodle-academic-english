<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Richmedia conversion handler
 */
class moodle1_mod_richmedia_handler extends moodle1_mod_handler {

    /** @var moodle1_file_manager */
    protected $fileman = null;

    /** @var int cmid */
    protected $moduleid = null;

    /**
     * Declare the paths in moodle.xml we are able to convert
     *
     * The method returns list of {@link convert_path} instances.
     * For each path returned, the corresponding conversion method must be
     * defined.
     *
     * Note that the path /MOODLE_BACKUP/COURSE/MODULES/MOD/RICHMEDIA does not
     * actually exist in the file. The last element with the module name was
     * appended by the moodle1_converter class.
     *
     * @return array of {@link convert_path} instances
     */
    public function get_paths() {
        return array(
            new convert_path('richmedia', '/MOODLE_BACKUP/COURSE/MODULES/MOD/RICHMEDIA',
                array(
                    'newfields' => array(
                        'whatgrade' => 0,
                        'richmediatype' => 'local',
                        'sha1hash' => null,
                        'revision' => '0',
                        'forcecompleted' => 1,
                        'forcenewattempt' => 0,
                        'lastattemptlock' => 0,
                        'displayattemptstatus' => 1,
                        'displaycoursestructure' => 1,
                        'timeopen' => '0',
                        'timeclose' => '0',
                        'introformat' => '0',
                    ),
                    'renamefields' => array(
                        'summary' => 'intro'
                    )
                )
            )
        );
    }

    /**
     * This is executed every time we have one /MOODLE_BACKUP/COURSE/MODULES/MOD/RICHMEDIA
     * data available
     */
    public function process_richmedia($data) {
        global $CFG;

        // get the course module id and context id
        $instanceid     = $data['id'];
        $currentcminfo  = $this->get_cminfo($instanceid);
        $this->moduleid = $currentcminfo['id'];
        $contextid      = $this->converter->get_contextid(CONTEXT_MODULE, $this->moduleid);

        // conditionally migrate to html format in intro
        if ($CFG->texteditors !== 'textarea') {
            $data['intro']       = text_to_html($data['intro'], false, false, true);
            $data['introformat'] = FORMAT_HTML;
        }

        // get a fresh new file manager for this instance
        $this->fileman = $this->converter->get_file_manager($contextid, 'mod_richmedia');

        // convert course files embedded into the intro
        $this->fileman->filearea = 'intro';
        $this->fileman->itemid   = 0;
        $data['intro'] = moodle1_converter::migrate_referenced_files($data['intro'], $this->fileman);

        // check 1.9 version where backup was created
        $backupinfo = $this->converter->get_stash('backup_info');
        if ($backupinfo['moodle_version'] < 2007110503) {
            // as we have no module version data, assume $currmodule->version <= $module->version
            // - fix data as the source 1.9 build hadn't yet at time of backing up.
            $data['grademethod'] = $data['grademethod']%10;
        }

        // update richmediatype (logic is consistent as done in richmedia/db/upgrade.php)
        $ismanifest = preg_match('/imsmanifest\.xml$/', $data['reference']);
        $iszippif = preg_match('/.(zip|pif)$/', $data['reference']);
        $isurl = preg_match('/^((http|https):\/\/|www\.)/', $data['reference']);
        if ($isurl) {
            if ($ismanifest) {
                $data['richmediatype'] = 'external';
            } else if ($iszippif) {
                $data['richmediatype'] = 'localtype';
            }
        }

        // migrate richmedia package file
        $this->fileman->filearea = 'package';
        $this->fileman->itemid   = 0;
        $this->fileman->migrate_file('course_files/'.$data['reference']);

        // start writing richmedia.xml
        $this->open_xml_writer("activities/richmedia_{$this->moduleid}/richmedia.xml");
        $this->xmlwriter->begin_tag('activity', array('id' => $instanceid, 'moduleid' => $this->moduleid,
            'modulename' => 'richmedia', 'contextid' => $contextid));
        $this->xmlwriter->begin_tag('richmedia', array('id' => $instanceid));

        foreach ($data as $field => $value) {
            if ($field <> 'id') {
                $this->xmlwriter->full_tag($field, $value);
            }
        }

        return $data;
    }

    /**
     * This is executed when we reach the closing </MOD> tag of our 'richmedia' path
     */
    public function on_richmedia_end() {
        // close richmedia.xml
        $this->xmlwriter->end_tag('richmedia');
        $this->xmlwriter->end_tag('activity');
        $this->close_xml_writer();

        // write inforef.xml
        $this->open_xml_writer("activities/richmedia_{$this->moduleid}/inforef.xml");
        $this->xmlwriter->begin_tag('inforef');
        $this->xmlwriter->begin_tag('fileref');
        foreach ($this->fileman->get_fileids() as $fileid) {
            $this->write_xml('file', array('id' => $fileid));
        }
        $this->xmlwriter->end_tag('fileref');
        $this->xmlwriter->end_tag('inforef');
        $this->close_xml_writer();
    }
}
