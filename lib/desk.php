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

include_once("lib/block.php");


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
		$areaXml = $this->desk->xPath("area[@name='{$area}']");

		if (count($areaXml)) {
			foreach($areaXml[0] as $entry) {
				foreach($entry as $col) {
//print_pre($col);
					$name = (string)$col->children()->getName();
					$pos = $col->attributes()->pos; // block light position

	// get block definition
					$block = $this->blocks->get_block($name);

	// insert block/position definition to lights
					if ($block and $pos) {

	// set position values for light
						foreach ($block->light as $light) {
							if (!$light->status->attributes()->pos) {

								foreach ($light->status as $status) {
									$status->addAttribute("pos",$pos);
								}
							}
						}
					}

					simplexml_insert($col[0],$block);
				}
			}

			return $areaXml[0];
		}
	}


//==============================================================================
// return a list of areas
	function get_areas() {
		$areas = $this->desk->xPath("//area");
		$areaArray = array();

// loop all areas defined
		foreach ($areas as $entry) {
			$name = (string)$entry->attributes()->name;

	//TODO get area status from database
			$status = "auto";
						
			$area = "<area name='".$name."' status='".$status."'/>";
			array_push($areaArray,$area);
		}
// create areas xml
		$areaObj = new simpleXmlElement("<areas>".implode($areaArray)."</areas>");

		return $areaObj;
	}
	
}
?>
