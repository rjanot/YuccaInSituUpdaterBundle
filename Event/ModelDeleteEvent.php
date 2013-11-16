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

class ModelDeleteEvent extends Event
{
    protected $models;
    protected $form_name;
    protected $ids;
    protected $configuration;

    /**
     * @param $form_name
     * @param $ids
     * @param $configuration
     * @param $models
     */
    public function __construct($form_name, $ids, $configuration, $models)
    {
        $this->form_name = $form_name;
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

    public function getIds()
    {
        return $this->ids;
    }
}
