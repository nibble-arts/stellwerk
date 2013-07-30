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

// $style = "comment.xslt";
//	$comm_style = simplexml_load_file($dataRoot.$style);
//	display_comment($anlage->comment,$comm_style);


class Control {
	private $control;
	private $desk;
	
//==============================================================================
// create control object
	function __construct($options) {

// load railway structure
		$xml = load_xml($options["path"],$options["control"]);
		if (!$xml) die ("*** no structure file found");
		else $this->control = $xml;
	}


//==============================================================================
// display comment of control data
	function display_comment() {
		$xslt = new XSLTProcessor(new SimpleXMLElement($xsl));
		$xslt->importStylesheet($xsl);
		echo $xslt->transformToXml($this->control);
	}
}




function load_xml($path,$file) {
	if (file_exists($path.$file)) {
		return simplexml_load_file($path.$file);
	}
	else
		return false;
}

?>