<?
require_once dirname(__FILE__) . "/../vendor/scssphp/scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

$compiler = new Compiler();

if ($argv[1]) {
  $css = $argv[1];
} elseif ($_GET['file']) {
  $css = $_GET['file'];
} else {
  $css = '';
}

$css = preg_replace('/\.css/', '.scss', $css);

$contents = file_get_contents(dirname(__FILE__) . '/../docs/css/' . $css);

header('Content-Type: text/css');
echo $compiler->compileString($contents)->getCss();

?>