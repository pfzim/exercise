<?php if(!defined("PROTECTED")) exit; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Test</title>
		<script type="text/javascript" src="script.js"></script>
	</head>
	<body>
		<table id="table">
			<thead>
				<tr>
					<th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Operation</th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach($db->data as $row)
	{
?>
				<tr id="row<?php eh($row[0]); ?>">
					<td><?php eh($row[0]); ?></td>
					<td><?php eh($row[1]); ?></td>
					<td><?php eh($row[2]); ?></td>
					<td><?php eh($row[3]); ?></td>
					<td><a href="#" onclick="f_edit(<?php eh($row[0]); ?>); return false;">Edit</a>&nbsp;<a href="#" onclick="f_delete(<?php eh($row[0]); ?>); return false;">Delete</a></td>
				</tr>
<?php
	}
?>
	</tbody>
		</table>
	<a href="#" onclick="f_edit(0); return false;">Add</a>
	</body>
</html>
