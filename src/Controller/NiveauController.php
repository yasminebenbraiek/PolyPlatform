<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Niveau;
use App\Entity\Filiere;
use App\Entity\Groupe;

class NiveauController extends AbstractController
{
    #[Route('/niveau', name: 'app_niveau')]
    public function index(): Response
    {
        return $this->render('niveau/index.html.twig', [
            'controller_name' => 'NiveauController',
        ]);
    }

    #[Route('/niveau/add', name: 'add_niveau')]

    public function add(Request $request)
    {
        $niveau = new Niveau();
        $fb = $this ->createFormBuilder($niveau)
                    ->add('libelleN')
                    ->add('Filiere',EntityType::class, [
                        'class' => Filiere::class,
                        'choice_label' => 'libelleF',
                    ])
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            return $this->redirectToRoute('consult_niveau');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('niveau/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/niveau/show/{id}', name: 'niveau')]

    public function afficherById($id, Request $request)
    {
        $niveau = $this->getDoctrine()
        ->getRepository(Niveau::class)
        ->find($id);
        if (!$niveau)
        {
            throw $this->createNotFoundException(
                'No niveau found for id '.$id
            );
        }
        $em=$this->getDoctrine()->getManager();
        $groupe=$em->getRepository(Groupe::class);
        $lesGroupes=$em
        ->getRepository(Groupe::class)
        ->findBy(['Niveau'=>$niveau]);

        return $this->render('niveau/niveau.html.twig' , [
             'Niveau' =>$niveau,
             'groupe'=>$groupe,
             'lesGroupes'=>$lesGroupes,

        ]);
    }

    #[Route('/niveau/consult', name: 'consult_niveau')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Niveau::class);
        $lesNiveaux = $repo->findAll();
        return $this->render('niveau/consult.html.twig',
        ['lesNiveaux'=> $lesNiveaux]);
    }

    #[Route('/niveau/delete/{id}', name: 'delete_niveau')]

    public function delete(Request $request, $id): Response
    {
        $niveau=$this->getDoctrine()
        ->getRepository(Niveau::class)
        ->find($id);
        if(!$niveau){
            throw $this->createNotfoundException(
                'No niveau found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $groupe=$entityManager->getRepository(Groupe::class);
        $lesGroupes=$entityManager
        ->getRepository(Groupe::class)
        ->findBy(['niveau'=>$niveau]);

        foreach($lesGroupes as $g){
            $entityManager->remove($g);
        }
        $entityManager->remove($niveau);

        $entityManager->flush();
        return $this->redirectToRoute('consult_niveau');
    }

    #[Route('/niveau/edit/{id}', name: 'edit_niveau', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $niveau = new Niveau();
        $niveau = $this->getDoctrine()->getRepository(Niveau::class) ->find($id);
        if (!$niveau) {
            throw $this->createNotFoundException(
            'No niveau found for id '.$id
            );
        }
        $fb = $this ->createFormBuilder($niveau)
                    ->add('libelleN')
                    ->add('Filiere',EntityType::class, [
                        'class' => Filiere::class,
                        'choice_label' => 'libelleF',
                    ])
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('consult_niveau');
        }
        return $this->render('niveau/add.html.twig',
        ['f' => $form->createView()] );
    }
}
