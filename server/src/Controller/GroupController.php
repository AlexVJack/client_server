<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/groups')]
class GroupController extends AbstractController
{
    public function __construct(
        private GroupRepository $groupRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer){
    }

    #[Route('/', name: 'group_index', methods: ['GET'])]
    public function index(): Response
    {
        $groups = $this->groupRepository->findAll();
        $jsonContent = $this->serializer->serialize($groups, 'json');

        return new Response($jsonContent, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/new', name: 'group_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $group = new Group();

        $data = json_decode($request->getContent(), true);
        $group->setName($data['name'] ?? '');

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        $jsonContent = $this->serializer->serialize($group, 'json');

        return new Response($jsonContent, Response::HTTP_CREATED, ['content-type' => 'application/json']);
    }

    #[Route('/{id}', name: 'group_show', methods: ['GET'])]
    public function show(Group $group): Response
    {
        $group = $this->groupRepository->find($group->getId());

        if (!$group) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->serializer->serialize($group, 'json');

        return new Response($jsonContent, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/{id}/edit', name: 'group_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id): Response
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $group->setName($data['name'] ?? '');

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new Response(json_encode($group), Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    #[Route('/{id}', name: 'group_delete', methods: ['DELETE'])]
    public function delete(Group $group): Response
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{groupId}/users/{userId}', name: 'group_add_user', methods: ['POST'])]
    public function addUserToGroup(int $groupId, int $userId): Response
    {
        $group = $this->groupRepository->find($groupId);
        if (!$group) {
            throw new NotFoundHttpException("Group not found.");
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException("User not found.");
        }

        $group->addUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new Response('User added to group successfully.', Response::HTTP_OK);
    }
}
