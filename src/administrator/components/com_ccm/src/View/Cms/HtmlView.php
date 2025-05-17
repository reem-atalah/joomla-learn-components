<?php
namespace Reem\Component\CCM\Administrator\View\Cms;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    public $item;
    public $form;
    public $state;

    public function display($tpl = null): void
    {
        /** @var CmsModel $model */
        $model = $this->getModel();

        $this->item          = $model->getItem();
        $this->form          = $model->getForm();
        $this->state         = $model->getState();

        if (\count($errors = $model->getErrors())) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();
        
        parent::display($tpl);
    }
}