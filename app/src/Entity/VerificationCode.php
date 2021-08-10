<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VerificationCodeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class VerificationCode
{
    use EntityTrait;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="verificationCode")
     */
    private $user;

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return static
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return static
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
