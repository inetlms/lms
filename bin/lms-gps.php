#!/usr/bin/php
<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, 
 *  USA.
 *
 *  $Id: lms-gps.php $
 */

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'C:' => 'config-file:',
	'q' => 'quiet',
	'h' => 'help',
	'v' => 'version',
	'u' => 'update',
);

foreach ($parameters as $key => $val) {
	$val = preg_replace('/:/', '', $val);
	$newkey = preg_replace('/:/', '', $key);
	$short_to_longs[$newkey] = $val;
}
$options = getopt(implode('', array_keys($parameters)), $parameters);
foreach($short_to_longs as $short => $long)
	if (array_key_exists($short, $options))
	{
		$options[$long] = $options[$short];
		unset($options[$short]);
	}


if (array_key_exists('config-file', $options) && is_readable($options['config-file']))
    $CONFIG_FILE = $options['config-file'];

include('/etc/lms/init_lms.php');


if (array_key_exists('version', $options))
{
	print <<<EOF
lms-gps.php
(C) 2001-2012 LMS Developers

EOF;
	exit(0);
}

if (array_key_exists('help', $options))
{
	print <<<EOF
lms-gps.php
(C) 2001-2012 LMS Developers

-C, --config-file=/etc/lms/lms.ini	alternate config file (default: /etc/lms/lms.ini);
-u, --update			update GPS coordinates using Google Maps API;
-h, --help			print this help and exit;
-v, --version			print version info and exit;
-q, --quiet			suppress any output, except errors;

EOF;
	exit(0);
}

$quiet = array_key_exists('quiet', $options);
if (!$quiet)
{
	print <<<EOF
lms-gps.php
(C) 2001-2012 LMS Developers

EOF;
}

$update = array_key_exists('update', $options);

$_APIKEY = $CONFIG['google']['apikey'];
if (!$_APIKEY) {
	echo "Unable to read apikey from configuration file.\n";
}

if ($update) {
	$loc = $DB->GetAll("SELECT id, location FROM nodes WHERE longitude IS NULL AND latitude IS NULL AND location IS NOT NULL AND location_house IS NOT NULL AND location !='' AND location_house !=''");
	if ($loc) {
		foreach($loc as $row) {
			$address = urlencode($row['location']." Poland");
			$link = "http://maps.google.com/maps/geo?q=".$address."&key=".$_APIKEY."&sensor=false&output=csv&oe=utf8";
			$page = file_get_contents($link);

			list($status, $accuracy, $latitude, $longitude) = explode(",", $page);

			if (($status == 200) && ($accuracy >= 4)) {
				$DB->Execute("UPDATE nodes SET latitude = ?, longitude = ? WHERE id = ?", array($latitude, $longitude, $row['id']));
				echo $row['id']." - OK\n";
			} else {
				echo $row['id']." - ERROR\n";
			}
			sleep(2);
		}
	}
}

?>
