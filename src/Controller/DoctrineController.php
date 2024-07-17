<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\CategoriaFormType;


class DoctrineController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/doctrine', name: 'doctrine_inicio')]
    public function index(): Response
    {
        return $this->render('doctrine/index.html.twig');
    }

    #[Route('/doctrine/categorias', name: 'doctrine_categorias')]
    public function categorias(): Response
    {
        // $datos = $entityManager->getRepository(Categoria::class)->findAll();
        $datos = $this->entityManager->getRepository(Categoria::class)->findBy(array(), array('id'=>'desc'));

        return $this->render('doctrine/categorias.html.twig', compact('datos'));
    }

    #[Route('/doctrine/categorias/add', name: 'doctrine_categorias_add')]
    public function categorias_add(Request $request, ValidatorInterface $validator, SluggerInterface $slugger): Response
    {
        $entity = new Categoria();
        $form = $this->createForm(CategoriaFormType::class, $entity);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken)) {
                $errors = $validator->validate($entity);
                if (count($errors) != 0) {
                    return $this->render('doctrine/categorias_add.html.twig', compact('form', 'errors'));
                }else{

                }
            }else{
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('doctrine_categorias_add');
            }
        }

        return $this->render('doctrine/categorias_add.html.twig', ['form' => $form, 'errors'=>array()]);
    }
}
