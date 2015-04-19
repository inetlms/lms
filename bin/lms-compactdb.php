#!/usr/bin/php
<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2015 LMS Developers
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
 *  $Id: index.php,v 1.219 2010/05/24 07:43:16 chilek Exp $
 */

// REPLACE THIS WITH PATH TO YOUR CONFIG FILE

// PLEASE DO NOT MODIFY ANYTHING BELOW THIS LINE UNLESS YOU KNOW
// *EXACTLY* WHAT ARE YOU DOING!!!
// *******************************************************************

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
        'C:' => 'config-file:',
        'h' => 'help',
        'l:' => 'level:',
        'o' => 'removeold',
        'd' => 'removedeleted',
        'v' => 'version',
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

if (array_key_exists('version', $options))
{
        print <<<EOF
lms-compatdb.php v1.0
(C) 2001-2015 LMS Developers

EOF;
        exit(0);
}

if (array_key_exists('help', $options))
{
        print <<<EOF
lms-compatdb.php
(C) 2001-2015 LMS Developers

-C, --config-file=/etc/lms/lms.ini      alternate config file (default: /etc/lms/lms.ini);
-h, --help                              print this help and exit;
-v, --version                           print version info and exit;
-l, --level                             compact level low,medium,high (default: medium)
                                        low    - Data older than one day will be combined into one day
                                        medium - Data older than one month will be combined into one day
                                        high   - Data older than one month will be combined into one hour
-o, --old                               remove records older than one year
-d, --deleted                           remove stats of deleted nodes

EOF;
        exit(0);
}


if (array_key_exists('config-file', $options))
        $CONFIG_FILE = $options['config-file'];
else
        $CONFIG_FILE = '/etc/lms/lms.ini';

if (array_key_exists('removeold', $options))
        $removeold=1;
else
        $removeold=0;

if (array_key_exists('removedeleted', $options))
        $removedeleted=1;
else
        $removedeleted=0;

if (array_key_exists('level', $options))
        switch ($options['level']):
            case 'low':
                $level='low';
                break;
            case 'medium':
                $level='medium';
                break;
            case 'high':
                $level='high';
                break;
            default:
                $level='medium';
        endswitch;

else
        $level='medium';

if (!is_readable($CONFIG_FILE))
        die('Unable to read configuration file ['.$CONFIG_FILE.']!');

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

// Check for configuration vars and set default values
$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
// Do some checks and load config defaults

require_once(LIB_DIR.'/config.php');
require_once(LIB_DIR.'/language.php');


// Init database

$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME);

if(!$DB)
{
        // can't working without database
        die("Fatal error: cannot connect to database!\n");
}

// Read configuration from database

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
        foreach($cfg as $row)
                $CONFIG[$row['section']][$row['var']] = $row['value'];

// Include required files (including sequence is important)

//require_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/common.php');

set_time_limit(0);

print('Compacting Database '.$_DBNAME.  PHP_EOL);
print('Level = '.$level.  PHP_EOL);
print('Remove old  = '.$removeold.  PHP_EOL);
print('Remove deleted  = '.$removedeleted.  PHP_EOL);

printf('%d records before compacting'.PHP_EOL ,$DB->GetOne('SELECT COUNT(*) FROM stats'));

flush();

if($removeold)
{
    if($deleted = $DB->Execute('DELETE FROM stats where dt < ?NOW? - 365*24*60*60'))
    {
        printf('%d at least one year old records have been removed'.  PHP_EOL, $deleted);
        flush();
    }
}

if($removedeleted)
{
    if($deleted = $DB->Execute('DELETE FROM stats WHERE nodeid NOT IN (SELECT id FROM nodes)'))
    {
        printf('%d records for deleted nodes has been removed'.  PHP_EOL, $deleted);
        flush();
    }
}

if(isset($level))
{
    $time = time();
    switch($level)
    {
        case 'medium' : $period = $time-30*24*60*60; $step = 24*60*60; break;//month, day
        case 'high' : $period = $time-365*24*60*60; $step = 60*60; break; //month, hour
        default: $period = $time-24*60*60; $step = 24*60*60; break; //1 day, day
    }

    if ($mintime = $DB->GetOne('SELECT MIN(dt) FROM stats'))
    {
            if ($CONFIG['database']['type'] != 'postgres')
            $multi_insert = true;
            else if (version_compare($DB->GetDBVersion(), '8.2') >= 0)
                $multi_insert = true;
            else
                $multi_insert = false;

            $nodes = $DB->GetAll('SELECT id, name FROM nodes ORDER BY name');

        foreach ($nodes as $node)
        {
            $deleted = 0;
            $inserted = 0;
            $maxtime = $period;
            $timeoffset = date('Z');
            $dtdivider = 'FLOOR((dt+'.$timeoffset.')/'.$step.')';

            $data = $DB->GetAll('SELECT SUM(download) AS download, SUM(upload) AS upload,
                    COUNT(dt) AS count, MIN(dt) AS mintime, MAX(dt) AS maxtime
                FROM stats WHERE nodeid = ? AND dt >= ? AND dt < ?
                GROUP BY nodeid, '.$dtdivider.'
                ORDER BY mintime', array($node['id'], $mintime, $maxtime));

            if ($data) {
                // If divider-record contains only one record we can skip it
                // This way we'll minimize delete-insert operations count
                // e.g. in situation when some records has been already compacted
                foreach($data as $rid => $record) {
                    if ($record['count'] == 1)
                        unset($data[$rid]);
                    else
                        break;
                }

                // all records for this node has been already compacted
                if (empty($data)) {
                    echo $node['name'].': 0  - removed, 0 - inserted'.  PHP_EOL;
                    flush();
                    continue;
                }

                $values = array();
                // set start datetime of the period
                $data = array_values($data);
                $nodemintime = $data[0]['mintime'];

                $DB->BeginTrans();

                // delete old records
                $DB->Execute('DELETE FROM stats WHERE nodeid = ? AND dt >= ? AND dt <= ?',
                    array($node['id'], $nodemintime, $maxtime));

                // insert new (summary) records
                foreach ($data as $record) {
                            $deleted += $record['count'];

                    if (!$record['download'] && !$record['upload'])
                        continue;

                    if ($multi_insert)
                        $values[] = sprintf('(%d, %d, %d, %d)',
                            $node['id'], $record['maxtime'], $record['upload'], $record['download']);
                    else
                        $inserted += $DB->Execute('INSERT INTO stats
                            (nodeid, dt, upload, download) VALUES (?, ?, ?, ?)',
                            array($node['id'], $record['maxtime'],
                                $record['upload'], $record['download']));
                }

                if (!empty($values))
                    $inserted = $DB->Execute('INSERT INTO stats
                        (nodeid, dt, upload, download) VALUES ' . implode(', ', $values));

                $DB->CommitTrans();

                echo $node['name'].': '.$deleted.' - removed, '.$inserted.' - inserted'.  PHP_EOL;
                flush();
            }
        }
    }
}

printf('%d records after compacting'.PHP_EOL ,$DB->GetOne('SELECT COUNT(*) FROM stats'));

flush();

$DB->Destroy();

?>
