<?php

function ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $IndexPage, $MenuOption, $TestNumber) {
	$i=0;
	do {
		$i++;
	} while ($i<sizeOf($IndexPage[1]) and substr($IndexPage[1][$i]['value'],4) != $MenuOption);

	if ($i>=sizeOf($IndexPage[1])) {
		error_log('Error finding option '.$MenuOption.'. Link not found.'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
		return false;
	}

	$SelectedPage = new URLDetails($CookieFile, $ServerPath.$IndexPage[1][$i]['href'], array());
	$Page=$SelectedPage->FetchPage($RootPath, $ServerPath, $TestNumber);

	return $Page;

}

function ChooseURLOption($RootPath, $ServerPath, $CookieFile, $URI, $TestNumber) {

	$SelectedPage = new URLDetails($CookieFile, $ServerPath.$URI, array());
	$Page=$SelectedPage->FetchPage($RootPath, $ServerPath, $TestNumber);

	return $Page;

}

function GetEditPage($Page, $IndexValue, $RootPath, $ServerPath, $CookieFile, $TestNumber) {
	foreach($Page[3] as $Link) {
		if ($Link['value'] == 'Edit') {
			$IndexCode = substr($Link['href'], strpos($Link['href'], "=") + 1);
			if ($IndexCode == $IndexValue) {
				$EditPage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $Link['href'], $TestNumber);
			}
		}
	}
	return $EditPage;
}

function GetDeletePage($Page, $IndexValue, $RootPath, $ServerPath, $CookieFile, $TestNumber) {
	foreach($Page[3] as $Link) {
		if ($Link['value'] == 'Delete') {
			$URLString = substr($Link['href'], strpos($Link['href'], "=") + 1);
			$TypeCode = substr($URLString, 0, strlen($URLString) - 11);
			if ($TypeCode == $IndexValue) {
				$DeletePage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $Link['href'], $TestNumber);
			}
		}
	}
	return $DeletePage;
}

function FetchIndex($Page) {
	foreach($Page[3] as $Link) {
		if ($Link['value'] == 'Edit') {
			$Index = substr($Link['href'], strpos($Link['href'], "=") + 1);
		}
	}
	return $Index;
}

?>