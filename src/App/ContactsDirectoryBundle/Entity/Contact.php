<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 29.11.13
 * Time: 18:19
 */

namespace App\ContactsDirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * Class Contact
 * @package App\ContactsDirectoryBundle\Entity
 *
 * @ORM\Entity
 */
class Contact {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $contact_id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $order_position;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    public $degree;

    /**
     * @return string
     */
    public function getName() {
        return $this->firstname.' '.$this->lastname.($this->degree?', '.$this->degree:'');
    }

    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getDegree() {
        return $this->degree;
    }

    /**
     * @return string[]
     */
    public function getTelephones() {
        throw new NotImplementedException('getTelephones');
    }

    /**
     * @return string[]
     */
    public function getEmails() {
        throw new NotImplementedException('getEmails');
    }

    /**
     * @return string
     */
    public function getEmail() {
        throw new NotImplementedException('getEmail');
    }

} 