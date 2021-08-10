<?php


namespace App\Service\Paypal\Checkout\Request\Object;

class Order implements \JsonSerializable
{
    public const INTENT_CAPTURE = 'CAPTURE';

    public const INTENT_AUTHORIZE = 'AUTHORIZE';

    /**
     * @var Context|null
     */
    private $context;

    /**
     * @var PurchaseUnit[]|null
     */
    private $purchaseUnits;

    /**
     * @var string|null
     */
    private $intent;

    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * @return static
     */
    public function setContext(?Context $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return PurchaseUnit[]|null
     *
     * @psalm-return array<array-key, PurchaseUnit>|null
     */
    public function getPurchaseUnits(): ?array
    {
        return $this->purchaseUnits;
    }

    /**
     * @return static
     */
    public function setPurchaseUnits(?array $purchaseUnits): self
    {
        $this->purchaseUnits = $purchaseUnits;
        return $this;
    }

    public function getIntent(): ?string
    {
        return $this->intent;
    }

    /**
     * @return static
     */
    public function setIntent(?string $intent): self
    {
        $this->intent = $intent;
        return $this;
    }

    /**
     * @return ((((array|mixed|string)[]|mixed)[][]|mixed)[]|string)[]
     *
     * @psalm-return array{intent?: string, application_context?: array{return_url?: mixed, cancel_url?: mixed, locale?: mixed, landing_page?: mixed, shipping_preference?: mixed, brand_name?: mixed}, purchase_units?: non-empty-list<array{items?: non-empty-list<array{unit_amount?: mixed, quantity?: string, name?: mixed}>, amount?: array{currency_code?: mixed, value?: mixed, breakdown?: array{item_total: array{currency_code?: mixed, value?: mixed}}}}>}
     */
    public function jsonSerialize(): array
    {
        $data = [];

        $this->intent ? $data['intent'] = $this->intent : null;
        $this->context ? $data['application_context'] = $this->context->jsonSerialize() : null;
        foreach ($this->purchaseUnits as $purchaseUnit) {
            $data['purchase_units'][] = $purchaseUnit->jsonSerialize();
        }

        return $data;
    }

    /**
     * @param string $intent
     * @param PurchaseUnit[] $purchaseUnits
     * @param Context $context
     *
     * @return static
     */
    public static function create(
        string $intent,
        array $purchaseUnits,
        Context $context
    ): self {
        return (new self())
            ->setContext($context)
            ->setIntent($intent)
            ->setPurchaseUnits($purchaseUnits);
    }
}
