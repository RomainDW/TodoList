<?php


namespace AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const DELETE = 'delete';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    public function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE])) {
            return false;
        }
        // only vote on Customer objects inside this voter
        if (!$subject instanceof Task) {
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }
        // you know $subject is a Task object, thanks to supports
        /** @var Task $task */
        $task = $subject;
        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
        }
        throw new LogicException('This code should not be reached!');
    }

    /**
     * @param Task $task
     * @param User $user
     * @return bool
     */
    private function canDelete(Task $task, User $user): bool
    {
        // if the logged user is admin & the owner of the task is anonymous, return true
        if (in_array('ROLE_ADMIN', $user->getRoles()) && $task->getUser()->getEmail() == 'anonymous@anonymous.com') {
            return true;
        }

        // if the logged user own the task, return true
        return $user === $task->getUser();
    }
}
