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

// script to save and retreave railway status informations

include_once("lib/sqlite.php");


class Status {
	var $statusPath = "status/";
	var $statusLife = 20; // life time for status informations
	var $db;
	
//=========================================================
// create or open status database

	function __construct() {
		$dbPath = "db/";
		$dbName = "status.db";

// create new database
		if (!file_exists($dbPath.$dbName)) {
			$this->db = new Database($dbPath.$dbName);

			$this->db->create_table("route","ID integer PRIMARY KEY AUTOINCREMENT,name text");
			$this->db->create_table("block","ID integer PRIMARY KEY AUTOINCREMENT,status integer,lock integer,time integer");
			$this->db->create_table("fifo","ID integer PRIMARY KEY AUTOINCREMENT,block integer,route integer");
		}

// open database
		else
			$this->db = new Database($dbPath.$dbName);
	}





//=========================================================
// update status informations
	function update() {
	}


//=========================================================
// get status information from module with id
	function get_status($id) {

// check if status db is up to date
		$query = "";
//		$time = $this->db->query($query);

//print_pre($this->db);

// update old entries


// return status information as xml

	}


//=========================================================
// looks if status information is alive
	function is_alive($id) {
	}
	
	
//=========================================================
// get timestamp of status file
	function get_timestamp($id) {
	}


//=========================================================
// look if status exists
	function status_exists($id) {
	}
	

//=========================================================
// create status definition
	function update_status($id) {
	}
}

?>
