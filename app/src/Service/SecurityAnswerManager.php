<?php

namespace App\Service;

use App\Entity\SecurityAnswer;
use App\Entity\SecurityQuestion;
use App\Entity\User;
use App\Repository\SecurityAnswerRepository;
use App\Repository\SecurityQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityAnswerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $emi;

    /**
     * @var SecurityAnswerRepository
     */
    private $securityAnswerRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SecurityQuestionRepository
     */
    private $securityQuestionRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $emi,
        SecurityAnswerRepository $securityAnswerRepository,
        UserRepository $userRepository,
        SecurityQuestionRepository $securityQuestionRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->emi = $emi;
        $this->securityAnswerRepository = $securityAnswerRepository;
        $this->userRepository = $userRepository;
        $this->securityQuestionRepository = $securityQuestionRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function setUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): SecurityAnswer
    {
        // Retrieving/Creating Security Answer
        $securityAnswer = $this->securityAnswerRepository->findOneBy(['user' => $user, 'question' => $question]);
        if (empty($securityAnswer)) {
            $securityAnswer = new SecurityAnswer();
    
            $securityAnswer->setUser($user);
            $securityAnswer->setQuestion($question);
        }

        $safeAnswer = strtolower(trim($answer));
        $securityAnswer->setAnswer(
            $this->passwordEncoder
                    ->encodePassword($user, $safeAnswer)
        );

        return $securityAnswer;
    }

    public function getAndSetUserSecurityAnswer(string $username, string $question, String $answer): SecurityAnswer
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new \RuntimeException('No user found with this username');
        }
        if (empty($securityQuestion)) {
            throw new \RuntimeException('No security question found with this name');
        }

        return $this->setUserSecurityAnswer($user, $securityQuestion, $answer);
    }

    public function saveUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): bool
    {
        $securityAnswer = $this->setUserSecurityAnswer($user, $question, $answer);

        $this->emi->persist($securityAnswer);
        $this->emi->flush();

        return true;
    }

    public function getAndSaveUserSecurityAnswer(string $username, string $question, String $answer): bool
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new \RuntimeException('No user found with this username');
        }
        if (empty($securityQuestion)) {
            throw new \RuntimeException('No security question found with this name');
        }

        return $this->saveUserSecurityAnswer($user, $securityQuestion, $answer);
    }

    public function checkUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): SecurityAnswer
    {
        // Retrieving/Creating Security Answer
        $securityAnswer = $this->securityAnswerRepository->findOneBy(['user' => $user, 'question' => $question]);
        if (empty($securityAnswer)) {
            return false;
        }

        $safeAnswer = strtolower(trim($answer));
        $encodedAnswer = $this->passwordEncoder
                    ->isPasswordValid($user, $safeAnswer);

        return $securityAnswer->getAnswer() === $encodedAnswer;
    }

    public function getAndCheckUserSecurityAnswer(string $username, string $question, String $answer): bool
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new \RuntimeException('No user found with this username');
        }
        if (empty($securityQuestion)) {
            throw new \RuntimeException('No security question found with this name');
        }

        return $this->checkUserSecurityAnswer($user, $securityQuestion, $answer);
    }

    private function findByUsername(String $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    private function findByName(String $name): ?SecurityQuestion
    {
        return $this->securityQuestionRepository->findOneBy(['name' => $name]);
    }
}
