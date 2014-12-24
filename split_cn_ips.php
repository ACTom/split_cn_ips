<?php

    if ($_GET['new']) {
        $content = file_get_contents('http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest');
        file_put_contents('delegated-apnic-latest', $content);
    } else {
        $content = file_get_contents('delegated-apnic-latest');
    }
    $lines = explode("\n", $content);
    $out = array();
    foreach ($lines as $line) {
        $line_arr = explode('|', $line);
        if (count($line_arr) < 7) {
            continue;
        }
        if ($line_arr[1] != 'CN') {
            continue;
        }
        if ($line_arr[2] != 'ipv4') {
            continue;
        }
        $mask = 32 - log($line_arr[4], 2);
        array_push($out, $line_arr[3] . '/' . $mask);
    }
    $split_str = php_sapi_name() == 'cli' ? "\n" : "<br />";
    echo implode($split_str, $out);