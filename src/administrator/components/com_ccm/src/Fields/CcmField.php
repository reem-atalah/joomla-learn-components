<?php
namespace Reem\Component\CCM\Administrator\Fields;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;

// prefixed the name of our field with our company name. 
// This helps prevent clashes with other field types defined by other developers.
class CcmField extends ListField {
    // define a custom form field
    // the name of the type for our new field
    protected $type = 'ccm';

    public function getOptions()
    {
        $db      = $this->getDatabase();
        $itemId  = Factory::getApplication()->input->getInt('id', 0);
        $options = [];

        if ($itemId) {
            $query = $db->getQuery(true)
            ->select($db->quoteName(['content_keys', 'ccm_mapping']))
            ->from($db->quoteName('#__ccm_cms'))
            ->where($db->quoteName('id') . ' = ' . (int) $itemId);
            $db->setQuery($query);
            $row = $db->loadAssoc();

            $contentKeys = [];
            if (!empty($row['content_keys'])) {
                $contentKeys = json_decode($row['content_keys'], true) ?: [];
            }

            foreach ($contentKeys as $key) {
            $options[] = (object)[
                'value' => $key,
                'text'  => $key
            ];
            }
        }
        return $options;
    }
}