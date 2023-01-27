<?php
class LPRecaptchaEntity {

    private static $__instance = null;
    private $isSuccess = false;
    private $errorCode;
    private $message = '';

    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }
    
    public function setSuccess($success)
    {
        $this->isSuccess = $success;
        return $this;
    }

    public function setErrorCodes($errorCode)
    {
        $this->iserrorCode = $errorCode;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function isSuccess()
    {
        return $this->isSuccess;
    }

    public function getErrorCodes()
    {
        return $this->errorCode;
    }

    public function getMessage()
    {
        return $this->message;
    }

}