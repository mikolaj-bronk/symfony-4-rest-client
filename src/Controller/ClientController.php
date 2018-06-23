<?php

namespace App\Controller;

use App\Dictionary\UrlDictionary;
use App\Entity\Items;
use App\Form\CreateType;
use App\Form\DeleteType;
use App\Services\ItemService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{
    Response,
    Request
};

use GuzzleHttp\Client;

class ClientController extends Controller
{
    private $client;
    private $items_service;

    public function __construct(ItemService $itemService)
    {
        $this->items_service = $itemService;
        $this->client = new Client();
    }

    /**
     * Display all items
     * @Route("/items/", name="items_all")
     */
    public function getAll()
    {
        $items = $this->items_service->getAllItems();

        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is greater than zero
     * @Route("/items/available", name="items_available")
     */
    public function getAvailable()
    {
        $items = $this->items_service->getAvailableItems();

        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is equal to zero
     * @Route("/items/unavailable", name="items_unavailable")
     */
    public function getUnavailable()
    {
        $items = $this->items_service->getUnavailableItems();

        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display items where amount is greater than five
     * @Route("/items/greaterthan5", name="items_greater_than_five")
     */
    public function getGreaterThanFive()
    {
        $items = $this->items_service->getGreaterThanFiveItems();

        return $this->render('client/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Create new item
     * @Route("/items/create", name="item_create")
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $request->get('create')['name'];
            $amount = $request->get('create')['amount'];
            $this->items_service->createItem($name, $amount);
            return $this->redirect(UrlDictionary::GET_ALL_ITEMS_URL);
        }

        return $this->render('client/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete item
     * @Route("/items/delete/{id}", name="item_delete")
     */
    public function delete(int $id)
    {
        $this->items_service->deleteItem($id);

        return $this->redirect(UrlDictionary::GET_ALL_ITEMS_URL);
    }
}
