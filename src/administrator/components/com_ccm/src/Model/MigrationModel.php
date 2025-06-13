<?php
namespace Reem\Component\CCM\Administrator\Model;

use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Factory;

/**
 * Class Migration
 *
 * @since  4.0.0
 */
class MigrationModel extends FormModel
{
    public function getItem($pk = null)
    {
        return [];
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_ccm.migration',
            'migration',
            [
                'control'   => 'jform',
                'load_data' => $loadData,
            ]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_ccm.edit.migration.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
    /**
     * Migrate from source CMS to target CMS.
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function migrate($sourceCmsId, $targetCmsId, $sourceType, $targetType)
    {
        $db = $this->getDatabase();

        // Get source CMS info
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__ccm_cms'))
            ->where($db->quoteName('id') . ' = ' . (int) $sourceCmsId);
        $db->setQuery($query);
        $sourceCms = $db->loadObject();

        // Get target CMS info
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__ccm_cms'))
            ->where($db->quoteName('id') . ' = ' . (int) $targetCmsId);
        $db->setQuery($query);
        $targetCms = $db->loadObject();

        // if (!$sourceCms || !$targetCms) {
        //     throw new \RuntimeException('Invalid source or target CMS.');
        // }

        $sourceItems = $this->getSourceItems($sourceCms, $sourceType);
        // if (empty($sourceItems)) {
        //     throw new \RuntimeException('No items found in source CMS.');
        // }
        $sourceToCcmItems = $this->convertSourceCmsToCcm($sourceCms, $sourceItems, $sourceType);
        // if (empty($sourceToCcmItems)) {
        //     throw new \RuntimeException('No items found to migrate from source CMS.');
        // }
        $ccmToTargetItems = $this->convertCcmToTargetCms($sourceToCcmItems, $targetCms, $targetType);

        $targetMigrationStatus = $this->migrateItemsToTargetCms($targetCms, $targetType, $ccmToTargetItems);

        return $targetMigrationStatus;
    }

    private function getSourceItems($sourceCms, $sourceType) {
        $sourceUrl = $sourceCms->url;
        $sourceEndpoint = $sourceUrl . '/' . $sourceType;

        // error_log("[MigrationModel] Fetching source items from: $sourceEndpoint");

        $http     = HttpFactory::getHttp();
        $sourceResponse = $http->get($sourceEndpoint, [
            'Accept' => 'application/json',
            // Add authentication if needed using $sourceCredentials
        ]);
        // error_log("[MigrationModel] Source response code: " . $sourceResponse->code);
        // error_log("[MigrationModel] Source response body: " . $sourceResponse->body);

        $sourceResponseBody = json_decode($sourceResponse->body, true);

        if (isset($sourceResponseBody[$sourceType]) && is_array($sourceResponseBody[$sourceType])) {
            // error_log("[MigrationModel] Found items under key: $sourceType");
            return $sourceResponseBody[$sourceType];
        } elseif (isset($sourceResponseBody['items']) && is_array($sourceResponseBody['items'])) {
            // error_log("[MigrationModel] Found items under key: items");
            return $sourceResponseBody['items'];
        } elseif (is_array($sourceResponseBody)) {
            // error_log("[MigrationModel] Source response body is array, returning as items");
            return $sourceResponseBody;
        }

        // error_log("[MigrationModel] Could not find items to migrate in source response.");
        throw new \RuntimeException('Could not find items to migrate in source response.');
    }

    private function convertSourceCmsToCcm($sourceCms, $sourceItems, $sourceType) {
        $sourceSchemaFile = strtolower($sourceCms->name) . '-ccm.json';        
        $schemaPath       = dirname(__DIR__, 1) . '/Schema/';
        // error_log("[MigrationModel] Loading source schema: " . $schemaPath . $sourceSchemaFile);
        $schema = json_decode(file_get_contents($schemaPath . $sourceSchemaFile), true);
        // error_log("schema: " . json_encode($schema, JSON_PRETTY_PRINT));
        // Find the ContentItem with the matching type
        $sourceToCcm = [];
        if (isset($schema['ContentItem']) && is_array($schema['ContentItem'])) {
            foreach ($schema['ContentItem'] as $contentItem) {
                if (isset($contentItem['type']) && $contentItem['type'] === $sourceType && isset($contentItem['properties'])) {
                    $sourceToCcm = $contentItem['properties'];
                    // error_log("[MigrationModel] Found mapping for source type: $sourceType");
                    break;
                }
            }
        }
        // error_log("sourceToCcm: " . json_encode($sourceToCcm, JSON_PRETTY_PRINT));

        $ccmItems = [];
        foreach ($sourceItems as $item) {
            $ccmItem = [];
            foreach ($sourceToCcm as $sourceKey => $ccmKey) {
                if ($ccmKey && isset($item[$sourceKey])) {
                    $ccmItem[$ccmKey] = $item[$sourceKey];
                }
            }
            $ccmItems[] = $ccmItem;
        }

        // error_log("[MigrationModel] Converted " . count($ccmItems) . " source items to CCM format.");
        // error_log("[MigrationModel] CCM items: " . json_encode($ccmItems));
        return $ccmItems;
    }

    private function convertIsoToJoomlaDate($isoDate) {
        if (empty($isoDate)) {
            return null;
        }
        try {
            $dt = new \DateTime($isoDate);
            return $dt->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return $isoDate; // fallback to original if parsing fails
        }
    }

    private function convertCcmToTargetCms($ccmItems, $targetCms, $targetType) {
        $targetSchemaFile = strtolower($targetCms->name) . '-ccm.json';
        $schemaPath       = dirname(__DIR__, 1) . '/Schema/';
        $ccmToTarget      = json_decode(file_get_contents($schemaPath . $targetSchemaFile), true);

        // Find the ContentItem with the matching type
        $targetToCcm = [];
        if (isset($ccmToTarget['ContentItem']) && is_array($ccmToTarget['ContentItem'])) {
            foreach ($ccmToTarget['ContentItem'] as $contentItem) {
                if (isset($contentItem['type']) && $contentItem['type'] === $targetType && isset($contentItem['properties'])) {
                    $targetToCcm = $contentItem['properties'];
                    // error_log("[MigrationModel] Found mapping for target type: $targetType");
                    break;
                }
            }
        }
        if (empty($targetToCcm)) {
            // error_log("[MigrationModel] No mapping found for target CMS type: $targetType");
            throw new \RuntimeException('No mapping found for target CMS type: ' . $targetType);
        }

        $targetItems = [];
        foreach ($ccmItems as $ccmItem) {
            $targetItem = [];
            foreach ($targetToCcm as $targetKey => $ccmMap) {
                if (is_array($ccmMap)) {
                    $ccmKey = $ccmMap['ccm'] ?? null;
                    // Handle mapped value
                    if ($ccmKey && isset($ccmItem[$ccmKey])) {
                        $value = $ccmItem[$ccmKey];
                        // Handle value mapping (e.g., status string to int)
                        if (isset($ccmMap['map']) && is_array($ccmMap['map'])) {
                            $value = $ccmMap['map'][$value] ?? ($ccmMap['default'] ?? $value);
                        }
                        $targetItem[$targetKey] = $value;
                    } elseif (isset($ccmMap['default'])) {
                        $targetItem[$targetKey] = $ccmMap['default'];
                    }
                } else {
                    // Simple mapping
                    if ($ccmMap && isset($ccmItem[$ccmMap])) {
                        $targetItem[$targetKey] = $ccmItem[$ccmMap];
                    }
                }
            }
            // error_log("[MigrationModel] Mapped CCM item to target item: " . json_encode($targetItem));
            // Add required fields for target CMS if needed (example for Joomla)
            // if ($targetType === 'articles') {
            //     $targetItem['catid'] = $targetItem['catid'] ?? 2;
            //     $targetItem['language'] = $targetItem['language'] ?? '*';

            //     if (isset($ccmItem['status'])) {
            //         switch ($ccmItem['status']) {
            //             case 'publish':
            //                 $targetItem['state'] = 1;
            //                 break;
            //             case 'draft':
            //             case 'pending':
            //             case 'future':
            //             case 'private':
            //                 $targetItem['state'] = 0;
            //                 break;
            //             case 'trash':
            //                 $targetItem['state'] = -2;
            //                 break;
            //             default:
            //                 $targetItem['state'] = 0;
            //         }
            //     }
            // }
            if (!empty($targetItem['created'])) {
                $targetItem['created'] = $this->convertIsoToJoomlaDate($targetItem['created']);
            }
            if (!empty($targetItem['modified'])) {
                $targetItem['modified'] = $this->convertIsoToJoomlaDate($targetItem['modified']);
            }
            $targetItems[] = $targetItem;
        }

        // error_log("[MigrationModel] Converted " . count($targetItems) . " CCM items to target CMS format.");
        return $targetItems;
    }

    private function migrateItemsToTargetCms($targetCms, $targetType, $ccmToTargetItems) {
        $targetUrl         = $targetCms->url;
        $targetEndpoint    = $targetUrl . '/' . $targetType;
        $targetCredentials = $targetCms->credentials;

        // error_log("[MigrationModel] Migrating items to target endpoint: $targetEndpoint");

        $http = HttpFactory::getHttp();
        foreach ($ccmToTargetItems as $idx => $item) {
            // error_log("[MigrationModel] Migrating item #" . ($idx + 1) . ": " . json_encode($item));
            $response = $http->post($targetEndpoint, json_encode($item), [
                'Authorization' => 'Bearer ' . $targetCredentials,
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/json'
            ]);
            // error_log("[MigrationModel] Response code: " . $response->code);
            if ($response->code === 201 || $response->code === 200) {
                // error_log("[MigrationModel] Successfully migrated item #" . ($idx + 1));
            }
            else
                throw new \RuntimeException('Error migrating item: ' . $response->body);
        }
        // error_log("[MigrationModel] Migration to target CMS completed.");
        return true;
    }
}