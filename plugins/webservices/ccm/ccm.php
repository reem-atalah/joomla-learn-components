<?php
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route as ApiRoute;
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
        $router->createCRUDRoutes(
            'v1/migration',
            'migration',
            ['component' => 'com_ccm']
        );
        // $router->addRoutes([
        //     [
        //         'method'    => ['POST'],
        //         'route'     => 'v1/migration',
        //         'task'      => 'migration.create',
        //         'defaults'  => ['component' => 'com_ccm'],
        //     ],
        // ]);
    }
}