#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);
include('includes/config.php');
RegisterTest(basename(__FILE__, '.php'), 'Work Centre Input Test');
$CookieFile = sha1(uniqid(mt_rand(), true));

$IndexPage=KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password, basename(__FILE__, '.php'));

$SetupPage=FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Manufacturing');

$WorkCentrePage=ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Work Centre', basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($WorkCentrePage[2]);

$WorkCentreInsertPage = new URLDetails($CookieFile, $ServerPath.$WorkCentrePage[2]['Action'], $PostData);
$Page=$WorkCentreInsertPage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

$Fields = array('code'=>$PostData['Code'], 'location'=>$PostData['Location'], 'description'=>$PostData['Description'], 'overheadrecoveryact'=>$PostData['OverheadRecoveryAct'], 'overheadperhour'=>$PostData['OverheadPerHour']);

if (!assertDB('workcentres', $Fields, $PostData, 'inserted', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);

$EditPage = GetEditPage($Page, $Fields['code'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

$PostData=FillFormWithRandomData($EditPage[2]);

$WorkCentreUpdatePage = new URLDetails($CookieFile, $ServerPath.$EditPage[2]['Action'], $PostData);
$Page=$WorkCentreUpdatePage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

$Fields = array('code'=>$PostData['Code'], 'location'=>$PostData['Location'], 'description'=>$PostData['Description'], 'overheadrecoveryact'=>$PostData['OverheadRecoveryAct'], 'overheadperhour'=>$PostData['OverheadPerHour']);
if (!assertDB('workcentres', $Fields, $PostData, 'updated', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);

$DeletePage = GetDeletePage($Page, $PostData['Code'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

if (!assertNotDB('workcentres', $Fields, $PostData, 'deleted', basename(__FILE__, '.php'))) AbortTest($CookieFile, 1);

KwaMojaLogout($RootPath, $ServerPath, $CookieFile);
unlink($CookieFile);
LogMessage(basename(__FILE__, '.php'), 0, 'Test completed successfuly', '');
exit(0);
?>