<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user/insert', name: 'admin_insert_user')]
    public function insertUser(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager):Response
    {
        if($request->getMethod() == "POST"){
            $email = $request->request->get("email");
            $password = $request->request->get("password");

            $user = new User();
            try{
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );
                $user->setEmail($email);
                $user->setPassword($hashedPassword);
                $user->setRoles(["ROLE_ADMIN"]);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'utilisateur créer');
            } catch(\Exception $e){
                // attention,, éviter de récuperer le message
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('admin/user/insert.html.twig');
    }
}