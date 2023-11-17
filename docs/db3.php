<?php


	# what I had before:
	#ini_set('error_reporting',E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

	# I have this now, since I'm not worrying about php warnigs in PHP8
	error_reporting(E_ERROR | E_PARSE);

# ini_set('error_reporting', E_ALL);
# ini_set('display_errors', 1);

	require '../vendor/autoload.php';
	use Michelf\Markdown;
	
	$GLOBALS['content_dir'] = dirname(__FILE__) . "/../content/db3";

	if (preg_match("/^local./", $_SERVER['HTTP_HOST']))
		$GLOBALS['local_access'] = true;

	if ($_GET['expand'])
		$GLOBALS['expand'] = $_GET['expand'];

	$GLOBALS['note_route'] = $_GET['note'];
    $GLOBALS['tag_route'] = $_GET['tag'];

    if ($argv[1])
    	$GLOBALS['tag_route'] = $argv[1];

	$GLOBALS['files_dir'] = $GLOBALS['content_dir'] . "/files/";
	$GLOBALS['logs_dir'] = $GLOBALS['content_dir'] . "/Logs";
	$GLOBALS['tag_names_file'] = $GLOBALS['content_dir'] . "/tags.txt";

	$GLOBALS['essays'] = array();

	$GLOBALS['tag_to_essays'] = array();
	$GLOBALS['tag_to_new'] = array();
	$GLOBALS['essay_to_tags'] = array();

	$GLOBALS['tag_to_title'] = array();

	# $GLOBALS['index'] = json_decode(file_get_contents("./content/index.json"));

	$GLOBALS['tag_to_name'] = array();

	$GLOBALS['new_icon'] = '<img src="/images/icons/new.gif" />';

	process_notes();
	// process_logs();
	process_tag_names();

	if ($argv[1] == 'list') {
		$tags = array_keys($GLOBALS['tag_to_essays']);
	    foreach ($tags as $tag)
	    	if (!special_tag($tag))
	    		print "$tag\n";		
	    exit;
	}

	function print_nav_tags($tags,$hide_total = false) {
	    foreach ($tags as $tag) {
	    	$count = count($GLOBALS['tag_to_essays'][$tag]);
	    	$should_bold = $GLOBALS['tag_to_new'][$tag] && $tag != "_new";
	    	$bold_start = $should_bold ? "<b>" : "";
	    	$bold_end = $should_bold ? "</b>" : "";
		    echo "$bold_start<a href=\"?tag=$tag\">" . strtolower(tag_name_sub($tag)) . "</a>$bold_end";
		    if (!$hide_total)
		    	echo " <span class=\"count\">$count</span>";

		    echo "<br/>\n";
		    
		}
	}

	function print_nav() {

		echo "<div id=\"db\">";

	    
	    $tags = array_keys($GLOBALS['tag_to_essays']);
	    usort($tags, "tag_count_sort");

	    $main_tags = array();
	    $special_tags = array();

	    foreach ($tags as $tag) {
	    	if (special_tag($tag))
	    		array_push($special_tags,$tag);
	    	else
	    		array_push($main_tags,$tag);
	    }

		if ($GLOBALS['local_access']) {
			echo "<b>Special Tags</b><p/>";
		    print_nav_tags($special_tags,true);
		    echo "<a href=\"?tag=_nystbd\">not-yet-initial-dev</a>";
		    print "<p/>";
		}		


		echo "<b>Total</b> <span class=\"count\">";

	    echo count($GLOBALS['essays']);

		echo "</span><p/>";

    	print_nav_tags($main_tags);

	    echo "</div>";
	}

	function process_hash_tags_and_title($tags,$title) {
		if (in_array("_pi",$tags) && !$GLOBALS['local_access'])
			return;
		$has_new = false;
		if (in_array("_new",$tags))
			$has_new = true;

		foreach ($tags as $tag) {

    		if (!$GLOBALS['tag_to_essays'][$tag]) {
    			$GLOBALS['tag_to_essays'][$tag] = array();
    		}
    		array_push($GLOBALS['tag_to_essays'][$tag],$title);

    		if ($has_new)
    			$GLOBALS['tag_to_new'][$tag] = true;

    		if (!$GLOBALS['essay_to_tags'][$title]) {
    			$GLOBALS['essay_to_tags'][$title] = array();
    		}
    		array_push($GLOBALS['essay_to_tags'][$title],$tag);
		}
	}

	function process_notes() {
		$folder = $GLOBALS['files_dir'];
		if ($subfolder_handle = opendir($folder)) {
			while (false !== ($subfolder = readdir($subfolder_handle))) {
        		if (preg_match('/^[A-Za-z0-9]/',$subfolder)) {

        			$subfolder = $folder . "/" . $subfolder;

        			$note_handle = opendir($subfolder);

        			while (false !== ($note = readdir($note_handle))) {
				        if (preg_match('/.txt$/',$note)) {
				        	$note_file = $subfolder . "/" . $note;
							$contents = file_get_contents($note_file);

							$title = $note;

							$GLOBALS['essays'][$title] = $contents;

							if ($tags = get_tags($note))
								process_hash_tags_and_title($tags,$title);
				        }
        			}        			        			
				}
			}
		}
	}

	function process_tag_names() {
		$file = $GLOBALS['tag_names_file'];
    	foreach(file($file) as $line) {
    		$line = rtrim($line);
    		$components =  preg_split('/:/',$line);
    		$tag = $components[0];
    		$name = $components[1];
			$GLOBALS['tag_to_name'][$tag] = $name;
		}
	}

	function process_logs() {
		$folder = $GLOBALS['logs_dir'];
		if ($log_handle = opendir($folder)) {
			while (false !== ($log = readdir($log_handle))) {
        		if (preg_match('/^[A-Za-z0-9]/',$log)) {
		        	$log_file = $folder . "/" . $log;

		        	$contents = file_get_contents($log_file);

		        	$lines = preg_split('/\n/',$contents);

		        	foreach ($lines as $line) {
		        		if (preg_match('/^- /',$line)) {
		        			$line = preg_replace('/^- /','',$line);
		        			$line = preg_replace('/\s*$/','',$line);
		        			$tags = get_tags($line);
		        			$line = remove_tags($line);

		        			$body = "";

							if (preg_match('/^(.*?)\.(.*)$/',$line,$matches)) {
								$title = $matches[1];
								$body = $matches[2];
							} else {
								$title = $line;
							}

							$GLOBALS['essays'][$title] = $body;

							if ($tags)
								process_hash_tags_and_title($tags,$title);
							
		        		}
		        	}
        		}
			}
		}
	}

	function tag_name_sub($tag) {
 		if($GLOBALS['tag_to_name'][$tag])
 			return $GLOBALS['tag_to_name'][$tag];
 		return $tag;
	}


	function print_essays($essays) {

 		usort($essays,"essay_sort");

		// $i = 0;
 		foreach ($essays as $essay) {
			if ($GLOBALS['local_access'] && !$GLOBALS['expand'])
				print_essay($essay,true);
			else
				print_essay($essay);

			// if($i < count($essays) - 1)
			//  	hr_tag();
			// $i++;
 		}
		
		print("<i>" . count($essays) . " entries</i>");
	}

	// future for maybe custom queries
	function print_tagset() {
 		$dev = array_keys($GLOBALS['essays']);
 		// $dev = $GLOBALS['tag_to_essays']["_dev"];
 		$stbd = $GLOBALS['tag_to_essays']["_stbd"];
 		$pi = $GLOBALS['tag_to_essays']["_pi"];

 		print_essays(array_diff(array_diff($dev, $stbd),$pi));
	}

	function print_tag($tag) {

 		$essays = $GLOBALS['tag_to_essays'][$tag];
 		
 		if(!$essays)
 			return;
 		
		$idea = ltrim($tag, "_");

		if ($idea_data = $GLOBALS['index']->ideas->$idea) {

			echo "<div class='tag-description'>";

			if ($subtitle = $idea_data->subtitle)
				echo "<p><b>" . $subtitle . "</b></p>";

			if ($description = $idea_data->description) {
				echo MyMarkdown($description);
			}			

			echo "</div>";
			echo "<h3>Entries</h3>";
		}

		print_essays($essays);

	}

	# from https://stackoverflow.com/questions/640931/recursively-counting-files-with-php
	function getFileCount($path) {
	    $size = 0;
	    $ignore = array('.','..','cgi-bin','.DS_Store','tags.txt');
	    $files = scandir($path);
	    foreach($files as $t) {
	        if(in_array($t, $ignore)) continue;
	        if (is_dir(rtrim($path, '/') . '/' . $t)) {
	            $size += getFileCount(rtrim($path, '/') . '/' . $t);
	        } else {
	            $size++;
	        }   
	    }
	    return $size;
	}

	function get_total() {
		return getFileCount($GLOBALS['content_dir']);
	}

	function print_about() {
		echo <<<EOT
		
		<h2></h2>

<div class="note-body"><p>You can read <a href="https://medium.com/philosophistry">full posts on Medium</a>, or you can browse the scratchpad:</p></div>

EOT;
	}

	function wiki_slug($str) {
		$str = preg_replace('/ /','_',$str);
		$str = "/wiki/$str";
		return $str;
	}


	function wiki_markdown($contents) {

		$contents = str_replace("#","####",$contents);

		preg_match_all('/\[\[(.*?)\]\]/',$contents,$links, PREG_SET_ORDER);	

		$contents = preg_replace('/\[\[((.*\/){0,1}(.*?))\]\]/','[\3](\1)',$contents);

		foreach ($links as $link) {

			$link = $link[1];
			$newlink = wiki_slug($link);

			$contents = preg_replace("#\($link\)#","($newlink)",$contents);
		}

		return Markdown($contents);
	}

	function print_wiki() {
		$count = wiki_count();
		echo "<b>Wiki</b>  <span class=\"count\">$count</span>";
		echo wiki_markdown(file_get_contents($GLOBALS['content_dir'] . "/Wiki/index.txt"));
	}

	function wiki_count() {
		$fi = new FilesystemIterator($GLOBALS['content_dir'] . "/Wiki", FilesystemIterator::SKIP_DOTS);
		return iterator_count($fi);
	}

	function print_essay($title, $excerpt = false) {
		echo "<p class='essay'><b class='title'><a href=\"?note=" . urlencode($title) . "\">" . titleify($title,$excerpt) . "</a></b>\n";		

		if ($excerpt) {
			if ($body = $GLOBALS['essays'][$title]) {
				echo " — ";
				echo substr($body,0,144);
			}
		} else {
			echo "<div class=\"note-body\">";
			echo MyMarkdown($GLOBALS['essays'][$title]);
			echo "</div>\n\n";
		}

		echo "<br/>";
		
		$tags = $GLOBALS['essay_to_tags'][$title];

	    foreach ($tags as $tag) {
	    	if (special_tag($tag) && $GLOBALS['local_access'] || !special_tag($tag) || $tag == "_new")
		    	echo " <a href='?tag=$tag'>" . $tag . "</a>\n";
		}
		echo "</p>";		

	}

	function dep_print_essay($title) {

		// echo "<h4>" . titleify($title) . "</h4>\n";
		echo "<div class=\"note-body\">";
		echo MyMarkdown($GLOBALS['essays'][$title]);
		echo "</div>\n\n";

		$tags = $GLOBALS['essay_to_tags'][$title];

		echo "<div class=\"note-tags\">";
	    foreach ($tags as $tag) {
	    	if (special_tag($tag) && $GLOBALS['local_access'] || !special_tag($tag) || $tag == "_new")
		    	echo "<a href='?tag=$tag'>" . $tag . "</a>\n";
		}
		echo "</div>";

	}


	function hr_tag() {
		$spaces = str_repeat('&nbsp;',5);
		$diamond = '&diams;';
		print "<div class='hr'>" . $diamond . $spaces . $diamond . $spaces . $diamond . "</div>";
	}

	function tag_count_sort($a,$b) {
		return count($GLOBALS['tag_to_essays'][$b]) - count($GLOBALS['tag_to_essays'][$a]);
	}

	function essay_sort($a,$b) {
		$a_tags = $GLOBALS['essay_to_tags'][$a];
		$b_tags = $GLOBALS['essay_to_tags'][$b];

		if (in_array("_new",$a_tags) && !in_array("_new",$b_tags))
			return -1;
		if (!in_array("_new",$a_tags) && in_array("_new",$b_tags))
			return 1;
		return strnatcasecmp($a, $b);
	}

	function get_tags($line) {
    	if (preg_match_all('/#(.*?)([\. ]|$)/',$line,$matches)) {
    		$tags = $matches[1];
    		if (preg_match('/^- /',$line,$matches)) {
    			array_push($tags,"_stbd");
    		}

			$contents = $GLOBALS['essays'][$line];
			if (preg_match('/---/',$contents))
    			array_push($tags,"_idd");

    		return $tags;
    	}

    	return false;
	}		


	function remove_tags($text) {
		$text = preg_replace('/ *#.*/','',$text);
		return $text;
	}

	function titleify($text, $preserve_dash = false) {
		$text = preg_replace('/\.txt/','',$text);
		$text = preg_replace('/^ _/','',$text);
		if (!$preserve_dash)
			$text = preg_replace('/^- /','',$text);

		$text = remove_tags($text);
		return $text;
	}

	function special_tag($tag) {
		return preg_match("/^_/",$tag);
	}

	function MyMarkDown($text) {
		$text = Markdown::defaultTransform($text);
		// $text = preg_replace('/<blockquote>\s*?<p>/','<blockquote>',$text);
		// $text = preg_replace('/<\/p>\s*?<\/blockquote>/','</blockquote>',$text);
		return $text;
	}

?>

<?


	$home = false;
	$show_title = true;

	if ($tag = $GLOBALS['tag_route']) {
		$tag_name = $tag;
 		if($GLOBALS['tag_to_name'][$tag])
 			$tag_name = $GLOBALS['tag_to_name'][$tag];

		$title = ucwords(tag_name_sub($tag));
	}  elseif ($note = $GLOBALS['note_route']) {
		$title = titleify($note,false);
		$show_title = false;
	} else {
		$home = true;
		$title = "Philosophistry";
	}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta name="viewport" content="width=<?= $home ? '600' : 'device-width' ?>, initial-scale=1.0">

<link rel="stylesheet" href="/db.css">

<title><?= $title ?><? $home ? " (Philosophistry)" : "" ?></title>

</head>

<div class="site-title"><a href="/db3.php">Notes</a> by <a href='https://philipkd.com/'>Philip Dhingra</a></div>

<div class="entry">

<? if (!$home) { ?>

<div class="page-title"><?= $show_title ? $title : '' ?> 

<?
// $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// $text = ' + ';
// if ($GLOBALS['expand']) {
// 	$text = ' - ';
// 	$url = preg_replace('/&expand=1/','',$url);
// } else {
// 	$url .= '&expand=1';
// }

?>

<!-- <span class="expand-btn">
	<a href="<?= $url ?>"><?= $text ?></a>
</span>
 -->
</div>

<? } ?>

<?php

	if ($note_route = $GLOBALS['note_route']) {
		print_essay(urldecode($note_route));
	} else if ($tag_route = $GLOBALS['tag_route']) {
		if ($tag_route == "_nystbd")
			print_tagset();
 		else
 			print_tag($GLOBALS['tag_route']);
 	} else {
		print_nav();

 	}

?>


<br clear="all"/>
<br/>
<a href="https://licensebuttons.net/l/by/4.0/"><img src="https://licensebuttons.net/l/by/4.0/80x15.png"></a>