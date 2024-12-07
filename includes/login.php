<?php

function webERPLogIn($CookieFile, $RootPath, $ServerPath, $Company, $UserName, $Password, $TestNumber) {

	$LoginScreenDetails = new URLDetails($CookieFile, $RootPath.'index.php', array());
	$LoginScreenDetails->FetchPage($RootPath, $ServerPath, $TestNumber);
	$FormDetails = $LoginScreenDetails->GetFormDetails();

	for ($i=0; $i<sizeOf($FormDetails['Selects']['select']['CompanyNameField']['options']); $i++) {
		if ($FormDetails['Selects']['select']['CompanyNameField']['options'][$i]['value']==$Company) {
			$PostArray['CompanyNameField']=$FormDetails['Selects']['select']['CompanyNameField']['options'][$i]['value'];
			break;
		}
	}

	for ($i=0; $i<sizeOf($FormDetails['Texts']['text']); $i++) {
		if ($FormDetails['Texts']['text'][$i]['name']=='UserNameEntryField') {
			$PostArray['UserNameEntryField']=$UserName;
			break;
		}
	}

	for ($i=0; $i<sizeOf($FormDetails['Passwords']['password']); $i++) {
		if ($FormDetails['Passwords']['password'][$i]['name']=='Password') {
			$PostArray['Password']=$Password;
			break;
		}
	}

	for ($i=0; $i<sizeOf($FormDetails['Hiddens']['hidden']); $i++) {
		if ($FormDetails['Hiddens']['hidden'][$i]['name']=='FormID') {
			$PostArray['FormID']=$FormDetails['Hiddens']['hidden'][$i]['value'];
			break;
		}
	}

	if (sizeOf($FormDetails['Submits']) > 0) {
		for ($i=0; $i<sizeOf($FormDetails['Submits']['submit']); $i++) {
			if ($FormDetails['Submits']['submit'][$i]['name']=='SubmitUser') {
				$PostArray['SubmitUser']='Login';
				break;
			}
		}
	}

	for ($i=0; $i<sizeOf($FormDetails['Buttons']['button']); $i++) {
		if ($FormDetails['Buttons']['button'][$i]['name']=='SubmitUser') {
			$PostArray['SubmitUser']='Login';
			break;
		}
	}

	$IndexScreenDetails = new URLDetails($CookieFile, $RootPath.'index.php', $PostArray);
	$IndexPage=$IndexScreenDetails->FetchPage($RootPath, $ServerPath, $TestNumber);

	return $IndexPage;
}

function webERPLogout($RootPath, $ServerPath, $CookieFile) {
	$IndexScreenDetails = new URLDetails($CookieFile, $RootPath.'Logout.php', array());
	$IndexPage=$IndexScreenDetails->FetchPage($RootPath, $ServerPath, basename(__FILE__, '.php'));
}

?>