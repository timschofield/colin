<?php
error_reporting(E_ALL && ~E_WARNING);
include('../includes/config.php');

$db = mysqli_connect($Host, $DBUser, $DBPassword, 'colin', $DBPort);

$SQL = "SELECT testoutput
			FROM outputs
			WHERE runtime='" . $_GET['runtime'] . "'
				AND testnumber='" . $_GET['test'] . "'";
$Result = mysqli_query($db, $SQL);
$Row = mysqli_fetch_row($Result);

echo html_entity_decode($Row[0]);

?>