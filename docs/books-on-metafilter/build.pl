#!perl

use LWP::Simple;

for ($year = 2013; $year >= 2003; $year--) {
	&build('work',$year);
	&build('hr',$year);
}

sub build {
	$cat = shift;
	$year = shift;
	$url = "http://local.philipkd.com/books-on-metafilter/src/?cat=$cat&year=$year";
	$file = "$cat-$year.html";
	print "$url > $cat\n";
	# `curl -o $cat-$year.html $url`;

	getstore($url,$file);
}

# 2003 - 2013

# `curl http://local.philipkd.com/books-on-metafilter/?cat=hr&year=2012