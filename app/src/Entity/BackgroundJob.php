<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BackgroundJobRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BackgroundJob
{
    use Metadata;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastExecution;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    public const STATUS_RUNNING = 'running';

    public const STATUS_ERROR = 'error';

    public const STATUS_SUCCESS = 'success';

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastExecution(): ?\DateTimeInterface
    {
        return $this->lastExecution;
    }

    public function setLastExecution(\DateTimeInterface $lastExecution): self
    {
        $this->lastExecution = $lastExecution;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function init(string $name): void
    {
        $this->setName($name);
        $this->setLastExecution(Carbon::now()->setTimezone('UTC'));
    }

    public function running(): void
    {
        $this->setStatus(self::STATUS_RUNNING);
    }

    public function success(): void
    {
        $this->setStatus(self::STATUS_SUCCESS);
    }

    public function error(): void
    {
        $this->setStatus(self::STATUS_ERROR);
    }
}
