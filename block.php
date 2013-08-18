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

class Block {
	private $blocks;
	
	function __construct($options) {

		$xml = load_xml($options["path"],$options["blocks"]);
		if (!$xml) die ("*** no block definition file found");
		else $this->blocks = $xml;
	}
	
	
// return block data
	function get_block($block) {
// filter direct field entry
		if ($block and $block != "field") {
			$data = $this->blocks->xPath("//{$block}");
			if ($data) return $data[0];
		}
	}
}

?>
