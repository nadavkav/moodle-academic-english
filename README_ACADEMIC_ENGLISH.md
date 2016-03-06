Overview
========
Log of all changes applied to this version including:
(1) Install of new modules
(2) Changes in system settings
(3) Core & external plugin patches
On top of MOODLE_29_STABLE


Changelog
=========
## 1/3/2016:
Install: mod/h5p + "Internal module":h5p/h5p-php-library (branch:file-storage-interface)
Settings: 
mod_hvp | export - (download) disable?
mod_hvp | enable_save_content_state = enable
Patch: mod/hvp/library/styles/h5p.css - change interactions widgets style
Install: enrol/autoenrol (https://github.com/markward/enrol_autoenrol.git)
Settings:
enrol_autoenrol | defaultenrol (add instance to all new courses?)
Site administration ► Plugins ► Enrolments ► Manage enrol plugins - enable autoenrol
Add autoenrol to existing courses and set "Enrol when" to: "Logging into site"

## 3/3/2016: 
Patch: mod/h5p - Add support to xAPI statements dispatch using ADL JS xAPI lib (https://github.com/adlnet/xAPIWrapper)
       [Reference Documentation Here](http://adlnet.github.io/xAPIWrapper/)
       should be used with an LRS to log xAPI statements (tested with: watershedlrs and grassblade)
       
## 4/3/2016:
Install: user/profile/field/autocomplete (https://github.com/nadavkav/moodle-profilefield_autocomplete.git)
Settings:
registerauth = enable
Patch: Apply MDL-51247 forms: All new aria-pimped autocomplete mform element. (from Moodle 3.0 stable)

Admin: add custom profile field of type autocomplete + use institute list:
https://gist.github.com/nadavkav/5a37a3a917484b6d05df
patch: Adjust signin.php and login.php
settings:
$CFG->authloginviaemail = true;
$CFG->rememberusername = yes; (and hide it from login ui)
$CFG->sessiontimeout = 2592000; // 60sec X 60min * 24h * 30days
$CFG->extendedusernamechars = true; (to enable emails inside username fields, register new user page)
$CFG->guestloginbutton = false;

## 6/3/2016:
Install: local/flavours - local/flavours (new) save and restore system settings & plugins 
Install: blocks/admin_presets (new) export and import system settings

settings:
$CFG->sitepolicyguest = 'link to disclaimer' (should add one to page footer from theme admin settings too)
$CFG->sitepolicy = 'link to disclaimer' (should add one to page footer from theme admin settings too)

patch: add signup/register button to user login page
patch: add policy/disclaimer link to user login page

TODO
====
mod/h5p - add xAPI JS to PHP AJAX to grade book?
filter/h5p
enrol/autoenrol - switch to a forked repo and get all the latest pull request fixes (https://github.com/markward/enrol_autoenrol/network)
Auto approve registered users?
Disable self approve emails?