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
    /**
     * Save the CMS item.
     *
     * @param   string  $key      The name of the primary key of the URL variable.
     * @param   string  $urlVar   The name of the URL variable if different from the primary key.
     *
     * @return  void
     */
    public function save($key = null, $urlVar = null)
    {
        $data    = $this->input->post->get('jform', [], 'array');
        $url = isset($data['url']) ? $data['url'] : '';

        /** @var CmsModel $model */
        $model   = $this->getModel();
        $oldItem = $model->getItem($this->input->getInt('id', 0));
        $old_url = $oldItem ? $oldItem->url : null;

        // if ($url !== $old_url && $url !== '') {
        //     $model->discoverCmsProperties($url);
        //     $model->mapCmsToCCM();
        // }

        parent::save($key, $urlVar);
    }
}