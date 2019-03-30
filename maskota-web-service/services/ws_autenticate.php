<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/ws_gr1f0sp3ruanos/system/ext/utilitarios.php");
$_validate	= false;
$dominio 	= desencriptar('Ck7JNfpublQF6G14w3F356/7KEGHFjXcCTrQIzrUfu0=');
$user 		= 'd389b2fbefe25555a4124ec7be77b6c3e81328ca';
$password 	= '691b55f01aa2c390018b651001e579acacd3f67a';
$usuarios = array($user => $password);

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');	
    header('WWW-Authenticate: Digest realm="'.$dominio.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($dominio).'"');    
	echo json_encode(array('STATUS'=>array(-1,"Se requiere autenticacion para continuar"))); 
	die('');
}

// Analizar la variable PHP_AUTH_DIGEST
if (!($datos = analizar_http_digest($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($usuarios[$datos['username']]))
{	
	echo json_encode(array('STATUS'=>array(0,"Credenciales incorrectas"))); 
    die('');
}	

// Generar una respuesta válida
$A1 = md5($datos['username']. ':' . $dominio . ':' . $usuarios[$datos['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$datos['uri']);
$respuesta_válida = md5($A1.':'.$datos['nonce'].':'.$datos['nc'].':'.$datos['cnonce'].':'.$datos['qop'].':'.$A2);
if ($datos['response'] != $respuesta_válida)
{
	echo json_encode(array('STATUS'=>array(0,"No se ha autenticado correctamente"))); 
	die('');
}
else{ 
	//echo 'Se ha identificado como: ' . $datos['username']; 
	$_validate = true;
}


// Función para analizar la cabecera de autenticación HTTP
function analizar_http_digest($txt)
{
	// Protección contra datos ausentes
    $partes_necesarias = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $datos = array();
    $claves = implode('|', array_keys($partes_necesarias));
    
	preg_match_all('@(' . $claves . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $coincidencias, PREG_SET_ORDER);
    
	foreach ($coincidencias as $c) {
        $datos[$c[1]] = $c[3] ? $c[3] : $c[4];
		unset($partes_necesarias[$c[1]]);
    }
    return $partes_necesarias ? false : $datos;
}

?>
















