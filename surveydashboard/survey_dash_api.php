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
 * Survey Dashboard API endpoint
 *
 * @package     local_surveydashboard
 * @author      Avishek Ram
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/survey_dashboard.php');

use local_surveydashboard\survey_dashboard;

// Set JSON content type
header('Content-Type: application/json');

require_login();

// Check if user has required capabilities
$context = context_system::instance();
if (!has_capability('local/surveydashboard:manage', $context)) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => get_string('nopermissions', 'error')]));
}

// Validate and get parameters
$term = required_param('term', PARAM_INT);    
$status = required_param('status', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_RAW);

// Validate parameter values
if (!in_array($term, [1, 2, 3, 4])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => get_string('invalidterm', 'local_surveydashboard')]));
}

if (!in_array($status, [0, 1])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => get_string('invalidstatus', 'local_surveydashboard')]));
}

// Validate sesskey for database operations
if (!confirm_sesskey($sesskey)) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => get_string('invalidrequest', 'error')]));
}

try {
    $survey = new survey_dashboard();
    $survey->update_survey_status($term, $status, $USER->id);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => get_string('updatesuccess', 'local_surveydashboard')
    ]);
    
} catch (moodle_exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}

