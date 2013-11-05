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
 * elgg_sso plugin settings
 *
 * @package    local
 * @subpackage elgg_sso
 * @copyright  2013 onwards Mediamaisteri Group {@link http://www.mediamaisteri.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('localsetting_elgg_sso', 'Elgg SSO');
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configpasswordunmask(
    	'local_elgg_sso/api_key', // name
        new lang_string('api_key', 'local_elgg_sso'), // Visible name
        new lang_string('api_key_desc', 'local_elgg_sso'), // Description
        null // Default setting
	));
}
