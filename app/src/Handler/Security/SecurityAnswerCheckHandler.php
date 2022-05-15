<?php

namespace App\Handler\Security;

use App\Entity\SecurityQuestion;
use App\Entity\User;
use App\Exception\Security\SecurityQuestionNotFound;
use App\Exception\Security\UsernameNotFound;
use App\Repository\SecurityAnswerRepository;
use App\Repository\SecurityQuestionRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityAnswerCheckHandler
{
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
        SecurityQuestionRepository $securityQuestionRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        SecurityAnswerRepository $securityAnswerRepository,
        UserRepository $userRepository
    ) {
        $this->securityQuestionRepository = $securityQuestionRepository;
        $this->passwordEncoder = $passwordEncoder;

        $this->securityAnswerRepository = $securityAnswerRepository;
        $this->userRepository = $userRepository;
    }

    public function checkUserSecurityAnswer(User $user, SecurityQuestion $question, String $answer): bool
    {
        // Retrieving Security Answer
        $securityAnswer = $this->securityAnswerRepository->findOneBy(['user' => $user, 'question' => $question]);
        if (empty($securityAnswer)) {
            return false;
        }

        $safeAnswer = strtolower(trim($answer));
        $encodedAnswer = $this->passwordEncoder
                    ->encodePassword($user, $safeAnswer);

        return $securityAnswer->getAnswer() === $encodedAnswer;
    }

    public function checkUserSecurityAnswers(User $user, array $questions, array $answers): bool
    {
        $nbQuestions = sizeof($questions);
        $allCheck = $nbQuestions > 0 && $nbQuestions === sizeof($answers);

        for ($i = 0; $allCheck && $i < $nbQuestions; $i++) {
            $allCheck = $this->checkUserSecurityAnswer($user, $questions[$i], $answers[$i]);
        }

        return $allCheck;
    }


    public function getAndCheckUserSecurityAnswer(string $username, string $question, String $answer): bool
    {
        $user = $this->findByUsername($username);
        $securityQuestion = $this->findByName($question);
        if (empty($user)) {
            throw new UsernameNotFound();
        }
        if (empty($securityQuestion)) {
            throw new SecurityQuestionNotFound();
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
