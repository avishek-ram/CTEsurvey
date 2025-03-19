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
 * Survey Dashboard management page
 *
 * @package     local_surveydashboard
 * @author      Avishek Ram
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/survey_dashboard.php');

use local_surveydashboard\survey_dashboard;

$PAGE->set_url(new moodle_url('/local/surveydashboard/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_surveydashboard'));
$PAGE->set_heading(get_string('pluginname', 'local_surveydashboard'));

require_login();

// Check if user has required capabilities
if (!has_capability('local/surveydashboard:manage', $PAGE->context)) {
    throw new moodle_exception('nopermissions', 'error', '', 'local/surveydashboard:manage');
}

// Get survey dashboard instance
$survey = new survey_dashboard();

// Get all survey records
$records = $survey->get_all_surveys();

// Initialize survey status variables
$survey_status = array(
    'sem_end_survey_isactive' => false,
    'mid_sem_survey_isactive' => false,
    'quarter_end_survey_isactive' => false,
    'trimester_end_survey_isactive' => false
);

// Process survey records
foreach ($records as $rec) {
    switch ($rec->surveytype) {
        case 1:
            $survey_status['sem_end_survey_isactive'] = ($rec->surveystatus == 1);
            break;
        case 2:
            $survey_status['mid_sem_survey_isactive'] = ($rec->surveystatus == 1);
            break;
        case 3:
            $survey_status['quarter_end_survey_isactive'] = ($rec->surveystatus == 1);
            break;
        case 4:
            $survey_status['trimester_end_survey_isactive'] = ($rec->surveystatus == 1);
            break;
    }
}

// Prepare template context
$templatecontext = (object)[
    'sem_end_survey_isactive' => $survey_status['sem_end_survey_isactive'],
    'mid_sem_survey_isactive' => $survey_status['mid_sem_survey_isactive'],
    'quarter_end_survey_isactive' => $survey_status['quarter_end_survey_isactive'],
    'trimester_end_survey_isactive' => $survey_status['trimester_end_survey_isactive'],
    'sesskey' => sesskey(),
    'config' => $CFG
];

// Output the page
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_surveydashboard/dashboard', $templatecontext);
echo $OUTPUT->footer();