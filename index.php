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
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package   mod_bim2
 * @copyright 2010 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// Replace bim2 with the name of your module and remove this line

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

if (! $course = $DB->get_record('course', array('id' => $id))) {
    error('Course ID is incorrect');
}

require_course_login($course);

add_to_log($course->id, 'bim2', 'view all', "index.php?id=$course->id", '');

/// Print the header

$PAGE->set_url('/mod/bim2/view.php', array('id' => $id));
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->shortname);

echo $OUTPUT->header();

/// Get all the appropriate data

if (! $bim2s = get_all_instances_in_course('bim2', $course)) {
    echo $OUTPUT->heading(get_string('nobim2s', 'bim2'), 2);
    echo $OUTPUT->continue_button("view.php?id=$course->id");
    echo $OUTPUT->footer();
    die();
}

/// Print the list of instances (your module will probably extend this)

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}

foreach ($bim2s as $bim2) {
    if (!$bim2->visible) {
        //Show dimmed if the mod is hidden
        $link = '<a class="dimmed" href="view.php?id='.$bim2->coursemodule.'">'.format_string($bim2->name).'</a>';
    } else {
        //Show normal if the mod is visible
        $link = '<a href="view.php?id='.$bim2->coursemodule.'">'.format_string($bim2->name).'</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($bim2->section, $link);
    } else {
        $table->data[] = array ($link);
    }
}

echo $OUTPUT->heading(get_string('modulenameplural', 'bim2'), 2);
print_table($table);

/// Finish the page

echo $OUTPUT->footer();
