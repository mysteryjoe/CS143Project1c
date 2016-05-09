<?php
ini_set('error_reporting', E_ALL&~E_NOTICE&~E_WARNING);

$request = &$_REQUEST;
$get = &$_REQUEST;
$post = &$_REQUEST;

$config = array(
	'dbhost' => 'localhost',
	'dbname' => 'CS143',
	'dbuser' => 'cs143',
	'dbpass' => '',
	'dbchar' => 'gbk',
);

$mysql_resource = mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']) or exit(mysql_error());
				  mysql_select_db($config['dbname'], $mysql_resource) or exit(mysql_error());
				  mysql_query("set names '{$config['dbchar']}'") or exit(mysql_error());
				  


function errorTips($msg){
	echo "<script>alert('". addslashes($msg) ."');location.href=document.referrer</script>";
	exit();
}