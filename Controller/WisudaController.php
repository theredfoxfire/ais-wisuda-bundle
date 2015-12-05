<?php

namespace Ais\WisudaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\WisudaBundle\Exception\InvalidFormException;
use Ais\WisudaBundle\Form\WisudaType;
use Ais\WisudaBundle\Model\WisudaInterface;


class WisudaController extends FOSRestController
{
    /**
     * List all wisudas.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing wisudas.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many wisudas to return.")
     *
     * @Annotations\View(
     *  templateVar="wisudas"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getWisudasAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_wisuda.wisuda.handler')->all($limit, $offset);
    }

    /**
     * Get single Wisuda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Wisuda for a given id",
     *   output = "Ais\WisudaBundle\Entity\Wisuda",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the wisuda is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="wisuda")
     *
     * @param int     $id      the wisuda id
     *
     * @return array
     *
     * @throws NotFoundHttpException when wisuda not exist
     */
    public function getWisudaAction($id)
    {
        $wisuda = $this->getOr404($id);

        return $wisuda;
    }

    /**
     * Presents the form to use to create a new wisuda.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newWisudaAction()
    {
        return $this->createForm(new WisudaType());
    }
    
    /**
     * Presents the form to use to edit wisuda.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisWisudaBundle:Wisuda:editWisuda.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the wisuda id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when wisuda not exist
     */
    public function editWisudaAction($id)
    {
		$wisuda = $this->getWisudaAction($id);
		
        return array('form' => $this->createForm(new WisudaType(), $wisuda), 'wisuda' => $wisuda);
    }

    /**
     * Create a Wisuda from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new wisuda from the submitted data.",
     *   input = "Ais\WisudaBundle\Form\WisudaType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisWisudaBundle:Wisuda:newWisuda.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postWisudaAction(Request $request)
    {
        try {
            $newWisuda = $this->container->get('ais_wisuda.wisuda.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newWisuda->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_wisuda', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing wisuda from the submitted data or create a new wisuda at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\WisudaBundle\Form\WisudaType",
     *   statusCodes = {
     *     201 = "Returned when the Wisuda is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisWisudaBundle:Wisuda:editWisuda.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the wisuda id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when wisuda not exist
     */
    public function putWisudaAction(Request $request, $id)
    {
        try {
            if (!($wisuda = $this->container->get('ais_wisuda.wisuda.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $wisuda = $this->container->get('ais_wisuda.wisuda.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $wisuda = $this->container->get('ais_wisuda.wisuda.handler')->put(
                    $wisuda,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $wisuda->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_wisuda', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing wisuda from the submitted data or create a new wisuda at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\WisudaBundle\Form\WisudaType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisWisudaBundle:Wisuda:editWisuda.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the wisuda id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when wisuda not exist
     */
    public function patchWisudaAction(Request $request, $id)
    {
        try {
            $wisuda = $this->container->get('ais_wisuda.wisuda.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $wisuda->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_wisuda', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Wisuda or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return WisudaInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($wisuda = $this->container->get('ais_wisuda.wisuda.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $wisuda;
    }
    
    public function postUpdateWisudaAction(Request $request, $id)
    {
		try {
            $wisuda = $this->container->get('ais_wisuda.wisuda.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $wisuda->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_wisuda', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
