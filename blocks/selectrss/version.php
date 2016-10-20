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
 * Version info for the RSS client block.
 *
 * @package     block_selectrss
 *
 * @copyright   Sebsoft.nl
 * @author      Mike Uding <mike@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$plugin            = new stdClass();
$plugin->version   = 2015091711; // YYYYMMDDHH !
$plugin->requires  = 2013111800; // YYYYMMDDHH !
$plugin->cron      = 0;
$plugin->component = 'block_selectrss';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '1.0.3 (build 2015091711)';