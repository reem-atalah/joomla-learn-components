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

        // helps in adding the action buttons, Save, Apply, and Cancel
        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        $canDo = ContentHelper::getActions('com_ccm');
        $toolbar = Toolbar::getInstance();
        ToolbarHelper::title(Text::_('COM_CCM_CMS_NAME_' . ($isNew ? 'ADD' : 'EDIT')));
        if ($canDo->get('core.create'))
        {
            if ($isNew)
                $toolbar->apply('cms.save');
            else
                $toolbar->apply('cms.apply');
            $toolbar->save('cms.save');
        }
        $toolbar->cancel('cms.cancel', 'JTOOLBAR_CLOSE');
    }
}