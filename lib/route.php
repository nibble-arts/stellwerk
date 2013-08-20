<?PHP

class route {
	private $route;

	function __construct($options) {

	// load route structure
		$xml = load_xml($options["path"],$options["route"]);
		if (!$xml) die ("*** no route file found");
		else $this->route = $xml;
	}
	

//==============================================================================
// get route
	function get_route($start,$target) {
		$route = $this->route->$start->$target;

//print_pre($start." ".$target);
		
		foreach($route->route as $entry) {
			if ($routeId = (string)$entry->attributes()->id) {
// call recursion
				$tempArray = explode("=>",$routeId);

				simplexml_insert($entry, $this->get_route($tempArray[0],$tempArray[1])->route);
			}
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
}

?>
