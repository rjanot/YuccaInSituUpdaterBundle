<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yucca\InSituUpdaterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yucca\InSituUpdaterBundle\Event\ModelMapEvent;

class DeleteController extends Controller
{
    public function indexAction($form_name)
    {
        /**
         * Get Updater Service
         */
        $updaterService = $this->container->get('yucca_in_situ_updater.updater');

        /**
         * Get requested ids
         */
        $request = $this->getRequest();
        $requestIds = $request->query->get('ids', array());

        /**
         * Submit form
         */
        if ($request->isMethod('POST')) {
            //Delete
            $updaterService->deleteModels($form_name, $requestIds);

            //Redirect
            $parameters = array(
                'form_name'=>$form_name
            );

            return $this->render('YuccaInSituUpdaterBundle:Delete:done.html.twig');
        }

        /**
         * Render
         */
        return $this->render('YuccaInSituUpdaterBundle:Delete:ask.html.twig');
    }
}
