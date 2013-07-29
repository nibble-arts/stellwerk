<?PHP

$dataRoot = "data/";


// load railway structure
if (!$anlage = load_xml($dataRoot,"anlage.xml")) die ("no structure file found");

// load block definitions
if (!$anlage = load_xml($dataRoot,"blocks.xml")) die ("no block definition file found");

// load desk structure
if (!$anlage = load_xml($dataRoot,"bgh.xml")) die ("no desk definition file found");


// $style = "comment.xslt";
//	$comm_style = simplexml_load_file($dataRoot.$style);
//	display_comment($anlage->comment,$comm_style);



function display_comment($xml,$xsl) {
	$xslt = new XSLTProcessor(new SimpleXMLElement($xsl));
	$xslt->importStylesheet($xsl);
	echo $xslt->transformToXml($xml);
}


function load_xml($path,$file) {
	if (file_exists($path.$file)) {
		return simplexml_load_file($path.$file);
	}
	else
		return false;
}

?>
