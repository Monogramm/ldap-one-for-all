<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ApiToken
{
    use EntityTrait;

    /**
     * @ORM\Column(type="text")
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     */
    private $user;


    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function isNowExpired(): bool
    {
        return $this->isExpiredAt(Carbon::now());
    }

    public function isExpiredAt(\DateTimeInterface $reference): bool
    {
        return $this->getExpiredAt() <= $reference;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
