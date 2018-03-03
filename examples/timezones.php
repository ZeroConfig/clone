<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

require_once __DIR__ . '/../vendor/autoload.php';

$winter                    = new stdClass();
$winter->amsterdam         = new stdClass();
$winter->holland           = $winter->amsterdam;
$winter->amsterdam->offset = '+1';

$summer                  = 🗐($winter);
$summer->holland->offset = '+2';

echo json_encode(
    [
        'winter' => $winter,
        'summer' => $summer
    ],
    JSON_PRETTY_PRINT
) . PHP_EOL;
