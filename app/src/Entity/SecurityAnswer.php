<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Security Answer entity.
 *
 * Security Answer that a User set for a SecurityQuestion.
 * Answers are expected to be forced to lowercase and then hash it.
 *
 * @see https://cheatsheetseries.owasp.org/cheatsheets/Choosing_and_Using_Security_Questions_Cheat_Sheet.html
 *
 * @ORM\Entity(repositoryClass="App\Repository\SecurityAnswerRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"user", "question"})
 */
class SecurityAnswer
{
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SecurityQuestion", inversedBy="answers")
     * @Assert\NotBlank()
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $answer;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getQuestion(): SecurityQuestion
    {
        return $this->question;
    }

    public function setQuestion(SecurityQuestion $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @return static
     */
    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
