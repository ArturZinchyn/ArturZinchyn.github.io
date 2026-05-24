<?php
namespace App;

class Serializer {
    private array $encoders = [];

    public function addEncoder(\App\Encoder\EncoderInterface $encoder) {
        $this->encoders[] = $encoder;
    }

    public function convert(string $data, string $from, string $to): string {
        $decoder = null;
        $encoder = null;

        foreach ($this->encoders as $e) {
            if ($e->supports($from)) $decoder = $e;
            if ($e->supports($to)) $encoder = $e;
        }

        if (!$decoder || !$encoder) {
            throw new \Exception("Unsupported format");
        }

        $arrayData = $decoder->decode($data);
        return $encoder->encode($arrayData);
    }
}