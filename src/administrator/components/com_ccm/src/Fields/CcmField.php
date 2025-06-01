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
            ->select($db->quoteName(['content_keys_types', 'ccm_mapping']))
            ->from($db->quoteName('#__ccm_cms'))
            ->where($db->quoteName('id') . ' = ' . (int) $itemId);
            $db->setQuery($query);
            $row = $db->loadAssoc();

            $contentKeys = [];
            if (!empty($row['content_keys_types'])) {
                $decoded = json_decode($row['content_keys_types'], true) ?: [];
                $contentKeys = array_keys($decoded);
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

    protected function getInput()
    {
        $jsonPath = JPATH_ADMINISTRATOR . '/components/com_ccm/src/Model/ccm.json';
        if (!file_exists($jsonPath)) {
            return '<div class="alert alert-warning">ccm.json not found</div>';
        }

        $json = json_decode(file_get_contents($jsonPath), true);
        if (!$json || !isset($json['ContentItem']['properties'])) {
            return '<div class="alert alert-danger">Invalid ccm.json</div>';
        }

        $properties = $json['ContentItem']['properties'];
        $options    = $this->getOptions();
        $html       = '<div class="ccm-mapping-fields">';
        foreach ($properties as $key => $definition) {
            $label = ucfirst($key);
            $selected = isset($this->value[$key]) ? $this->value[$key] : '';
            $html .= '<div class="control-group">';
            $html .= '<label class="control-label" for="' . $this->id . '_' . $key . '">' . $label . '</label>';
            $html .= '<div class="controls">';
            $html .= '<select name="' . $this->name . '[' . $key . ']" id="' . $this->id . '_' . $key . '" class="form-select">';
            // $html .= '<option value="">- Select -</option>';
            foreach ($options as $option) {
                $isSelected = ($selected == $option->value) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($option->value, ENT_QUOTES) . '"' . $isSelected . '>' . htmlspecialchars($option->text, ENT_QUOTES) . '</option>';
            }
            $html .= '</select>';
            $html .= '</div></div>';
        }
        $html .= '</div>';
        return $html;
    }
}