<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Philip Dhingra</title>

<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="styles.css?refresh=192834">

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

<div id="resume">

<?php

	include_once "markdown.php";
	include_once "csvimporter.php";

	$GLOBALS['content_dir'] = dirname(__FILE__) . "/../content/Database";
	function MyMarkDown($text) {
		$text = Markdown($text);
		$text = preg_replace('#</{0,1}p/{0,1}>#','',$text);
		return $text;
	}

?>

<?

$importer = new CsvImporter(dirname(__FILE__) . "/../content/CV/index.csv",true, ",");
$data = $importer->get();

$cur_cat = "Top";

foreach ($data as $row) {
?>

<?
	if ($cur_cat != $row['cat']) {
		if ($cur_cat == 'Top') {
?>

<p/>

<div id='contact'>
	San Francisco, CA · <script>document.write('<'+'a'+' '+'h'+'r'+'e'+'f'+'='+"'"+'&'+'#'+'1'+'0'+'9'+';'+'&'+'#'+'9'+'7'+';'+'i'+'l'+'t'+'o'+'&'+'#'+'5'+'8'+';'+'m'+'e'+'&'+'#'+'6'+'4'+';'+'p'+'&'+'#'+'1'+'0'+'4'+';'+'&'+'#'+'1'+'0'+'5'+';'+'l'+'%'+'6'+'9'+'p'+'k'+'&'+'#'+'1'+'0'+'0'+';'+'&'+'#'+'4'+'6'+';'+'c'+'%'+'&'+'#'+'5'+'4'+';'+'F'+'&'+'#'+'1'+'0'+'9'+';'+"'"+'>')</script>✉️ Email</a><noscript>[Turn on JavaScript to see the email address]</noscript> <a href="https://www.linkedin.com/in/philipkd"><img src='icons_/linkedin.png' /> LinkedIn</a></div>

<?
		}

		echo "</ul><h2>" . $row['cat'] . "</h2><ul>\n";
	}

	$cur_cat = $row['cat'];

	if ($cur_cat != 'Top')
		echo "<li>";
?>

	<a href="<?= $row['url'] ?>"><?= $row['title'] ?></a><?= ($row['desc'] != "") ? ": " : "" ?><?= MyMarkdown($row['desc']) ?>

	<?= ($cur_cat == 'Top') ? "<p/>" : "</li>" ?>

<? } ?>

</ul>


<!-- 


REMOVED LINKS

<a href="http://philosophistry.com/specials/syriana/">Tangled Web of <i>Syriana</i></a><a href="http://philosophistry.com/specials/100-people.html">100 People Who Are Screwing America</a>
<li><a href="https://vimeo.com/105699696">Book Launch</a> for <i>Dear Hannah</i> 
<li><a href="https://medium.com/philosophistry/wobbly-tables-and-the-problem-with-futurism-934468d2308">Wobbly Tables and the Problem with Futurism</a> (featured by Jason Kottke)</li>
<li><a href="https://medium.com/@philipkd/how-i-finally-reined-in-my-spending-a1255a6f2736#.x9jd99k7b">How I Finally Reined in My Spending</a> (featured by Ramit Sethi)</li>
<li><a href="https://medium.com/@philipkd/how-i-published-my-book-on-medium-397b007dda85#.3tml02k21">What It's Like Publishing My Entire Book on Medium</a> (Medium cover story)</li>
<li><a href="http://philosophistry.com/archives/2011/02/dna-test-23andme-review.html">What's it like getting DNA test results</a> (front page of Hacker News)</li>
<li><a href="https://philosophistry.com/archives/2009/05/this-is-going-into-my-best-ever-box-of-forum-threa.html">Our soon-to-be outdated beliefs</a> (featured by Jason Kottke)
<li><a href="https://medium.com/@philipkd/why-i-skip-stack-overflow-c012fadb6e65">Why I Skip Stack Overflow</a> (front page of Hacker News)</li>
<li><a href="http://www.youtube.com/watch?v=5aMaqpWoqmQ">Rave Light</a>
<li><a href="https://www.amazon.com/gp/product/B06XBX9KBF/?tag=philosophistr-20">Philosophistry: The Love of Rhetoric</a>
<li><a href="https://www.amazon.com/gp/product/B002DEMFKO/?tag=philosophistr-20">The Perfect Thread: How One Question Tapped into the Soul of MetaFilter</a>
<li>William Shatner's <i>Weird or What?</i> episode on <a href="http://vimeo.com/philosophistry/brayroadbeast">The Bray Road Beast</a> (2012)
<li>Short film "<a href="http://www.youtube.com/watch?v=VqYOQL8Qq2k">#Zombies: Followers of the Dead</a>" (2011)
<li>What is <a href="http://philosophistry.com/perfectalbum/">The Perfect Album, according to Plastic</a>, a web community
<li>First-round pick in <a href="http://philosophistry.com/archives/scans/2006/04/slashdot01.jpg">Slashdot's re-design Contest</a>

-->

</div><!-- #resume -->

</div> <!-- #main -->

