<?php

namespace Ais\WisudaBundle\Handler;

use Ais\WisudaBundle\Model\WisudaInterface;

interface WisudaHandlerInterface
{
    /**
     * Get a Wisuda given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return WisudaInterface
     */
    public function get($id);

    /**
     * Get a list of Wisudas.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Wisuda, creates a new Wisuda.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return WisudaInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Wisuda.
     *
     * @api
     *
     * @param WisudaInterface   $wisuda
     * @param array           $parameters
     *
     * @return WisudaInterface
     */
    public function put(WisudaInterface $wisuda, array $parameters);

    /**
     * Partially update a Wisuda.
     *
     * @api
     *
     * @param WisudaInterface   $wisuda
     * @param array           $parameters
     *
     * @return WisudaInterface
     */
    public function patch(WisudaInterface $wisuda, array $parameters);
}
