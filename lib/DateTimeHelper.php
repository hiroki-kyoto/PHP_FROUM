<?php

class DateTimeHelper{
	public static function getNowTime(){
		$now = new DateTime('now');
		return $now->format('Y-m-d H:i:s');
	}
	public static function getAgeByBirthday($birthday){
		$now = new DateTime('now');
		$interval = $birthday->diff($now);
		return $interval->y;
	}
	
	public static function addDay($dt, $days){
		$dt->add(new DateInterval("P".$days."D"));
		return $dt;
	}
	public static function addYear($dt, $years){
		$dt->add(new DateInterval("P".$years."Y"));
		return $dt;
	}
	public static function subDay($dt, $days){
		$dt->sub(new DateInterval("P".$days."D"));
		return $dt;
	}
	public static function subYear($dt, $years){
		$dt->sub(new DateInterval("P".$years."Y"));
		return $dt;
	}
}

?>
