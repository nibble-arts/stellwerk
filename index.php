<html>
	<head>
		<title>Stellwerk</title>
		
		<link rel="stylesheet" type="text/css" href="stellwerk.css">
		
		<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="js/display.js" type="text/javascript"></script>
		<noscript><div id="noscript">Bitte Javascript aktivieren</div></noscript>
	</head>

<!--
http://localhost/stellwerk/api.php
http://www.iggmp.net/stellwerk/api.php
-->
	<body onload="init('stellwerk','http://localhost/stellwerk/api.php')">
		<div id="desk_1"></div>
		<div id="desk_2"></div>
		<div id="desk_3"></div>
		<div id="desk_4"></div>

		<div id="status">
			<span id="deskStatus" class="status">Desk</span>
			<span id="syncStatus" class="status">Sync</span>
		</div>
	</body>
</html>
