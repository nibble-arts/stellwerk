<?PHP

function route($start) {
	global $control;

//TODO check status
	$route = $control->get_route($start);

	if (count($route)) {
		foreach($route->children() as $entry) {
			$entry->addAttribute("start",$start);
		}
	}
	return $route;
}

?>
