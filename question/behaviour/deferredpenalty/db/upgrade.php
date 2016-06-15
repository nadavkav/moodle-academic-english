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
 * Post-install script for the deferredpenalty graded question behaviour.
 *
 * @package   qbehaviour_deferredpenalty
 * @copyright 2013 The Open Universtiy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Deferred penalty graded question behaviour upgrade code.
 */
function xmldb_qbehaviour_deferredpenalty_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    
    if ($oldversion < 2016022802) {

        // Track page of quiz attempts.
        $table = new xmldb_table('quiz');

        $field = new xmldb_field('gradepenalty', XMLDB_TYPE_NUMBER, '6', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, 0);

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Deferred penalty graded question behaviour savepoint reached.
        upgrade_plugin_savepoint(true, 2016022802, 'qbehaviour', 'deferredpenalty');
    }

    return true;
}

