#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);
include('includes/config.php');
RegisterTest(basename(__FILE__, '.php'), 'Supplier types general data test');
$CookieFile = sha1(uniqid(mt_rand(), true));

$IndexPage=KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password, basename(__FILE__, '.php'));

$SetupPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

$SuppliersTypePage=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Supplier Types', basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($SuppliersTypePage[2]);

$SuppliersTypeInsertPage = new URLDetails($CookieFile, $ServerPath.$SuppliersTypePage[2]['Action'], $PostData);
$Page=$SuppliersTypeInsertPage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

$Fields = array('typeid'=>FetchIndex($Page), 'typename'=>$PostData['TypeName']);

if (!assertDB('suppliertype', $Fields, $PostData, 'inserted')) exit(1);

$EditPage = GetEditPage($Page, $Fields['typeid'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($EditPage[2]);
$SuppliersTypeUpdatePage = new URLDetails($CookieFile, $ServerPath.$EditPage[2]['Action'], $PostData);
$Page=$SuppliersTypeUpdatePage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

$Fields = array('typeid'=>$PostData['SelectedType'], 'typename'=>$PostData['TypeName']);
if (!assertDB('suppliertype', $Fields, $PostData, 'updated')) exit(1);

$DeletePage = GetDeletePage($Page, $PostData['SelectedType'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

if (!assertNotDB('suppliertype', $Fields, $PostData, 'deleted')) exit(1);

KwaMojaLogout($RootPath, $ServerPath, $CookieFile);
unlink($CookieFile);
LogMessage(basename(__FILE__, '.php'), 0, 'Test completed successfuly', '');
exit(0);
?>