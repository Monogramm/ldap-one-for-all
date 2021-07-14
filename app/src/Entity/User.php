<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    use EntityTrait;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups("admin")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     * @Groups("admin")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups("admin")
     */
    private $language = 'en';

    /**
     * @ORM\Column(type="json")
     * @Groups("admin")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     * @Groups("admin")
     */
    private $isVerified;

    /**
     * @ORM\OneToMany(targetEntity="ApiToken", mappedBy="user", cascade={"REMOVE"})
     */
    private $tokens;

    /**
     * @ORM\OneToOne(targetEntity="VerificationCode", mappedBy="user", cascade={"remove"})
     */
    private $verificationCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function __construct(string $username = null, string $email = null, $verified = false, $enabled = true)
    {
        $this->tokens = new ArrayCollection();
        $this->username = $username;
        $this->email = $email;
        $this->isVerified = $verified;
        $this->enabled = $enabled;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        // Ensure there are no duplicates AND no holes in array keys
        $roles = [];
        foreach ($this->roles as $role) {
            if (!in_array($role, $roles)) {
                $roles[] = $role;
            }
        }

        // guarantee every enabled user at least has ROLE_USER
        if ($this->isEnabled() && !in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        if ($this->isVerified() && !in_array('ROLE_VERIFIED_USER', $roles)) {
            $roles[] = 'ROLE_VERIFIED_USER';
        }

        return $roles;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function addToken(ApiToken $token): void
    {
        $this->tokens->add($token);
        $token->setUser($this);
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function isVerified()
    {
        return $this->isVerified;
    }

    public function verify(): self
    {
        $this->isVerified = true;

        return $this;
    }

    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    public function getAllowedGroups(): array
    {
        $groups = ['default'];

        if (in_array('ROLE_ADMIN', $this->getRoles(), true)) {
            $groups[] = 'admin';
        }

        return $groups;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->getRoles(), true);
    }
}
