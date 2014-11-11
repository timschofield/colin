<?php

global $LogDB; // Make sure it IS global, regardless of our context

$LogDB = mysqli_connect('p:'.$Host, $DBUser, $DBPassword, 'colin', $DBPort);

function RegisterTest($TestID, $TestDescription) {
	global $LogDB;

	$SQL = "SELECT COUNT(*)
				FROM tests
				WHERE testnumber='" . $TestID . "'";
	$Result = mysqli_query($LogDB, $SQL);
	$Row = mysqli_fetch_row($Result);
	if ($Row[0] == 0) {
		$SQL = "INSERT INTO `tests` (testnumber,
									description
								) VALUES (
									'" . $TestID . "',
									'" . $TestDescription . "'
								)";
		$Result = mysqli_query($LogDB, $SQL);
	} else {
		$SQL = "UPDATE `tests` SET description='" . $TestDescription . "'
					WHERE  testnumber='" . $TestID . "'";
		$Result = mysqli_query($LogDB, $SQL);
	}
}

function LogMessage($TestID, $Status, $Message, $Output) {
	global $LogDB;
	$Output=str_replace("\t", '', $Output);
	$SQL = "INSERT INTO outputs (runtime,
								testnumber,
								status,
								message,
								testoutput
							) VALUES (
								CURRENT_TIMESTAMP,
								'" . $TestID . "',
								'" . $Status . "',
								'" . $Message . "',
								'" . mysqli_real_escape_string($LogDB, htmlentities($Output)) . "'
							)";
	$Result = mysqli_query($LogDB, $SQL);
}


?>