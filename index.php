<?php
function eh($text)
{
	echo htmlspecialchars($text);
}

	header("Content-Type: text/html; charset=utf-8");
	define("PROTECTED", "YES");
	
	require_once("db.php");

	$db = new DB();
	if($db->connect() && $db->select("SELECT m.`id`, m.`name`, j1.`name`, m.`price` FROM `tbl_goods` AS m LEFT JOIN `tbl_categories` AS j1 ON j1.`id` = m.`cid`"))
	{
		include("tpl.list.php");
	}
	else
	{
		include("tpl.error.php");
	}
