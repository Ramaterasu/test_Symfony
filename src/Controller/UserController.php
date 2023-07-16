<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;





class UserController extends AbstractController
{
    #[Route('/user', name: 'getUsers', methods: ['GET'])]  #
    public function getUsers(UsersRepository $UsersRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $UsersRepository->findAll();
        $jsonUsers = $serializer->serialize($users, 'json');
        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('/user/{email}', name: 'getUser', methods: ['GET'])]
    public function getOne($email, UsersRepository $UsersRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $UsersRepository->findOneByEmail($email);
        if ($user) {
            $jsonUser = $serializer->serialize($user, 'json');
            return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/user/createUser', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        $userFound = $em->getRepository(Users::class)->findOneBy(['username'=>$username, 'email' => $email]);

            if($userFound) {
                return new JsonResponse(['message' => 'User already exists'], Response::HTTP_CONFLICT);
            }

            $user = new Users();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setAddress('address');
            $user->setPhonenumber('phoneNumber');

            $em->persist($user);
            $em->flush();

            return new Jsonresponse(['message' => 'user created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/user/updateUser/{id}', name: 'UPDATE', methods: ['PUT'])]
    public function updateUser($id, request $request, EntityManagerInterface $em, UsersRepository $UsersRepository): Jsonresponse
    {
        $user = $UsersRepository->find($id);

        if(!$user)
        {
            return new JsonResponse(['message'=>'User Not Found !'], Response::HTTP_NOT_FOUND);
        }
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $address= $request->request->get('address');
        $phone = $request->request->get('phoneNumber');

        $userFound = $em->getRepository(Users::class)->findOneBy(['username'=>$username, 'email'=>$email]);

        if ($userFound && $userFound->getId() !== $id)
        {
            return new JsonResponse(['message'=> 'User already exists'], Response::HTTP_CONFLICT);
        }

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setAddress($address);
        $user->setPhonenumber($phone);


        $em->flush();

        return new Jsonresponse(['message' => 'user UPDATED successfully'], Response::HTTP_CREATED);

    }

    #[Route('/user/deleteUser/{id}', name: 'DELETE', methods: ['DELETE'])]
    public function deleteUser($id, request $request, EntityManagerInterface $em, UsersRepository $UsersRepository): Jsonresponse
    {
        $user = $UsersRepository->find($id);

        if(!$user)
        {
            return new JsonResponse(['message'=>'User Not Found !'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['message'=>'Successfully deleted !'], Response::HTTP_CREATED);
    }

}
