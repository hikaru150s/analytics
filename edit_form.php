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
 * Form for editing analytics block instances.
 *
 * @package     block_analytics
 * @copyright   2018 Haikal Handamara <haikal_adha@hotmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing block_analytics block instances.
 *
 * @package    block_analytics
 * @copyright  2018 Haikal Handamara <haikal_adha@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_analytics_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_analytics.
     */
    protected function specific_definition($mform) {

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Please keep in mind that all elements defined here must start with 'config_'.

    }
}
