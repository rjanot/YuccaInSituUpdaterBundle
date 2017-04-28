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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yucca\InSituUpdaterBundle\Event\ModelMapEvent;

class FormController extends Controller
{
    public function indexAction(Request $request, $form_name)
    {
        /**
         * Get Updater Service
         */
        $updaterService = $this->container->get('yucca_in_situ_updater.updater');

        /**
         * Get requested ids
         */
        $requestIds = $request->query->get('ids', array());

        /**
         * Get Form
         */
        $form = $updaterService->getAutoGeneratedForm(
            $form_name,
            $requestIds,
            $request->query->get('add',0)
        );

        /**
         * Submit form
         */
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                //Launch custom event to override some form mapping (like Plain text Password to hashed password for example)
                $configuration = $updaterService->getConfiguration($form_name);
                $event = new ModelMapEvent(
                    $form_name,
                    $form,
                    $requestIds,
                    $configuration,
                    $form->getData()
                );
                $this->container->get('event_dispatcher')->dispatch(
                    sprintf(
                        '%s:%s',
                        \Yucca\InSituUpdaterBundle\Events::onModelMap,
                        isset($configuration['event_suffix']) ? $configuration['event_suffix'] : 'default'
                    ),
                    $event
                );

                //Save
                $updaterService->saveModels($form_name, $requestIds, $form->getData());

                //Redirect
                $parameters = array(
                    'form_name'=>$form_name
                );
                if($request->query->get('add',0)) {
                    $parameters['add']=1;
                }

                return $this->render('YuccaInSituUpdaterBundle:Form:refresh_reload.html.twig', array(
                    'redirect_to'=>$this->generateUrl(
                        'yucca_in_situ_updater_form',
                        array_merge(
                            $parameters,
                            array('ids'=>$requestIds)
                        )
                    )
                ));
            }
        }

        /**
         * Render
         */
        return $this->render('YuccaInSituUpdaterBundle:Form:form.html.twig', array(
            'form'=>$form->createView(),
        ));
    }
}
