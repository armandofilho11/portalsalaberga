<?php
$file = file_get_contents(dirname(__DIR__, 2) . '/app/main/views/professor.php');

$braces_open = substr_count($file, '{');
$braces_close = substr_count($file, '}');
$parens_open = substr_count($file, '(');
$parens_close = substr_count($file, ')');
$brackets_open = substr_count($file, '[');
$brackets_close = substr_count($file, ']');

echo "Chaves abertas: $braces_open\n";
echo "Chaves fechadas: $braces_close\n";
echo "Parênteses abertos: $parens_open\n";
echo "Parênteses fechados: $parens_close\n";
echo "Colchetes abertos: $brackets_open\n";
echo "Colchetes fechados: $brackets_close\n";

$errors = [];
if ($braces_open !== $braces_close) {
    $errors[] = "❌ Chaves desbalanceadas! Abertas: $braces_open, Fechadas: $braces_close";
}
if ($parens_open !== $parens_close) {
    $errors[] = "❌ Parênteses desbalanceados! Abertos: $parens_open, Fechados: $parens_close";
}
if ($brackets_open !== $brackets_close) {
    $errors[] = "❌ Colchetes desbalanceados! Abertos: $brackets_open, Fechados: $brackets_close";
}

if (empty($errors)) {
    echo "\n✅ Nenhum erro de sintaxe detectado!\n";
} else {
    echo "\n" . implode("\n", $errors) . "\n";
}
?>