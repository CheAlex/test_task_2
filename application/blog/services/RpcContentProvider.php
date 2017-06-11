<?php

namespace Blog\Service;

class RpcContentProvider implements ContentProviderInterface
{
    public function get(string $host, string $uri): ContentResponse
    {
        $query = http_build_query([
            'host' => $host,
            'uri' => $uri,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://content.dev:80/api?' . $query);
//        curl_setopt($ch, CURLOPT_URL, 'http://content.dev:80/api?host=blog1.dev&uri=page_1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $jsonData = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($jsonData == false) {
            throw new \Exception('Connection error, ' . curl_error($ch));
        }

        if ($httpStatusCode !== 200) {
            throw new \Exception('Wrong content data');
        }

        $data = json_decode($jsonData, true);

        if (!$data || !isset($data['html'])) {
            throw new \Exception('Wrong content data');
        }

        $response = new ContentResponse($data['code'], $data['html'], $data['error']);

        echo 666;

        return $response;
    }
}
