<?php
namespace Reem\Component\CCM\Administrator\View\Migration;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Reem\Component\CCM\Administrator\Migration\Migration;

class HtmlView extends BaseHtmlView
{

    public function display($tpl = null): void
    {
        echo 'TEST';

        $migration = new Migration();
        $migration->migrate();

        echo "Migration completed.\n";

        parent::display($tpl);
    }
}