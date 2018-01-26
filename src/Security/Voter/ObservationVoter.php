<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Observation;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ObservationVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"
    const SHOW   = 'show';
    const EDIT   = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        // this voter is only executed for three specific permissions on Observation objects
        return $subject instanceof Observation && in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true);
    }

    protected function voteOnAttribute($attribute, $observation, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // the user must be logged in; if not, deny permission
        if (!$user instanceof User) {
            return false;
        }

        // the logic of this voter is pretty simple: if the logged user is the
        // author of the given observation, grant permission; otherwise, deny it.
        // (the supports() method guarantees that $observation is a Observation object)
        return $user === $observation->getUser();
    }
}
