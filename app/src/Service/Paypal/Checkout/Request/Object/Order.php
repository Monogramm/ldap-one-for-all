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

    public function setContext(?Context $context): Order
    {
        $this->context = $context;
        return $this;
    }

    public function getPurchaseUnits(): ?array
    {
        return $this->purchaseUnits;
    }

    public function setPurchaseUnits(?array $purchaseUnits): Order
    {
        $this->purchaseUnits = $purchaseUnits;
        return $this;
    }

    public function getIntent(): ?string
    {
        return $this->intent;
    }

    public function setIntent(?string $intent): Order
    {
        $this->intent = $intent;
        return $this;
    }

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
     * @return Order
     */
    public static function create(
        string $intent,
        array $purchaseUnits,
        Context $context
    ) {
        return (new self())
            ->setContext($context)
            ->setIntent($intent)
            ->setPurchaseUnits($purchaseUnits);
    }
}
