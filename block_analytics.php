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
 * Block analytics is defined here.
 *
 * @package     block_analytics
 * @copyright   2018 Haikal Handamara <haikal_adha@hotmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * analytics block.
 *
 * @package    block_analytics
 * @copyright  2018 Haikal Handamara <haikal_adha@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_analytics extends block_list {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_analytics');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
		global $CFG, $OUTPUT, $COURSE, $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = html_writer::tag('pre', 'This block is part of Adaptive Learning Suite');

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }
		
		// Dependencies Check
		// SQL 1: show tables WHERE tables_in_mtemp like 'mdl_adaptive%' or tables_in_mtemp like 'mdl_block_adg%' or tables_in_mtemp like 'mdl_collab%'
		$var = "tables_in_$CFG->dbname";
		$checksql = "show tables where $var like 'mdl_adaptive%' or $var like 'mdl_block_adg%' or $var like 'mdl_collab%'";
		$dep = $DB->get_records_sql($checksql);
		$check = array(
			'mdl_adaptive'					=> false,
			'mdl_adaptive_transactions'		=> false,
			'mdl_block_adg_collab_history'	=> false,
			'mdl_block_adg_mbti_char'		=> false,
			'mdl_block_adg_mbti_group'		=> false,
			'mdl_block_adg_mbti_user'		=> false,
			'mdl_collab'					=> false,
			'mdl_collab_transactions'		=> false,
		);
		foreach ($dep as $v) {
			if (isset($check[$v->$var])) {
				$check[$v->$var] = true;
			}
		}
		$depStatus = true;
		foreach ($check as $v) {
			$depStatus = $depStatus && $v;
		}
		
		$context = context_course::instance($COURSE->id);
		$baseview = '/blocks/analytics/view.php';
		if ($depStatus) { // Dependencies ok
			if ( has_capability('block/analytics:managepages', $context) ) {
				// Teacher Mode
				$studentUrl = new moodle_url($baseview, array(
					'blockid'	=> $this->instance->id,
					'courseid'	=> $COURSE->id,
					'type'		=> 'student'
				));
				$classUrl = new moodle_url($baseview, array(
					'blockid'	=> $this->instance->id,
					'courseid'	=> $COURSE->id,
					'type'		=> 'class'
				));
				$correlationUrl = new moodle_url($baseview, array(
					'blockid'	=> $this->instance->id,
					'courseid'	=> $COURSE->id,
					'type'		=> 'correlation'
				));
				$this->content->items[]  = html_writer::link($studentUrl, 'View all students.');
				$this->content->items[]  = html_writer::link($classUrl, 'View summary of class.');
				$this->content->items[]  = html_writer::link($correlationUrl, 'Examine correlation on this class.');
			} else if ( has_capability('block/analytics:studentview', $context) ) {
				// Student Mode
				$summaryUrl = new moodle_url($baseview, array(
					'blockid'	=> $this->instance->id,
					'courseid'	=> $COURSE->id,
					'type'		=> 'progress'
				));
				
				$this->content->items[]  = html_writer::link($summaryUrl, 'See your progress.');
			} else {
				// Guest Mode
				$this->content->text = 'Please login to see the content of this blocks.';
			}
		} else { // Dependencies Problem
			if ( has_capability('block/analytics:managepages', $context) ) {
				// Teacher Mode
				$this->content->items[] = html_writer::tag('b', 'The following table(s) was not found! Please re-install your plugin associated with specified plugins and Add those plugins to this course!');
				$a = html_writer::start_tag('ul');
				foreach ($check as $k => $v) {
					if ($v == false) {
						$a .= html_writer::tag('li', $k);
					}
				}
				$a .= html_writer::end_tag('ul');
				$this->content->items[] = $a;
			} else if ( has_capability('block/analytics:studentview', $context) ) {
				// Student Mode
				$this->content->text = 'There was error on system. Please contact administrator.';
			} else {
				// Guest Mode
				$this->content->text = 'Please login to see the content of this blocks.';
			}
		}
		
        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_analytics');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true, 
                     'course-view-social' => false,
                     'mod' => true, 
                     'mod-quiz' => false);
    }
	
	public function instance_allow_multiple() {
          return true;
    }
}
