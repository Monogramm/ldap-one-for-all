<?php


namespace App\Entity;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Provides common fields needed in entities.
 */
trait Metadata
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @Groups({"admin", "default"})
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("admin")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("admin")
     */
    protected $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /** @ORM\PreFlush */
    public function touch(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = Carbon::now('UTC');
        }
        $this->updatedAt = Carbon::now('UTC');
    }
}
