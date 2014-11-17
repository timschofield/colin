<?php
error_reporting(E_ALL && ~E_WARNING);
include('../includes/config.php');

$db = mysqli_connect($Host, $DBUser, $DBPassword, 'colin', $DBPort);

if (!isset($_POST['SelectedDate'])) {
	$_POST['SelectedDate'] = date('Y-m-d');
}

$SQL = "SELECT runtime,
				tests.testnumber,
				description,
				status,
				message,
				testoutput
			FROM tests
			INNER JOIN outputs
				ON tests.testnumber=outputs.testnumber
			WHERE DATE(runtime)='" . $_POST['SelectedDate'] . "'
			ORDER BY runtime, testnumber";
$Result = mysqli_query($db, $SQL);

echo '<!DOCTYPE html>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<title>Colin\'s Results Page</title>
				<link href="colin.css" rel="stylesheet" type="text/css" />
			</head>';

echo '<body>
		<img src="Colin.png" />
		<span id="title">Colin\'s Results Page</span>';

$DatesSQL = "SELECT DISTINCT DATE(runtime) FROM outputs";
$DatesResult = mysqli_query($db, $DatesSQL);
echo '<form id="DateForm" method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" >';
echo 'Select Date of Tests:   ';
echo '<select name="SelectedDate" onchange="document.forms[\'DateForm\'].submit();">';
while ($DatesRow = mysqli_fetch_row($DatesResult)) {
	if ($DatesRow[0] == $_POST['SelectedDate']) {
		echo '<option selected="selected" value="' . $DatesRow[0] . '">' . $DatesRow[0] . '</option>';
	} else {
		echo '<option value="' . $DatesRow[0] . '">' . $DatesRow[0] . '</option>';
	}
}
echo '</select>
		</form>';
echo '<table>
		<tr>
			<th>Message Time</th>
			<th>Test Number</th>
			<th>Description</th>
			<th>Status</th>
			<th>Message</th>
		</tr>';

while ($Row = mysqli_fetch_row($Result)) {
	switch($Row[3]) {
		case 2:
			$class='Failure';
			break;
		case 1:
			$class='Warning';
			break;
		case 0:
			$class='Success';
			break;
	}
	echo '<tr class="' . $class . '">
			<td>' . $Row[0] . '</td>
			<td>' . $Row[1] . '</td>
			<td>' . $Row[2] . '</td>
			<td>' . $class . '</td>
			<td>' . $Row[4] . '</td>';
	if ($Row[5] == '') {
		echo '<td>Details</td>';
	} else {
		echo '<td><a href="ShowDetails.php?runtime=' . urlencode($Row[0]) . '&test=' . urlencode($Row[1]) . '" target="_blank">Details</a></td>';
	}
	echo '</tr>';
}
echo'</table>';

?>