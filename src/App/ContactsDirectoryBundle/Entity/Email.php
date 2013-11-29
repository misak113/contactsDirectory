<?php
/**
 * Created by JetBrains PhpStorm.
 * User: misak113
 * Date: 29.11.13
 * Time: 21:29
 * To change this template use File | Settings | File Templates.
 */

namespace App\ContactsDirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package App\ContactsDirectoryBundle\Entity
 *
 * @ORM\Entity
 */
class Email {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $email_address;

    /**
     * @var Contact
     * @ORM\ManyToOne(targetEntity="Contact")
     */
    protected $contact;

    /**
     * @return string
     */
    public function getEmailAddress() {
        return $this->email_address;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->email_address = $emailAddress;
    }
}