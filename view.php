<?php

/**
 * Prints a particular instance of bim2
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package   mod_bim2
 * @copyright 2010 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace bim2 with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // bim2 instance ID - it should be named as the first character of the module

print "<h1> Hello there </h1>";

die;

if ($id) {
    $cm         = get_coursemodule_from_id('bim2', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $bim2  = $DB->get_record('bim2', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $bim2  = $DB->get_record('bim2', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $bim2->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('bim2', $bim2->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, 'bim2', 'view', "view.php?id=$cm->id", $bim2->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/bim2/view.php', array('id' => $cm->id));
$PAGE->set_title($bim2->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'bim2')));

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');

// Output starts here
echo $OUTPUT->header();

// Replace the following lines with you own code
echo $OUTPUT->heading('Yay! It works!');

// Finish the page
echo $OUTPUT->footer();
