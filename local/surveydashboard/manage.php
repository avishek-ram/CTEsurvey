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

require_once(__DIR__ . '/../../config.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/surveydashboard/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Survey Dashboard');

require_login();

//check if is admin
$user_id = $USER->id;
// $admins = get_admins();
// $is_admin = array_key_exists($user_id, $admins);

if (!is_siteadmin()) {
    //redirect(new moodle_url('/', array('redirect' => 0)));
    echo $OUTPUT->header();
    echo "<h2>Admin Access Required for Survey Dashboard</h2>";
    echo $OUTPUT->footer();
    die;

}

//get types of survey from database and make switch
$records = $DB->get_records('survey_dashboard');

//1 is semester end survey
//2 is mid sem survey
//3 is quarter end survey
$sem_end_survey_isactive = false;
$mid_sem_survey_isactive = false;
$quarter_end_survey_isactive = false;
$trimester_end_survey_isactive = false;

foreach($records as $rec){
    if($rec->surveytype == "1")
    {
        if($rec->surveystatus == "1"){
            $sem_end_survey_isactive = true;
        }
    }
    else if($rec->surveytype == "2")
    {
        if($rec->surveystatus == "1"){
            $mid_sem_survey_isactive = true;
        }
    }
    else if($rec->surveytype == "3")
    {
        if($rec->surveystatus == "1"){
            $quarter_end_survey_isactive = true; 
        }
    }
    else if($rec->surveytype == "4")
    {
        if($rec->surveystatus == "1"){
            $trimester_end_survey_isactive = true; 
        }
    }

}

echo $OUTPUT->header();
//echo($records);
$templatecontxt = (object)[
    'sem_end_survey_isactive' => $sem_end_survey_isactive,
    'mid_sem_survey_isactive' => $mid_sem_survey_isactive,
    'quarter_end_survey_isactive' => $quarter_end_survey_isactive,
    'trimester_end_survey_isactive' => $trimester_end_survey_isactive,
    'api_url' => $CFG->wwwroot . "/local/surveydashboard/survey_dash_api.php"
];

echo $OUTPUT->render_from_template('local_surveydashboard/dashboard',$templatecontxt);
echo $OUTPUT->footer();