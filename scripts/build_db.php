<?php

include_once('tools.php');

$cmd = "php " . reldir() . "/../docs/db.php list";
$results = explode("\n",`$cmd`);
foreach ($results as $tag) {
	if ($tag) {
		$cmd = "php " . reldir() . "/../docs/db.php $tag > " . reldir() . "/../docs/db/$tag.html";
		print "$cmd\n";
		`$cmd`;
	}
}

?>