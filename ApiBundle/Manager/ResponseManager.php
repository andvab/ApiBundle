<?php

namespace ApiBundle\Manager;

/**
 * Class ResponseManager
 * @package ApiBundle\Manager
 */
class ResponseManager
{
    /**
     * Compile success response array
     *
     * @param array $data
     * @param array $meta
     * @return array
     */
    public function compileSuccess($data, $meta = array())
    {
        $result = array(
            'code' => 200,
            'data' => $data,
        );

        $result['meta'] = $meta;

        return $result;
    }

    /**
     * Compile errors response array
     *
     * @param mixed $errors
     * @param int   $code
     * @return array
     */
    public function compileErrors($errors, $code)
    {
        $result = array();

        if (is_array($errors)) {
            foreach ($errors as $errorKey => $errorMessage) {
                $result[] = $this->getErrorObject($errorMessage, $errorKey);
            }

            if (count($result) === 1) {
                $result = reset($result);
            }
        } else {
            $result = $this->getErrorObject($errors);
        }

        return array(
            'code'   => $code,
            'errors' => $result,
        );
    }

    /**
     * Get errors array for compile
     *
     * @param string $message
     * @param mixed  $source
     *
     * @return array
     */
    protected function getErrorObject($message, $source = null)
    {
        $error = array(
            'message' => $message,
        );

        if ($source && !is_int($source)) {
            $error['source'] = $source;
        }

        return $error;
    }
}
