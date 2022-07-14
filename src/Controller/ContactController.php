<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    #[Route('/api/contact/{name}', name: 'get_contacts_by_name', methods: ['GET'])]
    public function getByName($name): JsonResponse
    {
        $contacts = $this->contactRepository->findByNameField($name);
        if(empty($contacts))
        {
            return $this->json('No content', Response::HTTP_NO_CONTENT);
        }
        return $this->json($contacts, Response::HTTP_OK);
    }

    #[Route('/api/contact', name: 'add_contact', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->contactRepository->saveContact($data);
        if(!is_array($response))
        {
            return $response;
        }
        return $this->json($response, Response::HTTP_CREATED);
    }
    /**
     * @Route("/api/contact/{id}", name="delete_contact", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        if(empty($contact))
        {
            return $this->json(['status' => 'Contact not found'], Response::HTTP_NOT_FOUND);
        }
        $this->contactRepository->removeContact($contact);
        return new JsonResponse(['status' => 'Contact deleted'], Response::HTTP_NO_CONTENT);
    }
    /**
     * @Route("/api/contact/{id}", name="update_contact", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        if(empty($contact))
        {
            return $this->json(['status' => 'Contact not found'], Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);

        $updatedContact = $this->contactRepository->updateContact($data, $contact);
        if(!is_array($updatedContact))
        {
            return $updatedContact;
        }

        return new JsonResponse($updatedContact, Response::HTTP_OK);
    }
}
