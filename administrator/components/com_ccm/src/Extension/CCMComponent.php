<?php

namespace Reem\Component\CCM\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Psr\Container\ContainerInterface;

// starting point for the component
class CCMComponent extends MVCComponent implements BootableExtensionInterface
{
    public function boot(ContainerInterface $container) {
        // Initialize your component here
    }
}
