<?

use Michelf\Markdown;
require_once '../libs/php-markdown-lib/Michelf/Markdown.inc.php';
function Markdown($text) {
	return Markdown::defaultTransform($text);
}

?>

<?

$my_text = "# hello";

$my_html = Markdown($my_text);

echo $my_html;

?>

