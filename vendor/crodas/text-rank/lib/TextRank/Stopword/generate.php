<?php

foreach (glob(__DIR__ . "/*.txt") as $file) {
    $lang  = substr(basename($file), 0, -14);
    $words = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $stopwords[$lang] = array_combine($words, array_fill(0, count($words), 1));
}

$php =  "<?php return " . var_export($stopwords, true) . ";";
file_put_contents(__DIR__ . '/Stopword.php', $php);
