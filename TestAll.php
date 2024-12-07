#!/usr/bin/php8.3
<?php

$TestsToRun = glob('*.phpt');

foreach ($TestsToRun as $Test) {
	exec('~/bin/colin/'.$Test, $Results, $ExitCode);
	echo $ExitCode."\n";
}

?>
