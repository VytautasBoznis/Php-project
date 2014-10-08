<?php
require_once "database/config.php";

function get_mysql_con()
{
	global $db;
	
	return new mysqli($db['host'],$db['user'],$db['pass'],$db['database']);
}

class user
{
	public $user_id;
	public $user_name;
	public $user_pass;
	public $first_name;
	public $last_name;
	public $user_level;
	
	function __construct($u_id,$u_name,$u_pass,$f_name,$l_name,$u_level)
	{
		$this-> user_id = $u_id;
		$this-> user_name = $u_name;
		$this-> user_pass = $u_pass;
		$this-> first_name = $f_name;
		$this-> last_name = $l_name;
		$this-> user_level = $u_level;
	}
}

class university
{
	public $uni_id;
	public $uni_name;
	
	function __construct($uni_id,$uni_name)
	{
		$this-> uni_id = $uni_id;
		$this-> uni_name = $uni_name;
	}
	
	function get_programs()
	{
		$link = get_mysql_con();
		$result= $link->query("SELECT * FROM `".PROGRAM_DATA."` WHERE `uni_id` = '".$uni_id."'");
		
		$programs = Array();
		while($row = $result-> fetch_assoc())
		{
			$program = new program($row['program_id'],$row['uni_id'],$row['program_name'],$row['program_desc']);
			array_push($programs,$program);
		}
		
		$result->close();
		$link->close();
		
		return $programs;
	}
}

class program
{
	public $program_id;
	public $uni_id;
	public $program_name;
	public $program_desc;
		
	function __construct($program_id,$uni_id,$program_name,$program_desc)
	{
		$this-> program_id = $program_id;
		$this-> uni_id = $uni_id;
		$this-> program_name = $program_name;
		$this-> program_desc = $program_desc;
	}
	
	function get_criteria()
	{
		$link = get_mysql_con();
		$result = $link->query("SELECT * FROM `".CRITERIA_DATA."` WHERE `program_id` = '".$this ->program_id."'");
		
		$criterias = Array();
		while($row = $result-> fetch_assoc())
		{
			$criteria = new criteria($row['criteria_id'],$row['program_id'],$row['criteria_name'],$row['criteria_part']);
			array_push($criterias,$criteria);
		}
		
		$result->close();
		$link->close();
		
		return $criterias;
	}
	
}

class criteria
{
	public $criteria_id;
	public $program_id;
	public $criteria_name;
	public $procentile_part;
	
	function __construct($criteria_id,$program_id,$criteria_name,$procentile_part)
	{
		$this->criteria_id = $criteria_id;
		$this->program_id = $program_id;
		$this->criteria_name = $criteria_name;
		$this->procentile_part = $procentile_part;
	}
}

// === Vartotoju duomenu valdymas ===

function add_user($user_name,$user_pass,$firs_name,$last_name,$user_level)
{
	$link = get_mysql_con();
	$stmt = "INSERT INTO `".USER_DATA."`(`user_name`, `user_pass`, `first_name`, `last_name`, `user_level`) VALUES ('".$user_name."','".$user_pass."','".$firs_name."','".$last_name."','".$user_level."')";
	if ($link ->query($stmt))
	{
		$link->close();
		return true;
	}
	else
	{
		$link->close();
		return false;
	}
}

function get_user_id($user_name,$user_pass)
{
	$link = get_mysql_con();
	$result = $link ->query("SELECT `user_id` FROM `".USER_DATA."` WHERE `user_name` = '".$user_name."' AND `user_pass` = '".$user_pass."'");
	$link -> close();
	
	if($result->num_rows > 0)
	{
		$row = $result -> fetch_assoc();
		$result -> close(); 
		return $row['user_id'];
	}
	else
		return 0;
}

function get_user_data($user_id,$get_all)
{
	if(!$get_all)
	{
		$link = get_mysql_con();
		$result = $link ->query("SELECT * FROM `".USER_DATA."` WHERE `user_id` = '".$user_id."'");
		$row = $result -> fetch_assoc();
		$result ->close();
		$link->close();
	
		return new user($row['user_id'],$row['user_name'],$row['user_pass'],$row['first_name'],$row['last_name'],$row['user_level']);
	}
	else
	{
		$link = get_mysql_con();
		$result = $link ->query("SELECT * FROM `".USER_DATA."`");
		
		$users = Array();
		while($row = $result->fetch_assoc())
		{
			$user = new user($row['user_id'],$row['user_name'],$row['user_pass'],$row['first_name'],$row['last_name'],$row['user_level']);
			array_push($users,$user);
		}
		
		$result ->close();
		$link->close();
	
		return $users;
	}
}

function edit_user($user_id,$user_name,$user_pass,$first_name,$last_name,$user_level)
{
	$link = get_mysql_con();
	$result = $link->query("UPDATE `".USER_DATA."` SET `user_name`='".$user_name."', `user_pass`='".$user_pass."',`first_name` = '".$first_name."',`last_name` = '".$last_name."',`user_level` = '".$user_level."' WHERE `user_id` = '".$user_id."'");
	$link->close();

	return $result;
}

function delete_user($user_id)
{
	$link = get_mysql_con();
	$result = $link ->query("DELETE FROM `".USER_DATA."` WHERE `user_id`='".$user_id."'");
	$link->close();
	
	return $result;
}
//=== Universiteto duomenu valdymas ===

function add_university($uni_name)
{
	$link = get_mysql_con();
	$result = $link -> query("INSERT INTO `".UNI_DATA."`(`uni_name`) VALUES('".$uni_name."')");
	$link->close();

	return $result;
}

function get_uni_data($uni_id,$get_all)
{
	if(!$get_all)
	{
		$link = get_mysql_con();
		$result = $link ->query("SELECT * FROM `".UNI_DATA."` WHERE `uni_id` = '".$uni_id."'");
		$row = $result -> fetch_assoc();
		$result ->close();
		$link->close();
		
		return new university($row['uni_id'],$row['uni_name']);
	}
	else
	{
		$link = get_mysql_con();
		$result = $link ->query("SELECT * FROM `".UNI_DATA."`");
		
		$universitys = Array();
		while($row = $result -> fetch_assoc())
		{
			$university = new university($row['uni_id'],$row['uni_name']);
			array_push($universitys,$university);
		}
		
		$result-> close();
		$link-> close();
		
		return $universitys;
	}			
}

function change_uni_name($uni_id,$uni_name)
{
	$link = get_mysql_con();
	$result =$link-> query("UPDATE `".UNI_DATA."` SET `uni_name` = '".$uni_name."' WHERE `uni_id` = '".$uni_id."'");
	$link->close();
	
	return $result;
}

function remove_uni($uni_id)
{
	//pries viska pasalina visas programas ir visus stojimus ir programas susijusius su situo uni.
	$link = get_mysql_con();
	$result = $link -> query("DELETE FROM `".UNI_DATA."` WHERE `uni_id` = '".$uni_id."'");
	$link->close();

	return $result;
}

//=== Universitreru programu valdymas ===

function add_program($program_name, $uni_id,$program_desc)
{
	$link = get_mysql_con();
	$result = $link -> query("INSERT INTO `".PROGRAM_DATA."`(`uni_id`,`program_name`,`program_desc`) VALUES('".$uni_id."','".$program_name."','".$program_desc."')");
	$link->close();

	return $result;
	
}

function get_uni_program($program_id,$get_all)
{
	$link = get_mysql_con();
	
	if($get_all)
	{
		$programs = Array();
		$result = $link ->query("SELECT * FROM `".PROGRAM_DATA."`");
		
		while($row = $result -> fetch_assoc())
		{
			$program = new program($row['program_id'],$row['uni_id'],$row['program_name'],$row['program_desc']);
			array_push($programs,$program);
		}
		
		$link->close();
		return $programs;
		
	}
	else
	{
		$result = $link ->query("SELECT * FROM `".PROGRAM_DATA."` WHERE `program_id` = '".$program_id."'");
		$row = $result -> fetch_assoc();
		$result ->close();
		$link->close();
		
		return new program($row['program_id'],$row['uni_id'],$row['program_name'],$row['program_desc']);
	}
}

function edit_program($program_id,$uni_id,$new_name,$program_desc)
{
	$link = get_mysql_con();
	$result =$link-> query("UPDATE `".PROGRAM_DATA."` SET `program_name` = '".$new_name."' , `uni_id` = '".$uni_id."' ,`program_desc` = '".$program_desc."' WHERE `program_id` = '".$program_id."'");
	$link->close();
	
	return $result;
}

function remove_program($program_id)
{
	$link = get_mysql_con();
	$result =$link-> query("DELETE FROM `".PROGRAM_DATA."` WHERE `program_id` = '".$program_id."'");
	$link->close();
	
	return $result;
}

function add_program_criteria($program_id,$criteria_name,$procentile_part)
{
	$link = get_mysql_con();
	$result = $link -> query("INSERT INTO `".CRITERIA_DATA."`(`program_id`,`criteria_name`,`criteria_part`) VALUES('".$program_id."','".$criteria_name."','".$procentile_part."')");
	$link->close();

	return $result;
}

function edit_criteria($criteria_id,$criteria_name,$procentile_part)
{
	$link = get_mysql_con();
	$result = $link->query("UPDATE `".CRITERIA_DATA."` SET `criteria_name` = '".$criteria_name."' ,`criteria_part` = '".$procentile_part."' WHERE `criteria_id` = '".$criteria_id."'");
	$link->close();
	return $result;
}

function get_criteria($criteria_id)
{
	$link = get_mysql_con();
	$result = $link ->query("SELECT * FROM `".CRITERIA_DATA."` WHERE `criteria_id` = '".$criteria_id."'");
	$row = $result -> fetch_assoc();
	$result ->close();
	$link->close();
		
	return new criteria($row['criteria_id'],$row['program_id'],$row['criteria_name'],$row['criteria_part']);
}

function remove_criteria($criteria_id)
{
	$link = get_mysql_con();
	$result =$link-> query("DELETE FROM `".CRITERIA_DATA."` WHERE `criteria_id` = '".$criteria_id."'");
	$link->close();
	
	return $result;
}
?>