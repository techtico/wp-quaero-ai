<?php

/**
 * Curl Methods.
 *
 */
class Quaero_Ai_Curl
{

    // class variable that will hold the curl request handler
    private $handler = null;

    // class variable that will hold the url
    private $url = '';

    // class variable that will hold the info of our request
    public $info = [];

    // class variable that will hold the data inputs of our request
    private $data = [];

    // class variable that will tell us what type of request method to use (defaults to get)
    private $method = 'get';

    // class variable that will hold the response of the request in string
    public $content = '';

    // class variable that will tell us what headers to use
    private $headers = '';


    // function to set data inputs to send
    public function url($url = '')
    {
        $this->url = $url;
        return $this;
    }

    // function to set data inputs to send
    public function data($data = [])
    {
        $this->data = $data;
        return $this;
    }

    // function to set request method (defaults to get)
    public function method($method = 'get')
    {
        $this->method = $method;
        return $this;
    }

    // function to set Headers
    public function headers($headers = '')
    {
        $this->headers = $headers;
        return $this;
    }


    // function that will send our request
    public function send()
    {
        try {

            if ($this->handler == null) {
                $this->handler = curl_init();
            }

            switch (strtolower($this->method)) {
                case 'post':
                    curl_setopt_array($this->handler, [
                        CURLOPT_URL => $this->url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => $this->data,
                        CURLOPT_POSTFIELDS => $this->data,
                        CURLOPT_HTTPHEADER => $this->headers,
                    ]);
                    break;
                case 'put':
                    curl_setopt_array($this->handler, [
                        CURLOPT_URL => $this->url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'PUT',
                        CURLOPT_POSTFIELDS => $this->data,
                        CURLOPT_HTTPHEADER => $this->headers,
                    ]);
                    break;
                case 'delete':
                    curl_setopt_array($this->handler, [
                        CURLOPT_URL => $this->url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'DELETE',
                        CURLOPT_POSTFIELDS => $this->data,
                        CURLOPT_HTTPHEADER => $this->headers,
                    ]);
                    break;

                default:
                    curl_setopt_array($this->handler, [
                        CURLOPT_URL => $this->url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => $this->headers,
                    ]);
                    break;
            }

            $this->content = curl_exec($this->handler);

            $this->info = curl_getinfo($this->handler);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // function that will close the connection of the curl handler
    public function close()
    {

        curl_close($this->handler);
        $this->handler = null;
    }
}
