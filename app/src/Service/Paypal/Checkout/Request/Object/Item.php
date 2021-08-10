<?php


namespace App\Service\Paypal\Checkout\Request\Object;

class Item implements \JsonSerializable
{
    private $unitAmount;

    private $name;

    private $quantity;

    public function getUnitAmount(): ?Amount
    {
        return $this->unitAmount;
    }

    /**
     * Set item amount.
     *
     * @param Amount $unitAmount the amount of the item.
     *
     * @return static
     */
    public function setUnitAmount(?Amount $unitAmount): self
    {
        $this->unitAmount = $unitAmount;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set item name.
     *
     * @param string $name the item name.
     *
     * @return static
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * Set item quantity.
     *
     * @param int|null $quantity
     *
     * @return static
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{unit_amount?: mixed, quantity?: string, name?: mixed}
     */
    public function jsonSerialize(): array
    {
        $data = [];

        $this->unitAmount ? $data['unit_amount'] = $this->unitAmount->jsonSerialize() : null;
        $this->quantity ? $data['quantity'] = (string) $this->quantity : null;
        $this->name ? $data['name'] = $this->name : null;

        return $data;
    }

    public static function create(
        string $name,
        int $quantity,
        string $price,
        string $currencyCode
    ): self {
        $item = new self();

        $amount = (new Amount())
            ->setCurrencyCode($currencyCode)
            ->setValue($price);

        $item
            ->setName($name)
            ->setUnitAmount($amount)
            ->setQuantity($quantity);

        return $item;
    }
}
