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
defined('MOODLE_INTERNAL') || die();

/**
 * Theme_enlight install function.
 *
 * @return void
 */
function xmldb_theme_enlight_install() {
    global $CFG;
    // Install typography link.
    $cparam = $CFG->custommenuitems;
    $customsettings = explode("\n", $cparam);
    $customsettings[] = 'Typography|/theme/enlight/typo/index.php';
    $customsettings1 = array_unique($customsettings);
    $custommenus = implode("\n", $customsettings1);
    set_config('custommenuitems', $custommenus);
    if (method_exists('core_plugin_manager', 'reset_caches')) {
        core_plugin_manager::reset_caches();
    }
    // Set the default background.
    $fs = get_file_storage();

    // Logo.
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlight';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid    = get_admin()->id;
    $filerecord->filearea  = 'logo';
    $filerecord->filepath  = '/';
    $filerecord->itemid    = 0;
    $filerecord->filename  = 'logo.png';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlight/pix/home/logo.png');

    // Login bg.
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlight';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid    = get_admin()->id;
    $filerecord->filearea  = 'loginbg';
    $filerecord->filepath  = '/';
    $filerecord->itemid    = 0;
    $filerecord->filename  = 'loginbg.jpg';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlight/pix/home/loginbg.jpg');

    // Slider images.
    $numberofslides = 12;
    for ($i = 1; $i <= $numberofslides; $i++) {
        $fs = get_file_storage();
        $p = $i % 3;
        $filerecord = new stdClass();
        $filerecord->component = 'theme_enlight';
        $filerecord->contextid = context_system::instance()->id;
        $filerecord->userid = get_admin()->id;
        $filerecord->filearea = 'slide'. $i.'image';
        $filerecord->filepath = '/';
        $filerecord->itemid = 0;
        $filerecord->filename = 'slide'. $i .'image.jpg';
        $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlight/pix/home/slide'. $p .'.jpg');
    }

    // Footer background Image.
    $fs = get_file_storage();
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlight';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid    = get_admin()->id;
    $filerecord->filearea  = 'footbgimg';
    $filerecord->filepath  = '/';
    $filerecord->itemid    = 0;
    $filerecord->filename  = 'footbgimg.jpg';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlight/pix/home/footbgimg.jpg');

}