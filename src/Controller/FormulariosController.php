<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Entity\PersonaEntity;
use App\Entity\PersonaEntityValidation;
use App\Entity\PersonaEntityUpload;
use App\Form\PersonaEntityFormType;
use App\Form\PersonaValidationType;
use App\Form\PersonaUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormulariosController extends AbstractController
{
    #[Route('/formularios', name: 'formularios_inicio')]
    public function index(): Response
    {
        return $this->render('formularios/index.html.twig');
    }

    #[Route('/formularios/simple', name: 'formulario_simple')]
    public function simple(Request $request): Response
    {
        $form = $this->createFormBuilder(null)
                ->add('nombre', TextType::class, ['label' => 'nombre'])
                ->add('correo', TextType::class, ['label' => 'email'])
                ->add('telefono', TextType::class, ['label' => 'telefono'])
                ->add('save', SubmitType::class)
                ->getForm();

        $submittedToken=$request->request->get('token');

        $form->handleRequest($request);
        // si viene la petición POST del formulario
        if($form->isSubmitted())
        {
            if ($this->isCsrfTokenValid('generico', $submittedToken)) {
                $campos = $form->getData();
                // print_r($campos);
                echo "Nombre:".$campos['nombre']."correo:".$campos['correo']."telefono:".$campos['telefono'];
                die();
            }else{
                $this->addFlash('css','warning');
                $this->addFlash('mensaje','Ocurrió un error inesperado');
                return $this->redirectToRoute('formulario_simple');
            }
        }
        return $this->render('formularios/simple.html.twig', compact('form'));
    }



    #[Route('/formularios/entity', name: 'formulario_entity')]
    public function entity(Request $request): Response
    {
        $persona = new Persona;
        $form = $this->createFormBuilder($persona)
                ->add('nombre', TextType::class, ['label' => 'nombre'])
                ->add('correo', TextType::class, ['label' => 'email'])
                ->add('telefono', TextType::class, ['label' => 'telefono'])
                ->add('save', SubmitType::class)
                ->getForm();

        $submittedToken=$request->request->get('token');

        $form->handleRequest($request);
        // si viene la petición POST del formulario
        if($form->isSubmitted())
        {
            if ($this->isCsrfTokenValid('generico', $submittedToken)) {
                $campos = $form->getData();
                // print_r($campos);
                echo "Nombre:".$campos->getNombre()."correo:".$campos->getCorreo()."telefono:".$campos->getTelefono();
                die();
            }else{
                $this->addFlash('css','warning');
                $this->addFlash('mensaje','Ocurrió un error inesperado');
                return $this->redirectToRoute('formulario_simple');
            }
        }
        return $this->render('formularios/entity.html.twig', compact('form'));
    }



    #[Route('/formularios/type-form', name: 'formulario_type_form')]
    public function type_form(Request $request): Response
    {
        
        $persona = new PersonaEntity();
        $form = $this->createForm(PersonaEntityFormType::class, $persona);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken))
            {
                $campos = $form->getData();
                echo "Nombre: " . $campos->getNombre() . 
                     "Correo: " . $campos->getCorreo() . 
                     "Telefono: " . $campos->getTelefono() . 
                     "Intereses: " . implode(", ", $campos->getIntereses());
                die();
            }else
            {
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('formulario_type_form');
            }
        }

        return $this->render('formularios/type_form.html.twig', compact('form'));
    }




    #[Route('/formularios/validation', name: 'formulario_valitation')]
    public function validation(Request $request, ValidatorInterface $validator): Response
    {
        $persona = new PersonaEntityValidation();
        $form=$this->createForm(PersonaValidationType::class, $persona);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken))
            {
                $errors = $validator->validate($persona);
                if(count($errors) != 0)
                {
                    return $this->render('formularios/validation.html.twig', 
                    ['form' => $form, 'errors' => $errors]);
                }else
                {
                    $campos = $form->getData();
                    echo "Nombre: " . $campos->getNombre() . 
                         "Correo: " . $campos->getCorreo() . 
                         "Telefono: " . $campos->getTelefono() . 
                         "Pais: " . $campos->getPais() . 
                         "Intereses: " . implode(", ", $campos->getIntereses());
                    die(); 
                }
            }else
            {
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('formulario_validation');
            }
        }

        return $this->render('formularios/validation.html.twig', ['form' => $form, 'errors' => array()]);
    }




    #[Route('/formularios/upload', name: 'formulario_upload')]
    public function upload(Request $request, ValidatorInterface $validator, SluggerInterface $slugger): Response
    {
        $persona = new PersonaEntityUpload();
        $form=$this->createForm(PersonaUploadType::class, $persona);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('generico', $submittedToken))
            {
                $errors = $validator->validate($persona);
                if(count($errors) != 0)
                {
                    return $this->render('formularios/upload.html.twig', 
                    ['form' => $form, 'errors' => $errors]);
                }else
                {
                    $foto = $form->get('foto')->getData();
                    if($foto)
                    {
                        $originalName=pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFileName = time() . '.' . $foto->guessExtension();

                        
                        // $safeFilename = $slugger->slug($originalName);
                        // $newFileName = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();

                        
                        try {
                            $foto->move(
                                $this->getParameter('fotos_directory'),
                                $newFileName
                            );
                        } catch (FileException $th) {
                            throw \Exception("mensaje", "Ups, ocurrio un error al intentar subir el archivo");
                        }
                        $persona->setFoto($newFileName);
                    }
                    $campos = $form->getData();
                    echo "Nombre: " . $campos->getNombre() . " | " .
                         "Correo: " . $campos->getCorreo() . " | " .
                         "Telefono: " . $campos->getTelefono() . " | " .
                         "Pais: " . $campos->getPais() . " | " .
                         "Intereses: " . implode(", ", $campos->getIntereses()) . " | " .
                         "Foto: " . $newFileName;
                    die(); 
                }
            }else
            {
                $this->addFlash('css', 'warning');
                $this->addFlash('mensaje', 'Ocurrió un error inesperado');
                return $this->redirectToRoute('formulario_upload');
            }
        }

        return $this->render('formularios/upload.html.twig', ['form' => $form, 'errors' => array()]);
    }

}
