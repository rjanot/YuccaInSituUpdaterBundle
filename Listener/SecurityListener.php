<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Yucca\InSituUpdaterBundle\Listener;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yucca\InSituUpdaterBundle\Event\AclCheckEvent;

class SecurityListener
{
    protected $securityAuthorizationChecker;

    public function __construct(AuthorizationCheckerInterface $securityAuthorizationChecker)
    {
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
    }

    /**
     * @param AclCheckEvent $event
     * @throws AccessDeniedException
     */
    public function onAclCheck(AclCheckEvent $event)
    {
        $configuration = $event->getConfiguration();
        if (false === $this->securityAuthorizationChecker->isGranted($configuration['roles'])) {
            throw new AccessDeniedException();
        }

        $event->setHandled();
    }
}
