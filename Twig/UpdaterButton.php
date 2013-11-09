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
use Symfony\Component\Security\Core\SecurityContextInterface;
use Yucca\InSituUpdaterBundle\Service\Updater;

class UpdaterButton extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    public $twigEnvironment;
    /**
     * @var Updater
     */
    protected $updater;

    public function __construct(Updater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
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
                array('is_safe' => array('html'))
            )
        );
    }

    /**
     * @param $formName
     * @param $ids
     * @return bool
     */
    protected function isAllowed($formName, $ids)
    {
        $configuration = $this->updater->getConfiguration($formName);
        $models = $this->updater->getModels($formName, $ids, $configuration);

        try {
            $this->updater->checkSecurity($formName, $ids, $configuration, $models);

            return true;
        } catch (AccessDeniedException $e) {
            return false;
        }

    }

    public function inSituUpdater($formName, $ids)
    {
        if (false === $this->isAllowed($formName, $ids)) {
            return '';
        }

        return $this->twigEnvironment->render(
            'YuccaInSituUpdaterBundle:UpdaterButton:updater-button.html.twig',
            array(
                'form_name' => $formName,
                'ids' => $ids,
            )
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
