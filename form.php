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
 * @package    local
 * @subpackage adminsql
 * @copyright  2015 Institut Obert de Catalunya
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class adminsql_form extends moodleform {

    public function definition() {
        global $DB, $CFG, $COURSE;

        $mform =& $this->_form;

        $mform->addElement('textarea', 'querysql', get_string('querysql', 'local_adminsql'), 'rows="15" cols="80"');
        $mform->setType('querysql', PARAM_RAW_TRIMMED);
        $mform->disabledIf('querysql', 'showprocesslist', 'checked');

        $options = array_combine(range(10, 100, 10), range(10, 100, 10));
        $mform->addElement('select', 'limitsql', get_string('limitsql', 'local_adminsql'), $options);
        $mform->setType('limitsql', PARAM_INT);
        $mform->disabledIf('limitsql', 'showprocesslist', 'checked');

        $mform->addElement('checkbox', 'showprocesslist', get_string('showprocesslist','local_adminsql'));


        $this->add_action_buttons(false, get_string('submit'));
    }

    public function validation($data, $files) {
        global $DB, $CFG, $db, $USER;

        $errors = parent::validation($data, $files);

        if (isset($data['querysql'])) {
            if (empty($data['querysql']) and !isset($data['showprocesslist'])) {
                $errors['querysql'] = get_string('required');
            }
            else if (preg_match('/\b(ALTER|CREATE|DROP|GRANT|TRUNCATE|VACUUM|REINDEX|DISCARD|LOCK)\b/i', $data['querysql'])) {
                $errors['querysql'] = get_string('notallowedstatement', 'local_adminsql');
            } else if (strpos($data['querysql'], ';') !== false) {
                $errors['querysql'] = get_string('nosemicolon', 'local_adminsql');
            }
        }
        return $errors;
    }
}
