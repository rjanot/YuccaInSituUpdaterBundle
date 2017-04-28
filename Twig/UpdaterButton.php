<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yucca\InSituUpdaterBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yucca\InSituUpdaterBundle\Service\Updater;

class UpdaterButton extends \Twig_Extension
{
    /**
     * @var Updater
     */
    protected $updater;

    public function __construct(Updater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * getFilters
     *
     * @access public
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'in_situ_updater_button',
                array($this, 'inSituUpdater'),
                array('needs_environment'=>true, 'is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction(
                'in_situ_deletion_button',
                array($this, 'inSituDeletion'),
                array('needs_environment'=>true, 'is_safe' => array('html'))
            )
        );
    }

    /**
     * @param $formName
     * @param $ids
     * @param $add
     * @return bool
     */
    protected function isAllowed($formName, $ids, $add=false)
    {
        $configuration = $this->updater->getConfiguration($formName);

        if ($add) {
            $models = array();
        } else {
            $models = $this->updater->getModels($formName, $ids, $configuration);
        }

        try {
            $this->updater->checkSecurity($formName, $ids, $configuration, $models, $add);

            return true;
        } catch (AccessDeniedException $e) {
            return false;
        }
    }

    /**
     * @param $formName
     * @param $ids
     * @param bool $add
     * @return string
     */
    public function inSituUpdater(\Twig_Environment $env, $formName, $ids, $add=false)
    {
        if (false === $this->isAllowed($formName, $ids, $add)) {
            return '';
        }

        $args = array(
            'form_name' => $formName,
            'ids' => $ids,
        );

        if($add) {
            return $env->render(
                'YuccaInSituUpdaterBundle:UpdaterButton:add-button.html.twig',
                $args
            );
        } else {
            return $env->render(
                'YuccaInSituUpdaterBundle:UpdaterButton:updater-button.html.twig',
                $args
            );

        }
    }

    public function inSituDeletion(\Twig_Environment $env, $formName, $ids)
    {
        if (false === $this->isAllowed($formName, $ids)) {
            return '';
        }

        $args = array(
            'form_name' => $formName,
            'ids' => $ids,
        );

        return $env->render(
            'YuccaInSituUpdaterBundle:UpdaterButton:delete-button.html.twig',
            $args
        );
    }

    /**
     * getName
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'yucca_in_situ_updater_button';
    }
}
