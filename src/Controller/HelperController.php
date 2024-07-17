<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Utilidades\Utilidades;

class HelperController extends AbstractController
{
    #[Route('/helper', name: 'helper_inicio')]
    public function index(): Response
    {
        $saludo1 = Utilidades::saludo("uno");
        $aux = new Utilidades();
        $saludo2=$aux->saludo2("dos");
        return $this->render('helper/index.html.twig', compact('saludo1', 'saludo2'));
    }
}
