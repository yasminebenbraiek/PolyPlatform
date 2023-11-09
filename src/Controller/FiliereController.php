<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Filiere;
use App\Entity\Niveau;
use App\Entity\Groupe;
use App\Entity\Etudiant;

class FiliereController extends AbstractController
{
    #[Route('/filiere', name: 'app_filiere')]
    public function index(): Response
    {
        return $this->render('filiere/index.html.twig', [
            'controller_name' => 'FiliereController',
        ]);
    }

    #[Route('/filiere/add', name: 'add_filiere')]

    public function add(Request $request)
    {
        $filiere = new Filiere();
        $fb = $this ->createFormBuilder($filiere)
                    ->add('libelleF')
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($filiere);
            $em->flush();
            return $this->redirectToRoute('consult_filiere');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('filiere/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/filiere/show/{id}', name: 'filiere')]

    public function afficherById($id, Request $request)
    {
        $filiere = $this->getDoctrine()
        ->getRepository(Filiere::class)
        ->find($id);
        if (!$filiere)
        {
            throw $this->createNotFoundException(
                'No filiere found for id '.$id
            );
        }
        $em=$this->getDoctrine()->getManager();
        $niveau=$em->getRepository(Niveau::class);
        $lesNiveaux=$em
        ->getRepository(Niveau::class)
        ->findBy(['Filiere'=>$filiere]);

        return $this->render('filiere/filiere.html.twig' , [
             'Filiere' =>$filiere,
             'niveau'=>$niveau,
             'lesNiveaux'=>$lesNiveaux,

        ]);
    }

    #[Route('/filiere/consult', name: 'consult_filiere')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Filiere::class);
        $lesFilieres = $repo->findAll();
        return $this->render('filiere/consult.html.twig',
        ['lesFilieres'=> $lesFilieres]);
    }

    #[Route('/filiere/delete/{id}', name: 'delete_filiere')]

    public function delete(Request $request, $id): Response
    {
        $filiere=$this->getDoctrine()
        ->getRepository(Filiere::class)
        ->find($id);
        $entityManager=$this->getDoctrine()->getManager();
        $niveau=$entityManager->getRepository(Niveau::class);
        $lesNiveaux=$entityManager
        ->getRepository(Niveau::class)
        ->findBy(['Filiere'=>$filiere]);

        foreach($lesNiveaux as $n){
            $groupe=$entityManager->getRepository(Groupe::class);
            $lesGroupes=$entityManager
            ->getRepository(Groupe::class)
            ->findBy(['Niveau'=>$n]);

            foreach($lesGroupes as $g){
                $etudiant=$entityManager->getRepository(Etudiant::class);
                $lesEtudiants=$entityManager
                ->getRepository(Etudiant::class)
                ->findBy(['Groupe'=>$g]);

                foreach($lesEtudiants as $et){
                    $et->setGroupe(null);
                }       
                $entityManager->remove($g);
            }
            
            $entityManager->remove($n);
        }
        $entityManager->remove($filiere);

        $entityManager->flush();
        return $this->redirectToRoute('consult_filiere');
    }

    #[Route('/filiere/edit/{id}', name: 'edit_filiere', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $filiere = new Filiere();
        $filiere = $this->getDoctrine()->getRepository(Filiere::class) ->find($id);
        if (!$filiere) {
            throw $this->createNotFoundException(
            'No filiere found for id '.$id
            );
        }
        $fb = $this ->createFormBuilder($filiere)
                    ->add('libelleF')
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('consult_filiere');
        }
        return $this->render('filiere/add.html.twig',
        ['f' => $form->createView()] );
    }
}
