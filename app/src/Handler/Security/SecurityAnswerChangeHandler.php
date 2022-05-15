<?php

namespace App\Handler\Security;

use App\Entity\SecurityAnswer;
use App\Entity\SecurityQuestion;
use App\Entity\User;
use App\Exception\Security\SecurityQuestionNotFound;
use App\Exception\Security\UsernameNotFound;
use App\Repository\SecurityAnswerRepository;
use App\Repository\SecurityQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityAnswerChangeHandler
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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SecurityQuestionRepository
     */
    private $securityQuestionRepository;

    public function __construct(
        EntityManagerInterface $emi,
        SecurityAnswerRepository $securityAnswerRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        SecurityQuestionRepository $securityQuestionRepository
    ) {
        $this->emi = $emi;
        $this->securityAnswerRepository = $securityAnswerRepository;
        $this->passwordEncoder = $passwordEncoder;

        $this->userRepository = $userRepository;
        $this->securityQuestionRepository = $securityQuestionRepository;
    }

    public function getUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): SecurityAnswer
    {
        // Retrieving/Creating Security Answer
        $securityAnswer = $this->securityAnswerRepository->findOneBy(['user' => $user, 'question' => $question]);
        if (empty($securityAnswer)) {
            $securityAnswer = new SecurityAnswer();
    
            $securityAnswer->setUser($user);
            $securityAnswer->setQuestion($question);
        }

        $safeAnswer = strtolower(trim($answer));
        if (empty($safeAnswer)) {
            return false;
        }

        $securityAnswer->setAnswer(
            $this->passwordEncoder
                    ->encodePassword($user, $safeAnswer)
        );

        return $securityAnswer;
    }

    public function setUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): bool
    {
        $securityAnswer = $this->getUserSecurityAnswer($user, $question, $answer);

        $this->emi->persist($securityAnswer);
        $this->emi->flush();

        // XXX Revoke all sessions?

        return true;
    }

    public function setUserSecurityAnswers(User $user, array $questions, array $answers): bool
    {
        $nbQuestions = sizeof($questions);
        if ($nbQuestions === 0 || $nbQuestions !== sizeof($answers)) {
            return false;
        }

        for ($i = 0; $i < $nbQuestions; $i++) {
            $securityAnswer = $this->getUserSecurityAnswer($user, $questions[$i], $answers[$i]);

            $this->emi->persist($securityAnswer);
        }
        $this->emi->flush();

        // XXX Revoke all sessions?

        return true;
    }


    public function findUserSecurityAnswer(string $username, string $question, String $answer): SecurityAnswer
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new UsernameNotFound();
        }
        if (empty($securityQuestion)) {
            throw new SecurityQuestionNotFound();
        }

        return $this->getUserSecurityAnswer($user, $securityQuestion, $answer);
    }

    public function saveUserSecurityAnswer(string $username, string $question, String $answer): bool
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new UsernameNotFound();
        }
        if (empty($securityQuestion)) {
            throw new SecurityQuestionNotFound();
        }

        return $this->setUserSecurityAnswer($user, $securityQuestion, $answer);
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
