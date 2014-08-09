<?php
namespace Yucca\InSituUpdaterBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class FormPostCreationEvent
 *
 * @package Yucca\InSituUpdaterBundle\Event
 */
class FormPostCreationEvent extends Event
{
    protected $form;
    protected $configuration;

    /**
     * @param $form
     * @param $configuration
     */
    public function __construct($form, $configuration)
    {
        $this->form = $form;
        $this->configuration = $configuration;
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
