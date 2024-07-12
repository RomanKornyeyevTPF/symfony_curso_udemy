<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TemplateController extends AbstractController
{
    #[Route('/template', name: 'template_inicio')]
    public function index(): Response
    {
        return $this->render("template/index.html.twig");
    }

    #[Route('/template/params/{id}/{slug}', name: 'template_params')]
    // Se puede añadir también defaults para que no se reciban los parametros, meter esto entre paréntesis:
    // defaults:['id'=>0, 'slug'=>'']
    public function params(int $id = 0, string $slug = ''): Response
    {
        die("id={$id} | slug={$slug}");
    }

    #[Route('/template/excepcion', name: 'template_excepcion')]
    public function excepcion(): Response
    {
        throw $this->createNotFoundException("Esta URL no está disponible");
    }

    #[Route('/template/trabajo', name: 'template_trabajo')]
    public function trabajo(): Response
    {
        //interpolación
        return $this->render("template/trabajo.html.twig", [ 'nombre' => 'César' ]);
    }

    #[Route('/template/layout', name: 'template_layout')]
    public function layout(): Response
    {
        return $this->render("template/layout.html.twig");
    }
}