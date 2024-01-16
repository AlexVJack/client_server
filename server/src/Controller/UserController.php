<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer){
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        $jsonContent = $this->serializer->serialize($users, 'json');

        return new Response($jsonContent, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/new', name: 'user_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $data = json_decode($request->getContent(), true);

        $user->setName($data['name'] ?? '');
        $user->setEmail($data['email'] ?? '');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $jsonContent = $this->serializer->serialize($user, 'json');

        return new Response($jsonContent, Response::HTTP_CREATED, ['content-type' => 'application/json']);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->serializer->serialize($user, 'json');

        return new Response($jsonContent, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $user->setName($data['name'] ?? '');
        $user->setEmail($data['email'] ?? '');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $jsonContent = $this->serializer->serialize($user, 'json');

        return new Response($jsonContent, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
