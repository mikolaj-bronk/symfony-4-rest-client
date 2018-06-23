<?php

namespace App\Controller;

use App\Dictionary\{
    MessageDictionary,
    UrlDictionary
};
use App\Form\{
    CreateType,
    DeleteType,
    UpdateType
};
use App\Services\ItemService;
use Symfony\Component\HttpFoundation\{
    Response,
    Request
};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;
use stdClass;

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
    public function getAll(): Response
    {
        $items = $this->items_service->getAllItems();

        return $this->render('client/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * Display items where amount is greater than zero
     * @Route("/items/available", name="items_available")
     */
    public function getAvailable() : Response
    {
        $items = $this->items_service->getAvailableItems();

        return $this->render('client/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * Display items where amount is equal to zero
     * @Route("/items/unavailable", name="items_unavailable")
     */
    public function getUnavailable() : Response
    {
        $items = $this->items_service->getUnavailableItems();

        return $this->render('client/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * Display items where amount is greater than
     * @Route("/items/available/{amount}", name="items_greater_than")
     */
    public function getGreaterThanFive(int $amount) : Response
    {
        $items = $this->items_service->getGreaterThanItems($amount);

        return $this->render('client/index.html.twig', [
            'items' => $items,
            'amount' => $amount,
        ]);
    }

    /**
     * Update item
     * @Route("/update/{id}", name="item_update")
     */
    public function update(int $id, Request $request): Response
    {
        $form = $this->createFormForUpdate(
            $this->items_service->getOneItem($id)
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->get('update');
            return $this->updateItem($id, $formData);
        }

        return $this->render('client/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function updateItem(int $id, array $formData)
    {
        $message = $this->items_service->updateItem(
            $id,
            $formData['name'],
            $formData['amount']
        );

        $this->addFlash(MessageDictionary::SUCCESS_CLASS, $message);
        return $this->redirect(UrlDictionary::GET_ALL_ITEMS_URL);
    }

    private function createFormForUpdate(stdClass $item)
    {
        return $this->createForm(UpdateType::class, [
            'name' => $item->name,
            'amount' => $item->amount,
        ]);
    }

    /**
     * Create new item
     * @Route("/items/create", name="item_create")
     */
    public function create(Request $request) : Response
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->get('create');

            $message = $this->items_service->createItem(
                $formData['name'],
                $formData['amount']
            );

            $this->addFlash(MessageDictionary::SUCCESS_CLASS, $message);

            return $this->redirect(UrlDictionary::GET_ALL_ITEMS_URL);
        }

        return $this->render('client/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete item
     * @Route("/delete/{id}", name="item_delete")
     */
    public function delete(int $id) : Response
    {
        $message = $this->items_service->deleteItem($id);
        $this->addFlash(MessageDictionary::SUCCESS_CLASS, $message);

        return $this->redirect(UrlDictionary::GET_ALL_ITEMS_URL);
    }
}
