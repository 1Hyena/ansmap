<?php
// MIT License
//
// Copyright (c) 2025 Erich Erstu
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

function ansmap_create($width, $height) {
    $ansmap = array();

    for ($i = 0; $i < $height; ++$i) {
        $row = array();

        for ($j = 0; $j < $width; ++$j) {
            $row[] = ansmap_create_cell();
        }

        $ansmap[] = $row;
    }

    return $ansmap;
}

function ansmap_create_cell() {
    return array(
        "symbol" => " "
    );
}

function ansmap_clone_cell($cell) {
    $clone = ansmap_create_cell();

    $clone["symbol"] = $cell["symbol"];

    return $clone;
}

function ansmap_blit(
    &$src, &$dst, $src_x, $src_y, $dst_x, $dst_y, $width, $height
) {
    // Copies a rectangular area of the source ansmap to the destination ansmap.
    // The src_x and src_y parameters are the top left corner of the area to
    // copy from the source ansmap, and dst_x and dst_y are the corresponding
    // position in the destination ansmap. This routine respects the destination
    // clipping rectangle, and it will also clip if you try to blit from areas
    // outside the source bitmap.

    for ($dy = 0; $dy < $height; ++$dy) {
        if ($src_y + $dy >= count($src) || $dst_y + $dy >= count($dst)) {
            break;
        }

        for ($dx = 0; $dx < $width; ++$dx) {
            if ($src_x + $dx >= count($src[$src_y + $dy])
            ||  $dst_x + $dx >= count($dst[$dst_y + $dy])) {
                break;
            }

            $dst[$dst_y + $dy][$dst_x + $dx] = ansmap_clone_cell(
                $src[$src_y + $dy][$src_x + $dx]
            );
        }
    }
}

function ansmap_draw_symbol(&$amp, $sym, $x, $y) {
    $h = count($amp);

    if ($y < 0 || $y >= $h || $x < 0 || $x >= count($amp[$y])) {
        return;
    }

    $amp[$y][$x]["symbol"] = $sym;
}

function ansmap_draw_text(&$amp, $txt, $x, $y) {
    $lines = explode("\n", $txt);
    $sym_y = $y;

    foreach ($lines as $line) {
        $symbols = mb_str_split($line);
        $sym_x = $x;

        foreach ($symbols as $sym) {
            ansmap_draw_symbol($amp, $sym, $sym_x, $sym_y);
            $sym_x++;
        }

        $sym_y++;
    }
}

function ansmap_to_string(&$amp) {
    $str = "";

    for ($y = 0; $y < count($amp); ++$y) {
        for ($x = 0; $x < count($amp[$y]); ++$x) {
            $str .= $amp[$y][$x]["symbol"];
        }

        $str .= "\r\n";
    }

    return $str;
}

$amp_yolo = ansmap_create(4, 1);
ansmap_draw_text($amp_yolo, "YOLO", 0, 0);

$amp_screen = ansmap_create(80, 20);
ansmap_blit($amp_yolo, $amp_screen, 0, 0, 40, 10, 4, 1);

echo ansmap_to_string($amp_screen);
