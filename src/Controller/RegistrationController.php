<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Etudiant;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/admin/register', name: 'register_user')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('consult_user');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/consult', name: 'consult_user')]
    public function consult(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(User::class);
        $users = $repo->findAll();        
        return $this->render('registration/consult.html.twig',
        ['users'=> $users]);
    }

    #[Route('/admin/delete/{id}', name: 'delete_user')]

    public function delete(Request $request, $id): Response
    {
        $user=$this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);
        if(!$user){
            throw $this->createNotfoundException(
                'No user found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $etudiant=$entityManager
        ->getRepository(Etudiant::class)
        ->findBy(['User'=>$user]);
        foreach($etudiant as $e){
        $e->setUser(null);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('consult_user');
    }

}
