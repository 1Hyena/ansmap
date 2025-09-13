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


function ansmap_channel_get_cipher($channel) {
    $mapping = array(
        "background" => array(
            "d" => array("bg"   => 40),
            "r" => array("bg"   => 41),
            "g" => array("bg"   => 42),
            "y" => array("bg"   => 43),
            "b" => array("bg"   => 44),
            "m" => array("bg"   => 45),
            "c" => array("bg"   => 46),
            "w" => array("bg"   => 47),
            "D" => array("bold" => 1, "fg" => 30, "inverse" => 7),
            "R" => array("bold" => 1, "fg" => 31, "inverse" => 7),
            "G" => array("bold" => 1, "fg" => 32, "inverse" => 7),
            "Y" => array("bold" => 1, "fg" => 33, "inverse" => 7),
            "B" => array("bold" => 1, "fg" => 34, "inverse" => 7),
            "M" => array("bold" => 1, "fg" => 35, "inverse" => 7),
            "C" => array("bold" => 1, "fg" => 36, "inverse" => 7),
            "W" => array("bold" => 1, "fg" => 37, "inverse" => 7)
        ),
        "foreground" => array(
            "d" => array("fg"   => 30),
            "r" => array("fg"   => 31),
            "g" => array("fg"   => 32),
            "y" => array("fg"   => 33),
            "b" => array("fg"   => 34),
            "m" => array("fg"   => 35),
            "c" => array("fg"   => 36),
            "w" => array("fg"   => 37),
            "D" => array("bold" => 1, "fg" => 30),
            "R" => array("bold" => 1, "fg" => 31),
            "G" => array("bold" => 1, "fg" => 32),
            "Y" => array("bold" => 1, "fg" => 33),
            "B" => array("bold" => 1, "fg" => 34),
            "M" => array("bold" => 1, "fg" => 35),
            "C" => array("bold" => 1, "fg" => 36),
            "W" => array("bold" => 1, "fg" => 37)
        ),
        "decoration" => array(
            "#" => array("hidden"           => 8),
            "?" => array("faint"            => 2),
            "/" => array("italic"           => 3),
            "_" => array("underline"        => 4),
            "*" => array("blinking"         => 5),
            "-" => array("strikethrough"    => 9),
            "0" => array("blinking" => 5, "strikethrough" => 9),
            "1" => array("underline" => 4, "strikethrough" => 9),
            "2" => array("underline" => 4, "blinking" => 5),
            "3" => array(
                "underline" => 4, "blinking" => 5, "strikethrough" => 9
            ),
            "4" => array("italic" => 3, "strikethrough" => 9),
            "5" => array("italic" => 3, "blinking" => 5),
            "6" => array("italic" => 3, "blinking" => 5, "strikethrough" => 9),
            "7" => array("italic" => 3, "underline" => 4),
            "8" => array("italic" => 3, "underline" => 4, "strikethrough" => 9),
            "9" => array("italic" => 3, "underline" => 4, "blinking" => 5),
            "A" => array(
                "italic" => 3, "underline" => 4, "blinking" => 5,
                "strikethrough" => 9
            ),
            "B" => array("faint" => 2, "strikethrough" => 9),
            "C" => array("faint" => 2, "blinking" => 5),
            "D" => array("faint" => 2, "blinking" => 5, "strikethrough" => 9),
            "E" => array("faint" => 2, "underline" => 4),
            "F" => array("faint" => 2, "underline" => 4, "strikethrough" => 9),
            "G" => array("faint" => 2, "underline" => 4, "blinking" => 5 ),
            "H" => array(
                "faint" => 2, "underline" => 4, "blinking" => 5,
                "strikethrough" => 9
            ),
            "I" => array("faint" => 2, "italic" => 3 ),
            "J" => array("faint" => 2, "italic" => 3, "strikethrough" => 9),
            "K" => array("faint" => 2, "italic" => 3, "blinking" => 5),
            "L" => array(
                "faint" => 2, "italic" => 3, "blinking" => 5,
                "strikethrough" => 9
            ),
            "M" => array("faint" => 2, "italic" => 3, "underline" => 4),
            "N" => array(
                "faint" => 2, "italic" => 3, "underline" => 4,
                "strikethrough" => 9
            ),
            "O" => array(
                "faint" => 2, "italic" => 3, "underline" => 4, "blinking" => 5
            ),
            "P" => array(
                "faint" => 2, "italic" => 3, "underline" => 4, "blinking" => 5,
                "strikethrough" => 9
            )
        )
    );

    return array_key_exists($channel, $mapping) ? $mapping[$channel] : array();
}

function ansmap_channel_decode($channel, $value) {
    $mapping = ansmap_channel_get_cipher($channel);

    return array_key_exists($value, $mapping) ? $mapping[$value] : array();
}

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
        "decoration"    => " "
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

function ansmap_draw_text(&$amp, $txt, $x, $y, $decoration = null) {
    $lines = explode("\n", $txt);
    $sym_y = $y;

    if (is_array($decoration)) {
        $cipher = ansmap_channel_get_cipher("decoration");
        $best_score = null;
        $best_key = " ";

        if (in_array("hidden", $decoration, true)) {
            $decoration = array("hidden");
        }

        foreach ($cipher as $key => $dict) {
            $score = 0;

            for ($i = 0; $i < count($decoration); $i++) {
                $var = $decoration[$i];

                if (array_key_exists($var, $dict)) {
                    $score++;
                }
            }

            if ($best_score == null || $best_score < $score) {
                $best_key = $key;
                $best_score = $score;
            }

            if ($score >= count($decoration)) {
                break;
            }
        }

        $decoration = $best_key;
    }
    else $decoration = " ";

    foreach ($lines as $line) {
        $symbols = mb_str_split($line);
        $sym_x = $x;

        foreach ($symbols as $sym) {
            ansmap_channel_set_value($amp, "symbol", $sym, $sym_x, $sym_y);
            ansmap_channel_set_value(
                $amp, "decoration", $decoration, $sym_x, $sym_y
            );

            $sym_x++;
        }

        $sym_y++;
    }
}

function ansmap_create_from_text($txt, $channel = "symbol") {
    $lines = explode("\n", rtrim($txt, "\n"));
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
    $background_colors, $symbols = "", $foreground_colors = "", $decoration = ""
) {
    $amp_bgc = ansmap_create_from_text($background_colors,  "background");
    $amp_sym = ansmap_create_from_text($symbols,            "symbol");
    $amp_fgc = ansmap_create_from_text($foreground_colors,  "foreground");
    $amp_dec = ansmap_create_from_text($decoration,         "decoration");

    $amp = ansmap_create(
        max(
            ansmap_get_width($amp_bgc),
            ansmap_get_width($amp_sym),
            ansmap_get_width($amp_fgc),
            ansmap_get_width($amp_dec)
        ),
        max(
            ansmap_get_height($amp_bgc),
            ansmap_get_height($amp_sym),
            ansmap_get_height($amp_fgc),
            ansmap_get_height($amp_dec)
        )
    );

    ansmap_draw_sprite($amp, $amp_bgc, 0, 0);
    ansmap_draw_sprite($amp, $amp_sym, 0, 0);
    ansmap_draw_sprite($amp, $amp_fgc, 0, 0);
    ansmap_draw_sprite($amp, $amp_dec, 0, 0);

    return $amp;
}

function ansmap_to_string(&$amp) {
    $str = "";
    $h = ansmap_get_height($amp);
    $w = ansmap_get_width($amp);

    for ($y = 0; $y < $h; ++$y) {
        $line_setup = array();

        for ($x = 0; $x < $w; ++$x) {
            $next_setup = array();

            foreach ($amp[$y][$x] as $chan => $value) {
                $chan_setup = ansmap_channel_decode($chan, $value);

                if (array_key_exists("inverse", $chan_setup)
                && !array_key_exists("inverse", $next_setup)) {
                    if (array_key_exists("fg", $next_setup)) {
                        $next_setup["bg"] = $next_setup["fg"] + 10;
                        unset($next_setup["fg"]);
                    }
                }

                if (array_key_exists("inverse", $next_setup)
                && !array_key_exists("inverse", $chan_setup)) {
                    if (array_key_exists("fg", $chan_setup)) {
                        $chan_setup["bg"] = $chan_setup["fg"] + 10;
                        unset($chan_setup["fg"]);
                    }
                }

                $next_setup = array_merge($next_setup, $chan_setup);
            }

            $reset = false;
            $sequence = array();

            foreach ($line_setup as $key => $value) {
                if (!array_key_exists($key, $next_setup)) {
                    $reset = true;
                    break;
                }
            }

            if ($reset == false) {
                foreach ($next_setup as $key => $value) {
                    if (!array_key_exists($key, $line_setup)
                    || $line_setup[$key] !== $value) {
                        $sequence[] = $value;
                    }
                }
            }
            else {
                foreach ($next_setup as $key => $value) {
                    $sequence[] = $value;
                }
            }

            if (count($sequence) > 0) {
                $str .= "\x1B[".($reset ? "0;" : "").implode(
                    ";", $sequence
                )."m";
            }
            else if ($reset) {
                $str .= "\x1B[0m";
            }

            $str .= $amp[$y][$x]["symbol"];

            if ($reset) {
                $line_setup = $next_setup;
            }
            else {
                $line_setup = array_merge($line_setup, $next_setup);
            }
        }

        if (!empty($line_setup)) {
            $str .= "\x1B[0m";
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
