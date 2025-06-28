<?php

declare(strict_types=1);

dataset('jsonOrgPass', function () {
    $pattern = __DIR__ . '/../JSONOrg/pass*.json';
    foreach (glob($pattern) as $file) {
        $json = file_get_contents($file, true);
        $expected = json_decode($json, true);
        yield basename($file) => [$json, $expected];
    }
});
