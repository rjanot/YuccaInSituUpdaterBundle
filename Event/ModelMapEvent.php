<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Yucca\InSituUpdaterBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\Form;

class ModelMapEvent extends Event
{
    /**
     * @var
     */
    protected $models;
    /**
     * @var
     */
    protected $form_name;
    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;
    /**
     * @var
     */
    protected $ids;
    /**
     * @var
     */
    protected $configuration;

    /**
     * @param $form_name
     * @param $form
     * @param $ids
     * @param $configuration
     * @param $models
     */
    public function __construct($form_name, Form $form, $ids, $configuration, $models)
    {
        $this->form_name = $form_name;
        $this->form = $form;
        $this->ids = $ids;
        $this->configuration = $configuration;
        $this->models = $models;
    }

    public function getModels()
    {
        return $this->models;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getFormName()
    {
        return $this->form_name;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    public function getIds()
    {
        return $this->ids;
    }
}
