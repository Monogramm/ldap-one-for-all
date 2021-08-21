<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\Length(min=3)
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
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="ApiToken", mappedBy="user", cascade={"REMOVE"})
     */
    private $tokens;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("admin")
     */
    private $isVerified;

    /**
     * @ORM\OneToOne(targetEntity="VerificationCode", mappedBy="user", cascade={"remove"})
     */
    private $verificationCode;

    /**
     * @var array $metadata Metadata only used by frontend client(s).
     * @ORM\Column(type="json")
     * @Groups("default")
     */
    private $metadata = [];

    public function __construct(string $username = null, string $email = null, $verified = false, $enabled = true)
    {
        $this->username = $username;
        $this->email = $email;
        $this->enabled = $enabled;
        $this->tokens = new ArrayCollection();
        $this->isVerified = $verified;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return static
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return static
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return static
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null
     */
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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return static
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return static
     */
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

        return $roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: string, 1?: string}
     */
    public function getAllowedGroups(): array
    {
        $groups = ['default'];

        if ($this->isAdmin()) {
            $groups[] = 'admin';
        }

        return $groups;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return static
     */
    private function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return static
     */
    public function enable(): self
    {
        return $this->setEnabled(true);
    }

    /**
     * @return static
     */
    public function disable(): self
    {
        return $this->setEnabled(false);
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

    public function isVerified()
    {
        return $this->isVerified;
    }

    /**
     * @return static
     */
    private function setVerified(bool $verified): self
    {
        $this->isVerified = $verified;
        return $this;
    }

    /**
     * @return static
     */
    public function verify(): self
    {
        $this->setVerified(true);

        if (!$this->hasRole('ROLE_VERIFIED_USER')) {
            $this->roles[] = 'ROLE_VERIFIED_USER';
        }

        return $this;
    }

    /**
     * @return static
     */
    public function unverify(): self
    {
        $this->setVerified(false);

        if ($this->hasRole('ROLE_VERIFIED_USER')) {
            // Ensure there are no duplicates AND no holes in array keys
            $roles = [];
            foreach ($this->roles as $role) {
                if ($role !== 'ROLE_VERIFIED_USER' && !in_array($role, $roles)) {
                    $roles[] = $role;
                }
            }
            $this->roles[] = $roles;
        }

        return $this;
    }

    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * Get user metadata.
     * @return array the metadata only used by frontend client(s).
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set the user metadata.
     *
     * @param array $metadata the new user metadata
     *
     * @return static
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get a specific field from the user metadata.
     * @param string $meta the user metadata to retrieve
     * @param mixed $default the default value if not present
     * @return mixed
     */
    public function getMeta(string $meta, $default = null)
    {
        return $this->metadata[$meta] ?? $default;
    }

    /**
     * Set a specific field in the user metadata.
     *
     * @param string $meta the user metadata field to set
     * @param mixed $data the new user metadata
     *
     * @return static
     */
    public function setMeta(string $meta, $data): self
    {
        $this->metadata[$meta] = $data;

        return $this;
    }

    /**
     * Unset a specific field in the user metadata.
     *
     * @param string $meta the user metadata field to unset
     *
     * @return static
     */
    public function unsetMeta(string $meta): self
    {
        unset($this->metadata[$meta]);

        return $this;
    }
}
