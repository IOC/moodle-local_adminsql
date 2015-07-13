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

require_once($CFG->libdir.'/tablelib.php');

class local_adminsql_renderer extends plugin_renderer_base {

    public function sql_result($data) {

        if ($data === true) {
            echo  html_writer::tag('div', 'OK', array('class' => 'alert alert-success'));
        } else {
            echo html_writer::tag('h3', get_string('results', 'local_adminsql'));
            echo html_writer::start_div('adminsql_overflow');
            $table = new flexible_table('adminsql-results');
            $header = false;
            foreach ($data as $d) {
                $row = get_object_vars($d);
                if (!$header) {
                    $header = true;
                    $table->define_columns(array_keys($row));
                    $table->define_headers(array_keys($row));
                    $table->set_attribute('class', 'incompatibleblockstable generaltable');
                    $table->define_baseurl('/local/adminsql/index.php');
                    $table->setup();
                }
                $table->add_data(array_values($row));
            }
            $data->close();
            $table->print_html();
            echo html_writer::end_div();
        }
    }
}
