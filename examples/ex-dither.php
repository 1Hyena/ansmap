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
    "w" => array(128,128,128),
    "D" => array( 64, 64, 64),
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

                $generated[] = array(
                    "rgb" => array($r, $g, $b),
                    "bgc" => $bg_key,
                    "fgc" => $fg_key,
                    "sym" => $dit_key
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

$dither_palette = generate_dither_palette($palette, $dither);

$txt = "";
$txt_bg = "";
$txt_fg = "";

for ($r = 0; $r <= 256; $r += 32) {
    for ($g = 0; $g <= 256; $g += 32) {
        for ($b = 0; $b <= 256; $b += 32) {
            $info = palette_lookup($dither_palette, $r, $g, $b);
            $txt_bg .= $info["bgc"];
            $txt_fg .= $info["fgc"];
            $txt .= $info["sym"];
        }

    }

    $txt_bg .= "\n";
    $txt_fg .= "\n";
    $txt .= "\n";
}

$amp = ansmap_create_from_texts($txt_bg, $txt, $txt_fg);

echo ansmap_to_string($amp);
