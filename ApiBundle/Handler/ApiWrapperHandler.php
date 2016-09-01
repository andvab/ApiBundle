<?php

namespace ApiBundle\Handler;

use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;

/**
 * Class ApiWrapperHandler
 * @package ApiBundle\Handler
 */
class ApiWrapperHandler implements ExceptionWrapperHandlerInterface
{

    /**
     * @inheritdoc
     */
    public function wrap($data)
    {
        /** @var \Symfony\Component\Debug\Exception\FlattenException $exception */
        $exception = $data['exception'];

        $newException = array(
            'code'    => 500,
            'errors' => array(
                'message' => $exception->getMessage(),
            ),
        );

        return $newException;
    }
}
