<?PHP
//
// STELLWERK
//
// This file is part of the STELLWERK railway control system.
//
// Copyright(c) 2013 Thomas H Winkler
// thomas.winkler@iggmp.net
//
// This file may be licensed under the terms of of the
// GNU General Public License Version 3 (the ``GPL'').
//
// Software distributed under the License is distributed
// on an ``AS IS'' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//

// sqlite3 status database access

class Database {
	var $db;
	
//===========================================================
// open or create database
	function __construct($path) {
		$this->db = new SQLite3($path);
	}


//===========================================================
// create table if not exists
	function create_table($table,$define) {
		$query = "CREATE TABLE {$table}({$define})";
		if (!$this->table_exists($table))
			$this->db->exec($query);
	}
	

//===========================================================
// close database
	function close() {
		$this->db->close();
	}


//===========================================================
// insert data in table
//    content = array(field => data, ...)
	function insert($table,$content) {
		$fields = array();
		$data = array();

		foreach ($content as $key => $entry) {
			array_push($fields,$key);
			array_push($data,"'{$entry}'");
		}

		$query = "INSERT INTO {$table} (".implode(",",$fields).") values(".implode(",",$data).")";

		$this->db->exec($query);
	}


//===========================================================
// truncate table (clear all data in it)
	function truncate($table) {
		if ($this->table_exists($table)) {
			$this->db->exec("DELETE FROM {$table}");
		}
	}


//===========================================================
// checks if a table exists
	function table_exists($table) {
		$list = $this->db->query("SELECT * FROM sqlite_master WHERE type='table' and name='{$table}'");
		$temp = $list->fetchArray();
	
		if (is_array($temp)) return true;
		return false;
	}
}



?>
