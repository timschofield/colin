<?php

function ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $IndexPage, $MenuOption) {
	$i=0;
	do {
		$i++;
	} while ($i<sizeOf($IndexPage[1]) and substr($IndexPage[1][$i]['value'],4) != $MenuOption);

	if ($i>=sizeOf($IndexPage[1])) {
		error_log('Error finding option '.$MenuOption.'. Link not found.'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
		return false;
	}

	$SelectedPage = new URLDetails($CookieFile);
	$SelectedPage->SetURL($ServerPath.$IndexPage[1][$i]['href']);

	$Page=$SelectedPage->FetchPage($RootPath, $ServerPath);

	return $Page;

}

function ChooseURLOption($RootPath, $ServerPath, $CookieFile, $URI) {

	$SelectedPage = new URLDetails($CookieFile);
	$SelectedPage->SetURL($ServerPath.$URI);

	$Page=$SelectedPage->FetchPage($RootPath, $ServerPath);

	return $Page;

}

?>