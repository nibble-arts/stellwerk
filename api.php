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

// scripts for communication with the railway devices and the control desks
// desk commands
// cmd = getdesk & area=id1,id2,...
//       get the desk definition starting with the area id

include_once("xml.php");
include_once("control.php");
include_once("desk.php");
include_once("route.php");
include_once("signal.php");


//=========================================================
// ajax option definiton
//TODO get from configuration file

$options = array(
	"path" => "data/",
	"control" => "anlage.xml",
	"route" => "route.xml",
	"desk" => "silberwaldbahn.xml",
	"blocks" => "blocks.xml"
);


//=========================================================
// variable definition

$control = new Control($options);
$desk = new Desk($options);
$output = new simpleXmlElement("<apiXml></apiXml>");
$error = "no error";

if (isset($_GET["cmd"])) $cmd = $_GET["cmd"];
if (isset($_GET["area"])) $area = $_GET["area"];
if (isset($_GET["start"])) $start = $_GET["start"];


//=========================================================
// parse api call */

if (isset($cmd)) {
	
	switch($cmd) {

// get list of defined areas
		case "getareas":
			simplexml_insert($output,$desk->get_areas(),"all");
			break;

// get desk definiton
		case "getdesk":
			if (isset($area))
				simplexml_insert($output,$desk->get_desk($area),"all");
			else
				$error = "Missing api area parameter in 'getdesk' command";
			break;

//=========================================================
// get blocks able to be deactivated
//TODO for debug use
	// signal:   0 ... free
	//           1 ... occupied
	//           3 ... requested
	//           4 ... locked
		case "getblock":
			$output = new simpleXmlElement("<apiXml><data><E1 status='0' signal='0'/><S22 status='0' signal='1'/><W3 status='0' signal='2'/></data></apiXml>");
			break;
			
//=========================================================
// get switches able to be switched
//TODO for debug use
	// signal:   0 ... in normal position
	//           1 ... in switched position
	//           3 ... moving
	//           4 ... error
	//           4 ... locked
		case "getswitch":
			$output = new simpleXmlElement("<apiXml><data><W1 status='0' signal='0'/><W2 status='0' signal='1'/><W3 status='0' signal='2'/></data></apiXml>");
			break;
			
//=========================================================
// get switches able to be switched
//TODO for debug use
	// signal:   0 ... stop
	//           1 ... free
	//           2 ... free slow
	//           3 ... locked
		case "getsignal":
			$output = new simpleXmlElement("<apiXml><data></data></apiXml>");
			simplexml_insert($output->data,signal());
			break;

//=========================================================
// get switches able to be switched
//TODO for debug use
		case "getroute":
			if (isset($start)) {
				$output = new simpleXmlElement("<apiXml><data></data></apiXml>");
				simplexml_insert($output->data,route($start));
			}
			else
				$error = "Api no route start defined";
			break;
			

// unknow command
		default:
			$error = "Api command '{$cmd}' unknown";
	}
}
else

//=========================================================
// no command found
	$error = "Api command missing";

// insert error message
simplexml_insert($output,new simpleXmlElement("<error>{$error}</error>"));

// send result
echo $output->asXML();




//=========================================================
// debug output
function print_pre($content) {
	echo "<pre>";
		print_r($content);
	echo "</pre>";
}
?>

