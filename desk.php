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

// load and create the desk data

include_once("block.php");


class Desk {
	var $desk;
	var $blocks;
	
	function __construct($options) {
		$this->desk = $options["desk"];

		$xml = load_xml($options["path"],$options["desk"]);
		if (!$xml) die ("*** no desk definition file found");
		else $this->desk = $xml;

		// load block definitions

		$this->blocks = new Block($options);
	}


//==============================================================================
// get desk data
	function get_desk($area) {

// parse blocks
		foreach($this->desk as $entry) {
			foreach($entry as $col) {
				$name = (string)$col->children()->getName();

				$col[0] = "";
				simplexml_insert($col[0],$this->blocks->get_block($name));
//				$this->blocks->get_block($col->children()->getName());
			}
		}

		return $this->desk->asXML();
	}

}
?>
