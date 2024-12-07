#!/usr/bin/php8.3
<?php
error_reporting(E_ALL && ~E_WARNING);

/* Include the necessary passwords and include files
 */
include('includes/config.php');

RegisterTest(basename(__FILE__, '.phpt'), 'Sales types general data test');

/* Create a random number identifier to hold the
 * client side cookie file for this session.
 */
$CookieFile = sha1(uniqid(mt_rand(), true));

/* Log into webERP and retrieve  the first index page
 */
$IndexPage = webERPLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password, basename(__FILE__, 'phpt'));

/* Move to the menu page we are looking for, in this case 'Setup'
 * Note this test assumes that the user whose details are in
 * includes/config.php has the language setup to English/British
 */
$SetupPage = FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

/* Select the option for the menu item we wish to test and download
 * the page.
 */
$SalesTypePage = ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Sales Types', basename(__FILE__, '.phpt'));

/* As this is a generalised test rather than a specific
 * edge casewe want to use randomised data so we fill
 * the form with the random data
 */
$PostData = FillFormWithRandomData($SalesTypePage[2]);

/* Submit the form with the data and retrieve a page
 * containing the resulting html page details
 */
$SalesTypeInsertPage = new URLDetails($CookieFile, $ServerPath . $SalesTypePage[2]['Action'], $PostData);
$Page = $SalesTypeInsertPage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

/* Construct an array of the following format:
 * 		$Fields[database field name] = Expected value of this field
 * for all fields being updated by this script
 */
$Fields = array('typeabbrev'=>$PostData['TypeAbbrev'], 'sales_type'=>$PostData['Sales_Type']);

/* Test that the database contains the correct record and if it fails
 * then abort the test with and exit code of 1
 */
if (!assertDB('salestypes', $Fields, $PostData, 'inserted', 0)) exit(1);

/* Find the link to edit the sales type just entered and fetch
 * that page
 */
$EditPage = GetEditPage($Page, $PostData['TypeAbbrev'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

/* As this is a generalised test rather than a specific
 * edge casewe want to use randomised data so we fill
 * the form with the random data
 */
$PostData = FillFormWithRandomData($EditPage[2]);

/* Submit the form with the data and retrieve a page
 * containing the resulting html page details
 */
$SalesTypeUpdatePage = new URLDetails($CookieFile, $ServerPath . $EditPage[2]['Action'], $PostData);
$Page = $SalesTypeUpdatePage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

/* Construct an array of the following format:
 * 		$Fields[database field name] = Expected value of this field
 * for all fields being updated by this script
 */
$Fields = array('typeabbrev'=>$PostData['TypeAbbrev'], 'sales_type'=>$PostData['Sales_Type']);

/* Test that the database contains the correct record and if it fails
 * then abort the test with and exit code of 1
 */
if (!assertDB('salestypes', $Fields, $PostData, 'updated', 'SalesTypes')) exit(1);

/* Find the link to delete the sales type just entered and fetch
 * that page
 */
$DeletePage = GetDeletePage($Page, $PostData['TypeAbbrev'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

/* Test that the database does not contain the record and if it fails
 * then abort the test with and exit code of 1
 */
if (!assertNotDB('salestypes', $Fields, $PostData, 'deleted','SalesTypes')) exit(1);

/* Logout of the webERP instance, delete the cookie file
 * and exit with a 0 response code
 */
webERPLogout($RootPath, $ServerPath, $CookieFile);
UpdateTest(0);
unlink($CookieFile);
LogMessage(basename(__FILE__, '.php'), 0, 'Test completed successfuly', '');
exit(0);
?>