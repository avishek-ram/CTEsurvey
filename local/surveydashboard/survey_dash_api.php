<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_surveydashboard
 * @author      Avishek
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



// $PAGE->set_url(new moodle_url('/local/surveydashboard/manage.php'));
// $PAGE->set_context(\context_system::instance());
// $PAGE->set_title('Avishek Tester');



require_once(__DIR__ . '/../../config.php');


// TODO Add sesskey check to edit
$term   = optional_param('term', null, PARAM_INT);    
$status  = optional_param('status', null, PARAM_INT);

require_login();

if (isguestuser() && !isset($term) && !isset($status)) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = null;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
    $header = "$SITE->shortname: $strmymoodle (GUEST)";
    $pagetitle = $header;

} else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = fullname($USER);
    $pagetitle = $strmymoodle;


    $id = $DB->get_field('survey_dashboard', 'id', array('surveytype' => $term), MUST_EXIST);
    $rec = new stdClass();
    $rec->id = $id;
    $rec->surveytype = $term;
    $rec->surveystatus   = $status;
    $rec->statuslastupdated   = strtotime(date("d-m-Y"));  //check how date time is saved in moodle
    $rec->lastupdatedby   = $userid;
    $DB->update_record('survey_dashboard', $rec);
}

