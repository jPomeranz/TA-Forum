<?php
class DbUtil{
    public static $loginUser = "***REMOVED***"; 
    public static $loginPass = "***REMOVED***$";
    public static $loginUser_insecure = "***REMOVED***"; 
    public static $loginPass_insecure = "***REMOVED***";
    public static $host = "***REMOVED***"; // DB Host
    public static $schema = "***REMOVED***"; // DB Schema
    
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
