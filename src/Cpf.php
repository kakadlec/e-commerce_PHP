<?php

namespace Store;

use Exception;

class Cpf {
    public $value;
    private $FIRST_DIGIT_FACTOR = 10;
    private $SECOND_DIGIT_FACTOR = 11;

    /**
     * @throws Exception
     */
    public function __construct(string $value)  {
        if (!$this->validate($value)) throw new Exception('Invalid CPF');
        $this->value = $value;
    }

    private function validate(?string $rawValue) {
        if (!$rawValue) return false;
        $cpf = $this->cleanCPF($rawValue);
        if($this->isInvalidLength($cpf)) return false;
        if($this->isIdenticalDigits($cpf)) return false;
        $calculatedCheckDigit1 = $this->calculateCheckDigit($cpf, $this->FIRST_DIGIT_FACTOR);
        $calculatedCheckDigit2 = $this->calculateCheckDigit($cpf, $this->SECOND_DIGIT_FACTOR);
        $checkDigit = $this->extractCheckDigit($cpf);
        $calculatedCheckDigit = "{$calculatedCheckDigit1}{$calculatedCheckDigit2}";
        return $checkDigit === $calculatedCheckDigit;
    }

    private function cleanCPF(string $rawValue): string {
        return preg_replace('/\D/', '', $rawValue);
    }

    private function isInvalidLength(string $cpf): bool
    {
        $length = strlen($cpf);
        return $length !== 11;
    }

    private function isIdenticalDigits(string $cpf): bool
    {
        $firstDigit = $cpf[0];
        $digits = mb_str_split($cpf);
        foreach ($digits as $digit) {
            if($digit !== $firstDigit) return false;
        }
        return true;
    }

    private function calculateCheckDigit(string $cpf, int $factor) {
        $digits = mb_str_split($cpf);
        $total = array_reduce($digits, function($total, $digit) use (&$factor) {
            if ($factor > 1) $total += (int)$digit * $factor--;
            return $total;
        }, 0);
        $rest = $total % 11;
        return ($rest < 2) ? 0 : 11 - $rest;
    }

    private function extractCheckDigit(string $cpf) {
        return substr($cpf, -2);
    }
}