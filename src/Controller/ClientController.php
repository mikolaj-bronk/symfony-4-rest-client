<?php

namespace App\Controller;

use App\Entity\Items;
use App\Form\CreateType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{
    Response,
    Request
};
use Symfony\Component\Form\Extension\Core\Type\{
    TextType,
    SubmitType
};
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
     * @Route("/items/all", name="client_get_all")
     */
    public function getAll()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/items');
        $items = json_decode($response->getBody());
        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is greater than zero
     * @Route("/items/found", name="client_get_items_where_amount_is_greater_than_0")
     */
    public function getItemsWhereAmountIsGreaterThanZero()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/items/found');
        $items = json_decode($response->getBody());
        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is equal to zero
     * @Route("/items/notfound", name="client_get_items_where_amount_is_equal_to_zero")
     */
    public function getItemsWhereAmountIsEqualToZero()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/items/notfound');
        $items = json_decode($response->getBody());
        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is greater than five
     * @Route("/items/foundfive", name="client_get_items_where_amount_is_greater_than_five")
     */
    public function getItemsWhereAmountIsGreaterThanFive()
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/items/foundfive');
        $items = json_decode($response->getBody());
        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Create new item
     * @Route("/create")
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/add', [
                'form_params' => [
                    'name' => $request->get('create')['name'],
                    'amount' => $request->get('create')['amount']
                ]
            ]);
        }

       return $this->render('client/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
