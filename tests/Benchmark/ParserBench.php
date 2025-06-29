<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use JsonParser\Parser;

/** @Revs(100) */
class ParserBench
{
    public function benchSimpleDocument(): void {
        $parser = new Parser("{}")->parse();
    }

    public function benchComplexDocument(): void {
        $content = file_get_contents(__DIR__.'/Datasets/all.json');
        $parser = new Parser($content)->parse();
    }

    public function benchComplexDocumentJSON(): void {
        $content = file_get_contents(__DIR__.'/Datasets/all.json');
        $parser = json_decode($content);
    }
}
