<?php
//encode.php
//two method to encode the string 
//first, md5 encode, Irreversible method
//second, base64_encode, reversible method

class Encoder
{
	static public function IrrevEncode($key, $value)
	{
		$kcode = md5($key);
		$kcode = substr($kcode,0,strlen($kcode)-5);
		$vcode = md5($value);
		$vcode = substr($vcode,3,strlen($vcode)-6);
		return ($kcode."".$vcode);
	}
	
	static public function RevEncode($key, $value)
	{
		return base64_encode(Des::encrypt($key, $value));
	}
}

?>
