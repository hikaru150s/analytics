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
 * Plugin upgrade steps are defined here.
 *
 * @package     block_analytics
 * @category    upgrade
 * @copyright   2018 Haikal Handamara <haikal_adha@hotmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/upgradelib.php');

/**
 * Execute block_analytics upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_analytics_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read the Upgrade API documentation:
    // https://docs.moodle.org/dev/Upgrade_API
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at:
    // https://docs.moodle.org/dev/XMLDB_editor
	
	if ($oldversion < 2018121400) { // Upgrade to 0.2.0
		// Define table block_analytics_about to be created.
        $table = new xmldb_table('block_analytics_about');

        // Adding fields to table block_analytics_about.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $table->add_field('about', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('aboutformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_analytics_about.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_analytics_about.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
		
		// Define table block_analytics_files to be created.
        $table = new xmldb_table('block_analytics_files');

        // Adding fields to table block_analytics_files.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $table->add_field('file', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table block_analytics_files.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_analytics_files.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Analytics savepoint reached.
        upgrade_block_savepoint(true, 2018121400, 'analytics');
	}
	
    return true;
}
