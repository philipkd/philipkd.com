<?php

function reldir() {
	$dir = str_replace(getcwd(),"",__DIR__);
	$dir = preg_replace("#^/#","",$dir);
	return $dir;
}

print reldir();
print "\n";
print getcwd();
print "\n";
print __DIR__;
print "\n";

?>