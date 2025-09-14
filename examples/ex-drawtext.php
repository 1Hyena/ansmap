<?php

require_once("../ansmap.php");

$amp = ansmap_create(20, 6);

ansmap_draw_text(
    $amp,
    "╔══════════════════╗\n".
    "║                  ║\n".
    "║                  ║\n".
    "║                  ║\n".
    "║                  ║\n".
    "╚══════════════════╝",
    0, 0, "blue-bg", "bold", "white"
);

ansmap_draw_text($amp, "B",  8, 2, "bold", "blue", "underline");
ansmap_draw_text($amp, "S",  9, 2, "bold", "yellow", "strikethrough");
ansmap_draw_text($amp, "O", 10, 2, "bold", "cyan", "blinking");
ansmap_draw_text($amp, "D", 11, 2, "bold", "magenta", "italic");
ansmap_draw_text($amp, "╌╌╌╌", 8, 3);

echo ansmap_to_string($amp);
