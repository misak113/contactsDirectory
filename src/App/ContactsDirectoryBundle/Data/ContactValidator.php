<?php
/**
 * Created by JetBrains PhpStorm.
 * User: misak113
 * Date: 30.11.13
 * Time: 1:03
 * To change this template use File | Settings | File Templates.
 */

namespace App\ContactsDirectoryBundle\Data;


use Symfony\Component\Validator\Exception\ValidatorException;

class ContactValidator {

    /**
     * Zvaliduje zadané pole a vrátí popřípadě vyjímku s chybou
     * Pokud validní, nic nevyhazuje
     *
     * @param array $contactArray
     * @throws \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function validate($contactArray) {
        if (!isset($contactArray['firstname']) || !is_string($contactArray['firstname']) || !$contactArray['firstname'])
            throw new ValidatorException('Je třeba zadat Jméno');
        if (!isset($contactArray['lastname']) || !is_string($contactArray['lastname']) || !$contactArray['lastname'])
            throw new ValidatorException('Je třeba zadat Příjmení');
        if (!isset($contactArray['degree']) || !is_string($contactArray['degree']))
            throw new ValidatorException('Je třeba zadat Titul, stačí prázdný');
        if (!isset($contactArray['telephone']) || !is_string($contactArray['telephone']) || !$contactArray['telephone'])
            throw new ValidatorException('Je třeba zadat Telefon');
        if (!isset($contactArray['email']) || !is_string($contactArray['email']) || !$contactArray['email'])
            throw new ValidatorException('Je třeba zadat e-mail');
        if (!preg_match('~^[a-zA-Z0-9\-.]+@[a-zA-Z0-9\-.]+\.[a-zA-Z0-9\-.]{2,}$~', $contactArray['email']))
            throw new ValidatorException('E-mail musí být ve tvaru xxx@xxx.xxx');
        if (!preg_match('~^[0-9+ ]+$~', $contactArray['telephone']))
            throw new ValidatorException('Telefonní číslo může obsahovat pouze znaky číslic, mezery a znaménko +');
    }
}