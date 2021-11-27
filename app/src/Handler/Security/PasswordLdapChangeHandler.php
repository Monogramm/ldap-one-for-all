<?php


namespace App\Handler\Security;

use App\Entity\User;
use App\Exception\User\CurrentPasswordInvalid;
use App\Service\Ldap\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Ldap\Exception\InvalidCredentialsException;
use Symfony\Component\Ldap\Exception\LdapException;

class PasswordLdapChangeHandler
{
    private $ldap;

    private $emi;

    private $passwordChecker;

    public function __construct(
        Client $ldap,
        EntityManagerInterface $emi,
        PasswordCheckHandler $passwordChecker
    ) {
        $this->ldap = $ldap;
        $this->emi = $emi;
        $this->passwordChecker = $passwordChecker;
    }

    public function handle(string $newPassword, string $confirmPassword, ?string $oldPassword, User $user, string $query = '(objectClass=*)'): bool
    {
        // Should throw appropriate exception if not valid (but check old password ourselves)
        $isValid = $this->passwordChecker->handle($newPassword, $confirmPassword, null, $user);

        if (!$isValid) {
            return false;
        }

        $ldapMeta = $user->getMeta('ldap', []);
        if (!isset($ldapMeta['fullDn'])) {
            return false;
        }
        $fullDN = $ldapMeta['fullDn'];

        if (null !== $oldPassword) {
            // Check old password by binding to LDAP
            try {
                $this->ldap->bind($fullDN, $oldPassword);
            } catch (InvalidCredentialsException $exception) {
                throw new CurrentPasswordInvalid($exception);
            }
        } else {
            $this->ldap->bind();
        }

        // TODO Add parameter in Client LDAP for user password key
        $attributes = [
            'userPassword' => [$newPassword],
        ];
        try {
            $this->ldap->patch($fullDN, $query, $attributes);
        } catch (LdapException $exception) {
            // XXX Should we just throw the exception?
            //return false;
            throw $exception;
        }

        // Revoke all sessions
        foreach ($user->getTokens() as $token) {
            $this->emi->remove($token);
        }

        return true;
    }
}
