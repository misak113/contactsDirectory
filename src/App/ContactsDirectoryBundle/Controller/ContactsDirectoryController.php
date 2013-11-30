<?php
/**
 * Created by PhpStorm.
 * User: misak113
 * Date: 28.11.13
 * Time: 22:54
 */

namespace App\ContactsDirectoryBundle\Controller;

use App\ContactsDirectoryBundle\Data\ContactsData;
use App\ContactsDirectoryBundle\Data\ContactsException;
use App\ContactsDirectoryBundle\Data\ContactValidator;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidatorException;

class ContactsDirectoryController extends Controller {
    /** @var ContactsData @inject */
    protected $contactData;
    /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface|\Symfony\Component\HttpFoundation\Session\SessionBagInterface @inject */
    protected $flashes;
    /** @var ContactValidator @inject */
    protected $contactValidator;

    public function inject(ContactsData $contactData, Session $session, ContactValidator $contactValidator) {
        $this->contactData = $contactData;
        $this->flashes = $session->getFlashBag();
        $this->contactValidator = $contactValidator;
    }

    /**
     * Vypíše seznam kontaktů angularem
     * @Route("/contacts-directory", name="_app_contacts_directory")
     * @Template()
     */
    public function listAction() {

        try {
            // získá data se seznamem
            $contacts = $this->contactData->getContacts();
        } catch (ContactsException $e) {
            $contacts = array();
            $this->flashes->add('notice', $e->getMessage());
        }

        return array(
            'contacts' => $contacts
        );
    }

    /**
     * Uloží pořadí kontaktů
     */
    public function saveOrderAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contacts']))
            throw new NotFoundHttpException();

        // uloží pořadí do dat
        $contacts = $post['contacts'];
        $contacts = $this->contactData->saveOrder($contacts);

        // response
        $response = new JsonResponse();
        $response->setData(array(
            'contacts' => $contacts
        ));
        return $response;
    }

    /**
     * Přidá kontakt
     */
    public function addAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contact']))
            throw new NotFoundHttpException();

        // zvaliduje a pokud není ok, vrátí hlášku chyby
        $contact = $post['contact'];
        try {
            $this->contactValidator->validate($contact);
        } catch (ValidatorException $e) {
            // hláška chyby s statusCode 400
            $response = new JsonResponse(array('error' => $e->getMessage()));
            $response->setStatusCode(400);
            return $response;
        }
        // přidá pokud jsou validní data
        $contact = $this->contactData->add($contact);

        // response
        $response = new JsonResponse();
        $response->setData(array(
            'contact' => $contact
        ));
        return $response;
    }

    /**
     * Smaže kontakt
     */
    public function deleteAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contact']))
            throw new NotFoundHttpException();

        // smaže kontakt
        $contact = $post['contact'];
        $contact = $this->contactData->delete($contact);

        // response
        $response = new JsonResponse();
        $response->setData(array(
            'contact' => $contact
        ));
        return $response;
    }

} 