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

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Yucca\InSituUpdaterBundle\Event\AclCheckEvent;

class SecurityListener {
    protected $yuccaEntityManager;

    public function __construct(SecurityContextInterface $securityContext) {
        $this->securityContext = $securityContext;
    }

    /**
     * @param AclCheckEvent $event
     * @throws AccessDeniedException
     */
    public function onAclCheck(AclCheckEvent $event) {
        $configuration = $event->getConfiguration();
        if(false === $this->securityContext->isGranted($configuration['roles'])) {
            throw new AccessDeniedException();
        }
    }
} 
