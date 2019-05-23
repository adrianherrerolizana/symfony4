<?php
namespace App\Voters;


use App\Entity\Incidencia;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class voterIncidencia extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    private $security;

    public function __construct(Security $security)
    {
	    $this->security = $security;
    }

	protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Incidencia) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

		$user = $token->getUser();

        $incidencia = $subject;

		if ($user == $incidencia->getUser() || $this->security->isGranted('ROLE_ADMIN')){
			return true;
		}

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($incidencia, $user);
            case self::EDIT:
                return $this->canEdit($incidencia, $user);
        }

        throw new \LogicException('This code should not be reached!');

    }

    private function canView(Incidencia $incidencia, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($incidencia, $user)) {
            return true;
        }else{
        	return false;
        }
    }

    private function canEdit(Incidencia $incidencia, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $incidencia->getUser();
    }

}
