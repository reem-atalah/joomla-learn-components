<?php

namespace Reem\Component\CCM\Administrator\Model;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Language\Associations;

\defined('_JEXEC') or die;

class CmsModel extends AdminModel {

    public function getItem($pk = null)
    {
        $app = Factory::getApplication();
        $pk = $app->input->getInt('id', 0);
        $this->setState('cms.id', $pk);
        $item = parent::getItem($pk);
        if (empty($item)) {
            return false;
        }
        return $item;
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_ccm.cms',
            'cms',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState('com_ccm.edit.cms.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
}
