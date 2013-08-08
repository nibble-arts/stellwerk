<?PHP

function signal() {
	global $control;

//TODO check status
	$signal = $control->get_signal();

/*	if (count($route)) {
		foreach($route->children() as $entry) {
			$entry->addAttribute("start",$start);
		}
	}*/
	$data = new simpleXmlElement("<data></data>");
		
	if (count($signal)) {
		foreach($signal as $entry) {

			$newChild = $data->addChild((string)$entry);
			$newChild->addAttribute("type",$entry->attributes()->type);
		}
	}

	return $data;
}

?>
