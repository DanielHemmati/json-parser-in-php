<?php

declare(strict_types=1);

dataset('jsonOrgFail', function () {
    $pattern = __DIR__ . '/../JSONOrg/fail*.json';
    foreach (glob($pattern) as $file) {
        yield basename($file) => [file_get_contents($file, true)];
    }
});
