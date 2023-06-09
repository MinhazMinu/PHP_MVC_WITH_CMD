<?php
function show($arr, $die = false)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if ($die) {
        die();
    }
}
