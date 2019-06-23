<?php


namespace Tests\AppBundle\Form;

use AppBundle\DTO\TaskDTO;
use AppBundle\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new TaskType();
    }

    /**
     * Tests that form is correctly build according to specs.
     */
    public function testBuildForm()
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        // Tests number of calls to add method
        $formBuilderMock->expects($this->exactly(2))->method('add')->willReturnSelf()->withConsecutive(
            [$this->equalTo('title'), $this->equalTo(TextType::class)],
            [$this->equalTo('content'), $this->equalTo(TextareaType::class)]
        );
        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }

    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'test',
            'content' => 'test',
        ];
        $objectToCompare = new TaskDTO();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TaskType::class, $objectToCompare);
        $object = new TaskDTO();
        // ...populate $object properties with the data stored in $formData
        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertNotEquals($object, $objectToCompare);
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
