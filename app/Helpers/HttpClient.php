<?php


namespace App\Helpers;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    public static function get($url, $params = []) {
        $client = new HttpClient();
        $response = $client->_get($url, ['query' => $params]);
        return $response->getBody()->getContents();
    }

    public static function post($url, $params = [], $headers = []) {
        $client = new HttpClient();
        $response = $client->_post($url, ['form_params' => $params, 'headers' => $headers]);
        return $response->getBody()->getContents();
    }

    /**
     * HTTP get request.
     *
     *
     * @param string              $url
     * @param array $params
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function _get($url, $params) {
        $client = new \GuzzleHttp\Client();
        $params = [];

        if (isset(explode('?', $this->url)[1])) {
            parse_str(explode('?', $this->url)[1], $params);
            $url = explode('?', $this->url)[0];
        }

        $response = $client->request('GET', $url, $params);
        return $response;
    }

    /**
     * HTTP post request.
     *
     *
     * @param string              $url
     * @param array $params
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function _post($url, $params) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, $params);
        return $response;
    }
}
