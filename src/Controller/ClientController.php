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

    /**
     * Create new item
     * @Route("/create")
     */
    public function create(Request $request)
    {
        //TODO zmienić na jakiś lepszy sposób
        $form = $this->createForm(CreateType::class);

        if(!empty($request->get('create')['name'])) {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/add', [
                'form_params' => [
                    'name' => $request->get('create')['name'],
                    'amount' => $request->get('create')['amount']
                ]
            ]);
        }

       return $this->render('client/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
