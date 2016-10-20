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

defined('MOODLE_INTERNAL') || die();

/**
 * Indicates API features that the dialogue supports.
 *
 * @uses FEATURE_GROUPS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_BACKUP_MOODLE2
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function dialogue_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_COMPLETION_HAS_RULES:    return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_RATE:                    return false;
        case FEATURE_BACKUP_MOODLE2:          return true;

        default: return null;
    }
}

/**
 * Adds a dialogue instance
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $data
 * @param mod_dialogue_mod_form $form
 * @return int The instance id of the new dialogue or false on failure
 */

function dialogue_add_instance($data) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/mod/dialogue/locallib.php');

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;

    $result =  $DB->insert_record('dialogue', $data);

    return $result;
}

/**
 * Updates a dialogue instance
 *
 * Given an object containing all the necessary data, (defined by the form in
 * mod.html) this function will update an existing instance with new data.
 *
 * @param stdClass $data
 * @param mod_dialogue_mod_form $form
 * @return bool true on success
 */
function dialogue_update_instance($data, $mform) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/mod/dialogue/locallib.php');

    $data->timemodified = time();
    $data->id = $data->instance;

    $DB->update_record('dialogue', $data);

    return true;
}

/**
 * Deletes a dialogue instance
 *
 * Given an ID of an instance of this module, this function will permanently
 * delete the instance and any data that depends on it.
 * @param   int     id of the dialogue object to delete
 * @return  bool    true on success, false if not
 */
function dialogue_delete_instance($id) {
    global $DB;

    $dialogue = $DB->get_record('dialogue', array('id'=>$id), '*', MUST_EXIST);
    
    $cm = get_coursemodule_from_instance('dialogue', $dialogue->id, $dialogue->course, false, MUST_EXIST);
    
    $context = context_module::instance($cm->id);
    
    $fs = get_file_storage();
    
    // delete files
    $fs->delete_area_files($context->id);
    // delete flags
    $DB->delete_records('dialogue_flags', array('dialogueid'=>$dialogue->id));
    // delete bulk open rules
    $DB->delete_records('dialogue_bulk_opener_rules', array('dialogueid'=>$dialogue->id));
    // delete participants
    $DB->delete_records('dialogue_participants', array('dialogueid'=>$dialogue->id));
    // delete messages
    $DB->delete_records('dialogue_messages', array('dialogueid'=>$dialogue->id));
    // delete conversations
    $DB->delete_records('dialogue_conversations', array('dialogueid'=>$dialogue->id));
    // delete dialogue
    $DB->delete_records('dialogue', array('id'=>$dialogue->id));
    
    return true;
}

/**
 * Function to be run periodically according to the moodle cron
 * Mails new conversations out to participants, checks for any new
 * participants, and cleans up expired/closed conversations
 * @return   bool   true when complete
 */
function dialogue_process_bulk_openrules() {
    global $CFG, $DB;
    require_once($CFG->dirroot.'/mod/dialogue/locallib.php');
    
    mtrace('1. Dealing with bulk open rules...');
     
    $sql = "SELECT dbor.*
              FROM {dialogue_bulk_opener_rules} dbor
              JOIN {dialogue_messages} dm ON dm.conversationid = dbor.conversationid
             WHERE dm.state = :bulkautomated
               AND dbor.lastrun = 0
                OR (dbor.includefuturemembers = 1 AND dbor.cutoffdate > dbor.lastrun)";

    $params = array('bulkautomated' => dialogue::STATE_BULK_AUTOMATED);
    $rs = $DB->get_recordset_sql($sql, $params);
    if ($rs->valid()) {
        foreach ($rs as $record) {
            // try and die elegantly
            try {
                // setup dialogue
                $dialogue = dialogue::instance($record->dialogueid);
                if (!$dialogue->is_visible()){
                    mtrace(' Skipping hidden dialogue: '.$dialogue->activityrecord->name);
                    continue;
                }
                // setup conversation
                $conversation = new dialogue_conversation($dialogue, (int) $record->conversationid);

                $withcapability = 'mod/dialogue:receive';
                $groupid = 0; // it either a course or a group, default to course
                $requiredfields = user_picture::fields('u');
                if ($record->type == 'group') {
                    $groupid = $record->sourceid;
                }

                $conversationsopened = 0;

                // get users that can receive
                $enrolledusers = get_enrolled_users($dialogue->context, $withcapability, $groupid, $requiredfields);

                $sentusers = $DB->get_records('dialogue_flags',
                                            array('conversationid' => $conversation->conversationid,
                                                    'flag' => dialogue::FLAG_SENT),
                                            '',
                                            'userid');

                $users = array_diff_key($enrolledusers, $sentusers);
                foreach ($users as $user) {
                    // don't start with author
                    if ($user->id == $conversation->author->id) {
                        continue;
                    }
                    // get a copy of the conversation
                    $copy = $conversation->copy();
                    $copy->add_participant($user->id);
                    $copy->save();
                    $copy->send();
                    // mark the sent in automated conversation, so can track who sent to
                    $conversation->set_flag(dialogue::FLAG_SENT, $user);
                    unset($copy);
                    mtrace('  opened '. $conversation->subject . ' with ' . fullname($user));
                    // up open count
                    $conversationsopened++;

                }
                $DB->set_field('dialogue_bulk_opener_rules', 'lastrun', time(), array('conversationid'=>$record->conversationid));
                mtrace(' Opened '. $conversationsopened . ' for conversation ' . $conversation->subject);
            } catch (moodle_exception $e) {
                mtrace($e->module . ' : ' . $e->errorcode);
            }
        }
    } else {
        mtrace(' None to process');
    }
    $rs->close();
   
    return true;
}

/**
 * Adds information about unread messages, that is only required for the course view page (and
 * similar), to the course-module object.
 * @param cm_info $cm Course-module object
 */
function dialogue_cm_info_view(cm_info $cm) {
    global $CFG;
    require_once($CFG->dirroot . '/mod/dialogue/locallib.php');

    // Get tracking status (once per request)
    static $initialised;
    static $usetracking, $strunreadmessagesone;
    if (!isset($initialised)) {
        if ($usetracking = dialogue_can_track_dialogue()) {
            $strunreadmessagesone = get_string('unreadmessagesone', 'dialogue');
        }
        $initialised = true;
    }

    if ($usetracking) {
        $unread = dialogue_cm_unread_total(new dialogue($cm));
        if ($unread) {
            $out = '<span class="unread"> <a href="' . $cm->url . '">';
            if ($unread == 1) {
                $out .= $strunreadmessagesone;
            } else {
                $out .= get_string('unreadmessagesnumber', 'dialogue', $unread);
            }
            $out .= '</a></span>';
            $cm->set_after_link($out);
        }
    }
}

/**
 * Return a small object with summary information about what a user has done
 * with a given particular instance of this module
 *  - $return->time = the time they did it
 *  - $return->info = a short text description
 *
 * Used for user activity reports.
 * @param   object  $course
 * @param   object  $user
 * @param   object  $dialogue
 *
 * @return stdClass|null
 */
function dialogue_user_outline($course, $user, $mod, $dialogue) {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/dialogue/locallib.php');

    $sql = "SELECT COUNT(DISTINCT dm.timecreated) AS count, 
                     MAX(dm.timecreated) AS timecreated
              FROM {dialogue_messages} dm
             WHERE dm.dialogueid = :dialogueid
               AND dm.authorid = :userid
               AND dm.state = :state";

    $params = array('dialogueid' => $dialogue->id, 'userid' => $user->id, 'state' => dialogue::STATE_OPEN);
    $record = $DB->get_record_sql($sql, $params);
    if ($record) {
        $result = new stdClass();
        $result->info = $record->count.' '.get_string('messages', 'dialogue');
        $result->time = $record->timecreated;
        return $result;
    }

    return null;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $dialogue
 * @return bool
 */
function dialogue_user_complete($course, $user, $mod, $dialogue) {
    global $DB, $CFG, $OUTPUT;
    return true;
}

/**
 * Given a course and a date, prints a summary of all the new
 * messages posted in the course since that date
 *
 * @global object
 * @global object
 * @global object
 * @uses CONTEXT_MODULE
 * @uses VISIBLEGROUPS
 * @param object $course
 * @param bool $viewfullnames capability
 * @param int $timestart
 * @return bool success
 */
function dialogue_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG, $USER, $DB, $OUTPUT;
    return true;
}


/**
 * Return a list of 'view' actions to be reported on in the participation reports
 * @return  array of view action labels
 */
function dialogue_get_view_actions() {
    return array('view', 'view all', 'view by role', 'view conversation');
}

/**
 * Return a list of 'post' actions to be reported on in the participation reports
 * @return array of post action labels
 */
function dialogue_get_post_actions() {
    return array('open conversation', 'close conversation', 'delete conversation','reply');
}

/**
 * Returns all other caps used in module
 * @return array
 */
function dialogue_get_extra_capabilities() {
    return array('moodle/site:accessallgroups', 'moodle/site:viewfullnames', 'moodle/site:trustcontent');
}


/**
 * Determine if a user can track dialogue entries.
 *
 * Checks the site dialogue activity setting and the user's personal preference
 * for trackread which is a similar requirement/preference so we treat them
 * as equals. This is closely modelled on similar function from course/lib.php
 *
 * @todo needs work
 * @param mixed $userid The user object to check for (optional).
 * @return boolean
 */
function dialogue_can_track_dialogue($user = false) {
    global $USER, $CFG;

    $trackunread = get_config('dialogue', 'trackunread');
    // return unless enabled at site level
    if (empty($trackunread)) {
        return false;
    }

    // default to logged if no user passed as param
    if ($user === false) {
        $user = $USER;
    }

    // dont allow guests to track
    if (isguestuser($user) or empty($user->id)) {
        return false;
    }

    return true;
}

/**
 * Serves the dialogue attachments. Implements needed access control ;-)
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @param array $options
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - justsend the file
 */
function dialogue_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload = true, $options = array()) {
    global $CFG, $DB, $USER;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_course_login($course, true, $cm);

    $fileareas = array('message', 'attachment');
    if (!in_array($filearea, $fileareas)) {
        return false;
    }

    $itemid = (int)array_shift($args);
    if (!$message = $DB->get_record('dialogue_messages', array('id'=>$itemid))) {
        return false;
    }

    if (!$conversation = $DB->get_record('dialogue_conversations', array('id'=>$message->conversationid))) {
        return false;
    }

    if (!$dialogue = $DB->get_record('dialogue', array('id'=>$cm->instance))) {
        return false;
    }

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/mod_dialogue/$filearea/$itemid/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
       return false;
    }

    // Force non image formats to be downloaded
    if (!$file->is_valid_image()) {
        $forcedownload = true;
    }

    // Send the file
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/* Event handler functions @TODO */
function dialogue_user_enrolled($eventdata) {
    cache_helper::purge_by_event($event);
    return true;
}
function dialogue_user_unenrolled($eventdata) {
    return true;
}
function dialogue_groups_member_added($eventdata) {
    return true;
}
function dialogue_groups_member_removed($eventdata) {
    return true;
}
