<?php
namespace Reem\Component\CCM\Administrator\View\CCM;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $items;
    public $filterForm;
    public $activeFilters;
    public $pagination;
    public $state;

    public function display($tpl = null): void
    {
        /** @var Ccm $model */
        $model = $this->getModel();

        $this->items         = $model->getItems();
        $this->pagination    = $model->getPagination();
        $this->state         = $model->getState();
        $this->filterForm    = $model->getFilterForm();
        $this->activeFilters = $model->getActiveFilters();

        parent::display($tpl);
    }
}