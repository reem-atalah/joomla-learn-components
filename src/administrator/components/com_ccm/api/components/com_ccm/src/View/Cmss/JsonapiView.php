<?php
namespace Reem\Component\CCM\Api\View\Cmss;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;

class JsonapiView extends BaseApiView
{
    protected $fieldsToRenderList = [
        'id',
        'cms_name'
    ];
}