<?
require_once dirname(__FILE__) . "/../vendor/scssphp/scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

$compiler = new Compiler();

$contents = file_get_contents(dirname(__FILE__) . '/../docs/css/test.scss');


// echo $compiler->compileString('
//   $color: #abc;
//   div { color: lighten($color, 20%); }
// ')->getCss();

echo $compiler->compileString($contents)->getCss();

?>