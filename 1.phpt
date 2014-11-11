#!/usr/bin/php5
<?php
error_reporting(E_ALL && ~E_WARNING);

/* Include the necessary passwords and include files
 */
include('includes/config.php');
RegisterTest(basename(__FILE__, '.php'), 'Customer types general data test');
/* Create a random number identifier to hold the
 * client side cookie file for this session.
 */
$CookieFile = sha1(uniqid(mt_rand(), true));

/* Log into KwaMoja and retrieve  the first index page
 */
$IndexPage = KwaMojaLogIn($CookieFile, $RootPath, $ServerPath, $CompanyName, $UserName, $Password, basename(__FILE__, '.php'));

/* Move to the menu page we are looking for, in this case 'Setup'
 * Note this test assumes that the user whose details are in
 * includes/config.php has the language setup to English/British
 */
$SetupPage = FindModule($RootPath, $ServerPath, $CookieFile, $IndexPage, 'Setup');

/* Select the option for the menu item we wish to test and download
 * the page.
 */
$CustomersTypePage = ChooseMenuOption($RootPath, $ServerPath, $CookieFile, $SetupPage, 'Customer Types', basename(__FILE__, '.php'));

/* As this is a generalised test rather than a specific
 * edge casewe want to use randomised data so we fill
 * the form with the random data
 */
$PostData = FillFormWithRandomData($CustomersTypePage[2]);

/* Submit the form with the data and retrieve a page
 * containing the resulting html page details
 */
$CustomersTypeInsertPage = new URLDetails($CookieFile, $ServerPath . $CustomersTypePage[2]['Action'], $PostData);
$Page = $CustomersTypeInsertPage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

/* Construct an array of the following format:
 * 		$Fields[database field name] = Expected value of this field
 * for all fields being updated by this script
 */
$Fields = array('typeid'=>FetchIndex($Page), 'typename'=>$PostData['TypeName']);

/* Test that the database contains the correct record and if it fails
 * then abort the test with and exit code of 1
 */
if (!assertDB('debtortype', $Fields, $PostData, 'inserted')) exit(1);

/* Find the link to edit the customer type just entered and fetch
 * that page
 */
$EditPage = GetEditPage($Page, $Fields['typeid'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));

/* As this is a generalised test rather than a specific
 * edge casewe want to use randomised data so we fill
 * the form with the random data
 */
$PostData=FillFormWithRandomData($EditPage[2]);

/* Submit the form with the data and retrieve a page
 * containing the resulting html page details
 */
$CustomersTypeUpdatePage = new URLDetails($CookieFile, $ServerPath.$EditPage[2]['Action'], $PostData);
$Page=$CustomersTypeUpdatePage->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));

/* Construct an array of the following format:
 * 		$Fields[database field name] = Expected value of this field
 * for all fields being updated by this script
 */
$Fields = array('typeid'=>$PostData['SelectedType'], 'typename'=>$PostData['TypeName']);
if (!assertDB('debtortype', $Fields, $PostData, 'updated')) exit(1);

$DeletePage = GetDeletePage($Page, $PostData['SelectedType'], $RootPath, $ServerPath, $CookieFile, basename(__FILE__, '.php'));
/* Test that the database does not contain the record and if it fails
 * then abort the test with and exit code of 1
 */
if (!assertNotDB('debtortype', $Fields, $PostData, 'deleted')) exit(1);

/* Logout of the KwaMoja instance, delete the cookie file
 * and exit with a 0 response code
 */
KwaMojaLogout($RootPath, $ServerPath, $CookieFile);
unlink($CookieFile);
LogMessage(basename(__FILE__, '.php'), 0, 'Test completed successfuly', '');
exit(0);
?>