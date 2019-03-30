<?php
$valid_passwords = array ("DB440D77B3DEDE7E97BAB38C690CCD7E791D15DA" => "891DFBE7C25B0B8212494AEF85A6DA0A9886C132");
$valid_users = array_keys($valid_passwords);
$user = ""; $pass = "";
if(isset($_SERVER['PHP_AUTH_USER'])){$user = $_SERVER['PHP_AUTH_USER'];}
if(isset($_SERVER['PHP_AUTH_PW'])){$pass = $_SERVER['PHP_AUTH_PW'];}
$_validate = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$_validate) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  echo json_encode(array('STATUS'=>array(0,"Credenciales incorrectas"))); 
  die ("");
}
else{
	return $_validate; 
}	
//http://localhost:8083/ws_gr1f0sp3ruanos/system/ws/ws_reporte_cli.php?rpt=1&p1=01/01/2011&p2=01/08/2013&p3=&p4=&p5=


/*
$_validate = array();
if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

	$valid_passwords = array ("DB440D77B3DEDE7E97BAB38C690CCD7E791D15DA" => "891DFBE7C25B0B8212494AEF85A6DA0A9886C132");
	$valid_users = array_keys($valid_passwords);
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$_validate = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

	if (!$_validate) {
	  header('WWW-Authenticate: Basic realm="My Realm"');
	  header('HTTP/1.0 401 Unauthorized');
	  echo json_encode(array('STATUS'=>array(0,"Credenciales incorrectas"))); 
	  die ("");
	}
	else{
		return $_validate; 
	}
}
else{
	echo json_encode(array('STATUS'=>array(0,"No tiene permisos para consultar esta Url"))); 
	return $_validate;
}
*/

?>