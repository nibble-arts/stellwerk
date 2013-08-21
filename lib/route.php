<?PHP

class route {
	private $route;
	private $error;

	function __construct($options) {

	// load route structure
		$xml = load_xml($options["path"],$options["route"]);
		if (!$xml) die ("*** no route file found");
		else $this->route = $xml;

		$this->error = "";
	}
	

//==============================================================================
// get route
	function get_route($start,$target) {
		$this->error = "";

		if (!$this->route->$start) {
			$this->error = "'$start' not found";
			return false; // no start
		}

		if (!$this->route->$start->$target) {
			$this->error = "$start => $target not found";
			return false; // no target
		}
		
		$route = $this->route->$start->$target;

// routes defined
		if ($route->route) {
			foreach($route->route as $entry) {
				if ($routeId = (string)$entry->attributes()->id) {
// call recursion
					$tempArray = explode("=>",$routeId);

					simplexml_insert($entry, $this->get_route($tempArray[0],$tempArray[1])->route);
				}
			}
		} else {
			$this->error = "no route defined for $start => $target";
		}

		return $route;
	}


//==============================================================================
// get list of routes
	function get_route_list($start) {

//TODO check status

		$route = $this->route->$start;

// add start to route targets
		if (count($route)) {
			foreach($route->children() as $entry) {
				$entry->addAttribute("start",$start);
			}
		}

		return $route;
	}


//==============================================================================
// get error message
	function get_error() {
		return (string)$this->error;
	}
}

?>
