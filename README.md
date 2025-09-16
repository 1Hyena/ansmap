# AnsMap Library ###############################################################

AnsMap is a data structure that stores high level instructions for printing ANSI
art to the terminal. The AnsMap Library is accompanied by a set of methods for
ansmap construction and for the conversion of ansmap images to ANSI escape code
sequences.


# API ##########################################################################

* `ansmap_create (width, height)`

    _Creates a memory ansmap sized width by height. The ansmap will have
    clipping turned on, and the clipping rectangle set to the full size of the
    ansmap. The minimum height of the ansmap must be 1 and width can't be
    negative._

    Returns the created ansmap.

    > Examples using this: [ex-drawtext](#ex-drawtext), [ex-sprite](#ex-sprite).

* `ansmap_create_from_text (text, channel = "symbol")`

    _Creates a memory ansmap with the width and height big enough to exactly
    contain the provided "text" string parameter. The channel parameter can be
    either "symbol", "background", "foreground" or "decoration"._

    Returns the created ansmap.

    > Examples using this: [ex-background](#ex-background),
    > [ex-hello](#ex-hello), [ex-palette](#ex-palette), [ex-sprite](#ex-sprite).

* `ansmap_create_from_texts`
    `(background, symbols = "", foreground = "", decoration = "")`

    _Creates a memory ansmap with the width and height big enough to exactly
    contain the provided texts in their respective channels._

    Returns the created ansmap.

    > Examples using this: [ex-palette](#ex-palette), [ex-sprite](#ex-sprite).

* `ansmap_to_string (ansmap)`

    Returns all of the symbols contained in the given ansmap as a sequence of
    ANSI escape codes and UTF-8 encoded text segments.

    > Examples using this: [ex-background](#ex-background),
    > [ex-drawtext](#ex-drawtext), [ex-hello](#ex-hello),
    > [ex-palette](#ex-palette), [ex-sprite](#ex-sprite).

* `ansmap_get_height (ansmap)`

    Returns the height of the specified ansmap.

    > Examples using this: [ex-sprite](#ex-sprite).

* `ansmap_get_width (ansmap)`

    Returns the width of the specified ansmap.

    > Examples using this: [ex-sprite](#ex-sprite).

* `ansmap_blit (src, dst, src_x, src_y, dst_x, dst_y, width, height)`

    _Copies a rectangular area of the source ansmap to the destination ansmap.
    The src_x and src_y parameters are the top left corner of the area to
    copy from the source ansmap, and dst_x and dst_y are the corresponding
    position in the destination ansmap. This routine respects the destination
    clipping rectangle, and it will also clip if you try to blit from areas
    outside the source bitmap._

* `ansmap_draw_sprite (dst, src, x, y)`

    _Draws a copy of the sprite ansmap onto the destination ansmap at the
    specified position. This is almost the same as ansmap_blit(sprite, amp, 0,
    0, x, y, sprite_w, sprite_h), but it uses a masked drawing mode where
    transparent values are skipped, so the background image will show through
    the masked parts of the sprite. Transparent values are marked by a space
    symbol._

    > Examples using this: [ex-sprite](#ex-sprite).

* `ansmap_channel_set_value (ansmap, channel, value, x, y)`

    _Writes a symbol to the specified position in the ansmap. The channel can
    be either "symbol", "background", "foreground" or "decoration"._

* `ansmap_draw_text (ansmap, text, x, y, ...styles)`

    _Writes the string "text" onto the ansmap at position x, y, using the
    specified styles (if any). The styles argument list may contain any
    combination of the following styles: "black", "red", "green", "yellow",
    "blue", "magenta", "cyan", "white", "black-bg", "red-bg", "green-bg",
    "yellow-bg", "blue-bg", "magenta-bg", "cyan-bg", "white-bg", "bold",
    "hidden", "faint", "italic", "underline", "blinking", "strikethrough"._

    > Examples using this: [ex-drawtext](#ex-drawtext).

* `ansmap_channel_swap (ansmap, first, second)`

    _Swaps the "first" and "second" channel contents of the given ansmap. The
    values of the "first" and "second" string parameters could be "symbol",
    "background", "foreground" or "decoration"._

* `ansmap_channel_clear (ansmap, channel)`

    _Clears the contents of the specified channel in the given ansmap. The
    channel can be either "symbol", "background", "foreground" or "decoration"._


# Examples #####################################################################

## ex-hello ####################################################################

https://github.com/1Hyena/ansmap/blob/a22750a0cf41e99fe7f00650230efcfe39618dc8/examples/ex-hello.php#L5-L7

![screenshot](img/ex-hello.png "console output of ex-hello.php")


## ex-background ###############################################################

https://github.com/1Hyena/ansmap/blob/84a20863ff1234b6e1b42e7d8bd559196c99155e/examples/ex-background.php#L5-L17

![screenshot](img/ex-background.png "console output of ex-background.php")


## ex-sprite ###################################################################

https://github.com/1Hyena/ansmap/blob/f71bcb62fbb2cba008a016bec8cca769a622005d/examples/ex-sprite.php#L27-L40

![screenshot](img/ex-sprite.png "console output of ex-sprite.php")


## ex-drawtext #################################################################

https://github.com/1Hyena/ansmap/blob/332b010fc706dfa4540f5590080ebccfb975aa81/examples/ex-drawtext.php#L7-L22

![screenshot](img/ex-drawtext.png "console output of ex-drawtext.php")


## ex-palette ##################################################################

![screenshot](img/ex-palette.png "console output of ex-palette.php")

[ex-palette.php](https://github.com/1Hyena/ansmap/blob/master/examples/ex-palette.php)


# Demos ########################################################################

## amp2ans #####################################################################

The amp2ans demo shows off the ansmap (AMP) file format and how such files could
be converted into ANSI escape sequences for the terminal to display. In the
below screenshot the file contents of [mud.amp](demo/mud.amp) is shown. Then,
that file is given as an input to the
[amp2ans.php](https://github.com/1Hyena/ansmap/blob/master/demo/amp2ans.php)
script which will write the respective ANSI escape sequences into its standard
output.

![screenshot](img/amp2ans.png "console output of amp2ans.php")

In the following screenshot the [punisher.amp](demo/punisher.amp) file from the
_demo_ directory has been given as an input to the _amp2ans.php_ script.

![screenshot](img/punisher.png "ANSI art from the punisher.amp file")

# License ######################################################################

The AnsMap Library has been authored by Erich Erstu and is released under the
[MIT](LICENSE) license.
