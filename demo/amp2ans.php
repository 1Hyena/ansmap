<?php

require_once("../ansmap.php");

if (count($argv) <= 1) {
    exit(
        "synopsis: php amp2ans.php FILE\n".
        "example:  php ".$argv[0]." punisher.amp\n"
    );
}

$file = $argv[1];

if (!file_exists($file)) {
    exit('file does not exist: '.$file."\n");
}

$lines = file($file, FILE_IGNORE_NEW_LINES);

$content = array(
    "symbol" => "",
    "foreground" => "",
    "background" => "",
    "decoration" => ""
);

$channel = "symbol";

foreach ($lines as $line_num => $line) {
    if ($line === "#AMP_FOREGROUND") {
        $channel = "foreground";
    }
    else if ($line === "#AMP_BACKGROUND") {
        $channel = "background";
    }
    else if ($line === "#AMP_DECORATION") {
        $channel = "decoration";
    }
    else {
        $content[$channel] .= $line."\n";
    }
}

$amp = ansmap_create_from_texts(
    $content["background"],
    $content["symbol"],
    $content["foreground"],
    $content["decoration"]
);

echo ansmap_to_string($amp);
