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
	//Defino las variables
	$SSLv2=0;$SSLv3=0;$TLSv10=0;$TLSv11=0;$TLSv12=0;$TLSv13=0;
	//$ip: variable donde almaceno la ip que obtengo del archivo
	$ip=fgets($fp);
	if (strlen($ip)==0)
		exit;
	echo "\nLa ip a escanear es: ".$ip;
	//Ejecuto el archivo script.sh, pasandole como parametro la ip que saco del fichero.txt
	$comando="./script.sh ". $ip;
	echo "\nEl comando a ejecutar es: ".$comando;
	$retval=shell_exec($comando);
	//Separo los protocolos devueltos
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
	echo "Insertando datos en la DB...\n";
	$ip=trim($ip);
	$sql="Insert into ips values(null,'$ip',443,$SSLv2,$SSLv3,$TLSv10,$TLSv11,$TLSv12,$TLSv13,unix_timestamp())";
	echo "Comando ejecutado:\n "."\t".$sql."\n";
	$resultado=mysqli_query($link,$sql);
	echo "resultado del query: ".$resultado."\n";
}
fclose($fp);

?>
