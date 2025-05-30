<?php

namespace Reem\Component\CCM\Administrator\Model;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Http\HttpFactory;

\defined('_JEXEC') or die;

class CmsModel extends AdminModel {

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_ccm.cms',
            'cms',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState('com_ccm.edit.cms.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }
        // Set the mapped key as the default value for your field
        if (isset($data->ccm_mapping)) {
            $mapping = json_decode($data->ccm_mapping, true);
            if (!empty($mapping['content_keys'])) {
                $data->content_key = $mapping['content_keys'];
            }
        }
        return $data;
    }

    public function discoverCmsProperties($url)
    {
        $response = HttpFactory::getHttp()->get($url, [
            'Accept' => 'application/json',
        ]);
        $body = json_decode($response->body, true);

        // the content is the array object in the response body
        $contentItem = [];
        foreach ($body as $value) {
            if (is_array($value)) {
                $contentItem = $value;
                break;
            }
        }

        $sourceKeys = array_keys($contentItem[0]);

        // Try to get the ID from the request or model state
        $id   = (int) ($this->getState($this->getName() . '.id') ?: Factory::getApplication()->input->getInt('id'));
        // $item = $this->getItem($id);
        $data = [
            'id'           => $id,
            'content_keys' => json_encode($sourceKeys, JSON_UNESCAPED_UNICODE),
        ];
        $this->save($data);

        // Reload and return the updated item from the database
        return $this->getItem($id);
    }

    public function mapCmsToCCM()
    {
        $ccmSchema = json_decode(file_get_contents(__DIR__ . '/ccm.json'), true);
        // error_log("CCM Schema: " . print_r($ccmSchema, true));
        $ccmFields = array_keys($ccmSchema['ContentItem']['properties']);
        error_log("CCM Fields: " . print_r($ccmFields, true));

        $mapping    = [];
        $id         = (int) ($this->getState($this->getName() . '.id') ?: Factory::getApplication()->input->getInt('id'));
        $item       = $this->getItem($id);
        $sourceKeys = json_decode($item->content_keys, true);
        error_log("Source keys2: " . print_r($sourceKeys, true));

        foreach ($ccmFields as $ccmField) {
            $bestMatch = null;
            $highestScore = 0;
            foreach ($sourceKeys as $sourceKey) {
                // Calculate similarity using levenshtein and similar_text
                // $lev = levenshtein(strtolower($ccmField), strtolower($sourceKey));
                // similar_text(strtolower($ccmField), strtolower($sourceKey), $percent);

                // // Combine both metrics for a score (lower levenshtein and higher percent is better)
                // $score = (100 - min($lev, 100)) + $percent;

                // if ($score > $highestScore) {
                //     $highestScore = $score;
                //     $bestMatch = $sourceKey;
                // }
                if (strtolower($ccmField) === strtolower($sourceKey)) {
                    $bestMatch = $sourceKey;
                    break;
                }
            }
            // Only map if similarity is reasonably high
            // $mapping[$ccmField] = ($highestScore > 120) ? $bestMatch : null;

            $mapping[$ccmField] = $bestMatch;
            error_log("Mapping: " . $ccmField . " => " . ($bestMatch ?? 'null'));
        }
        error_log("Final Mapping: " . print_r($mapping, true));
        error_log("id: " . $id);

        $data = [
            'id'           => $id,
            'ccm_mapping'  => json_encode($mapping, JSON_UNESCAPED_UNICODE),
        ];
        $this->save($data);

    }
}
