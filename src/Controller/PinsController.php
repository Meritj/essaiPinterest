<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{

    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository  $repo): Response
    {
        /* ai enleve : EntityManagerInterface $em des parametres de la fonction$pin3 = new Pin();

        $pin3->setTitle('La recette pourrie');
        $pin3->setDescription('Voici une recette qui pue');
        dump($pin3);

        $em->persist($pin3);
        $em->flush();

        $repo = $em->getRepository(Pin::class);

        $pins=$repo->findAll();*/

        return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show")
     */
    public function show(Pin $pin):Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em):Response
    {
        $pin = new Pin;

       $form = $this->createFormBuilder($pin)
            ->add('title', null, /*TextType::class,*/
                ['attr' => ['autofocus'=> true ]])

           ->add('description', null /*TextareaType::class*/, [
                'attr'=> ['rows'=> 10, 'cols'=> 50 ]])

           /*->
           MAUVAISE PRATIQUE : mettre le bouton ici --> on le met dans la vue
           add('submit',SubmitType::class, ['label'=>'Create Pin'])
*/
           ->getForm()
        ;

       $form->handleRequest($request);

       if ($form->isSubmitted()&&$form->isValid()){
           /*$data = $form->getData();
            $pin = new Pin();
            $pin->setTitle($data['title']);
            $pin->setDescription($data['description']);*/
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
       }

        return $this->render('pins/create.html.twig', [
                'monFormulaire'=>$form->createView()
        ]);

    }
}


    /*  if ($request->isMethod('POST')){
            $data = $request->request->all();

            if($this->isCsrfTokenValid('pins.create', $data['_token'])){
                $pin = new Pin();
                $pin->setTitle($data['title']);
                $pin->setDescription($data['description']);
                $em->persist($pin);
                $em->flush();
            }

            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig');
    } */