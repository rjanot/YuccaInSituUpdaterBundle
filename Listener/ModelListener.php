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

use Yucca\Component\EntityManager;
use Yucca\InSituUpdaterBundle\Event\ModelDeleteEvent;
use Yucca\InSituUpdaterBundle\Event\ModelLoadEvent;
use Yucca\InSituUpdaterBundle\Event\ModelSaveEvent;

class ModelListener
{
    protected $yuccaEntityManager;

    public function __construct(EntityManager $yuccaEntityManager)
    {
        $this->yuccaEntityManager = $yuccaEntityManager;
    }

    /**
     * @param ModelLoadEvent $event
     * @return array
     * @throws \InvalidArgumentException
     */
    public function onModelLoad(ModelLoadEvent $event)
    {
        $configuration = $event->getConfiguration();
        $ids = $event->getIds();

        //Get models
        $models = array();
        foreach ($configuration['entities'] as $entityIndex => $entity) {
            if (false === isset($ids[$entityIndex])) {
                throw new \InvalidArgumentException(sprintf('Entity "%s" not found in configuration', $entity['name']));
            }
            $models['entity:'.$entity['name']] = $this->yuccaEntityManager->load(
                $entity['entity_class_name'],
                $ids[$entityIndex]
            );
        }
        $event->setModels($models);
    }

    /**
     * @param ModelSaveEvent $event
     * @return array
     * @throws \InvalidArgumentException
     */
    public function onModelSave(ModelSaveEvent $event)
    {
        foreach ($event->getModels() as $index => $model) {
            //Launch event save
            $this->save($event, $index, $model);
        }
    }

    /**
     * @param ModelDeleteEvent $event
     * @return array
     * @throws \InvalidArgumentException
     */
    public function onModelDelete(ModelDeleteEvent $event)
    {
        foreach ($event->getModels() as $index => $model) {
            //Launch event save
            $this->delete($event, $index, $model);
        }
    }

    protected function save(ModelSaveEvent $event, $index, $model)
    {
        $this->yuccaEntityManager->save($model);
    }

    protected function delete(ModelDeleteEvent $event, $index, $model)
    {
        $this->yuccaEntityManager->remove($model);
    }
}
