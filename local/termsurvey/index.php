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
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/my/lib.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/termsurvey/index.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Survey');

require_login();

$survey_type   = optional_param('surveytype', null, PARAM_INT);    
$course_id  = optional_param('course_id', null, PARAM_INT);

if (isguestuser() && !isset($survey_type) && !isset($course_id)) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    echo $OUTPUT->header();
    echo "<h2>Invalid Redirect</h2>";
    echo $OUTPUT->footer();
    die;

} else {        

    $params = array ("course_id" => $course_id);
    $course_mapping = $DB->get_record_select('fnu_course_code_mapping', "courseid = :course_id", $params);

    $templatecontxt = (object)[
        'survey_type' => $survey_type,
        'course_id' => $course_id,
        'api_url' => $CFG->wwwroot . "/local/termsurvey/survey_api.php",   
        'course_name' => $course_mapping ?$course_mapping-> coursecode : $course_id,
        'homepage' => $CFG->wwwroot . "/my",
    ];

    if($DB->record_exists('fnu_survey_attempts', array('user_id' => $USER->id, 'course' => $course_mapping ?trim($course_mapping-> coursecode) : $course_id, 'survey_type'=> $survey_type))){
        echo $OUTPUT->header();
        echo "<h4>You have already completed the survey</h4>";
        echo $OUTPUT->footer();
        die;
    }
    

    //1 is semester end survey
    //2 is mid sem survey
    //3 is quarter end survey
    //4 is trimester
    if($survey_type == 1){
        echo $OUTPUT->render_from_template('local_termsurvey/render_semester_survey',$templatecontxt);
    }
    else if($survey_type == 2){
        //echo $OUTPUT->render_from_template('local_termsurvey/render_midsemester_survey',$templatecontxt);
        echo $OUTPUT->header();
        echo "<h4>Survey Not Available</h4>";
        echo $OUTPUT->footer();
    }
    else if($survey_type == 3){
        echo $OUTPUT->render_from_template('local_termsurvey/render_quarter_survey',$templatecontxt);
    }
    else if($survey_type == 4){
        echo $OUTPUT->render_from_template('local_termsurvey/render_trimester_survey',$templatecontxt);
    }
    else{
        echo $OUTPUT->header();
        echo "<h2>Invalid Redirect</h2>";
        echo $OUTPUT->footer();
        die;
    }

 
}
