<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 28.11.13
 * Time: 22:54
 */

namespace App\ContactsDirectoryBundle\Controller;

use App\ContactsDirectoryBundle\Data\ContactsData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContactsDirectoryController extends Controller {
    /** @var ContactsData @inject */
    protected $contactData;

    public function __construct(ContactsData $contactData) {
        $this->contactData = $contactData;
    }

    /**
     * @Route("/contacts-directory", name="_app_contacts_directory")
     * @Template()
     */
    public function listAction() {

        $contacts = $this->contactData->getContacts();

        return array(
            'contacts' => $contacts
        );
    }

} 