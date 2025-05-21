<?php
namespace Reem\Component\CCM\Administrator\Fields;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;

// prefixed the name of our field with our company name. 
// This helps prevent clashes with other field types defined by other developers.
class CmsField extends ListField {
    // define a custom form field
    // the name of the type for our new field
    protected $type = 'cms';

    public function getOptions()
    {
        error_log('CmsField loaded!');
        $db = $this->getDatabase();
        $query = $db->getQuery(true);
        $query->select('a.id, a.cms_name')
            ->from('#__ccm_cms AS a')
            ->order('a.cms_name', 'asc');
        $db->setQuery($query);
        $options = [];
        foreach ($db->loadAssocList() as $row) {
            $options[] = (object)[
                'value' => $row['id'],
                'text'  => $row['cms_name']
            ];
        }

        return array_merge(parent::getOptions(), $options);
    }
}