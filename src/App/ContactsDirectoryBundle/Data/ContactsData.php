<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 28.11.13
 * Time: 23:57
 */

namespace App\ContactsDirectoryBundle\Data;


use App\ContactsDirectoryBundle\Entity\Contact;
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
}

class ContactsException extends \Exception {};