<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Service\FileUploader;


/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    private $validator;
    private $fileUploader;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator, FileUploader $fileUploader)
    {
        parent::__construct($registry, Contact::class);
        $this->validator = $validator;
        $this->fileUploder = $fileUploader;
    }
    /**
     * Save the contact
     * @data Contact data as param
     * return array|json response
     */
    public function saveContact($data)
    {
        $contact = new Contact();
        if (array_diff(['first_name', 'last_name', 'address_information', 'phone_number', 'birthday', 'email_address'], array_keys($data))) {
            return new JsonResponse('Expecting mandatory parameters!', 422);
        }
        $this->prepareEntity($data, $contact);

        $errors = $this->validator->validate($contact);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, 400);
        }
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();
        return $contact->toArray();
    }
    /**
     * Update contact
     * @param $data new data
     * @param $contact entity
     * return array|jsonResponse
     */
    public function updateContact($data, $contact)
    {
        if (array_diff(['first_name', 'last_name', 'address_information', 'phone_number', 'birthday', 'email_address'], array_keys($data))) {
            return new JsonResponse('Expecting mandatory parameters!', 422);
        }
        $this->prepareEntity($data, $contact);
        $errors = $this->validator->validate($contact);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, 400);
        }
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();
        return $contact->toArray();
    }
    /**
     * set the properties on Contact Entity
     */
    public function prepareEntity($data, $contact)
    {
        $date = new \DateTime($data['birthday']);
        return $contact
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setAddressInformation($data['address_information'])
            ->setPhoneNumber($data['phone_number'])
            ->setBirthday($date)
            ->setEmailAddress($data['email_address'])
            ->setPicture($data['picture']);
    }

    /**
    * @return Contact[] Returns an array of Contact objects
    */
    public function findByNameField($value)
    {
        $contacts = $this->createQueryBuilder('contact')
        ->Where('contact.first_name LIKE :value')
        ->setParameter(':value', '%' . $value . '%')
        ->orWhere('contact.last_name LIKE :value')
        ->setParameter(':value', '%' . $value . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
        if(empty($contacts))
        {
            return $contacts;
        }
        return $this->processContacts($contacts);

    }
    /**
     * assign contacts on an array to return
     */
    public function processContacts($contacts)
    {
        foreach($contacts as $contact)
        {
            $processedContacts[] = $contact->toArray();
        }
        return $processedContacts;
    }
    /**
     * delete Contact
     * @param Contact Entity
     */
    public function removeContact(Contact $contact)
    {
        $this->getEntityManager()->remove($contact);
        $this->getEntityManager()->flush();
    }
}
