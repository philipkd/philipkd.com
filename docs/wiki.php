<?php

require '../vendor/autoload.php';
use Michelf\Markdown;

function slug($str) {
	return preg_replace('/ /','_',$str);
}

function unslug($str) {
	return preg_replace('/_/',' ',$str);
}

function MyMarkdown2($contents) {


	preg_match_all('/\[\[(.*?)\]\]/',$contents,$links, PREG_SET_ORDER);	

	$contents = preg_replace('/\[\[((.*\/){0,1}(.*?))\]\]/','[\3](\1)',$contents);

	foreach ($links as $link) {

		$link = $link[1];
		$newlink = slug($link);

		$contents = preg_replace("#\($link\)#","($newlink)",$contents);
	}

	return Markdown::defaultTransform($contents);
}

if (isset($argv)) {
// if ($argv[1]) {
	$note = $argv[1];
} elseif ($_GET['note']) {
	$note = unslug($_GET['note']);
	if (preg_match('#/$#',$note))
		$note .= 'index';
} else {
	$note = 'index';
}

$parent_dir = '';

# match any parent dir except './'
if (preg_match('#^([^.]*/)#',$note,$matches))
	$parent_dir = $matches[1];


$contents = file_get_contents(dirname(__FILE__) . '/../content/Wiki/pages/' . $note . '.txt');

$title = preg_replace('/(.*){0,1}\/(.*)/','\2',$note);
if ($title == 'index')
	$title = 'Home';

$wikititle = ucfirst(rtrim($parent_dir,"/")) . " Wiki";

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/wiki.css">

<title><?= $title ?></title>

</head>

<div class="site-title"><a href="./"><?= $wikititle ?></a> by <a href="https://philipkd.com/">Philip Dhingra</a></div>

<div class="entry">

<div class="page-title"><?= $title ?></div>

<?= MyMarkdown2($contents) ?>

<br/>
<a href="https://licensebuttons.net/l/by/4.0/"><img src="https://licensebuttons.net/l/by/4.0/80x15.png"></a>