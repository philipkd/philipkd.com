<?php

include_once('tools.php');

$cmd = "php " . reldir() . "/../docs/db.php list";
$results = explode("\n",`$cmd`);
$cmds = [];
foreach ($results as $tag) {
	if ($tag) {
		array_push($cmds,"php " . reldir() . "/../docs/db.php $tag > " . reldir() . "/../docs/db/$tag.html");
	}
}

array_push($cmds,"php " . reldir() . "/../docs/db.php > " . reldir() . "/../docs/db/index.html");

foreach ($cmds as $cmd) {
	print "$cmd\n";
	`$cmd`;
}

?>