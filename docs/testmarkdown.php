<?

// require_once '../libs/php-markdown-lib/Michelf/Markdown.inc.php';
require_once '../libs/php-markdown-lib/Michelf/MarkdownExtra.inc.php';

// use Michelf\Markdown;
use Michelf\MarkdownExtra;

function Markdown($text) {
	return MarkdownExtra::defaultTransform($text);
}

?>

<?

$my_text = <<<EOS

#yo

| Syntax      | Description |
| ----------- | ----------- |
| Header      | Title       |
| Paragraph   | Text        |
EOS;

$my_html = Markdown($my_text);

echo $my_html;

?>

