<?php

function FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, $Module) {
	$i=0;
	do {
		$i++;
	} while ($i<sizeOf($IndexPage[1]) and $IndexPage[1][$i]['value'] != $Module);
	if ($i>=sizeOf($IndexPage[1])) {
		error_log('Error finding module '.$Module.'. Link not found.'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
		return false;
	}
	$SelectedModuleScreen = new URLDetails($CookieFile);
	$SelectedModuleScreen->SetURL($ServerPath.$IndexPage[1][$i]['href']);

	$ModulePage=$SelectedModuleScreen->FetchPage($RootPath, $ServerPath);
	return $ModulePage;
}

?>