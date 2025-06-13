<?php
namespace Reem\Component\CCM\Administrator\Fields;
use Joomla\CMS\Form\Field\ListField;

// prefixed the name of our field with our company name. 
// This helps prevent clashes with other field types defined by other developers.
class CmsField extends ListField {
    // define a custom form field
    // the name of the type for our new field
    protected $type = 'cms';

    public function getOptions()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);
        $query->select('a.id, a.name')
            ->from('#__ccm_cms AS a')
            ->order('a.name', 'asc');
        $db->setQuery($query);
        $options = [
            (object)[
                'value' => '',
                'text'  => '-Select-'
            ]
        ];
        foreach ($db->loadAssocList() as $row) {
            $options[] = (object)[
                'value' => $row['id'],
                'text'  => $row['name']
            ];
        }

        return array_merge(parent::getOptions(), $options);
    }
}