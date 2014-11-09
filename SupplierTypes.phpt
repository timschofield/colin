#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);
include('includes/config.php');

$CookieFile = sha1(uniqid(mt_rand(), true));

$IndexPage=KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password);

$SetupPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

$SuppliersTypePage=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Supplier Types');

$PostData=FillFormWithRandomData($SuppliersTypePage[2]);

$SuppliersTypeInsertPage = new URLDetails($CookieFile);
$SuppliersTypeInsertPage->SetURL($ServerPath.$SuppliersTypePage[2]['Action']);
$SuppliersTypeInsertPage->SetPostArray($PostData);

$Page=$SuppliersTypeInsertPage->FetchPage($RootPath, $ServerPath);

$Fields['typename'] = $PostData['TypeName'];

$InputDump = print_r($PostData, true);
if (!assertDB('suppliertype', $Fields)) {
	error_log('**Error**'.' The supplier type does not seem to have been inserted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	error_log($InputDump."\n\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

foreach($Page[3] as $Link) {
	if ($Link['value'] == 'Edit') {
		$TypeCode = substr($Link['href'], strpos($Link['href'], "=") + 1);
		$HRef = $Link['href'];
	}
}
$EditPage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $HRef);
$PostData=FillFormWithRandomData($EditPage[2]);

$SuppliersTypeUpdatePage = new URLDetails($CookieFile);
$SuppliersTypeUpdatePage->SetURL($ServerPath.$EditPage[2]['Action']);
$SuppliersTypeUpdatePage->SetPostArray($PostData);

$Page=$SuppliersTypeUpdatePage->FetchPage($RootPath, $ServerPath);
$Fields['typeid'] = $PostData['SelectedType'];
$Fields['typename'] = $PostData['TypeName'];

$InputDump = print_r($PostData, true);
if (!assertDB('suppliertype', $Fields)) {
	error_log('**Error**'.' The supplier type does not seem to have been updated correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	error_log($InputDump."\n\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

foreach($Page[3] as $Link) {
	if ($Link['value'] == 'Delete') {
		$URLString = substr($Link['href'], strpos($Link['href'], "=") + 1);
		$TypeCode = substr($URLString, 0, strlen($URLString)-11);
		if ($TypeCode == $PostData['SelectedType']) {
			$DeletePage = ChooseURLOption($RootPath, $ServerPath, $CookieFile, $Link['href']);
		}
	}
}

if (assertDB('suppliertype', $Fields)) {
	error_log('**Error**'.' The supplier type does not seem to have been deleted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

unlink($CookieFile);
exit(0);
?>