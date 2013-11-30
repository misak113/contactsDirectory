<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 29.11.13
 * Time: 18:19
 */

namespace App\ContactsDirectoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
    protected $id;

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
    protected $degree;

    /**
     * @var Telephone[]
     * @ORM\OneToMany(targetEntity="Telephone", mappedBy="contact")
     */
    protected $telephones;

    /**
     * @var Email[]
     * @ORM\OneToMany(targetEntity="Email", mappedBy="contact")
     */
    protected $emails;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

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
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }
    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }
    /**
     * @param string $degree
     * @return $this
     */
    public function setDegree($degree) {
        $this->degree = $degree;
        return $this;
    }
    /**
     * @param Telephone $telephone
     * @return $this
     */
    public function setTelephone($telephone) {
        if (!$this->telephones)
            $this->telephones = new ArrayCollection();
        $telephone->setContact($this);
        $this->telephones[] = $telephone;
        return $this;
    }
    /**
     * @param Email $email
     * @return $this
     */
    public function setEmail($email) {
        if (!$this->emails)
            $this->emails = new ArrayCollection();
        $email->setContact($this);
        $this->emails[] = $email;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTelephones() {
        $array = array();
        foreach ($this->telephones as $telephone) {
            $array[] = $telephone->getNumber();
        }
        return $array;
    }

    /**
     * @return string[]
     */
    public function getEmails() {
        $array = array();
        foreach ($this->emails as $email) {
            $array[] = $email->getEmailAddress();
        }
        return $array;
    }

    /**
     * @return string
     */
    public function getEmail() {
        if (count($this->emails) == 0)
            return '';
        return $this->emails[0]->getEmailAddress();
    }

    /**
     * return array representation of contact (not magic yet)
     * @return array
     */
    public function __toArray() {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'email' => $this->getEmail(),
            'emails' => $this->getEmails(),
            'telephones' => $this->getTelephones(),
        );
    }

    /**
     * Nastaví pořadí kontaktu v seznamu
     * @param int $order
     */
    public function setOrder($order) {
        $this->order_position = $order;
    }
} 