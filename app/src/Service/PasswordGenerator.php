<?php


namespace App\Service;

class PasswordGenerator
{
    public const ALPHA_LOWER = 'qwertyuiopasdfghjklzxcvbnm';
    public const ALPHA_UPPER = 'QWERTYUIOPASDFGHJKLZXCVBNM';
    public const NUM = '1234567890';
    public const SAFE_SPEC_CHARS = '+-*@&#';

    public const ALL_SAFE_CHARS = self::ALPHA_LOWER . self::ALPHA_UPPER . self::NUM . self::SAFE_SPEC_CHARS;

    private $defaultChars;
    private $defaultCharsEndIndex;

    public function __construct(
        $defaultChars = self::ALL_SAFE_CHARS
    ) {
        $this->defaultChars = $defaultChars;
        $this->defaultCharsEndIndex = strlen($defaultChars) - 1;
    }

    public function generate(int $length): string
    {
        $password = '';

        while ($length--) {
            $password .= $this->defaultChars[random_int(0, $this->defaultCharsEndIndex)];
        }

        return $password;
    }
}
