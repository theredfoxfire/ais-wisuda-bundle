<?php

namespace Ais\WisudaBundle\Tests\Handler;

use Ais\WisudaBundle\Handler\WisudaHandler;
use Ais\WisudaBundle\Model\WisudaInterface;
use Ais\WisudaBundle\Entity\Wisuda;

class WisudaHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\WisudaBundle\Tests\Handler\DummyWisuda';

    /** @var WisudaHandler */
    protected $wisudaHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $wisuda = $this->getWisuda();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($wisuda));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->wisudaHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $wisudas = $this->getWisudas(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($wisudas));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->wisudaHandler->all($limit, $offset);

        $this->assertEquals($wisudas, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $wisuda = $this->getWisuda();
        $wisuda->setTitle($title);
        $wisuda->setBody($body);

        $form = $this->getMock('Ais\WisudaBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($wisuda));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $wisudaObject = $this->wisudaHandler->post($parameters);

        $this->assertEquals($wisudaObject, $wisuda);
    }

    /**
     * @expectedException Ais\WisudaBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $wisuda = $this->getWisuda();
        $wisuda->setTitle($title);
        $wisuda->setBody($body);

        $form = $this->getMock('Ais\WisudaBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->wisudaHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $wisuda = $this->getWisuda();
        $wisuda->setTitle($title);
        $wisuda->setBody($body);

        $form = $this->getMock('Ais\WisudaBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($wisuda));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $wisudaObject = $this->wisudaHandler->put($wisuda, $parameters);

        $this->assertEquals($wisudaObject, $wisuda);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $wisuda = $this->getWisuda();
        $wisuda->setTitle($title);
        $wisuda->setBody($body);

        $form = $this->getMock('Ais\WisudaBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($wisuda));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->wisudaHandler = $this->createWisudaHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $wisudaObject = $this->wisudaHandler->patch($wisuda, $parameters);

        $this->assertEquals($wisudaObject, $wisuda);
    }


    protected function createWisudaHandler($objectManager, $wisudaClass, $formFactory)
    {
        return new WisudaHandler($objectManager, $wisudaClass, $formFactory);
    }

    protected function getWisuda()
    {
        $wisudaClass = static::DOSEN_CLASS;

        return new $wisudaClass();
    }

    protected function getWisudas($maxWisudas = 5)
    {
        $wisudas = array();
        for($i = 0; $i < $maxWisudas; $i++) {
            $wisudas[] = $this->getWisuda();
        }

        return $wisudas;
    }
}

class DummyWisuda extends Wisuda
{
}
