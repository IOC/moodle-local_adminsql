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
 * The access_adminsql event.
 *
 * @package    local_adminsql
 * @copyright  2015 Marc Català
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_adminsql\event;

defined('MOODLE_INTERNAL') || die();
/**
 * @since     Moodle 2.6
 * @copyright 2015 Marc Català
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
class access_adminsql extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'r'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = null;
    }

    public static function get_name() {
        return get_string('eventaccessadminsql', 'local_adminsql');
    }

    public function get_description() {
        return "The user with id {$this->userid} has accessed to Admin SQL.";
    }

    public function get_url() {
        return new \moodle_url('/local/adminsql/index.php');
    }
}
