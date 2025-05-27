<?php
namespace Reem\Component\CCM\Administrator\Migration;
// namespace Joomla\Component\Ccm\Administrator\Migration;

use Joomla\CMS\Http\HttpFactory;
/**
 * Class Migration
 *
 * @since  4.0.0
 */
class Migration
{
    /**
     * Migrate the component.
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function migrate()
    {
        // $url_wordpress = 'https://najmadates.wordpress.com/wp-json/wp/v2'; // need to be added in the config
        $url_wordpress = 'https://public-api.wordpress.com/rest/v1.1/sites/najmadates.wordpress.com'; // need to be added in the config
        $url_joomla = 'http://localhost:8000/api/index.php/v1'; // need to be added in the config
        $joomla_token = 'c2hhMjU2OjcyMDo2NDJlNjY4MjY5ZGVhOWYwZGQ2NWY5NGQwYjg2YWUwODk2ZTRiMmE0MGE1MTY4Y2JlZDZlMmZkNTc5MDU4MTgz'; // need to be added in the config
        $options = new \Joomla\Registry\Registry;
        
        // Export data from wordpress
        error_log('Starting migration from WordPress to Joomla');
        $response = HttpFactory::getHttp()->get($url_wordpress . '/posts', [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // error_log('Response: ' . print_r($response, true));
        if ($response->code === 200) {
            error_log('Successfully fetched data from WordPress');
            $body = json_decode($response->body, true);
            // error_log(print_r($body, true));

            if ($body && $body["found"])
            {
                error_log('Found ' . $body["found"] . ' posts in WordPress');
                $posts = $body["posts"];

                // call joomla import api
                foreach ($posts as $post) {
                    // Prepare data for Joomla API
                    $data = [
                        'title' => $post['title'],
                        'articletext' => $post['content'],
                        'status' => 'published', // or draft, etc.
                        'created' => date('Y-m-d H:i:s', strtotime($post['date'])),
                        'modified' => date('Y-m-d H:i:s', strtotime($post['modified'])),
                        'catid' => 2, //uncategorized
                        'language' => '*', // all languages
                    ];
                    error_log(json_encode($data));
                    // Send data to Joomla API
                    Factory::getApplication()->isClient('site');
                    $response_joomla = HttpFactory::getHttp($options)->post($url_joomla . '/content/articles', json_encode($data), 
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $joomla_token,
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                    error_log('Joomla API Response: ');
                    // error_log(print_r($response_joomla, true));
                    if ($response_joomla->code === 201) {
                        error_log('Successfully imported post');
                    } else {
                        error_log('Error importing post: ');
                    }
                }
            }
        } else {
            // Handle error
            error_log('Error fetching data from WordPress: ' . $response->code);
            throw new \RuntimeException('Error fetching data from WordPress: ' . $response->code);
        }
    }
}