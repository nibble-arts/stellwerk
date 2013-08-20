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

class Status {
	var $statusPath = "status/";
	var $statusLife = 20; // life time for status informations

//=========================================================
// update status informations
	function update() {

	}



//=========================================================
// get status information from module with id
	function get_status($id) {
	}


//=========================================================
// get timestamp of status file
	function get_timestamp($id) {
		$dir = dir($this->statusPath.$id);
		
		while ($entry = $dir->read()) {
			if ($entry != "." and $entry != "..")
			 	break;
		}

		$dir->close();

		return $entry;
	}


//=========================================================
// looks if status information is alive
	function is_alive($id) {
		$timestamp = $this->get_timestamp($id);

		if ((time() - $timestamp) > $this->statusLife)
			return FALSE;
		else
			return TRUE;
	}
	
	
//=========================================================
// look if status exists
	function status_exists($id) {

// status exist
// check timestamp
		if (file_exists($this->statusPath.$id)) {
			if ($this->is_alive($id))
				echo "status is alive";

			else {
				echo "status died";
// delete status
				$this->update_status($id);
			}	
		} else {


echo "create status $id<br>";
			$this->update_status($id);
		}
	}
	

//=========================================================
// create status definition
	function update_status($id) {
// delete old status
//		if (file_exists($this->statusPath.$id))
		

//TODO request status from railway

		mkdir($this->statusPath.$id);

		$fileHandle = fopen($this->statusPath.$id."/".time().".stat","w");
		fwrite($fileHandle,"<{$id}><status>0</status><signal>0</signal><position>0</position></{$id}>");
		fclose($fileHandle);
	}
}

?>
