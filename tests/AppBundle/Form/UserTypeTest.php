<?php


namespace Tests\AppBundle\Form;

use AppBundle\DTO\UserDTO;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new UserType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm()
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(4))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('username'), $this->equalTo(TextType::class)],
            [$this->equalTo('password'), $this->equalTo(RepeatedType::class)],
            [$this->equalTo('email'), $this->equalTo(EmailType::class)],
            [$this->equalTo('roles'), $this->equalTo(ChoiceType::class)]
        );
        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }

    // Bug with the invalid_message with repeated type
//    public function testSubmitValidData()
//    {
//        $formData = [
//            'username' => 'test',
//            'password' => 'test',
//            'email' => 'admin@email.com.com',
//            'roles' => ['ROLE_USER'],
//        ];
//        $objectToCompare = new UserDTO();
//        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
//        $form = $this->factory->create(UserType::class, $objectToCompare);
//        $object = new UserDTO();
//        // ...populate $object properties with the data stored in $formData
//        // submit the data to the form directly
//        $form->submit($formData);
//        $this->assertTrue($form->isSynchronized());
//        // check that $objectToCompare was modified as expected when the form was submitted
//        $this->assertNotEquals($object, $objectToCompare);
//        $view = $form->createView();
//        $children = $view->children;
//        foreach (array_keys($formData) as $key) {
//            $this->assertArrayHasKey($key, $children);
//        }
//    }
}
