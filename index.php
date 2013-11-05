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
 * Inform Elgg whether user is logged into Moodle.
 *
 * If session has been established, forward user back to original
 * Elgg url with Moodle user information.
 * 
 * @package    local
 * @subpackage elgg_sso
 * @copyright  2013 onwards Mediamaisteri Group {@link http://www.mediamaisteri.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require the Moodle configuration
require_once('../../config.php');

// Url of the Elgg site that requested the authentication
$url = optional_param('url', null, PARAM_NOTAGS);

// Nothing can be done if we don't know where the Elgg is located
if (empty($url)) {
	echo 'Elgg url is missing';
	die;
}

$config = get_config('local_elgg_sso');

// Use Elgg default authentication if SSO hasn't been configured
if (empty($config->api_key)) {
	forward_failed_sso($url);
}

if (isloggedin()) {
	$moodle_url = $CFG->wwwroot;
	$name = fullname($USER);
	$username = $USER->username;
	$email = $USER->email;
	$time = time();
	$code = md5($username . $time . $moodle_url . $config->api_key);

	$str_continue_info = get_string('continue_info', 'local_elgg_sso');
	$str_continue = get_string('continue', 'local_elgg_sso');
} else {
	forward_failed_sso($url);
}

function forward_failed_sso($url) {
	if (strpos($url, '?') !== false) {
		$redirect = "$url&response=0";
	} else {
		$redirect = "$url?response=0";
	}

	// Redirect back to url that requested the authentication
	header("Location: $redirect");
	exit;
}

/**
 * View a form that has all the data that Elgg needs to authenticate
 * the user. If javascript is enabled the form is posted automatically.
 * Otherwise a simple "Continue" button is displayed to the user. 
 */
echo <<<FORM
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<div style="text-align: center; margin-top: 100px;">
			<p>$str_continue_info <span id="counter">3</span></p>

			<form name="sso-form" action="$url" method="post">
				<input type="hidden" name="response" value="1" />
				<input type="hidden" name="url" value="$url" />
				<input type="hidden" name="name" value="$name" />
				<input type="hidden" name="username" value="$username" />
				<input type="hidden" name="email" value="$email" />
				<input type="hidden" name="time" value="$time" />
				<input type="hidden" name="code" value="$code" />
				<input type="submit" value="$str_continue"/>
			</form>
		</div>
		<script type="text/javascript">
			var seconds = 3;

	        interval = setInterval(function() {
	            var el = document.getElementById('counter');

	            if (seconds == 0) {
					document.forms["sso-form"].submit();
	            }

	            el.innerHTML = seconds;
	            seconds--;
	        }, 1000);
		</script>
	</body>
</html>
FORM;
