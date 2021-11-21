<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Security Question entity.
 *
 * @see https://cheatsheetseries.owasp.org/cheatsheets/Choosing_and_Using_Security_Questions_Cheat_Sheet.html
 *
 * @ORM\Entity(repositoryClass="App\Repository\SecurityQuestionRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("name")
 */
class SecurityQuestion
{
    use EntityTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @var array $question Question translation by locale.
     * Expected to be an associative array with the key being the locale
     * and value being the i18n question.
     *
     * @ORM\Column(type="json")
     * @Groups("default")
     */
    private $i18n = [];

    /**
     * @ORM\OneToMany(targetEntity="SecurityAnswer", mappedBy="question", cascade={"REMOVE"})
     */
    private $securityAnswers;

    public function __construct(string $name = null, $i18n = [])
    {
        $this->name = $name;
        $this->i18n = $i18n;
        $this->securityAnswers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get question internationalization.
     * @return array the question only used by frontend client(s).
     */
    public function getI18n()
    {
        return $this->i18n;
    }

    /**
     * Set the question internationalization.
     *
     * @param array $i18n the new question internationalization
     *
     * @return static
     */
    public function setI18n(array $i18n): self
    {
        $this->i18n = $i18n;

        return $this;
    }

    /**
     * Get a specific translation from the question.
     * @param string $locale the locale to retrieve
     * @return mixed
     */
    public function getI18nQuestion(string $locale)
    {
        return $this->i18n[$locale];
    }

    /**
     * Set a specific translation in the question.
     *
     * @param string $locale the locale to set
     * @param mixed $question the new question translation
     *
     * @return static
     */
    public function setI18nQuestion(string $locale, $question): self
    {
        $this->i18n[$locale] = $question;

        return $this;
    }

    /**
     * Unset a specific locale in the question.
     *
     * @param string $locale the question locale to unset
     *
     * @return static
     */
    public function unsetI18nQuestion(string $locale): self
    {
        unset($this->i18n[$locale]);

        return $this;
    }

    public function getSecurityAnswers()
    {
        return $this->securityAnswers;
    }

    public function addSecurityAnswer(SecurityAnswer $answer): void
    {
        $this->securityAnswers->add($answer);
        $answer->setQuestion($this);
    }
}
