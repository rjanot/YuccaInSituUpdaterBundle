<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Yucca\InSituUpdaterBundle\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Yucca\Component\EntityManager;
use Yucca\InSituUpdaterBundle\Event\AclCheckEvent;
use Yucca\InSituUpdaterBundle\Event\ModelDeleteEvent;
use Yucca\InSituUpdaterBundle\Event\ModelLoadEvent;
use Yucca\InSituUpdaterBundle\Event\ModelSaveEvent;
use Yucca\InSituUpdaterBundle\Form\AutoGeneratedFormType;

class Updater
{
    /**
     * @param ContainerInterface $container
     * @param EntityManager $yuccaEntityManager
     * @param FormFactory $formFactory
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function __construct(
        ContainerInterface $container,
        EntityManager $yuccaEntityManager,
        FormFactory $formFactory,
        EventDispatcherInterface $eventDispatcher,
        SecurityContextInterface $securityContext
    )
    {
        $this->container = $container;
        $this->yuccaEntityManager = $yuccaEntityManager;
        $this->formFactory = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->securityContext = $securityContext;
    }

    /**
     * @param $form_name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getConfiguration($form_name)
    {
        //Get Configuration or go die
        if(false === $this->container->hasParameter('yucca_in_situ_updater.'.$form_name))
        {
            throw new \InvalidArgumentException(sprintf('"%s" key not found in configuration', $form_name));
        }

        return $this->container->getParameter('yucca_in_situ_updater.'.$form_name);
    }

    /**
     * @param $form_name
     * @param $ids
     * @param $configuration
     * @return mixed
     * @throws \RuntimeException
     */
    public function getModels($form_name, $ids, $configuration)
    {

        //Load Models
        $event = new ModelLoadEvent($form_name, $ids, $configuration);
        $this->eventDispatcher->dispatch(
            sprintf(
                '%s:%s',
                \Yucca\InSituUpdaterBundle\Events::onModelLoad,
                isset($configuration['event_suffix']) ? $configuration['event_suffix'] : 'default'
            ),
            $event
        );

        $models = $event->getModels();
        if (empty($models)) {
            throw new \RuntimeException('No models retrieves');
        }

        return $models;
    }

    /**
     * @param $form_name
     * @param $ids
     * @param $configuration
     * @param $models
     * @param $add
     */
    public function checkSecurity($form_name, $ids, $configuration, $models, $add=false)
    {
        // Check roles
        $event = new AclCheckEvent($form_name, $ids, $configuration, $models, $add);
        $this->eventDispatcher->dispatch(
            sprintf(
                '%s:%s',
                \Yucca\InSituUpdaterBundle\Events::onAclCheck,
                isset($configuration['event_suffix']) ? $configuration['event_suffix'] : 'default'
            ),
            $event
        );

        if (false === $event->isHandled()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param $form_name
     * @param array $ids
     * @param $add
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getAutoGeneratedForm($form_name, array $ids, $add=false)
    {
        $configuration = $this->getConfiguration($form_name);


        //Load Models
        if ($add) {
           $models = array();
        } else {
            $models = $this->getModels($form_name, $ids, $configuration);
        }

        //Check security
        $this->checkSecurity($form_name, $ids, $configuration, $models, $add);

        //Create Form
        $form = $this->formFactory->create(
            new AutoGeneratedFormType(
                $configuration,
                $this->securityContext,
                null
            ),
            $models,
            array(
                'action'=>$this->container->get('router')->generate(
                        'yucca_in_situ_updater_form',
                        array('form_name'=>$form_name, 'ids'=>$ids, 'add'=>$add ? 1 : 0)
                )
            )
        );

        return $form;
    }

    /**
     * @param $formName
     * @param array $ids
     * @param array $models
     */
    public function saveModels($formName, array $ids, array $models)
    {
        $configuration = $this->getConfiguration($formName);

        $event = new ModelSaveEvent($formName, $ids, $configuration, $models);
        $this->eventDispatcher->dispatch(
            sprintf(
                '%s:%s',
                \Yucca\InSituUpdaterBundle\Events::onModelSave,
                isset($configuration['event_suffix']) ? $configuration['event_suffix'] : 'default'
            ),
            $event
        );
    }

    /**
     * @param $formName
     * @param array $ids
     * @param array $models
     */
    public function deleteModels($formName, array $ids)
    {
        $configuration = $this->getConfiguration($formName);

        $models = $this->getModels($formName, $ids, $configuration);

        $event = new ModelDeleteEvent($formName, $ids, $configuration, $models);
        $this->eventDispatcher->dispatch(
            sprintf(
                '%s:%s',
                \Yucca\InSituUpdaterBundle\Events::onModelDelete,
                isset($configuration['event_suffix']) ? $configuration['event_suffix'] : 'default'
            ),
            $event
        );
    }
}
