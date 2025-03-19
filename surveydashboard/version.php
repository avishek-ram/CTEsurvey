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
 * Version information for the local_surveydashboard plugin
 *
 * @package     local_surveydashboard
 * @author      Avishek Ram
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_surveydashboard';  // Full name of the plugin (used for diagnostics)
$plugin->version = 2024032001;  // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires = 2022112800;  // Requires this Moodle version (4.1)
$plugin->maturity = MATURITY_STABLE;     // The current maturity level of this version of the plugin
$plugin->release = 'v1.0.0';             // The release name of this version of the plugin
$plugin->dependencies = array(); // List of other plugins this plugin depends on