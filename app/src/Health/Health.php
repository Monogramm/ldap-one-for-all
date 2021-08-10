<?php

namespace App\Health;

use JsonSerializable;

class Health implements JsonSerializable
{
    /**
     * Status indicating that the component or subsystem is in an unknown state.
     */
    public const UNKNOWN = 'UNKNOWN';
    /**
     * Status indicating that the component or subsystem is functioning as
     * expected.
     */
    public const UP = 'UP';
    /**
     * Status indicating that the component or subsystem has suffered an
     * unexpected failure.
     */
    public const DOWN = 'DOWN';
    /**
     * Status indicating that the component or subsystem has been taken out of
     * service and should not be used.
     */
    public const OUT_OF_SERVICE = 'OUT_OF_SERVICE';

    /**
     * Default order to aggregate status.
     */
    public const DEFAULT_ORDER = [
        self::DOWN,
        self::OUT_OF_SERVICE,
        self::UP,
        self::UNKNOWN,
    ];

    private string $status;

    private array $details;

    public function __construct(string $status, ?array $details = [])
    {
        $this->status = $status;
        $this->details = $details;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return static
     */
    private function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * Set health detail for given throwable.
     *
     * @param \Throwable $th the exception
     *
     * @return static Health instance
     */
    public function withException(\Throwable $th): self
    {
        if ($th === null) {
            return $this;
        }
        return $this->withDetail('error', $th->getCode() . ': ' . $th->getMessage());
    }

    /**
     * Set detail using given key and value.
     *
     * @param string $key the detail key
     * @param mixed $value the detail value
     *
     * @return static Health instance
     */
    public function withDetail(string $key, $value): self
    {
        $this->details[$key] = $value;
        return $this;
    }

    /**
     * New Health instance with set status to UNKNOWN status.
     *
     * @return static Health instance
     */
    public function unknown(): self
    {
        return $this->setStatus(self::UNKNOWN);
    }

    /**
     * Set status to UP status.
     *
     * @return static Health instance
     */
    public function up(): self
    {
        return $this->setStatus(self::UP);
    }

    /**
     * Set status to DOWN and add details for given Throwable.
     *
     * @param \Throwable $th the exception
     *
     * @return static Health instance
     */
    public function down(\Throwable $th = null): self
    {
        return $this->down()->withException($th);
    }

    /**
     * Set status to OUT_OF_SERVICE.
     *
     * @return static Health instance
     */
    public function outOfService(): self
    {
        return $this->setStatus(self::OUT_OF_SERVICE);
    }

    /**
     * Aggregate health status backed by an ordered status list.
     *
     * @param Health $other the other health instance to aggregate with the current one
     * @param string[] $order the status aggregation order
     *
     * @return static Health instance
     */
    public function aggregate(Health $other, $order = self::DEFAULT_ORDER): self
    {
        $aggrStatus = self::aggregator($this, $other, $order);

        return $this->setStatus($aggrStatus);
    }

    /**
     * Aggregate 2 health statuses backed by an ordered status list.
     *
     * @param Health $first the first health instance to aggregate with the current one
     * @param Health $second the other health instance to aggregate with the current one
     * @param string[] $order the status aggregation order
     *
     * @return string Health instance
     */
    public static function aggregator(Health $first, Health $second, $order = self::DEFAULT_ORDER): string
    {
        $firstStatus = array_search($first->getStatus(), $order);
        if ($firstStatus === false) {
            $firstStatus = 0;
        }

        $secondStatus = array_search($second->getStatus(), $order);
        if ($secondStatus === false) {
            $secondStatus = 0;
        }

        $aggrIndex = min($firstStatus, $secondStatus);
        return $order[$aggrIndex];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $array = [
            'status' => $this->status,
        ];

        if (!empty($this->details)) {
            $array['details'] = $this->details;
        }

        return $array;
    }
}
