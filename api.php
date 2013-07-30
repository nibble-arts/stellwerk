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

$options = array(
	"path" => "data/",
	"control" => "anlage.xml",
	"desk" => "bgh.xml",
	"blocks" => "blocks.xml"
);

$control = new Control($options);
$desk = new Desk($options);


// parse api call */

if (isset($_GET["cmd"])) $cmd = $_GET["cmd"];
if (isset($_GET["area"])) $area = $_GET["area"];

if (isset($cmd)) {
	switch($cmd) {
		case "getdesk":
			if ($area)
				echo $desk->get_desk($area);
			break;
	}
}


function print_pre($content) {
	echo "<pre>";
		print_r($content);
	echo "</pre>";
}
?>

