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

function esc($str)
{
    return htmlspecialchars($str);
}


// print_r result in a .txt file
