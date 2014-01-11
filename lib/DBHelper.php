<?php

class DBHelper
{
	static public function connect()
	{
		$host="127.0.0.1";
		$user="root";
		$password="root";
		$dbname = "christianland";
	
		$conn = mysqli_connect($host,$user,$password,$dbname)
		or die("There's an error when attempting to access mysql database!");
		return $conn;
	}
	static public function test($table)
	{
		$conn = self::connect();
		$query = "SELECT * FROM ".$table;
		$res = mysqli_query($conn,$query)
		or die("There's an error when trying to query the table".$table);
		return $res;
	}
	static public function execsql($sql)
	{
		$conn = self::connect();
		$res = mysqli_query($conn,$sql)
		or die("There's an error when trying to execute this sql sentence: ".$sql);
		return $res;
		mysqli_close($conn);
	}
}

?>
