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
 * Blog Export Portfolio 
 *
 * @author Justin Hunt <poodllsupport@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
require_once($CFG->libdir.'/portfolio/plugin.php');
require_once($CFG->dirroot . '/blog/locallib.php');
require_once($CFG->dirroot . '/tag/lib.php');


class portfolio_plugin_blogexport extends portfolio_plugin_push_base {

	private $theblogentry=null;

    public function supported_formats() {
        return array(PORTFOLIO_FORMAT_FILE, 
		PORTFOLIO_FORMAT_PLAINHTML, 
		PORTFOLIO_FORMAT_RICHHTML);
    }

    public static function get_name() {
        return get_string('pluginname', 'portfolio_blogexport');
    }

    public function prepare_package() {
        // We send the files as they are, no prep required.
        return true;
    }

    public function get_interactive_continue_url() {
		global $CFG,$USER;
		if($this->get_export_config('editnow')){
			return $CFG->wwwroot . '/blog/edit.php?action=edit&entryid=' . $this->theblogentry->id;
		}else{
			return $CFG->wwwroot . '/blog/index.php?userid=' . $USER->id;
		}
    }

    public function expected_time($callertime) {
        // We trust what the portfolio says.
        return $callertime;
    }

    public function send_package() {
		global $CFG;

        $firstfile=true;
		$files = $this->exporter->get_tempfiles();
		$attachmentcount = count($files);
		$originaltime = null;//time();
		
		//make our blog entry
		//at this point we do not know if we have an html file or only "attachments"
		$blogentry= $this->make_entry($attachmentcount);
		if(!$blogentry){
			throw new portfolio_plugin_exception('sendfailed', 'portfolio_blogexport', $file->get_filename());
		}
		
		$currentfile=0;//loop counter
		$attachmentcount=0;//no. of attachemnts to blog post, reset here
		$content ="";//blog body text
		
		//the way we export files differs depending on portolio type
		switch ($this->exporter->get('formatclass')){
				
				case PORTFOLIO_FORMAT_RICHHTML:
				
					$rcount = 0; //replace file count
					$filesbyname = array();// key=>value by filename list of exported files
					
					//loop through exported files and store them by name, unless they are destined to be blog post body
					foreach ($this->exporter->get_tempfiles() as $file) {
						if(!$originaltime){$originaltime=$file->get_timecreated();}
						$filename= $file->get_filename();
						if(strlen($filename) > 5 && substr($filename,-5)==".html" && $content==""){
							//this will be the body of the blog post
							$content = $file->get_content();
						}else{
							$filesbyname[$filename] = $file;
						}
						$currentfile++;
					}
					
					//first we get the dir in zip that attached files are stored in
					$pformat = $this->exporter->get('format');
					$filedir = $pformat->get_file_directory();
					
					//loop through files we stored by name, and store as post files or attached files with the blog
					foreach($filesbyname as $fname => $file){
						//we swap out the links "site_files/somefile.ext" with pluginfilelinks
						//first urlencode the filename, because $content filenames will already have been
						$enc_fname = rawurlencode($fname);
						
						//this works for 2.2 assignment and forum posts
						$content = str_replace($filedir . $enc_fname,'@@PLUGINFILE@@/' . $enc_fname,$content,$rcount);
						
						//2.3 assignment doesn't massage the html the same. It uses @@pluginfile@@ as is.
						// But we still need to store our files correctly so we scan for that case too.
						if(!$rcount){
							$content = str_replace('@@PLUGINFILE@@/' . $enc_fname,'@@PLUGINFILE@@/' . $enc_fname,$content,$rcount);
						}
						
						//If a file is exported, but not linked to in the html, we need to decide what to do
						//WE could (i) not export them (ii) export as attachments (iii) export as post files
						//initially we exported as attachment(jan/feb 2013) but this is probably incorrect. Most mods will not
						//send unlinked files through in RichHTML, unless they are deleted files. And deleted files could be bad.
						//We choose to not export unlinked files.  But we may need to revisit this ... Justin 15/02/2013
						if($rcount>0){
							$success = $this->send_file($file,$blogentry,"post");
						}else{
							//we choose not to export unlinked files.
							//$success = $this->send_file($file,$blogentry,"attachment");
							//$attachmentcount++;
						}
						if(!$success){
							throw new portfolio_plugin_exception('sendfailed', 'portfolio_blogexport', $file->get_filename());
						}
						
					}
					break;
					
				case PORTFOLIO_FORMAT_PLAINHTML:
					foreach ($this->exporter->get_tempfiles() as $file) {
						if(!$originaltime){$originaltime=$file->get_timecreated();}
						$filename= $file->get_filename();
						if(strlen($filename) > 5 && substr($filename,-5)==".html" && $content==""){
							//this will be the body of the blog post
							$content = $file->get_content();
						}else{
							$success = $this->send_file($file,$blogentry,"attachment");
								//here we send the files up to the blog
							if($success){	
								$attachmentcount++;
							}else{
								throw new portfolio_plugin_exception('sendfailed', 'portfolio_blogexport', $file->get_filename());
							}
						}
						
					}//end of for each(default)	
					break;
					
				
				default:
					foreach ($this->exporter->get_tempfiles() as $file) {
						if(!$originaltime){$originaltime=$file->get_timecreated();}
						if (!$this->send_file($file,$blogentry,"attachment")) {
								//here we send the files up to the blog
								throw new portfolio_plugin_exception('sendfailed', 'portfolio_blogexport', $file->get_filename());
						}
						$attachmentcount++;
					}//end of for each(default)				
			}//end of switch
			
			//Get the date to assign the blog entry(only "now" works!!)
			//review later
			//if( $this->get_export_config('postdate')=='now'){
			if(true){	
				$postdate=time();
			}else{
				$postdate=$originaltime;
			}
			
			//add the body text and other meta data to the blog entry
			$data=array('subject'=> $this->get_export_config('postheading'),'created'=>$postdate,'publishstate'=>$this->get_export_config('postprivacy'),'summary'=>$content,'attachment'=>$attachmentcount);
			$this->edit_entry($blogentry,$data);
			
			//set a ref to the blog entry at instance level, so we can go straight to editing it if necessary
			$this->theblogentry = $blogentry;

    }
	
	private function edit_entry($entry,$params=array()) {
	global $CFG, $USER, $DB, $PAGE;
		
	/*	
	$sitecontext = get_context_instance(CONTEXT_SYSTEM);	
	$summaryoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>true, 'context'=>$sitecontext);
	$attachmentoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes);
     */

        foreach ($params as $var => $val) {
            $entry->$var = $val;
        }


        if (!empty($CFG->useblogassociations)) {
            $entry->add_associations();
        }

        //$entry->lastmodified = time();

        // Update record
        $DB->update_record('post', $entry);
        tag_set('post', $entry->id, $entry->tags);

		//TO DO: support logging with http://docs.moodle.org/dev/Migrating_logging_calls_in_plugins in M2.7
		if($CFG->version<2014051200){
			add_to_log(SITEID, 'blog', 'update', 'index.php?userid='.$USER->id.'&entryid='.$entry->id, $entry->subject);
		}
	
	}

	private function make_entry($attachmentcount){
			global $CFG,$COURSE;
			
			$sitecontext = context_system::instance();
			$courseid = $COURSE->id;
			$modid=0;//lets just use zero for now
			$entry  = new stdClass();
			$entry->id = null;
			$summaryoptions = array('subdirs'=>false, 'maxfiles'=> 99, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>true, 'context'=>$sitecontext);
		    $data=array('subject'=>'blogexportportfolio','format'=>1,'summaryformat'=>1,'publishstate'=>'draft','attachment'=>$attachmentcount);
			$blogentry = new blog_entry(null, $data, null);
            $blogentry->add();
			return $blogentry;
	
	}
	
	//File area should be either "post" or "attachment"
	private function send_file($file,$blogentry,$filearea="attachment"){
		global $CFG, $USER;
		
			$fs = get_file_storage();
			$sitecontext = context_system::instance();
			
			//make our filerecord
			$record = new stdClass();
			$record->filename = $file->get_filename();
			$record->filearea = $filearea;
			$record->component = "blog";
			$record->filepath = "/";
			$record->itemid   = $blogentry->id;
			$record->license  = $CFG->sitedefaultlicense;
			$record->author   = 'Moodle User';
			$record->contextid = $sitecontext->id;
			$record->userid    = $USER->id;
			$record->source    = '';
			
			$fs->create_file_from_storedfile($record, $file);

		return true;
	}
	
	/* Blog portfolio doesnt need these
    public function steal_control($stage) {
        global $CFG;
        if ($stage != PORTFOLIO_STAGE_CONFIG) {
            return false;
        }

    }
	*/

	/* Blog portfolio doesn't need this
    public function post_control($stage, $params) {
        if ($stage != PORTFOLIO_STAGE_CONFIG) {
            return;
        }

    }
	*/

    public static function allows_multiple_instances() {
        return false;
    }

    public static function has_admin_config() {
        return true;
    }

    public static function get_allowed_config() {
        return array('def_postheading', 'def_postprivacy');
    }
	
	//moodle 2.2 requires this function NOT be static
    public static function admin_config_form(&$mform) {
		$mform->addElement('text', 'def_postheading', get_string('defaultpostheading', 'portfolio_blogexport'));
		$mform->setDefault('def_postheading', get_string('defaultheading', 'portfolio_blogexport'));
		$mform->setType('def_postheading',PARAM_RAW_TRIMMED);
		
		//post privacy
		$privacyoptions = self::get_options('privacy');
		$mform->addElement('select', 'def_postprivacy', get_string('defaultpostprivacy', 'portfolio_blogexport'), $privacyoptions);
		$mform->setDefault('def_postprivacy', 'public');
		
		
		//rules
		$strrequired = get_string('required');
        $mform->addRule('def_postheading', $strrequired, 'required', null, 'client');
		$mform->addRule('def_postprivacy', $strrequired, 'required', null, 'client');
/*
        $mform->addElement('text', 'config1', get_string('config1', 'portfolio_blogexport'));
        $mform->setType('config1', PARAM_RAW_TRIMMED);
        $mform->addElement('text', 'config2', get_string('config2', 'portfolio_blogexport'));
        $mform->setType('config2', PARAM_RAW_TRIMMED);

        $strrequired = get_string('required');
        $mform->addRule('config1', $strrequired, 'required', null, 'client');
        $mform->addRule('config2', $strrequired, 'required', null, 'client');
*/  
  }
	
	public function has_export_config() {
        return true;
    }

	
	 public function export_config_form(&$mform) {
		//post heading
        $mform->addElement('text', 'plugin_postheading', get_string('postheading', 'portfolio_blogexport'));
		$mform->setDefault('plugin_postheading', $this->get_config('def_postheading'));
		$mform->setType('plugin_postheading',PARAM_RAW_TRIMMED);
		//$mform->setDefault('plugin_postheading', get_string('defaultheading', 'portfolio_blogexport'));

		//post privacy
		$privacyoptions = self::get_options('privacy');
		$mform->addElement('select', 'plugin_postprivacy', get_string('postprivacy', 'portfolio_blogexport'), $privacyoptions);
		$mform->setDefault('plugin_postprivacy', $this->get_config('def_postprivacy'));
		
		//post date		
		/*
		$dateoptions = self::get_options('date');
		$mform->addElement('select', 'plugin_postdate', get_string('postdate', 'portfolio_blogexport'), $dateoptions);
		$mform->setDefault('plugin_postdate', 'now');
		*/
		
		//edit at once
		$mform->addElement('checkbox', 'plugin_editnow', get_string('editnow', 'portfolio_blogexport'));
		$mform->setDefault('plugin_editnow', false);

    }
	
	private static function get_options($category){
		switch ($category){
			case 'privacy':
					return array('public' => get_string('publicpost', 'portfolio_blogexport'), 
						'draft' => get_string("privatepost", 'portfolio_blogexport'));
					break;
			case 'date':
					return array('now' => get_string('nowdate', 'portfolio_blogexport'), 
						'original' => get_string("originaldate", 'portfolio_blogexport'));
					break;
			
		}
	}


    public function get_allowed_export_config() {
        return array('postheading', 'postprivacy','postdate','editnow');
    }

    public function get_export_summary() {
		//$dateoptions = self::get_options('date');
		//get_string('postdate', 'portfolio_blogexport') => $dateoptions[$this->get_export_config('postdate')],
		
		$privacyoptions = self::get_options('privacy');
        return array(
					
				
                     get_string('postheading', 'portfolio_blogexport') => $this->get_export_config('postheading'),
					 get_string('postprivacy', 'portfolio_blogexport') => $privacyoptions[$this->get_export_config('postprivacy')],
					 get_string('editnow', 'portfolio_blogexport') => ($this->get_export_config('editnow') ? 'True' : 'False'));
                    // get_string('description') => $this->get_export_config('description'));
    }




}
