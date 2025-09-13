<?php

require_once("../ansmap.php");

$amp = ansmap_create_from_text("Hello World!");

echo ansmap_to_string($amp);
