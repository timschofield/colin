#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);
include('includes/config.php');

$CookieFile = sha1(uniqid(mt_rand(), true));

$IndexPage=KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password);

$SetupPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

$CustomersTypePage=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Customer Types');

$PostData=FillFormWithRandomData($CustomersTypePage[2]);

$CustomersTypeInsertPage = new URLDetails($CookieFile);
$CustomersTypeInsertPage->SetURL($ServerPath.$CustomersTypePage[2]['Action']);
$CustomersTypeInsertPage->SetPostArray($PostData);

$Page=$CustomersTypeInsertPage->FetchPage($RootPath, $ServerPath);

$Fields['typename'] = $PostData['TypeName'];

$InputDump = print_r($PostData, true);
if (!assertDB('debtortype', $Fields)) {
	error_log('**Error**'.' The customer type does not seem to have been inserted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
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

$CustomersTypeUpdatePage = new URLDetails($CookieFile);
$CustomersTypeUpdatePage->SetURL($ServerPath.$EditPage[2]['Action']);
$CustomersTypeUpdatePage->SetPostArray($PostData);

$Page=$CustomersTypeUpdatePage->FetchPage($RootPath, $ServerPath);
$Fields['typeid'] = $PostData['SelectedType'];
$Fields['typename'] = $PostData['TypeName'];

$InputDump = print_r($PostData, true);
if (!assertDB('debtortype', $Fields)) {
	error_log('**Error**'.' The customer type does not seem to have been updated correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
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

if (assertDB('debtortype', $Fields)) {
	error_log('**Error**'.' The customer type does not seem to have been deleted correctly using the following data:'."\n", 3, '/home/tim/kwamoja'.date('Ymd').'.log');
	unlink($CookieFile);
	exit(1);
}

unlink($CookieFile);
exit(0);
?>