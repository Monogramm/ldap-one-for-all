<?php


namespace App\Service\Paypal\Checkout\Request\Object;

class Amount implements \JsonSerializable
{
    private $currencyCode;

    private $value;

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * Set amount currency code.
     *
     * @param string $currencyCode Currency code for value.
     *
     * @return static
     */
    public function setCurrencyCode(?string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function getValue() :?string
    {
        return $this->value;
    }

    /**
     * Set amount value.
     *
     * @param string $value Amount value.
     *
     * @return static
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     *
     * @psalm-return array{currency_code?: mixed, value?: mixed}
     */
    public function jsonSerialize(): array
    {
        $data = [];

        $this->currencyCode ? $data['currency_code'] = $this->currencyCode : null;
        $this->value ? $data['value'] = $this->value : null;

        return $data;
    }
}
