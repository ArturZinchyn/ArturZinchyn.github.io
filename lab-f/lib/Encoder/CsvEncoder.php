<?php
namespace App\Encoder;

class CsvEncoder implements EncoderInterface {
    public function supports(string $format): bool {
        return in_array($format, ['csv', 'ssv', 'tsv']);
    }

    public function decode(string $data): array {
        $delimiter = $this->getDelimiter($_POST['input_format'] ?? 'csv');
        $lines = explode("\n", trim($data));
        if (empty($lines[0])) return [];

        $header = str_getcsv(array_shift($lines), $delimiter, '"', '\\');

        $result = [];
        foreach ($lines as $line) {
            $values = str_getcsv($line, $delimiter, '"', '\\');
            if (count($values) === count($header)) {
                $result[] = array_combine($header, $values);
            }
        }
        return $result;
    }

    public function encode(array $data): string {
        if (empty($data)) return "";
        $delimiter = $this->getDelimiter($_POST['output_format']);
        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($data[0]), $delimiter, '"', '\\');
        foreach ($data as $row) {
            fputcsv($output, $row, $delimiter, '"', '\\');
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }

    private function getDelimiter(string $format): string {
        return match ($format) {
            'ssv' => ';',
            'tsv' => "\t",
            default => ',',
        };
    }
}

