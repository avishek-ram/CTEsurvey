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
 * Survey Dashboard class
 *
 * @package     local_surveydashboard
 * @author      Avishek Ram
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_surveydashboard;

/**
 * Survey Dashboard class
 *
 * @package     local_surveydashboard
 * @author      Avishek
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class survey_dashboard {
    /** @var \moodle_database */
    protected $db;

    /**
     * Constructor
     */
    public function __construct() {
        global $DB;
        $this->db = $DB;
    }

    /**
     * Update survey status
     *
     * @param int $term The survey term
     * @param int $status The new status
     * @param int $userid The user ID who is making the update
     * @return bool True if successful
     * @throws \moodle_exception
     */
    public function update_survey_status($term, $status, $userid) {
        try {
            $id = $this->db->get_field('survey_dashboard', 'id', array('surveytype' => $term), MUST_EXIST);
            
            $rec = new \stdClass();
            $rec->id = $id;
            $rec->surveytype = $term;
            $rec->surveystatus = $status;
            $rec->statuslastupdated = time();
            $rec->lastupdatedby = $userid;
            
            return $this->db->update_record('survey_dashboard', $rec);
        } catch (\dml_exception $e) {
            throw new \moodle_exception('dbupdatefailed', 'local_surveydashboard', '', $e->getMessage());
        }
    }

    /**
     * Get survey status
     *
     * @param int $term The survey term
     * @return object The survey record
     * @throws \moodle_exception
     */
    public function get_survey_status($term) {
        try {
            return $this->db->get_record('survey_dashboard', array('surveytype' => $term), MUST_EXIST);
        } catch (\dml_exception $e) {
            throw new \moodle_exception('dbfetchfailed', 'local_surveydashboard', '', $e->getMessage());
        }
    }

    /**
     * Get all surveys
     *
     * @return array Array of survey records
     * @throws \moodle_exception
     */
    public function get_all_surveys() {
        try {
            return $this->db->get_records('survey_dashboard', null, 'surveytype ASC');
        } catch (\dml_exception $e) {
            throw new \moodle_exception('dbfetchfailed', 'local_surveydashboard', '', $e->getMessage());
        }
    }
} 