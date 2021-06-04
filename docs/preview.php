<?php ini_set('error_reporting',E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); ?> 

<?

	require_once '../libs/php-markdown-lib/Michelf/MarkdownExtra.inc.php';
	use Michelf\MarkdownExtra;

//	include_once "markdown.php";
	
	function Markdown($text) {
		return MarkdownExtra::defaultTransform($text);
	}


	$GLOBALS['slug_to_note'] = array();
	$GLOBALS['note_to_contents'] = array();

	$GLOBALS['note_dir'] = "./preview";

	process_notes();

	$stylesheets = [
		'medium' => '/mediumesque/medium.css',
		'nytimes' => '/nytimeslike/nytimes.css'
	];

	$GLOBALS['stylesheet'] = $stylesheets['medium'];


	function print_note($name) {

		echo "<div class=\"blogentry\"><div class=\"blogbody\">";
		echo Markdown($GLOBALS['note_to_contents'][$name]);
		echo "</div></div>";

	}

	function parse_note($note) {
		$contents = file_get_contents($GLOBALS['note_dir'] . "/$note");
		$GLOBALS['note_to_contents'][$note] = $contents;
	}

	function process_notes() {

		if ($handle = opendir($GLOBALS['note_dir'])) {

			while (false !== ($entry = readdir($handle))) {
		        if (preg_match('/.txt$/',$entry)) {

		        	$GLOBALS['note_to_slug'][$entry] = $entry;

		        }
		    }
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Markdown Preview</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="shortcut icon" href="/favicon.ico" >
<link rel="stylesheet" href="<?= $GLOBALS['stylesheet'] ?>" type="text/css" />
<link rel="stylesheet" href="../preview.css" type="text/css" />
<link href="https://g1.nyt.com/fonts/css/web-fonts.5810def60210a2fa7d0848f37e3fa048bb6147b1.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="main">

<?

	$notes = array_keys($GLOBALS['note_to_slug']);

	foreach ($notes as $note)
    	parse_note($note);

	sort($notes);

	foreach ($notes as $note)
    	print_note($note);

?>

</div>

</body></html>