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
 * Settings for the RSS client block.
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // Logo.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->pix_url('logo', 'block_selectrss') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->pix_url('donate', 'block_selectrss') . '" /></a>';
    $header = '<div class="block-selectrss-logopromo">' . $image . $donate . '</div>';
    $settings->add(new admin_setting_heading('block_selectrss_logopromo',
            get_string('promo', 'block_selectrss'),
            get_string('promodesc', 'block_selectrss', $header)));

    $settings->add(new admin_setting_configtextarea('block_selectrss/rss_feeds',
            get_string('rss_feeds', 'block_selectrss'),
            get_string('rss_feeds_info', 'block_selectrss'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('block_selectrss/num_items',
            get_string('num_items', 'block_selectrss'),
            get_string('num_items_info', 'block_selectrss'), 5, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_selectrss/max_get_num_items',
            get_string('get_num_items', 'block_selectrss'),
            get_string('get_num_items_info', 'block_selectrss'), 10, PARAM_INT));

    $settings->add(new admin_setting_configduration('block_selectrss/autocleanafter',
            get_string('autocleanafter', 'block_selectrss'),
            get_string('autocleanafter_info', 'block_selectrss'), 14 * 86400, 86400));

    $settings->add(new admin_setting_configtext('block_selectrss/max_keep_items',
            get_string('max_keep_items', 'block_selectrss'),
            get_string('max_keep_items_info', 'block_selectrss'), 10, PARAM_INT));
}