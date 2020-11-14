<?php

include_once('tools.php');

# $cmd = `php docs/wiki.php 

function slug($str) {
	return preg_replace('/ /','_',$str);
}

function process_dir($dir) {
	$folder = reldir() . '/../content/Wiki/pages/' . $dir;
	if ($folder_handle = opendir($folder)) {
		while (false !== ($file = readdir($folder_handle))) {
			if (preg_match('/\.txt$/',$file)) {
				$efile = str_replace("\"","\\\"",$file);
				$outfile = $dir . str_replace(".txt",".html",slug($efile));
				$infile = $dir . str_replace(".txt","",$efile);
				$cmd = "php docs/wiki.php \"$infile\" > \"./docs/wiki/$outfile\"";
				print "$cmd\n";
				`$cmd`;
			}
		}
	}
	$folder = reldir() . '/../content/Wiki/media/' . $dir;	
	if ($folder_handle = opendir($folder)) {
		while (false !== ($file = readdir($folder_handle))) {
			if (preg_match('/\.[a-z]{3}$/',$file)) {
				$efile = str_replace("\"","\\\"",$file);
				$outfile = $dir . $file;
				$infile = $folder . $file;
				
				$cmd = "cp \"$infile\" \"./docs/wiki/$outfile\"";
				print "$cmd\n";
				`$cmd`;
			}
		}
	}
}

process_dir('./');
process_dir('personal/');



?>