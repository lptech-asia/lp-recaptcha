<?php
/**
 * Simple Google ReCaptcha
 */
require 'recaptcha.entity.php';
class LPRecaptcha {

    private static $__instance = null;
    private $entity = null;
    private $threshold = 0.5;
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }

    public function __construct(String $secret = '')
    {
        if(empty($secret)) 
        {
            throw new Exception('Secret is empty');
        }

        $this->secret = $secret;
        $this->entity = LPRecaptchaEntity::getInstance();
        return $this;
    }

    public function setSitekey($site_key = '')
    {
        $this->site_key = $site_key;
        return $this;
    }
    
    public function setExpectedHostname(String $hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    public function setExpectedAction(String $action = 'submit')
    {
        $this->action = $action;
        return $this;
    }

    public function setScoreThreshold($threshold = '0.5')
    {
        $this->threshold = $threshold;
        return $this;
    }

    /**
     * set a timeout between the user passing the reCAPTCHA and your server processing it.
     *
     * @param [Int] $timeoutSeconds
     * @return void
     */
    public function setChallengeTimeout(Int $timeoutSeconds)
    {
        $this->timeoutSeconds = $timeoutSeconds;
        return $this;
    }


    public function verify($gRecaptchaResponse , $hostname = '')
    {
        $isVaild = true;
        if(empty($gRecaptchaResponse))
        {
            $this->entity->setMessage('Website này chưa cấu hình reCaptcha v3, Vui lòng kiểm tra Site key!');
            $isVaild = false;
        }
        if(empty($this->secret))
        {
            $this->entity->setMessage('Website này chưa cấu hình reCaptcha v3, Vui lòng kiểm tra Secret Key!');
            $isVaild = false;
        }
        if(!$isVaild) return $this->entity;
        $postfields = [
            'secret' => $this->secret, 
            'response' => $gRecaptchaResponse
        ];
        if($hostname)
        {
           $postfields['hostname'] = $hostname; 
        }
        // call curl to POST request 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
            $postfields
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);
        // verify the response 
        if($arrResponse["success"] == '1' && $arrResponse["action"] == $this->action && $arrResponse["score"] >= $this->threshold) {
            $this->entity->setSuccess(true);
        } else {
            $this->entity->setMessage(end($arrResponse['error-codes']));

        }
        return $this->entity;
    }


    public function render($form_id = 'contact-frm' , $action = 'submit')
    {
        echo <<< EOT
            <script src="https://www.google.com/recaptcha/api.js?render={$this->site_key}"></script>
            <script>
                $('#{$form_id}').submit(function(event) {
                    event.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute("{$this->site_key}", {action: '{$action}'}).then(function(token) {
                            $('#{$form_id}').prepend('<input type="hidden" name="token" value="' + token + '">');
                            $('#{$form_id}').prepend('<input type="hidden" name="action" value="{$action}">');
                            $('#{$form_id}').unbind('submit').submit();
                        });;
                    });
                });
            </script>
        EOT;
    }
}