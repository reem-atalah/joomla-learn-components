<?php
namespace Reem\Component\CCM\Api\Controller;
use Joomla\Component\Ccm\Administrator\Migration\Migration;
use Joomla\CMS\MVC\Controller\ApiController;

defined('_JEXEC') or die;

class MigrationController extends ApiController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        error_log('MigrationController constructed 1');
    }
    public function create()
    {
        try {
            error_log('MigrationController::migrate called');
            $migration = new Migration();
            $migration->migrate();

            $this->setMessage('Migration completed.');
        } catch (\Exception $e) {
            error_log('MigrationController::migrate error: ' . $e->getMessage());
            $this->setMessage('Migration failed: ' . $e->getMessage());
        }
    }
}