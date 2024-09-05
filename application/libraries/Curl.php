<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Curl {

    public function __construct() {
        $this->ci =& get_instance();
    }

    public function simple_get($url, $params = array(), $headers = array()) {
        $ch = curl_init();

        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers
        );

        if (!empty($params)) {
            $options[CURLOPT_POSTFIELDS] = $params;
            $options[CURLOPT_POST]      = true;
        }

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $result = 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }
}
