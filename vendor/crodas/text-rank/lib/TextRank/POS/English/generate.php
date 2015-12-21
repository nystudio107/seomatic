<?php

$fh = fopen(__DIR__ . "/lexicon.txt", 'r');
while($line = fgets($fh)) {
    $tags = array_map('rtrim', explode(' ', $line));
    $dict[strtolower(array_shift($tags))] = $tags;
}
fclose($fh);

ob_start();
echo "<?php return " . var_export($dict, true) . ";";

file_put_contents(__DIR__  . "/lexicon.php", ob_get_clean());

