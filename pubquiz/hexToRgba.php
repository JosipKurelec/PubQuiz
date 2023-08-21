<?php
function hexToRgba($hexColor) {
    $red = hexdec(substr($hexColor, 1, 2));
    $green = hexdec(substr($hexColor, 3, 2));
    $blue = hexdec(substr($hexColor, 5, 2));

    return "rgba($red, $green, $blue, 0.3)";
}
?>