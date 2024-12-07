#!/usr/bin/php8.3
<?php

$ModuleName = 'Setup';
$MenuOption = 'Credit Status';
$TableName = 'holdreasons';
$TestName = 'Credit Status Input Test';
$FieldNames = array('reasoncode'=>'ReasonCode',
					'reasondescription'=>'ReasonDescription',
					'dissallowinvoices'=>'DisallowInvoices'
					);
$IndexField = 'reasoncode';
$IndexFormField = $FieldNames[$IndexField];

error_reporting(E_ALL && ~E_WARNING);
include('includes/config.php');
RegisterTest(basename(__FILE__, '.php'), $TestName);
$CookieFile = sha1(uniqid(mt_rand(), true));

$IndexPage=webERPLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password, basename(__FILE__, '.php'));

$MenuPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, $ModuleName);

$FirstScreen=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $MenuPage, $MenuOption, basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($FirstScreen[2]);

$InsertPage = new URLDetails($CookieFile, $ServerPath.$FirstScreen[2]['Action'], $PostData);
$Page=$InsertPage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

foreach ($FieldNames as $DBField=>$FormField) {
	$Fields[$DBField] = $PostData[$FormField];
}

if (!assertDB($TableName, $Fields, $PostData, 'inserted', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);

$EditPage = GetEditPage($Page, $Fields[$IndexField], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($EditPage[2]);

$UpdatePage = new URLDetails($CookieFile, $ServerPath.$EditPage[2]['Action'], $PostData);
$Page=$UpdatePage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

foreach ($FieldNames as $DBField=>$FormField) {
	$Fields[$DBField] = $PostData[$FormField];
}

if (!assertDB($TableName, $Fields, $PostData, 'updated', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);
echo 'x'.$PostData[$IndexFormField].'x';
$DeletePage = GetDeletePage($Page, $PostData[$IndexFormField], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

if (!assertNotDB($TableName, $Fields, $PostData, 'deleted', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);

webERPLogout($RootPath, $ServerPath, $CookieFile);
UpdateTest(4);
unlink($CookieFile);
LogMessage(basename(__FILE__, '.php'), 0, 'Test completed successfuly', '');
exit(0);
?>