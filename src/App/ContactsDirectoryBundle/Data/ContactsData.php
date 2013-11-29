<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 28.11.13
 * Time: 23:57
 */

namespace App\ContactsDirectoryBundle\Data;


use App\ContactsDirectoryBundle\Entity\Contact;
use App\ContactsDirectoryBundle\Entity\Email;
use App\ContactsDirectoryBundle\Entity\Telephone;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Intl\Exception\NotImplementedException;

class ContactsData {

    /** @var EntityManager @inject */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function table() {
        return $this->entityManager->getRepository('App\ContactsDirectoryBundle\Entity\Contact');
    }

    /**
     * @throws ContactsException
     * @return array
     */
    public function getContacts() {
        /** @var Contact[] $contacts */
        $contacts = $this->table()
            ->createQueryBuilder('contact')
            ->orderBy('contact.order_position')
            ->getQuery()
            ->execute();
        if ($contacts === false)
            throw new ContactsException('Při získání kontaktů nastala chyba.');

        $array = array();
        foreach ($contacts as $contact) {
            $array[] = $contact->__toArray();
        }

        return $array;
    }

    /**
     * @param array $contacts
     * @return array
     * @throws ContactsException
     */
    public function saveOrder($contacts) {
        $contactsArray = array();
        $order = 1;
        foreach ($contacts as $contactArray) {
            if (!isset($contactArray['id']))
                continue;

            /** @var Contact $contact */
            $contact = $this->table()
                ->find($contactArray['id']);
            if (!$contact)
                continue;

            $contact->setOrder($order);
            $this->entityManager->persist($contact);
            $contactsArray[] = $contact->__toArray();
            $order++;
        }
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new ContactsException('Při ukládání nastala chyba.', 1, $e);
        }
        return $contactsArray;
    }

    /**
     * @param array $contactArray
     */
    public function add($contactArray) {
        $contact = new Contact();
        $contact->setFirstname($contactArray['firstname']);
        $contact->setLastname($contactArray['lastname']);
        $contact->setDegree($contactArray['degree']);
        $contact->setOrder($this->getNextOrder());

        $telephone = $this->createTelephone($contactArray['telephone']);
        $contact->setTelephone($telephone);

        $email = $this->createEmail($contactArray['email']);
        $contact->setEmail($email);

        $this->entityManager->persist($contact);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new ContactsException('Při ukládání nastala chyba.', 1, $e);
        }

        return $contact->__toArray();
    }

    /**
     * @param array $contactArray
     * @return array
     * @throws ContactsException
     */
    public function delete($contactArray) {
        try {
            /** @var Contact $contact */
            $contact = $this->table()->find($contactArray['id']);
            $contactArray = $contact->__toArray();
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new ContactsException('Při mazání nastala chyba.', 1, $e);
        }
        return $contactArray;
    }

    /**
     * @param string $number
     * @return Telephone
     */
    protected function createTelephone($number) {
        $telephone = new Telephone();
        $telephone->setNumber($number);
        $this->entityManager->persist($telephone);
        return $telephone;
    }

    /**
     * @param string $emailAddress
     * @return Email
     */
    protected function createEmail($emailAddress) {
        $email = new Email();
        $email->setEmailAddress($emailAddress);
        $this->entityManager->persist($email);
        return $email;
    }

    /**
     * @return int
     */
    protected function getNextOrder() {
        $res = $this->entityManager->getConnection()
            ->executeQuery('SELECT MAX(order_position)+1 AS order_position FROM contact LIMIT 1')
            ->fetch();
        return $res['order_position'];
    }
}

class ContactsException extends \Exception {};