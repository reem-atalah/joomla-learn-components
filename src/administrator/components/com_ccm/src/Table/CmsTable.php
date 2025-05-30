<?php
namespace Reem\Component\CCM\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;

class CmsTable extends Table
{
    protected $_jsonEncode = ['documents', "ccm_mapping"];

    public function __construct(DatabaseInterface $db)
    {
        parent::__construct('#__ccm_cms', 'id', $db);
    }
}