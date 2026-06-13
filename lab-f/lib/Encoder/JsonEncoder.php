<?php
namespace App\Encoder;

class JsonEncoder implements EncoderInterface {
    public function supports(string $format): bool {
        return $format === 'json';
    }

    public function decode(string $data): array {
        $trimmedData = trim($data);
        if (empty($trimmedData)) {
            return [];
        }

        $decoded = json_decode($trimmedData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $decoded ?? [];
    }

    public function encode(array $data): string {
        if (empty($data)) {
            return "[]";
        }

        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($encoded === false) {
            return "[]";
        }

        return $encoded;
    }
}