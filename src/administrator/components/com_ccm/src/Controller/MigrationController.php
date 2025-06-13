<?php
namespace Reem\Component\CCM\Administrator\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

class MigrationController extends BaseController
{
    public function apply()
    {
        $data = $this->input->post->get('jform', [], 'array');
        if (empty($data)) {
            $this->setMessage('No data provided for migration.', 'error');
            $this->setRedirect('index.php?option=com_ccm&view=migration');
            return;
        }

        $sourceCmsId = isset($data['source_cms']) ? (int) $data['source_cms'] : 0;
        $targetCmsId = isset($data['target_cms']) ? (int) $data['target_cms'] : 0;
        $sourceType  = isset($data['source_cms_object_type']) ? $data['source_cms_object_type'] : '';
        $targetType  = isset($data['target_cms_object_type']) ? $data['target_cms_object_type'] : '';

        /** @var MigrationModel $model */
        $model = $this->getModel();
        try {
            $targetMigrationStatus = $model->migrate($sourceCmsId, $targetCmsId, $sourceType, $targetType);
            if (!$targetMigrationStatus) {
                $this->setMessage('Migration failed: Target CMS migration status is false.', 'error');
                $this->setRedirect('index.php?option=com_ccm&view=migration');
                return;
            }
            $this->setMessage('Migration applied successfully.');
        } catch (\Exception $e) {
            $this->setMessage('Migration failed: ' . $e->getMessage(), 'error');
        }
        $this->setRedirect('index.php?option=com_ccm&view=migration');
    }
}