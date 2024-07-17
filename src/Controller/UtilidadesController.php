<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//mailer various shit
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

//api various shit (falta ver en casa)
use Symfony\Contracts\HttpClient\HttpClientInterface;

// filesystem (crear carpetas, copiar, pegar, etc. por user)
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

// pdf
use Dompdf\Dompdf;

class UtilidadesController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){

    }
    #[Route('/utilidades', name: 'utilidades_inicio')]
    public function index(): Response
    {
        return $this->render('utilidades/index.html.twig');
    }

    #[Route('/utilidades/enviar-email', name: 'utilidades_email')]
    public function enviar_email(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from(new Address('intranet@tpfingenieria.cloud', 'TPF'))
            ->to('roman.kornyeyev@tpfingenieria.com')
            ->cc('anaisabel.pedrajas@tpfingenieria.com', 'javier.corchero@tpfingenieria.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('mail prueba - borrar')
            //->text('texto mail')
            ->html('<p>
                        email de prueba con cc
                        <ul>
                            <li>asd</li>
                            <li>asd</li>
                            <li>asd</li>
                        </ul>
                        <strong>negrita</strong>
                    </p>');

        try{
            // $mailer->send($email);
        }catch(TransportExceptionInterface $e){
            throw new Exception($e);
        }

        return $this->render('utilidades/enviar_email.html.twig');
    }

    /*
        https://www.api.tamila.cl/api/login
        {
            "correo" : "info@tamila.cl",
            "password" : "p2gHNiENUw"
        }
    */

    #[Route('/utilidades/api-rest', name: 'utilidades_api_rest')]
    public function utilidades_api_rest(): Response
    {
        $response = $this->client->request(
            'GET' ,
            'https://www.api.tamila.cl/api/categorias',
            [
                'headers' =>
                [
                    'Authorization' => 'Bearer eyJ0eXAi0iJKV1QiLCIhbGci0iJIUZIINIiJ9.eyJpZCI6MZYsImlhdCI6MTY30Tg1NDU3NSwiZXhwIjoxNMjgyNDQ2NTC1fF0.KqkepI405D154SvR39JpdLPOP-H0473Upen103T2gBM'
                ]
            ]

        );
        $statusCode = $response->getStatusCode();
        echo $statusCode;
        return $this->render('utilidades/api_rest.html.twig');
    }


    #[Route('/utilidades/filesystem', name: 'utilidades_filesystem')]
    public function filesystem(): Response
    {
        $filesystem = new Filesystem();
        $ejemplo_mkdir = "C:\Apache24\htdocs\proyectos_symfony_pruebas\mi_directorio_filesystem";
        if (!$filesystem->exists($ejemplo_mkdir))
        {
            $filesystem->mkdir($ejemplo_mkdir, 0777);
        }else
        {
            $filesystem->copy('C:\Apache24\htdocs\proyectos_symfony_pruebas\imagen_prueba.png', $ejemplo_mkdir."\descarga_cli.png");
            $filesystem->rename($ejemplo_mkdir . "\descarga_cli.png", $ejemplo_mkdir . "\descarga_cli_modificado.png");
            $filesystem->remove([$ejemplo_mkdir . "\descarga_cli_modificado.png"]);
        }


        
        $filesystem->mkdir($ejemplo_mkdir, 0777);
        return $this->render('utilidades/file_system.html.twig');
    }

    #[Route('/utilidades/pdf', name: 'utilidades_pdf')]
    public function pdf(): Response
    {
        return $this->render('utilidades/pdf.html.twig');
    }

    #[Route('/utilidades/pdf/generar', name: 'utilidades_pdf_generar')]
    public function pdf_generar(): Response
    {
        //https://github.com/dompdf/dompdf
        $data = [
            'imageSrc' => $this->imageToBase64($this->getParameter('kernel.project_dir').'\public\img\tpf_logo.png'),
            'nombre' => 'Román',
            'pais' => 'ES_es',
            'telefono' => '+34 600 00 00 00',
            'correo' => 'roman@roman.com'
        ];
        $html = $this->renderView('utilidades/pdf_generar.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->stream('resume', ['Attachment' => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    private function imageToBase64($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


}
