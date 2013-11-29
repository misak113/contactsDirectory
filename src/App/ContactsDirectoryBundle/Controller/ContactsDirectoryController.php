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
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactsDirectoryController extends Controller {
    /** @var ContactsData @inject */
    protected $contactData;
    /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface|\Symfony\Component\HttpFoundation\Session\SessionBagInterface @inject */
    protected $flashes;

    public function inject(ContactsData $contactData, Session $session) {
        $this->contactData = $contactData;
        $this->flashes = $session->getFlashBag();
    }

    /**
     * @Route("/contacts-directory", name="_app_contacts_directory")
     * @Template()
     */
    public function listAction() {

        try {
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
     *
     */
    public function saveOrderAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contacts']))
            throw new NotFoundHttpException();

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
     *
     */
    public function addAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contact']))
            throw new NotFoundHttpException();

        $contact = $post['contact'];
        $contact = $this->contactData->add($contact);

        // response
        $response = new JsonResponse();
        $response->setData(array(
            'contact' => $contact
        ));
        return $response;
    }

    /**
     *
     */
    public function deleteAction() {
        $post = json_decode($this->getRequest()->getContent(), true);
        if (!isset($post['contact']))
            throw new NotFoundHttpException();

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