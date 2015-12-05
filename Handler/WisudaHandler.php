<?php

namespace Ais\WisudaBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\WisudaBundle\Model\WisudaInterface;
use Ais\WisudaBundle\Form\WisudaType;
use Ais\WisudaBundle\Exception\InvalidFormException;

class WisudaHandler implements WisudaHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Wisuda.
     *
     * @param mixed $id
     *
     * @return WisudaInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Wisudas.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Wisuda.
     *
     * @param array $parameters
     *
     * @return WisudaInterface
     */
    public function post(array $parameters)
    {
        $wisuda = $this->createWisuda();

        return $this->processForm($wisuda, $parameters, 'POST');
    }

    /**
     * Edit a Wisuda.
     *
     * @param WisudaInterface $wisuda
     * @param array         $parameters
     *
     * @return WisudaInterface
     */
    public function put(WisudaInterface $wisuda, array $parameters)
    {
        return $this->processForm($wisuda, $parameters, 'PUT');
    }

    /**
     * Partially update a Wisuda.
     *
     * @param WisudaInterface $wisuda
     * @param array         $parameters
     *
     * @return WisudaInterface
     */
    public function patch(WisudaInterface $wisuda, array $parameters)
    {
        return $this->processForm($wisuda, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param WisudaInterface $wisuda
     * @param array         $parameters
     * @param String        $method
     *
     * @return WisudaInterface
     *
     * @throws \Ais\WisudaBundle\Exception\InvalidFormException
     */
    private function processForm(WisudaInterface $wisuda, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new WisudaType(), $wisuda, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $wisuda = $form->getData();
            $this->om->persist($wisuda);
            $this->om->flush($wisuda);

            return $wisuda;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createWisuda()
    {
        return new $this->entityClass();
    }

}
