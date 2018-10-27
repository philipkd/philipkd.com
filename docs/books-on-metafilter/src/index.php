<head>

<?php

$cat = $_GET["cat"];
if (!$cat) {
	$cat = 'hr';
}

$year = intval($_GET["year"]);
if (!$year)
	$year = 2012;

?>


<?php

$title = "The Most Popular Self-Help Books on " . ($cat == 'work' ? "Work" : "Human Relations") . " According to Ask Metafilter (" . $year . ")";

?>

<title><?= $title ?></title>
<link rel="stylesheet" href="styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<script src="jquery-2.0.2.min.js" type="text/javascript"></script>


<script>
$(document).ready(function () {
	$('.mentions a').click(function() { $(this).parent().parent().find('.thumbs').toggle(); })
	$('select').change(function() { window.location = "./?cat=<?= $cat ?>&year=" + $(this).val(); });
});
</script>

</head>

<div id="canvas">

<div id="header">

<h1><?= $title ?></h1>

<h3>by <a href="http://philipkd.com/">Phil Dhingra</a> author of <a href="http://www.amazon.com/exec/obidos/ASIN/B002DEMFKO/philosophistr-20/ref=nosim/">The Perfect Thread: How One Question Tapped into the Soul of MetaFilter</a></h3>

<h2><a href="./">&laquo; Back to Index</a></h2>

</div>

<div id="books">

<?php


if ($year >= 2003 && $year <= 2012) {
	$file = "$cat-$year.html";
	if (file_exists($file))
		include($file);
	else
		echo "<div id=\"not-found\"><h2>No books found.</h2></div>";
}

?>

</div>

<br clear="all">

<div id="footer">

<a rel="license" href="http://creativecommons.org/licenses/by/2.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/2.0/80x15.png" /></a>

</div>


</div>