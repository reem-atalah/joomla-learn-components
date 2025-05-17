<?php
namespace Reem\Component\CCM\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;

class CcmTable extends Table
{
    public function __construct(DatabaseInterface $db)
    {
        parent::__construct('#__cms', 'cms_name', $db);
    }
}