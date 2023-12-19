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
 * @package     local_termsurvey
 * @author      Avishek
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */


// $PAGE->set_url(new moodle_url('/local/surveydashboard/manage.php'));
// $PAGE->set_context(\context_system::instance());
// $PAGE->set_title('Avishek Tester');

//header('Content-Type: text/plain');
//header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . '/../../config.php');


$rawdata = utf8_encode($_POST['survey_model']); // Don't forget the encoding
$uncleaned_data = json_decode($rawdata);
$survey_data = clean_param_array((array)$uncleaned_data,PARAM_NOTAGS);
//echo $data->test;

require_login();

if (isguestuser()) {  // Force them to see system default, no editing allowed
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

    echo json_encode("You Must be Logged in Before you Can Make Survey Submissions");
    die;

} else {        
    $userid = $USER->id;  
    // $context = context_user::instance($USER->id);
    // $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    // $header = fullname($USER);
    
    //step 1.  check if user has not already completed the survey for the course  //if completed give error to front end
    if(!$DB->record_exists('fnu_survey_attempts', array('user_id' => $userid, 'course' => trim($survey_data['course']), 'survey_type'=> strval($survey_data['survey_type'])))){

        $record = (object) [
            'user_id' => $userid,
            'survey_type' => $survey_data['survey_type'],
            'attempt_date' => time(),
            'course_id' => $survey_data['course_id'],
            'age' => $survey_data['age'],
            'gender' => $survey_data['gender'],
            'is_first_year' => $survey_data['is_first_year'],
            'campus' => $survey_data['campus'],
            'college' => $survey_data['college'],
            'course' =>  $survey_data['course'],
            'programme' => $survey_data['programme'],
            'course_q1' => $survey_data['course_q1'],
            'course_q2' => $survey_data['course_q2'],
            'course_q3' => $survey_data['course_q3'],
            'course_q4' => $survey_data['course_q4'],
            'course_q5' => $survey_data['course_q5'],
            'teacher_q1' => $survey_data['teacher_q1'], 
            'teacher_q2' => $survey_data['teacher_q2'],
            'teacher_q3' => $survey_data['teacher_q3'],
            'technical_q1' => $survey_data['technical_q1'],
            'audio' => $survey_data['audio'],
            'videos' => $survey_data['videos'],
            'readings' => $survey_data['readings'], 
            'recorded_lectures' => $survey_data['recorded_lectures'],
            'accessibility_input' => $survey_data['accessibility_input'],
            'has_labs' => $survey_data['has_labs'],
            'lab_q1' => $survey_data['lab_q1'],
            'has_clinicals' => $survey_data['has_clinicals'],
            'clinical_q1' => $survey_data['clinical_q1'],
            'suggestion' => $survey_data['suggestion'],
        ];

        header('Content-Type: application/json; charset=utf-8');

        $recordID = $DB->insert_record('fnu_survey_attempts', $record);
        if(isset($recordID)){
            echo json_encode("Thank You for Completing the Survey!");
            die;

        }else{
            echo json_encode("Error Occured While Survey Submission");
            die;
        }

    }else{  //if completed give error to front end

        echo json_encode("You have Already Completed the Survey");
        die;
    }
}

