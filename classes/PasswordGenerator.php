<?php

class PasswordGenerator
{
    private int $length;
    private int $lowercase;
    private int $uppercase;
    private int $numbers;
    private int $specials;

    public function __construct(int $length, int $lowercase, int $uppercase, int $numbers, int $specials)
    {
        $this->length = $length;
        $this->lowercase = $lowercase;
        $this->uppercase = $uppercase;
        $this->numbers = $numbers;
        $this->specials = $specials;
    }

    public function generate(): string
    {
        $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
        $uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numberChars = '0123456789';
        $specialChars = '!@#$%^&*()-_=+';

        $password = '';

        $password .= $this->getRandomChars($lowercaseChars, $this->lowercase);
        $password .= $this->getRandomChars($uppercaseChars, $this->uppercase);
        $password .= $this->getRandomChars($numberChars, $this->numbers);
        $password .= $this->getRandomChars($specialChars, $this->specials);

        if (strlen($password) < $this->length) {
            $allChars = $lowercaseChars . $uppercaseChars . $numberChars . $specialChars;
            $password .= $this->getRandomChars($allChars, $this->length - strlen($password));
        }

        return str_shuffle($password);
    }

    private function getRandomChars(string $characters, int $count): string
    {
        $result = '';

        for ($i = 0; $i < $count; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $result .= $characters[$index];
        }

        return $result;
    }
}