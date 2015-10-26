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
 * @package    local-adminsql
 * @author     Marc Català <reskit@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function process_sql($data) {
    global $DB, $USER;

    $sql = isset($data->querysql)?$data->querysql:'';
    try {
        if (isset($data->showprocesslist)) {
            $result = $DB->get_recordset_sql('SHOW FULL PROCESSLIST');
        } else if (preg_match('/\b(UPDATE|INSERT|INTO|DELETE)\b/i', $sql, $matches)) {
            $result = $DB->execute($sql);
            $eventdata = array();
            $eventdata['context'] = context_system::instance();
            $eventdata['userid'] = $USER->id;
            $eventdata['other'] = array();
            $eventdata['other']['statement'] = isset($matches[0]) ? $matches[0] : 'unknown';

            $event = \local_adminsql\event\update_adminsql::create($eventdata);
            $event->trigger();
        } else if (preg_match('/^\bKILL\b/i', $sql)) {
            $result = $DB->execute($sql);
        } else {
            $result = $DB->get_recordset_sql($sql, null, 0, $data->limitsql);
        }
    } catch (exception $e) {
        echo '<div class="alert alert-error">ERROR: '.$e->error.'</div>';
        return false;
    }

    return $result;
}
