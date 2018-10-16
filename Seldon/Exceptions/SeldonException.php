<?php
/**
 * Created by PhpStorm.
 * User: pkirillw
 * Date: 14.10.18
 * Time: 2:54
 */

namespace Seldon\Exceptions;


class SeldonException extends \Exception
{

    private $data;

    public function __construct($message, $code = 0, $data, Exception $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}