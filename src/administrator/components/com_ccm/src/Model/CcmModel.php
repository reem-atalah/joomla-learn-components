<?php

namespace Reem\Component\CCM\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Language\Associations;

\defined('_JEXEC') or die;

class CcmModel extends ListModel {

    public function __construct($config = [], ?MVCFactoryInterface $factory = null) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'a.id',
                'cms_name',
                'a.cms_name'
            ];

            if (Associations::isEnabled()) {
                $config['filter_fields'][] = 'association';
            }
        }
        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'cms_name', $direction = 'ASC') {
        $app   = Factory::getApplication();
        $value = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        $this->setState('list.limit', $value);

        $value = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery() {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $query->select(
            $this->getState('list.select', 'a.id, a.cms_name')
        )->from($db->quoteName('#__cms', 'a'));

        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('(a.cms_name LIKE ' . $search . ')');
        }
        $orderCol  = $this->state->get('list.ordering', 'a.cms_name');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
        return $query;
    }
}
