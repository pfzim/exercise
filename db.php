<?php
require_once("config.php");

class db
{
	private $link = NULL;
	public $data = NULL;
	private $error_msg = "";

	function __construct()
	{
		$link = NULL;
		$data = NULL;
		$error_msg = "";
	}
	
	function connect($db_host = DB_HOST, $db_user = DB_USER, $db_passwd = DB_PASSWD, $db_name = DB_NAME, $db_cpage = DB_CPAGE)
	{
		$this->link = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
		if(!$this->link)
		{
			$this->error_msg = mysqli_connect_error();
			return NULL;
		}

		if(!mysqli_set_charset($this->link, $db_cpage))
		{
			$this->error_msg = mysqli_error($this->link);
			mysqli_close($this->link);
			$this->link = NULL;
			return NULL;
		}
		
		return $this->link;
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	public function select($query)
	{
		$this->data = NULL;
		
		if(!$this->link)
		{
			return FALSE;
		}

		$res = mysqli_query($this->link, $query);
		if(!$res)
		{
			$this->error_msg = mysqli_error($this->link);
			return FALSE;
		}
		
		if(mysqli_num_rows($res) <= 0)
		{
			return FALSE;		
		}

		$this->data = array();
		
		while($row = mysqli_fetch_row($res))
		{
			$this->data[] = $row;
		}
		
		mysqli_free_result($res);
		
		return TRUE;
	}

	public function put($query)
	{
		if(!$this->link)
		{
			return FALSE;
		}

		$res = mysqli_query($this->link, $query);
		if(!$res)
		{
			$this->error_msg = mysqli_error($this->link);
			return FALSE;
		}
		
		//return mysqli_affected_rows($this->link);
		return TRUE;
	}

	public function last_id()
	{
		return mysqli_insert_id($this->link);
	}

	public function disconnect()
	{
		$this->data = NULL;
		$this->error_msg = "";
		
		if($this->link)
		{
			mysqli_close($this->link);
			$this->link = NULL;
		}
	}

	public function get_last_error()
	{
		return $this->error_msg;
	}
}