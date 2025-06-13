<?php
namespace Reem\Component\CCM\Administrator\View\Migration;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Reem\Component\CCM\Administrator\Migration\Migration;

class HtmlView extends BaseHtmlView
{

    public function display($tpl = null): void
    {
        /** @var Migration $model */
        $model = $this->getModel();
        
        $this->item  = $model->getItem();
        $this->form  = $model->getForm();
        $this->state = $model->getState();

        // after each step say echo "Mapping is done" --> then echo "Migration is done"
        //this can be added in js in frontend in mdeia folder
        //from webassets 
        parent::display($tpl);
    }
}