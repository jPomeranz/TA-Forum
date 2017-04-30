<?php
class DbUtil{
	public static $loginUser = "xxx";
	public static $loginPass = "xxx";
	public static $loginUser_insecure = "xxx";
	public static $loginPass_insecure = "xxx";
	public static $host = "xxx"; // DB Host
	public static $schema = "xxx"; // DB Schema

	public static function loginConnection($secure = true){
		$db = null;
		if($secure == true)
			$db = new mysqli(DbUtil::$host, DbUtil::$loginUser, DbUtil::$loginPass, DbUtil::$schema);
		else
			$db = new mysqli(DbUtil::$host, DbUtil::$loginUser_insecure, DbUtil::$loginPass_insecure, DbUtil::$schema);
		if($db->connect_errno){
			echo("Could not connect to db");
			$db->close();
			exit();
		}

		return $db;
	}
}
?>
