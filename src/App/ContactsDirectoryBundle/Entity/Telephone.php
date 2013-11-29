<?php
/**
 * Created by JetBrains PhpStorm.
 * User: misak113
 * Date: 29.11.13
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */

namespace App\ContactsDirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Telephone
 * @package App\ContactsDirectoryBundle\Entity
 *
 * @ORM\Entity
 */
class Telephone {
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Contact
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="telephones")
     */
    protected $contact;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $number;

    /**
     * @return string
     */
    public function getNumber() {
        return $this->number;
    }

}