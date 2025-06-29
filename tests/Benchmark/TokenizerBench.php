<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use JsonParser\Tokenizer;

/** @Revs(50) */
class TokenizerBench
{
    private array $testData;

    public function __construct()
    {
        $this->testData = [
            'tiny' => $this->generateJson(1),
            'small' => $this->generateJson(10),
            'medium' => $this->generateJson(100),
            'large' => $this->generateJson(1000),
            'huge' => $this->generateJson(10000),
            'mixed_types' => $this->generateMixedTypesJson(),
            'deep_nested' => $this->generateDeepNestedJson(),
            'wide_shallow' => $this->generateWideShallowJson(),
            'string_heavy' => $this->generateStringHeavyJson(),
            'number_heavy' => $this->generateNumberHeavyJson(),
        ];
    }

    /**
     * @Groups({"size"})
     */
    public function benchTinyJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['tiny']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"size"})
     */
    public function benchSmallJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['small']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"size"})
     */
    public function benchMediumJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['medium']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"size"})
     */
    public function benchLargeJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['large']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"size"})
     */
    public function benchHugeJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['huge']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"patterns"})
     */
    public function benchMixedTypesJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['mixed_types']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"patterns"})
     */
    public function benchDeepNestedJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['deep_nested']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"patterns"})
     */
    public function benchWideShallowJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['wide_shallow']);
        $this->consumeAllTokens($tokenizer);
    }

     /**
     * @Groups({"patterns"})
     */
    public function benchStringHeavy(): void
    {
        $tokenizer = new Tokenizer('"This is a very long string that will test the tokenizer performance with repeated content."');
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"patterns"})
     */
    public function benchStringHeavyJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['string_heavy']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"patterns"})
     */
    public function benchNumberHeavyJson(): void
    {
        $tokenizer = new Tokenizer($this->testData['number_heavy']);
        $this->consumeAllTokens($tokenizer);
    }

    /**
     * @Groups({"memory"})
     */
    public function benchMemoryEfficient(): void
    {
        $json = $this->generateJson(5000);
        $tokenizer = new Tokenizer($json);
        
        $tokenCount = 0;
        while ($tokenizer->nextToken() !== null) {
            $tokenCount++;
            // Simulate processing without storing tokens
        }
    }

    /**
     * @Groups({"stress"})
     */
    public function benchStressTest(): void
    {
        $json = $this->generateStressTestJson();
        $tokenizer = new Tokenizer($json);
        $this->consumeAllTokens($tokenizer);
    }

    private function consumeAllTokens(Tokenizer $tokenizer): void
    {
        while ($tokenizer->nextToken() !== null) {
            // Continue until all tokens are consumed
        }
    }

    private function generateJson(int $size): string
    {
        $data = [];
        for ($i = 0; $i < $size; $i++) {
            $data["key_$i"] = $this->getRandomValue();
        }
        return json_encode($data);
    }

    private function getRandomValue(): mixed
    {
        $types = ['string', 'number', 'boolean', 'null', 'array', 'object'];
        $type = $types[array_rand($types)];
        
        return match ($type) {
            'string' => 'value_' . uniqid(),
            'number' => rand(-1000, 1000) + (rand(0, 100) / 100),
            'boolean' => (bool)rand(0, 1),
            'null' => null,
            'array' => array_slice(range(1, 10), 0, rand(1, 5)),
            'object' => ['nested' => 'value'],
        };
    }

    private function generateMixedTypesJson(): string
    {
        return json_encode([
            'strings' => ['hello', 'world', 'test'],
            'numbers' => [1, 2.5, -3, 1e6],
            'booleans' => [true, false],
            'nulls' => [null],
            'arrays' => [[1, 2], [3, 4]],
            'objects' => [['a' => 1], ['b' => 2]],
            'mixed' => [
                'string' => 'value',
                'number' => 42,
                'boolean' => true,
                'null' => null,
                'array' => [1, 2, 3],
                'object' => ['nested' => 'value']
            ]
        ]);
    }

    private function generateDeepNestedJson(): string
    {
        $data = 'value';
        for ($i = 0; $i < 20; $i++) {
            $data = ['nested' => $data];
        }
        return json_encode($data);
    }

    private function generateWideShallowJson(): string
    {
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data["key_$i"] = "value_$i";
        }
        return json_encode($data);
    }

    private function generateStringHeavyJson(): string
    {
        $data = [];
        $longString = str_repeat('This is a very long string with many characters. ', 50);
        for ($i = 0; $i < 100; $i++) {
            $data["string_$i"] = $longString . $i;
        }
        return json_encode($data);
    }

    private function generateNumberHeavyJson(): string
    {
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data["num_$i"] = rand(-10000, 10000) + (rand(0, 100) / 100);
        }
        return json_encode($data);
    }

    private function generateStressTestJson(): string
    {
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data["key_$i"] = [
                'string' => str_repeat('stress_test_string_', 10) . $i,
                'number' => rand(-999999, 999999),
                'boolean' => (bool)rand(0, 1),
                'null' => null,
                'array' => range(1, 100),
                'object' => array_fill_keys(range('a', 'z'), 'value'),
                'escaped' => "String with \"quotes\" and \\backslashes\\ and \n\t\r special chars"
            ];
        }
        return json_encode($data);
    }
} 