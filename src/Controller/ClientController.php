<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class ClientController extends Controller
{
    private $client;

    public function __construct()
    {
       $this->client = new Client();
    }

    /**
     * Display all items
     * @Route("/", name="client")
     */
    public function getAll()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/items');
        $items = json_decode($response->getBody());
        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }
}
