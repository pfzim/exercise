<?php

function ee($str) // echo_exit
{
	echo $str;
	exit;
}

function je($value) //json_escape
{
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

function se($value) //sql_escape
{
    $escapers = array("\\", "\"", "\n", "\r", "\t", "\x08", "\x0c", "'", "\x1A", "\x00", "%", "_");
    $replacements = array("\\\\", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b", "\\'", "\\Z", "\\0", "\%", "\_");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

	header("Content-Type: text/plain; charset=utf-8");

	require_once("db.php");

	$action = '';
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
	}

	$id = 0;
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
	}

	$db = new DB();
	if(!$db->connect())
	{
		ee('{"result": 1, "status": "db error"}');
	}

	switch($action)
	{
		case 'add':
		{
			if(!$_POST['name'])
			{
				ee('{"result": 1, "status": "name undefined"}');
			}
			if(!$_POST['cid'])
			{
				ee('{"result": 1, "status": "cid undefined"}');
			}
			if(!$_POST['price'] || !preg_match('/^\d+(\.\d{1,2})?$/', $_POST['price']))
			{
				ee('{"result": 1, "status": "price undefined"}');
			}

			$name = se($_POST['name']);
			$cid = intval($_POST['cid']);
			$price = $_POST['price'];
			$category = "";
			
			if($db->put("INSERT INTO `tbl_goods` (`name`, `cid`, `price`) VALUES ('$name', $cid, $price)"))
			{
				if($id = $db->last_id())
				{
					if($db->select("SELECT m.`id`, m.`name` FROM `tbl_categories` AS m WHERE m.`id` = $cid LIMIT 1"))
					{
						$category = $db->data[0][1];
						ee('{"result": 0, "id": '.$id.', "name": "'.je($_POST['name']).'", "category": "'.je($category).'", "price": '.$price.'}');
					}
				}
			}
			break;
		}
		case 'edit':
		{
			if(!$id)
			{
				ee('{"result": 1, "status": "id undefined"}');
			}
			if(!$_POST['name'])
			{
				ee('{"result": 1, "status": "name undefined"}');
			}
			if(!$_POST['cid'])
			{
				ee('{"result": 1, "status": "cid undefined"}');
			}
			if(!$_POST['price'] || !preg_match('/^\d+(\.\d{1,2})?$/', $_POST['price']))
			{
				ee('{"result": 1, "status": "price undefined"}');
			}

			$name = se($_POST['name']);
			$cid = intval($_POST['cid']);
			$price = $_POST['price'];
			$category = "";
			
			if($db->put("UPDATE `tbl_goods` SET `name`='$name', `cid`=$cid, `price`=$price WHERE `id` = $id LIMIT 1"))
			{
				if($db->select("SELECT m.`id`, m.`name` FROM `tbl_categories` AS m WHERE m.`id` = $cid LIMIT 1"))
				{
					$category = $db->data[0][1];
					ee('{"result": 0, "id": '.$id.', "name": "'.je($_POST['name']).'", "category": "'.je($category).'", "price": '.$price.'}');
				}
			}
			break;
		}
		case 'delete':
		{
			if(!$id)
			{
				ee('{"result": 1, "status": "id undefined"}');
			}
			
			if($db->put("DELETE FROM `tbl_goods` WHERE `id` = $id LIMIT 1"))
			{
				ee('{"result": 0, "id": '.$id.'}');
			}
			break;
		}
		case 'categories':
		{
			if($db->select("SELECT m.`id`, m.`name` FROM `tbl_categories` AS m ORDER BY m.`name`"))
			{
				echo '{"result": 0, "list": [';
				$i = 0;
				foreach($db->data as $row)
				{
					if($i)
					{
						echo ', ';
					}
					echo '{"id": '.$row[0].', "name": "'.je($row[1]).'"}';
					$i++;
				}
				echo ']}';
				exit;
			}
			break;
		}
		default:
			ee('{"result": 1, "status": "action undefined"}');
	}

	ee('{"result": 1, "status": "unknown error"}');
