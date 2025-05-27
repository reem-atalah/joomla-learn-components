<?php
// define('_JEXEC', 1);

// require_once dirname(__DIR__) . '/includes/defines.php';
// require_once dirname(__DIR__) . '/includes/framework.php';

use Joomla\CMS\Factory;
use Joomla\Component\Ccm\Administrator\Migration\Migration;

// Bootstrap the application
$app = Factory::getApplication('site');

// Run the migration
$migration = new Migration();
$migration->migrate();

echo "Migration completed.\n";