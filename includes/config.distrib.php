<?php

$RootPath='http://localhost/webERP/';
$ServerPath='http://localhost/';
$CompanyName='weberp';
$UserName='admin';
$Password='weberp';

$Host = 'localhost';
$DBPort = 3306;

$DBUser = 'db_user';
$DBPassword = 'db_password';

include('includes/login.php');
include('includes/SelectModule.php');
include('includes/SelectMenuOption.php');
include('includes/FillForm.php');
include('includes/Asserts.php');
include('includes/logging.php');
include('classes/URLDetails.class.php');

?>