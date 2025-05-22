<?php
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Router\Route;
class PlgWebservicesCcm extends CMSPlugin
{
    public function onBeforeApiRoute(&$router)
    {
        $router->createCRUDRoutes(
            'v1/cmss',
            'cmss',
            ['component' => 'com_ccm']
        );
    }
}