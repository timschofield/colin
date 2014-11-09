#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);

/* Include the necessary passowrds and include files
 */
include('includes/config.php');

/* Create a random number identifier to hold the
 * client side cookie file for this session.
 */
$CookieFile = sha1(uniqid(mt_rand(), true));

/* Log into KwaMoja and retrieve  the first index page
 */
$IndexPage=KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password);

$SetupPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

$SalesTypePage=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Sales Types');

$PostData=FillFormWithRandomData($SalesTypePage[2]);

$SalesTypeInsertPage = new URLDetails($CookieFile);
$SalesTypeInsertPage->SetURL($ServerPath.$SalesTypePage[2]['Action']);
$SalesTypeInsertPage->SetPostArray($PostData);

$Page=$SalesTypeInsertPage->FetchPage($RootPath, $ServerPath);

$Fields['typeabbrev'] = $PostData['TypeAbbrev'];
$Fields['sales_type'] = $PostData['Sales_Type'];

$InputDump = print_r($PostData, true);
if (!assertDB('salestypes', $Fields)) {
	error_log('**Error**'.' The sales type does not seem to have been inserted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	error_log($InputDump."\n\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

foreach($Page[3] as $Link) {
	if ($Link['value'] == 'Edit') {
		$TypeCode = substr($Link['href'], strpos($Link['href'], "=") + 1);
		if ($TypeCode == $PostData['TypeAbbrev']) {
			$EditPage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $Link['href']);
		}
	}
}
$PostData=FillFormWithRandomData($EditPage[2]);

$SalesTypeUpdatePage = new URLDetails($CookieFile);
$SalesTypeUpdatePage->SetURL($ServerPath.$EditPage[2]['Action']);
$SalesTypeUpdatePage->SetPostArray($PostData);

$Page=$SalesTypeUpdatePage->FetchPage($RootPath, $ServerPath);
$Fields['typeabbrev'] = $PostData['TypeAbbrev'];
$Fields['sales_type'] = $PostData['Sales_Type'];

$InputDump = print_r($PostData, true);
if (!assertDB('salestypes', $Fields)) {
	error_log('**Error**'.' The sales type does not seem to have been updated correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	error_log($InputDump."\n\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

foreach($Page[3] as $Link) {
	if ($Link['value'] == 'Delete') {
		$URLString = substr($Link['href'], strpos($Link['href'], "=") + 1);
		$TypeCode = substr($URLString, 0, strlen($URLString)-11);
		if ($TypeCode == $PostData['TypeAbbrev']) {
			$DeletePage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $Link['href']);
		}
	}
}

if (assertDB('salestypes', $Fields)) {
	error_log('**Error**'.' The sales type does not seem to have been deleted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

unlink($CookieFile);
exit(0);
?>