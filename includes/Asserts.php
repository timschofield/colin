<?php

global $db; // Make sure it IS global, regardless of our context
$db = mysqli_connect('p:'.$Host, $DBUser, $DBPassword, $CompanyName, $DBPort);

global $LogDB; // Make sure it IS global, regardless of our context
$LogDB = mysqli_connect('p:'.$Host, $DBUser, $DBPassword, 'colin', $DBPort);


function assertDB($Table, $Fields, $PostData, $Mode, $Test) {
	global $db;
	$SQL = "SELECT COUNT(*) FROM " . $Table . " WHERE ";
	foreach ($Fields as $name=>$value) {
		$SQL .= $name . "='" . mysqli_real_escape_string($db, $value) . "' AND ";
	}
	$SQL = substr($SQL, 0, strlen($SQL)-5);
	$Result = mysqli_query($db, $SQL);
	$Row = mysqli_fetch_row($Result);
	if ($Row[0] == 0) {
		$InputDump = print_r($PostData, true);
		LogMessage($Test, 2, 'Error in database ' . $Mode, $InputDump);
		unlink($CookieFile);
		return false;
	} else {
		return true;
	}
}

function assertNotDB($Table, $Fields, $PostData, $Mode, $Test) {
	global $db;
	$SQL = "SELECT COUNT(*) FROM " . $Table . " WHERE ";
	foreach ($Fields as $name=>$value) {
		$SQL .= $name . "='" . mysqli_real_escape_string($db, $value) . "' AND ";
	}
	$SQL = substr($SQL, 0, strlen($SQL)-5);
	$Result = mysqli_query($db, $SQL);
	$Row = mysqli_fetch_row($Result);
	if ($Row[0] > 0) {
		$InputDump = print_r($PostData, true);
		LogMessage($Test, 2, 'Error in database ' . $Mode, $InputDump);
		unlink($CookieFile);
		return false;
	} else {
		return true;
	}
}

function AbortTest($CookieFile, $ReturnCode) {
	unlink($CookieFile);
	exit($ReturnCode);
}

function UpdateTest($TestNo) {
	global $LogDB;
	$SQL = "UPDATE tests SET lastrun=NOW() WHERE testnumber=" . $TestNo . "";
	$Result = mysqli_query($LogDB, $SQL);
}

?>