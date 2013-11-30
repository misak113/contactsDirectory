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

        // kontakty vrací jako prostá pole, ne objekty (kvůly json_encode)
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
        // projde postupně všechny a podle toho uloží jejich pořadí
        foreach ($contacts as $contactArray) {
            // pokud není nastaven id, vyřadí
            if (!isset($contactArray['id']))
                continue;

            // najde v DB
            /** @var Contact $contact */
            $contact = $this->table()
                ->find($contactArray['id']);
            // pokud nenajde, vyřadí
            if (!$contact)
                continue;

            // nastaví pořadí a uloží do db
            $contact->setOrder($order);
            $this->entityManager->persist($contact);
            // vrací pouze uložené a to jako array
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
        // vytvoří kontakt
        $contact = new Contact();
        $contact->setFirstname($contactArray['firstname']);
        $contact->setLastname($contactArray['lastname']);
        $contact->setDegree($contactArray['degree']);
        $contact->setOrder($this->getNextOrder());

        // přidá telefon
        $telephone = $this->createTelephone($contactArray['telephone']);
        $contact->setTelephone($telephone);

        // přidá email
        $email = $this->createEmail($contactArray['email']);
        $contact->setEmail($email);

        // uloží kontakt
        $this->entityManager->persist($contact);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new ContactsException('Při ukládání nastala chyba.', 1, $e);
        }

        // vrací pouze array
        return $contact->__toArray();
    }

    /**
     * @param array $contactArray
     * @return array
     * @throws ContactsException
     */
    public function delete($contactArray) {
        try {
            // pokusí se smazat dle ID
            /** @var Contact $contact */
            $contact = $this->table()->find($contactArray['id']);
            $contactArray = $contact->__toArray();
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new ContactsException('Při mazání nastala chyba.', 1, $e);
        }
        // vrací pole smazaného
        return $contactArray;
    }

    /**
     * Vytvoří a uloží telefon
     * @param string $number
     * @return Telephone
     */
    protected function createTelephone($number) {
        $telephone = new Telephone();
        $telephone->setNumber($number);
        $this->entityManager->persist($telephone); // @todo nevrátí do entity AUTO incremented id
        return $telephone;
    }

    /**
     * Vytvoří a uloží email
     * @param string $emailAddress
     * @return Email
     */
    protected function createEmail($emailAddress) {
        $email = new Email();
        $email->setEmailAddress($emailAddress);
        $this->entityManager->persist($email); // @todo nevrátí do entity AUTO incremented id
        return $email;
    }

    /**
     * Vrátí další pořadí pro zařazení na konec
     * @return int
     */
    protected function getNextOrder() {
        $res = $this->entityManager->getConnection()
            ->executeQuery('SELECT MAX(order_position)+1 AS order_position FROM contact LIMIT 1')
            ->fetch();
        return $res['order_position'];
    }
}

/**
 * Jednotná vyjímka třídy
 * Class ContactsException
 * @package App\ContactsDirectoryBundle\Data
 */
class ContactsException extends \Exception {};