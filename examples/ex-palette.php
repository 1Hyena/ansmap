<?php

require_once("../ansmap.php");

$palette = array(
    "d" => array(  0,  0,  0),
    "r" => array(128,  0,  0),
    "g" => array(  0,128,  0),
    "y" => array(128,128,  0),
    "b" => array(  0,  0,128),
    "m" => array(128,  0,128),
    "c" => array(  0,128,128),
    "w" => array(192,192,192),
    "D" => array(128,128,128),
    "R" => array(255,  0,  0),
    "G" => array(  0,255,  0),
    "Y" => array(255,255,  0),
    "B" => array(  0,  0,255),
    "M" => array(255,  0,255),
    "C" => array(  0,255,255),
    "W" => array(255,255,255)
);

$dither = array(
    " " => 0.00,
    "░" => 0.25,
    "▒" => 0.50,
    "▓" => 0.75,
    "█" => 1.00
);

/*
$dither = array(
    " " => 0.000,
    "𜴀𜴃𜴉𜴘" => 0.125,
    "𜴁𜴄𜴆𜴊𜴋𜴐𜴙𜴚𜴜𜴧𜴶𜴷𜴹𜴽𜵑𜵱𜵲𜵴𜵸𜶀" => 0.250,
    "𜴂𜴅𜴇𜴈𜴌𜴍𜴎𜴑𜴒𜴔𜴛𜴝𜴞𜴠𜴡𜴣𜴨𜴩𜴫𜴯𜴸𜴺𜴻𜴾𜴿𜵁𜵅𜵆𜵈𜵋𜵒𜵓𜵕𜵙𜵡𜵳𜵵𜵶𜵹𜵺𜵼𜶁𜶂𜶄𜶈𜶐𜶑𜶓𜶖𜶜𜶬𜶭𜶯𜶳𜶻𜷋" => 0.375,
    "𜴏𜴓𜴕𜴖𜴟𜴢𜴤𜴥𜴪𜴬𜴭𜴰𜴱𜴳𜴼𜵀𜵂𜵃𜵇𜵉𜵌𜵎𜵔𜵖𜵗𜵚𜵛𜵝𜵢𜵣𜵥𜵩𜵷𜵻𜵽𜵾𜶃𜶅𜶆𜶉𜶊𜶌𜶒𜶔𜶗𜶙𜶝𜶞𜶠𜶤𜶮𜶰𜶱𜶴𜶵𜶷𜶼𜶽𜶿𜷃𜷌𜷍𜷏𜷓" => 0.500,
    "𜵘" => 0.625,
    "𜵨" => 0.750,
    "𜶫" => 0.875,
    "█" => 1.000
);
*/

function generate_dither_palette($pal, $dit) {
    $generated = array();

    foreach ($pal as $bg_key => $bgc) {
        if ($bg_key === strtoupper($bg_key)) {
            continue;
        }

        foreach ($pal as $fg_key => $fgc) {
            foreach ($dit as $dit_key => $d) {
                $r = $bgc[0] * (1 - $d) + $fgc[0] * $d;
                $g = $bgc[1] * (1 - $d) + $fgc[1] * $d;
                $b = $bgc[2] * (1 - $d) + $fgc[2] * $d;

                $sym = mb_str_split($dit_key);
                $sym = $sym[rand() % count($sym)];

                $generated[] = array(
                    "rgb" => array($r, $g, $b),
                    "bgc" => $bg_key,
                    "fgc" => $fg_key,
                    "sym" => $sym
                );
            }
        }
    }

    return $generated;
}

function palette_lookup(&$pal, $r, $g, $b) {
    $best_index = 0;
    $best_score = null;

    for ($i = 0; $i < count($pal); ++$i) {
        $rgb = $pal[$i]["rgb"];

        $dr = ($rgb[0] - $r);
        $dg = ($rgb[1] - $g);
        $db = ($rgb[2] - $b);

        $score = $dr * $dr + $dg * $dg + $db * $db;

        if ($best_score === null || $best_score > $score) {
            $best_score = $score;
            $best_index = $i;
        }
    }

    return $pal[$best_index];
}

function lerp_rgb_array($colors) {
    $lerped = array();

    for ($i = 0; $i < count($colors); ++$i) {
        $c1 = $colors[$i];

        $lerped[] = $c1;

        $c2 = $colors[($i + 1) % count($colors)];

        $lerped[] = array(
            ($c1[0] + $c2[0])/2, ($c1[1] + $c2[1])/2, ($c1[2] + $c2[2])/2
        );
    }

    return $lerped;
}

$dither_palette = generate_dither_palette($palette, $dither);

$middle = array(
    array(255,   0,   0),
    array(255, 255,   0),
    array(  0, 255,   0),
    array(  0, 255, 255),
    array(  0,   0, 255),
    array(255,   0, 255)
);

$middle = lerp_rgb_array($middle);
$middle = lerp_rgb_array($middle);

$txt = "";
$txt_bg = "";
$txt_fg = "";
$width = 80;

for ($y = 0; $y < count($middle); ++$y) {
    for ($x = 0, $w = $width / 2; $x < $w; ++$x) {
        $p = $x / $w;

        $r = $middle[$y][0] * $p;
        $g = $middle[$y][1] * $p;
        $b = $middle[$y][2] * $p;

        $info = palette_lookup($dither_palette, $r, $g, $b);
        $txt_bg .= $info["bgc"];
        $txt_fg .= $info["fgc"];
        $txt .= $info["sym"];
    }

    for ($x = 0, $w = $width / 2; $x < $w; ++$x) {
        $p = $x / $w;

        $r = $middle[$y][0] * (1 - $p) + $p * 255;
        $g = $middle[$y][1] * (1 - $p) + $p * 255;
        $b = $middle[$y][2] * (1 - $p) + $p * 255;

        $info = palette_lookup($dither_palette, $r, $g, $b);
        $txt_bg .= $info["bgc"];
        $txt_fg .= $info["fgc"];
        $txt .= $info["sym"];
    }

    $txt_bg .= "\n";
    $txt_fg .= "\n";
    $txt .= "\n";
}

$amp = ansmap_create_from_texts($txt_bg, $txt, $txt_fg);

echo ansmap_to_string($amp);
