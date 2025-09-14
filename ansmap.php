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


function ansmap_get_ansi_code_table() {
    return array(
        "black"         => 30,
        "red"           => 31,
        "green"         => 32,
        "yellow"        => 33,
        "blue"          => 34,
        "magenta"       => 35,
        "cyan"          => 36,
        "white"         => 37,
        "black-bg"      => 40,
        "red-bg"        => 41,
        "green-bg"      => 42,
        "yellow-bg"     => 43,
        "blue-bg"       => 44,
        "magenta-bg"    => 45,
        "cyan-bg"       => 46,
        "white-bg"      => 47,
        "bold"          => 1,
        "inverse"       => 7,
        "hidden"        => 8,
        "faint"         => 2,
        "italic"        => 3,
        "underline"     => 4,
        "blinking"      => 5,
        "strikethrough" => 9
    );
}

function ansmap_channel_get_cipher($channel) {
    $mapping = array(
        "background" => array(
            "d" => array("black-bg"),
            "r" => array("red-bg"),
            "g" => array("green-bg"),
            "y" => array("yellow-bg"),
            "b" => array("blue-bg"),
            "m" => array("magenta-bg"),
            "c" => array("cyan-bg"),
            "w" => array("white-bg"),
            "D" => array("inverse", "bold", "black"),
            "R" => array("inverse", "bold", "red"),
            "G" => array("inverse", "bold", "green"),
            "Y" => array("inverse", "bold", "yellow"),
            "B" => array("inverse", "bold", "blue"),
            "M" => array("inverse", "bold", "magenta"),
            "C" => array("inverse", "bold", "cyan"),
            "W" => array("inverse", "bold", "white")
        ),
        "foreground" => array(
            "d" => array("black"),
            "r" => array("red"),
            "g" => array("green"),
            "y" => array("yellow"),
            "b" => array("blue"),
            "m" => array("magenta"),
            "c" => array("cyan"),
            "w" => array("white"),
            "D" => array("bold", "black"),
            "R" => array("bold", "red"),
            "G" => array("bold", "green"),
            "Y" => array("bold", "yellow"),
            "B" => array("bold", "blue"),
            "M" => array("bold", "magenta"),
            "C" => array("bold", "cyan"),
            "W" => array("bold", "white")
        ),
        "decoration" => array(
            "#" => array("hidden"),
            "?" => array("faint"),
            "/" => array("italic"),
            "_" => array("underline"),
            "*" => array("blinking"),
            "-" => array("strikethrough"),
            "0" => array("blinking", "strikethrough"),
            "1" => array("underline", "strikethrough"),
            "2" => array("underline", "blinking"),
            "3" => array("underline", "blinking", "strikethrough"),
            "4" => array("italic", "strikethrough"),
            "5" => array("italic", "blinking"),
            "6" => array("italic", "blinking", "strikethrough"),
            "7" => array("italic", "underline"),
            "8" => array("italic", "underline", "strikethrough"),
            "9" => array("italic", "underline", "blinking"),
            "A" => array("italic", "underline", "blinking", "strikethrough"),
            "B" => array("faint", "strikethrough"),
            "C" => array("faint", "blinking"),
            "D" => array("faint", "blinking", "strikethrough"),
            "E" => array("faint", "underline"),
            "F" => array("faint", "underline", "strikethrough"),
            "G" => array("faint", "underline", "blinking" ),
            "H" => array("faint", "underline", "blinking", "strikethrough"),
            "I" => array("faint", "italic" ),
            "J" => array("faint", "italic", "strikethrough"),
            "K" => array("faint", "italic", "blinking"),
            "L" => array("faint", "italic", "blinking", "strikethrough"),
            "M" => array("faint", "italic", "underline"),
            "N" => array("faint", "italic", "underline","strikethrough"),
            "O" => array("faint", "italic", "underline", "blinking"),
            "P" => array(
                "faint", "italic", "underline", "blinking","strikethrough"
            )
        )
    );

    return array_key_exists($channel, $mapping) ? $mapping[$channel] : array();
}

function ansmap_channel_decode($channel, $value) {
    $mapping = ansmap_channel_get_cipher($channel);
    $decoded = array();

    if (array_key_exists($value, $mapping)) {
        $code_table = ansmap_get_ansi_code_table();

        foreach ($mapping[$value] as $codename) {
            if (array_key_exists($codename, $code_table)) {
                $code = $code_table[$codename];

                if ($code >= 30 && $code <= 37) {
                    $decoded["fg"] = $code;
                }
                else if ($code >= 40 && $code <= 47) {
                    $decoded["bg"] = $code;
                }
                else {
                    $decoded[$codename] = $code;
                }
            }
        }
    }

    return $decoded;
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

function ansmap_draw_text(&$amp, $txt, $x, $y, ...$txt_styles) {
    $lines = explode("\n", $txt);
    $sym_y = $y;
    $decoration = " ";

    $style_to_channel_map = array(
        "bold"          => "foreground",
        "black"         => "foreground",
        "red"           => "foreground",
        "green"         => "foreground",
        "yellow"        => "foreground",
        "blue"          => "foreground",
        "magenta"       => "foreground",
        "cyan"          => "foreground",
        "white"         => "foreground",
        "black-bg"      => "background",
        "red-bg"        => "background",
        "green-bg"      => "background",
        "yellow-bg"     => "background",
        "blue-bg"       => "background",
        "magenta-bg"    => "background",
        "cyan-bg"       => "background",
        "white-bg"      => "background",
        "hidden"        => "decoration",
        "faint"         => "decoration",
        "italic"        => "decoration",
        "underline"     => "decoration",
        "blinking"      => "decoration",
        "strikethrough" => "decoration"
    );

    $channel_to_styles_map = array();
    $channel_to_cipher_key = array();

    foreach ($txt_styles as $style) {
        if (!array_key_exists($style, $style_to_channel_map)) {
            continue;
        }

        $channel = $style_to_channel_map[$style];

        if (!array_key_exists($channel, $channel_to_styles_map)) {
            $channel_to_styles_map[$channel] = array();
        }

        $channel_to_styles_map[$channel][] = $style;
    }

    if (array_key_exists("decoration", $channel_to_styles_map)
    && in_array("hidden", $channel_to_styles_map["decoration"], true)) {
        // No text decoration is needed if the text is hidden anyway.
        $channel_to_styles_map["decoration"] = array("hidden");
    }

    foreach ($channel_to_styles_map as $channel_name => $channel_styles) {
        $cipher = ansmap_channel_get_cipher($channel_name);
        $best_score = null;
        $best_key = " ";

        foreach ($cipher as $cipher_key => $cipher_styles) {
            $score = 0;

            for ($i = 0; $i < count($channel_styles); $i++) {
                $var = $channel_styles[$i];

                if (in_array($var, $cipher_styles, true)) {
                    $score++;
                }
            }

            if ($best_score == null || $best_score < $score) {
                $best_key = $cipher_key;
                $best_score = $score;
            }

            if ($score >= count($channel_styles)) {
                break;
            }
        }

        $channel_to_cipher_key[$channel_name] = $best_key;
    }

    foreach ($lines as $line) {
        $symbols = mb_str_split($line);
        $sym_x = $x;

        foreach ($symbols as $sym) {
            ansmap_channel_set_value($amp, "symbol", $sym, $sym_x, $sym_y);

            foreach ($channel_to_cipher_key as $channel_name => $cipher_key) {
                ansmap_channel_set_value(
                    $amp, $channel_name, $cipher_key, $sym_x, $sym_y
                );
            }

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
