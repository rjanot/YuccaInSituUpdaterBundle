<?php
/*
 * This file was delivered to you as part of the YuccaInSituUpdaterBundle package.
 *
 * (c) Rémi JANOT <r.janot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Yucca\InSituUpdaterBundle;

final class Events
{
    const onFormPreCreation = 'yucca_in_situ_updater.form_pre_creation';
    const onFormPostCreation = 'yucca_in_situ_updater.form_post_creation';
    const onAclCheck = 'yucca_in_situ_updater.acl_check';
    const onModelMap = 'yucca_in_situ_updater.model.map';
    const onModelLoad = 'yucca_in_situ_updater.model.load';
    const onModelSave = 'yucca_in_situ_updater.model.save';
    const onModelDelete = 'yucca_in_situ_updater.model.delete';
}
