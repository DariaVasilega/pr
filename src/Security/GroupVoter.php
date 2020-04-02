<?php

namespace App\Security;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class GroupVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Group) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Group $group */
        $group = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($group, $user);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Group $group, User $user)
    {
        if ($this->security->isGranted(['ROLE_ADMIN'])) {
            return true;
        } else {
            return $user === $group->getOwner();
        }
    }
}
