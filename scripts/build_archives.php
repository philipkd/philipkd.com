<?php

$archive_path = dirname(__FILE__) . "/../content/archives";

$build_path = dirname(__FILE__) . "/../docs/archives";

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($archive_path), RecursiveIteratorIterator::SELF_FIRST);

$footer = file_get_contents($archive_path . "/footer.html");
$header = file_get_contents($archive_path . "/header.html");

foreach ($objects as $name => $object) {
	if (preg_match("/\.html/",$name)) {
		$contents = file_get_contents($name);

		$contents = str_replace('<!--#include virtual="../../header.html" -->', $header, $contents);
		$contents = str_replace('<!--#include virtual="header.html" -->', $header, $contents);
		$contents = str_replace('<!--#include virtual="../../footer.html" -->', $footer, $contents);
		$contents = str_replace('<!--#include virtual="footer.html" -->', $footer, $contents);

		$newpath = $build_path . str_replace($archive_path, "", $name);
		echo "$newpath\n";

		if (!is_dir(dirname($newpath)))
			mkdir(dirname($newpath), 0777, true);

		file_put_contents($newpath, $contents);

	}
}

# file_get_contents($archive_path)

?>