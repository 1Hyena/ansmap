<?php

require_once("../ansmap.php");

$amp = ansmap_create_from_text(
    "    Ww    wW  CC\n".
    "    yyyyyyyy  CC\n".
    "    yyrRGGrR  yy\n".
    "    DDGGGGGG  yy\n".
    "yyDDyyDDyyDDyyGG\n".
    "GGGGDDyyDDyy  yy\n".
    "  rrrrrrrrrr  yy\n".
    "  DD      DD  yy\n",
    "background"
);

echo ansmap_to_string($amp);
