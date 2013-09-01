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
			$this->db->create_table("block","ID integer PRIMARY KEY AUTOINCREMENT,name text,status integer,lock integer,time integer,error integer");
			$this->db->create_table("fifo","ID integer PRIMARY KEY AUTOINCREMENT,name text,block integer,route integer");

			$this->db->create_table("signal","ID integer PRIMARY KEY AUTOINCREMENT,name text,status integer,time integer,error integer");
			$this->db->create_table("switch","ID integer PRIMARY KEY AUTOINCREMENT,name text,status integer,time integer,error integer");
		}

// open database
		else
			$this->db = new Database($dbPath.$dbName);
	}



//=========================================================
// initialize status database
	function init($control) {
		
//*********************************
// get block status
		$this->db->truncate("block"); // clear table
		$status = $control->get_status(); // get list of blocks

		foreach ($status as $entry) {
//TODO get status from block (free = 0, occupied = 1)
//  ==> get from block
//     name ... name of block
//     address ... ip address for call
//     status ... retreived status
//     error ... NOT NULL if an function or communication error occured

			$name = (string)$entry->attributes()->id;
			$address = (string)$entry->ip;
			$status = 0;
			$error = 0;

			$this->db->insert("block",array("name" => $name,"status" => $status,"lock" => "0","time" => time(),"error" => $error));
		}


//*********************************
// get switch status
		$this->db->truncate("switch"); // clear table
		$switch = $control->get_switch(); // get list of switches
		
		foreach ($switch as $entry) {
//TODO get status from switch (steight = 0, switched = 1)
//  ==> get from switch
//     name ... name of switch
//     address ... ip address for call
//     status ... retreived status
//     error ... NOT NULL if an function or communication error occured

			$name = (string)$entry->attributes()->id;
			$address = (string)$entry->ip;
			$status = 0;
			$error = 0;

			$this->db->insert("switch",array("name" => $name,"status" => $status,"time" => time(),"error" => $error));
		}


//*********************************
// set signal status
		$this->db->truncate("signal");
		$signal = $control->get_signal();

		foreach ($signal as $entry) {
//TODO set all stati from signals to 0 (stop)
//  ==> send to signal
//     name ... name of switch
//     address ... ip address for call
//     error ... NOT NULL if an function or communication error occured

			$name = (string)$entry->attributes()->id;
			$address = (string)$entry->ip;
			$error = 0;

			$this->db->insert("signal",array("name" => $name,"status" => "0","time" => time(),"error" => $error));
		}

	}
	

//=========================================================
// update status informations
	function update() {
		
	}


//=========================================================
// get status information from module with id
	function get_status($id) {

// check if status db is up to date


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
