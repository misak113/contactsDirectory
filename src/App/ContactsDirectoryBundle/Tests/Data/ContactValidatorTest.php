<?php
/**
 * Created by JetBrains PhpStorm.
 * User: misak113
 * Date: 30.11.13
 * Time: 1:23
 * To change this template use File | Settings | File Templates.
 */

namespace App\ContactsDirectoryBundle\Tests\Controller;

use App\ContactsDirectoryBundle\Data\ContactValidator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Exception\ValidatorException;


class ContactValidatorTest extends TestCase {
    /** @var ContactValidator */
    protected $validator;

    public function setUp() {
        $this->validator = new ContactValidator();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat Jméno
     */
    public function testValidateFirstname()
    {
        $data = array(
            'firstname' => '',
            'lastname' => 'Žabka',
            'degree' => 'Bc.',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat Jméno
     */
    public function testValidateFirstname2()
    {
        $data = array(
            //'firstname' => '',
            'lastname' => '',
            'degree' => '',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat Příjmení
     */
    public function testValidateLastname()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => '',
            'degree' => 'Bc.',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
    }
    /**
     */
    public function testValidateDegree()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => '',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
        $this->assertTrue(true);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat Titul, stačí prázdný
     */
    public function testValidateDegree2()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            //'degree' => '',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat e-mail
     */
    public function testValidateEmailEmpty()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => 'Bc.',
            //'email' => 'zabka@avantcore.cz',
            'telephone' => 'Je třeba zadat Telefon',
        );
        $this->validator->validate($data);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage E-mail musí být ve tvaru xxx@xxx.xxx
     */
    public function testValidateEmail()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => '',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
        $this->assertTrue(true);

        // not ok
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => '',
            'email' => 'zabkaavantcore.cz',
            'telephone' => '723922276',
        );
        $this->validator->validate($data);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Je třeba zadat Telefon
     */
    public function testValidateTelephone()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => 'Bc.',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '',
        );
        $this->validator->validate($data);
    }
    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Telefonní číslo může obsahovat pouze znaky číslic, mezery a znaménko +
     */
    public function testValidateTelephone2()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => 'Bc.',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '4321 343 434g',
        );
        $this->validator->validate($data);
    }
    /**
     */
    public function testValidateTelephoneOk()
    {
        $data = array(
            'firstname' => 'Michael',
            'lastname' => 'Žabka',
            'degree' => 'Bc.',
            'email' => 'zabka@avantcore.cz',
            'telephone' => '+4321 343 434',
        );
        $this->validator->validate($data);
        $this->assertTrue(true);
    }
}