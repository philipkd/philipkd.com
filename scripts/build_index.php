<?php

include_once('tools.php');

$cmds = [];

array_push($cmds,"php " . reldir() . "/../docs/index.php > " . reldir() . "/../docs/index.html");
array_push($cmds,"php " . reldir() . "/../docs/scss.php styles.scss > " . reldir() . "/../docs/css/styles.css");


foreach ($cmds as $cmd) {
	print "$cmd\n";
	`$cmd`;
}



?>