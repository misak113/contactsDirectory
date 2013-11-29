<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 28.11.13
 * Time: 23:57
 */

namespace App\ContactsDirectoryBundle\Data;


use Doctrine\ORM\EntityManager;
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
        throw new NotImplementedException('getContacts');
        $contacts = $this->table()
            ->createQueryBuilder('contact')
            ->select('contact.*')
            ->orderBy('order_position')
            ->getQuery()
            ->execute();
        if (!$contacts)
            throw new ContactsException('Při získání kontaktů nastala chyba.');

        return $contacts;
    }
}

class ContactsException extends \Exception {};