<?php
namespace Reem\Component\CCM\Administrator\Controller;
use Joomla\CMS\MVC\Controller\FormController;
class CmsController extends FormController
{
    protected function getRedirectToListAppend()
    {
        return '&view=cmss';
    }

    public function migrate()
    {
        error_log('CmsController::migrate called');
        $migration = new \Reem\Component\CCM\Administrator\Migration\Migration();
        $migration->migrate();

        // Optionally redirect or set a message
        $this->setMessage('Migration completed!');
        // $this->setRedirect('index.php?option=com_ccm');
    }
}