<?php if(!defined("PROTECTED")) exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Error</title>
		<script type="text/javascript" src="script.js"></script>
	</head>
	<body>
		<p><?php eh($db->get_last_error()); ?></p>
	</body>
</html>
