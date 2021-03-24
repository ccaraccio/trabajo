<?php
//Conexion a la base de datos
$usuario='fpuser';
$clave='xxxxx';
$db='ssldb';
$host='localhost';
$link=mysqli_connect($host,$usuario,$clave,$db);
if (!$link)
{
	echo "Error, no se pudo conectar a la Base de Datos";
	exit;
}
// Abrir archivo para lectura
$fp=fopen("./fichero.txt","r");
//Leer archivo hasta el final
while(!feof($fp)){
	$SSLv2=0;$SSLv3=0;$TLSv10=0;$TLSv11=0;$TLSv12=0;$TLSv13=0;
	$ip=fgets($fp);
	if (strlen($ip)==0)
		exit;
	echo "\nLa ip a escanear es: ".$ip." y mide: ".strlen($ip) ;
	// Este comando se encuentra dentro del archivo script.sh --> $comando="/usr/bin/nmap --script ssl-enum-ciphers -p 443 ".$ip." | "." grep -E 'TLSv|SSLv' ";
	$comando="./script.sh ". $ip;
	echo "\nEl comando a ejecutar es: ".$comando;
	$retval=shell_exec($comando);
	$protocolos=explode("|",$retval);
	foreach ($protocolos as $key=>$value){
		//El primer pipe no contiene valor
		if ($key>0){
			echo "[".$key."]"." valor: ".trim($value);
			$valor=trim($value);
			echo "Valor vale: ".$valor."\n";
			switch($valor)
			{
				case 'SSLv2:':
					$SSLv2=1;
					break;
				case  'SSLv3:':
					$SSLv3=1;
					break;
				case 'TLSv1.0:':
					$TLSv10=1;
					break;
				case 'TLSv1.1:':
					$TLSv11=1;
					break;
				case 'TLSv1.2:':
					$TLSv12=1;
					break;
				case 'TLSv1.3:':
					$TLSv13=1;
					break;
			}
		}
	}
	echo "Insertando datos en la DB...";
	$sql="Insert into ips values(null,'".$ip."',443,$SSLv2,$SSLv3,$TLSv10,$TLSv11,$TLSv12,$TLSv13,unix_timestamp())";
	echo $sql;
	$resultado=mysqli_query($link,$sql);
	echo "resultado del query: ".$resultado;
	//$ultima_linea=system($comando,$retval);
	//echo $ip;
	//echo "\nultima linea dice: ".$ultima_linea;
	//$comando2="./script2.sh ".$ip;
	//echo "\nEl segundo comando a ejecutar es: ".$comando2;
	//$ultima_linea2=system($comando2,$retval2);
}
fclose($fp);
//nmap --script ssl-enum-ciphers -p 443 $1 | grep -E "TLSv|SSLv"

?>
