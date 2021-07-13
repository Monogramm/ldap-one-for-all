<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PasswordResetCodeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PasswordResetCode
{
    use EntityTrait;

    /**
     * @ORM\Column(type="text")
     */
    private $code;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
