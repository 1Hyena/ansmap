<?php

require_once("../ansmap.php");

$amp_human = ansmap_create_from_texts(
    "    wwwwwwww  CC\n".
    "    wwwwwwww  CC\n".
    "    wwddRRdd  CC\n".
    "ccCCwwRRRRRR  CC\n".
    "CCccyyDDyyDDwwrr\n".
    "ccCCDDyyDDyy    \n".
    "  rrrrrrrrrr    \n".
    "  ww      ww    \n"
);

$amp_orc = ansmap_create_from_texts(
    "    Ww    wW  CC\n".
    "    yyyyyyyy  CC\n".
    "    yyrRGGrR  yy\n".
    "    DDGGGGGG  yy\n".
    "yyDDyyDDyyDDyyGG\n".
    "GGGGDDyyDDyy  yy\n".
    "  rrrrrrrrrr  yy\n".
    "  DD      DD  yy\n"
);

$amp_screen = ansmap_create(80, 20);

for ($y = 0; $y < ansmap_get_height($amp_screen); $y += 3) {
    $x = rand(0, ansmap_get_width($amp_screen) - 1);

    if (rand() % 2 === 0) {
        ansmap_draw_sprite($amp_screen, $amp_orc, $x, $y);
    }
    else {
        ansmap_draw_sprite($amp_screen, $amp_human, $x, $y);
    }
}

echo ansmap_to_string($amp_screen);
