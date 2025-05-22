<?php
namespace Reem\Component\CCM\Administrator\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Router\ApiRouter;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Component\Router\RouterView;

return new ApiRouter([
    'cmss' => [
        'controller' => 'cmss', // This should match your controller/resource
    ],
]);