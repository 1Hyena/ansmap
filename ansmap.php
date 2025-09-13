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
            $row[] = ansmap_cell_create();
        }

        $ansmap[] = $row;
    }

    return $ansmap;
}

function ansmap_get_height(&$amp) {
    return count($amp);
}

function ansmap_get_width(&$amp) {
    return ansmap_get_height($amp) > 0 ? count($amp[0]) : 0;
}

function ansmap_cell_create() {
    return array(
        "symbol"        => " ",
        "background"    => " ",
        "foreground"    => " ",
        "style"         => " "
    );
}

function ansmap_cell_clone($cell) {
    $clone = ansmap_cell_create();

    foreach ($cell as $key => $value) {
        $clone[$key] = $value;
    }

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

    $buffer = array();

    for ($dy = 0; $dy < $height; ++$dy) {
        if ($src_y + $dy >= count($src) || $dst_y + $dy >= count($dst)) {
            break;
        }

        for ($dx = 0; $dx < $width; ++$dx) {
            if ($src_x + $dx >= count($src[$src_y + $dy])
            ||  $dst_x + $dx >= count($dst[$dst_y + $dy])) {
                break;
            }

            $buffer[] = ansmap_cell_clone(
                $src[$src_y + $dy][$src_x + $dx]
            );
        }
    }

    $i = 0;

    for ($dy = 0; $dy < $height; ++$dy) {
        if ($src_y + $dy >= count($src) || $dst_y + $dy >= count($dst)) {
            break;
        }

        for ($dx = 0; $dx < $width; ++$dx) {
            if ($src_x + $dx >= count($src[$src_y + $dy])
            ||  $dst_x + $dx >= count($dst[$dst_y + $dy])) {
                break;
            }

            $dst[$dst_y + $dy][$dst_x + $dx] = $buffer[$i++];
        }
    }
}

function ansmap_cell_draw(&$dst, &$src) {
    foreach ($src as $key => $value) {
        if ($value === " ") {
            continue;
        }

        $dst[$key] = $value;
    }
}

function ansmap_draw_sprite(&$dst, &$src, $x, $y) {
    // Draws a copy of the sprite ansmap onto the destination ansmap at the
    // specified position. This is almost the same as blit(sprite, amp, 0, 0, x,
    // y, sprite->w, sprite->h), but it uses a masked drawing mode where
    // transparent values are skipped, so the background image will show through
    // the masked parts of the sprite. Transparent values are marked by a space
    // symbol.

    $width = ansmap_get_width($src);
    $height= ansmap_get_height($src);
    $dst_x = $x;
    $dst_y = $y;
    $src_x = 0;
    $src_y = 0;

    $buffer = array();

    for ($dy = 0; $dy < $height; ++$dy) {
        if ($src_y + $dy >= count($src) || $dst_y + $dy >= count($dst)) {
            break;
        }

        for ($dx = 0; $dx < $width; ++$dx) {
            if ($src_x + $dx >= count($src[$src_y + $dy])
            ||  $dst_x + $dx >= count($dst[$dst_y + $dy])) {
                break;
            }

            $buffer[] = ansmap_cell_clone(
                $src[$src_y + $dy][$src_x + $dx]
            );
        }
    }

    $i = 0;

    for ($dy = 0; $dy < $height; ++$dy) {
        if ($src_y + $dy >= count($src) || $dst_y + $dy >= count($dst)) {
            break;
        }

        for ($dx = 0; $dx < $width; ++$dx) {
            if ($src_x + $dx >= count($src[$src_y + $dy])
            ||  $dst_x + $dx >= count($dst[$dst_y + $dy])) {
                break;
            }

            ansmap_cell_draw($dst[$dst_y + $dy][$dst_x + $dx], $buffer[$i++]);
        }
    }
}

function ansmap_channel_set_value(&$amp, $channel, $value, $x, $y) {
    $h = ansmap_get_height($amp);
    $w = ansmap_get_width($amp);

    if ($y < 0 || $y >= $h || $x < 0 || $x >= $w) {
        return;
    }

    $amp[$y][$x][$channel] = $value;
}

function ansmap_draw_text(&$amp, $txt, $x, $y) {
    $lines = explode("\n", $txt);
    $sym_y = $y;

    foreach ($lines as $line) {
        $symbols = mb_str_split($line);
        $sym_x = $x;

        foreach ($symbols as $sym) {
            ansmap_channel_set_value($amp, "symbol", $sym, $sym_x, $sym_y);
            $sym_x++;
        }

        $sym_y++;
    }
}

function ansmap_create_from_text($txt, $channel = "symbol") {
    $lines = explode("\n", $txt);
    $sym_y = 0;

    $width = 0;
    $height = 0;
    $rows = array();

    foreach ($lines as $line) {
        $symbols = mb_str_split($line);
        $sym_x = 0;

        $cells = array();

        foreach ($symbols as $sym) {
            $cells[] = $sym;
            $sym_x++;

            $width = max($width, $sym_x);
        }

        $rows[] = $cells;

        $sym_y++;

        $height = max($height, $sym_y);
    }

    $amp = ansmap_create($width, $height);

    for ($y = 0; $y < count($rows); ++$y) {
        for ($x = 0; $x < count($rows[$y]); ++$x) {
            ansmap_channel_set_value($amp, $channel, $rows[$y][$x], $x, $y);
        }
    }

    return $amp;
}

function ansmap_create_from_texts(
    $background_colors, $symbols = "", $foreground_colors = "", $style = ""
) {
    $amp_bgc = ansmap_create_from_text($background_colors,  "background");
    $amp_sym = ansmap_create_from_text($symbols,            "symbol");
    $amp_fgc = ansmap_create_from_text($foreground_colors,  "foreground");
    $amp_sty = ansmap_create_from_text($style,              "style");

    $amp = ansmap_create(
        max(
            ansmap_get_width($amp_bgc),
            ansmap_get_width($amp_sym),
            ansmap_get_width($amp_fgc),
            ansmap_get_width($amp_sty)
        ),
        max(
            ansmap_get_height($amp_bgc),
            ansmap_get_height($amp_sym),
            ansmap_get_height($amp_fgc),
            ansmap_get_height($amp_sty)
        )
    );

    ansmap_draw_sprite($amp, $amp_bgc, 0, 0);
    ansmap_draw_sprite($amp, $amp_sym, 0, 0);
    ansmap_draw_sprite($amp, $amp_fgc, 0, 0);
    ansmap_draw_sprite($amp, $amp_sty, 0, 0);

    return $amp;
}

function ansmap_to_string(&$amp) {
    $str = "";
    $h = ansmap_get_height($amp);
    $w = ansmap_get_width($amp);

    for ($y = 0; $y < $h; ++$y) {
        for ($x = 0; $x < $w; ++$x) {
            $str .= $amp[$y][$x]["symbol"];
        }

        $str .= "\r\n";
    }

    return $str;
}

function ansmap_cell_channel_swap(&$cell, $c1, $c2) {
    $buf = $cell[$c1];
    $cell[$c1] = $cell[$c2];
    $cell[$c2] = $buf;
}

function ansmap_channel_swap(&$amp, $c1, $c2) {
    $h = ansmap_get_height($amp);
    $w = ansmap_get_width($amp);

    for ($y = 0; $y < $h; ++$y) {
        for ($x = 0; $x < $w; ++$x) {
            ansmap_cell_channel_swap($amp[$y][$x], $c1, $c2);
        }
    }
}

function ansmap_channel_clear(&$amp, $channel) {
    $cell = ansmap_cell_create();

    $h = ansmap_get_height($amp);
    $w = ansmap_get_width($amp);

    for ($y = 0; $y < $h; ++$y) {
        for ($x = 0; $x < $w; ++$x) {
            $amp[$y][$x][$channel] = $cell[$channel];
        }
    }
}

$amp_yolo = ansmap_create_from_texts("DDDD", "YOLO", "RGBW");

$amp_screen = ansmap_create(80, 20);
ansmap_blit($amp_yolo, $amp_screen, 0, 0, 40, 10, 4, 1);

echo ansmap_to_string($amp_screen);
