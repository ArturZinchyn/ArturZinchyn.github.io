<?php

namespace App\Encoder;

class YamlEncoder implements EncoderInterface
{
    public function supports(string $format): bool
    {
        return $format === 'yaml';
    }

    public function decode(string $data): array {
        $data = trim($data);
        if (empty($data)) return [];

        $result = [];
        $currentEntry = [];
        $lines = explode("\n", $data);

        foreach ($lines as $line) {
            $line = rtrim($line);
            if (str_starts_with(trim($line), '-')) {
                if (!empty($currentEntry)) {
                    $result[] = $currentEntry;
                }
                $currentEntry = [];
                continue;
            }

            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $currentEntry[trim($key)] = trim($value);
            }
        }

        if (!empty($currentEntry)) {
            $result[] = $currentEntry;
        }

        return $result;
    }
    public function encode(array $data): string
    {
        $yaml = "";
        foreach ($data as $row) {
            $yaml .= "- " . "\n";
            foreach ($row as $key => $value) {
                $yaml .= "  " . $key . ": " . $value . "\n";
            }
        }
        return $yaml;
    }
}