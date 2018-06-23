<?php

namespace App\Services;

use App\Dictionary\UrlDictionary;
use App\Exceptions\ItemException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ItemService
{
    private $client;
    private $api_url;
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->api_url = $container->getParameter('app_url');
        $this->client = new Client();
        $this->logger = $logger;
    }

    public function getAllItems()
    {
        $response = $this->handleRequest(Request::METHOD_GET, UrlDictionary::GET_ALL_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getOneItem($id)
    {
        $response = $this->handleRequest(Request::METHOD_GET, UrlDictionary::GET_ONE_ITEM_URL.'/'.$id);
        $item = $this->getResponseBody($response);

        return $item;
    }

    public function getAvailableItems()
    {
        $response = $this->handleRequest(Request::METHOD_GET, UrlDictionary::GET_AVAILABLE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getUnavailableItems()
    {
        $response = $this->handleRequest(Request::METHOD_GET, UrlDictionary::GET_UNAVAILABLE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function getGreaterThanFiveItems()
    {
        $response = $this->handleRequest(Request::METHOD_GET, UrlDictionary::GET_GREATER_THAN_FIVE_ITEMS_URL);
        $items = $this->getResponseBody($response);

        return $items;
    }

    public function createItem($name, $amount)
    {
        $data = [
            'form_params' => [
                'name' => $name,
                'amount' => $amount
            ],
        ];

        $response = $this->handleRequest(Request::METHOD_POST, UrlDictionary::CREATE_ITEM_URL, $data);

        return $this->getResponseBody($response, false);
    }

    public function updateItem($id, $name, $amount)
    {
        $data = [
            'form_params' => [
                'name' => $name,
                'amount' => $amount
            ],
        ];

        $response = $this->handleRequest(Request::METHOD_PUT, UrlDictionary::UPDATE_ITEM_URL.'/'.$id, $data);

        return $this->getResponseBody($response, false);
    }

    public function deleteItem($id)
    {
        $data = [
            'form_params' => [
                'id' => $id
            ]];

        $response = $this->handleRequest(Request::METHOD_DELETE, UrlDictionary::DELETE_ITEM_URL, $data);

        return $this->getResponseBody($response, false);
    }

    private function handleRequest(string $http_type, string $url, array $data = []): ?Response
    {
        $request = null;

        try {
            $request = $this->client->request($http_type, $this->api_url . $url, $data);
        } catch (RequestException $e) {
            $this->logger->error('guzzle exception error: ' . $e->getMessage());
        }

        return $request;
    }

    private function getResponseBody(?Response $response, $toJson = true, $object = false)
    {
        if (is_null($response)) {
            throw new ItemException('Cannot get the response from Guzzle request.');
        }

        return $toJson === true
            ? json_decode($response->getBody(), $object)
            : $response->getBody();
    }
}
