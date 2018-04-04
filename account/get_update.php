<?php
ini_set('max_execution_time',60);


if(!isset($_GET['c']))
	die("No any latest version found");

$c = $_GET['c'];
$getVersions = file_get_contents('versions') or die('');

$vv = explode("\n",$getVersions);

$newc = "";
$file = "";
$skipped = "";
foreach($vv as $x){
	$a = explode("=",$x);
	if($c == $a[0]){
		$newc = $a[1];
		$file = $a[2];
		$skipped = $a[3];
	}
}

if($newc == "")
	die("No New Update Available");

if(isset($_GET['nodownload']))
	die($newc);

if(isset($_GET['skipped']))
	die($skipped);

if(!isset($_GET['download']))
	die("Invalid Command");

$f = file_get_contents('file_version/'.$file) or die('Error download file');
header('Content-Type: application/zip');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="upgrade-'.$newc.'.zip"');
header('Content-Transfer-Encoding: binary');

print $f;