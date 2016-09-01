<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiController
 * @package ApiBundle\Controller
 */
class ApiController extends FOSRestController
{
    /**
     * @var array
     */
    private $apiMetadata = array();

    /**
     * @var array
     */
    private $serializationGroups = array();

    /**
     * @param array $serializationGroups
     */
    public function setSerializationGroups($serializationGroups)
    {
        $this->serializationGroups = $serializationGroups;
    }

    /**
     * Get modified array for api response
     *
     * @param null  $data
     * @param null  $statusCode
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view($data = null, $statusCode = null, array $headers = array())
    {
        $apiManager = $this->get('api.response_manager');

        $data = $data ? $data : array();

        if ($statusCode == 200) {
            $result = $apiManager->compileSuccess($data, $this->getMeta());
        } else {
            $statusCode = $statusCode ? $statusCode : 400;
            $result = $apiManager->compileErrors($data, $statusCode);
        }

        $view = parent::view($result, $statusCode);

        if (!empty($this->serializationGroups)) {
            $serializationContext = SerializationContext::create()->setGroups($this->serializationGroups);

            $view->setSerializationContext($serializationContext);
        }

        return $this->handleView($view);
    }

    /**
     * Check parameters from ParamFetcher
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array|bool
     */
    protected function checkParams(ParamFetcher $paramFetcher)
    {
        $paramNames = array_keys($paramFetcher->getParams());
        $errors     = array();

        foreach ($paramNames as $paramName) {
            try {
                $paramFetcher->get($paramName);
            } catch (BadRequestHttpException $e) {
                $errors[$paramName] = $e->getMessage();
            }
        }

        return $errors ? $errors : true;
    }

    /**
     * Add metadata to array for api response
     *
     * @param mixed $key
     * @param mixed $value
     */
    protected function addMeta($key, $value)
    {
        $this->apiMetadata = array_merge($this->apiMetadata, array($key => $value));
    }

    /**
     * Get metadata
     *
     * @return array
     */
    protected function getMeta()
    {
        return $this->apiMetadata;
    }
}
