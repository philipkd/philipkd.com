<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Philip Dhingra</title>

<link rel="shortcut icon" href="favicon.ico">

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap');
</style>

<link rel="stylesheet" href="/css/styles.css">

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18069474-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<div id="main">

<img id="cover" src="face10.png" alt="Phil unveiling Dear Hannah">

<h1>Philip Dhingra</h1>

<div id='contact'>

<?php

function get_svg($path, $id) {
	$svg = file_get_contents(dirname(__FILE__) . '/icons/' . $path);
	$svg = preg_replace('/fill:rgb\(74,74,77\);/','',$svg);
	$svg = preg_replace('/<svg/','<svg id=' . $id . ' ',$svg);
	return $svg;
}

?>


San Francisco<?= get_svg('dot.svg','dot') ?><script>document.write('<'+'a'+' '+'h'+'r'+'e'+'f'+'='+"'"+'&'+'#'+'1'+'0'+'9'+';'+'&'+'#'+'9'+'7'+';'+'i'+'l'+'t'+'o'+'&'+'#'+'5'+'8'+';'+'m'+'e'+'&'+'#'+'6'+'4'+';'+'p'+'&'+'#'+'1'+'0'+'4'+';'+'&'+'#'+'1'+'0'+'5'+';'+'l'+'%'+'6'+'9'+'p'+'k'+'&'+'#'+'1'+'0'+'0'+';'+'&'+'#'+'4'+'6'+';'+'c'+'%'+'&'+'#'+'5'+'4'+';'+'F'+'&'+'#'+'1'+'0'+'9'+';'+"'"+'>')</script><?= get_svg('email.svg','email') ?> Email</a><noscript>[Turn on JavaScript to see the email address]</noscript> <a href="https://www.linkedin.com/in/philipkd"><?= get_svg('linkedin.svg','linkedin') ?> LinkedIn</a></div>

<div id="resume">

<?php

	require dirname(__FILE__) . '/../vendor/autoload.php';
	use Michelf\Markdown;
	
	include_once "csvimporter.php";

	$GLOBALS['content_dir'] = dirname(__FILE__) . "/../content/Database";
	function MyMarkDown($text) {
		$text = Markdown::defaultTransform($text);
		$text = preg_replace('#</{0,1}p/{0,1}>#','',$text);
		return $text;
	}

	$importer = new CsvImporter(dirname(__FILE__) . "/../content/CV/thumbs.csv",true, ",");
	$thumbs_data = $importer->get();
	$THUMBS = array();
	foreach ($thumbs_data as $row) {
		$THUMBS[$row['loc']] = $row;
	}

?>

<?php function show_thumb($thumb) { ?>

	<div class='thumb'>
		<a href="<?= $thumb['url'] ?>"><img src='./thumbs/<?= $thumb['name'] ?>.png' /></a>
		<p><?= MyMarkdown($thumb['body']) ?></p>
	</div>

<?php } ?>

<?

$importer = new CsvImporter(dirname(__FILE__) . "/../content/CV/index.csv",true, ",");
$bullets = $importer->get();

$cur_cat = "";

foreach ($bullets as $row) {

	if ($cur_cat != $row['cat']) {
		$cur_cat = $row['cat'];

		if ($cur_cat == 'Top') {
			echo '<ul>';
		} else {
			echo '<h2>' . $cur_cat . "</h2><ul>\n";
		}

		if ($THUMBS[$cur_cat]) {	
			$thumb = $THUMBS[$cur_cat];
			show_thumb($thumb);

		}

	}

?>

	<li><a href="<?= $row['url'] ?>"><?= $row['title'] ?></a><?= ($row['desc'] != "") ? ": " : "" ?><?= MyMarkdown($row['desc']) ?></li>

<? } ?>

</ul>


<!-- 

Shout-out from [Jason Kottke](https://twitter.com/kottke/status/750698349762744320)

Shout-out from [Ramit Sethi](https://en.wikipedia.org/wiki/Ramit_Sethi)

Why does French sound the way it does
https://medium.com/philosophistry/why-does-french-sound-the-way-it-does-f957c9f08a3d?source=friends_link&sk=e9546c87e0feef4fc7f28a3784d476af


<a href="http://philosophistry.com/specials/syriana/">Tangled Web of <i>Syriana</i></a>

<a href="http://philosophistry.com/specials/100-people.html">100 People Who Are Screwing America</a>

<li><a href="https://medium.com/@philipkd/how-i-published-my-book-on-medium-397b007dda85#.3tml02k21">What It's Like Publishing My Entire Book on Medium</a> (Medium cover story)</li>

<li><a href="http://philosophistry.com/archives/2011/02/dna-test-23andme-review.html">What's it like getting DNA test results</a> (front page of Hacker News)</li>

<li><a href="https://philosophistry.com/archives/2009/05/this-is-going-into-my-best-ever-box-of-forum-threa.html">Our soon-to-be outdated beliefs</a> (featured by Jason Kottke)

<li><a href="https://medium.com/@philipkd/why-i-skip-stack-overflow-c012fadb6e65">Why I Skip Stack Overflow</a> (front page of Hacker News)</li>

<li>William Shatner's <i>Weird or What?</i> episode on <a href="http://vimeo.com/philosophistry/brayroadbeast">The Bray Road Beast</a> (2012)

<li>Short film "<a href="http://www.youtube.com/watch?v=VqYOQL8Qq2k">#Zombies: Followers of the Dead</a>" (2011)

<li>What is <a href="http://philosophistry.com/perfectalbum/">The Perfect Album, according to Plastic</a>, a web community

<li>First-round pick in <a href="http://philosophistry.com/archives/scans/2006/04/slashdot01.jpg">Slashdot's re-design Contest</a>

-->

</div><!-- #resume -->

</div> <!-- #main -->

