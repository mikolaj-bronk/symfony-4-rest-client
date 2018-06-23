<?php

namespace App\Services;

use App\Dictionary\UrlDictionary;
use App\Exceptions\ItemException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ItemService
{
    private $client;
    private $api_url;

    public function __construct(ContainerInterface $container)
    {
        $this->api_url = $container->getParameter('app_url');
        $this->client = new Client();
    }

    public function getAllItems()
    {
        $response = $this->handleRequest('GET', UrlDictionary::GET_ALL_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getAvailableItems()
    {
        $response = $this->handleRequest('GET', UrlDictionary::GET_AVAILABLE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getUnavailableItems()
    {
        $response = $this->handleRequest('GET', UrlDictionary::GET_UNAVAILABLE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getGreaterThanFiveItems()
    {
        $response = $this->handleRequest('GET', UrlDictionary::GET_GREATER_THAN_FIVE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function createItem($name, $amount)
    {
        $data = [
            'form_params' => [
            'name' => $name,
            'amount' => $amount
            ]];

        $this->handleRequest('POST', UrlDictionary::CREATE_ITEM_URL, $data);
    }

    public function deleteItem($id)
    {
        $data = [
            'form_params' => [
                'id' => $id
            ]];

        $this->handleRequest('DELETE', UrlDictionary::DELETE_ITEM_URL,$data);
    }

    private function handleRequest(string $http_type, string $url, array $data = []): ?Response
    {
        $request = null;

        try {
            $request = $this->client->request($http_type, $this->api_url . $url, $data);
        } catch (BadResponseException $e) {
            // TODO
        }
        if (is_null($request)) {
            throw new \Exception('tesa');
        }

        return $request;
    }

    private function getResponseBody(Response $response, $object = false)
    {
        if (is_null($response)) {
            throw new ItemException('Cannot get the response from Guzzle request');
        }

        return json_decode($response->getBody(), $object);
    }
}
