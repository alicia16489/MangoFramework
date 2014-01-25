<?php

    namespace core\components;

    class Response
    {
        protected $status = 200;
        protected $headers = array();
        protected $body;
        protected $length = 0;
        protected $type;
        protected static $statusCodes = array(
            // Informational
            100 => '100 Continue',
            101 => '101 Switching Protocols',
            // Success
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            // Client Error
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        public function __construct($type = 'json')
        {
            $this->type = $type;
        }

        public function status($code, $add = FALSE)
        {
            if (array_key_exists($code, self::$statusCodes)){
                $this->status = $code;
            } else {
                if ($add === TRUE) {
                    if (is_array($code)) {
                        foreach ($code as $kcode => $message) {
                            self::$statusCodes[$kcode] = $message;
                        }
                    } else {
                        Throw New \Exception('Invalid var type : "statusCode" must be an array in this case');
                    }
                } else {
                    Throw New \Exception('Invalid status code');
                }
            }

            return $this;
        }

        public function getStatus($messageOnly = FALSE, $code = 200)
        {
            if ($messageOnly === TRUE) {
                if (array_key_exists($code, self::$statusCodes)) {
                    return self::$statusCodes[$code];
                } else {
                    Throw New \Exception('Invalid status code');
                }
            } else {
                return $this->headers;
            }
        }

        public function header($key, $value = NULL)
        {
            if (is_array($key)) {
                foreach($key as $k => $v) {
                    $this->headers[$k] = $v;
                }
            } else {
                $this->headers[$key] = $value;
            }

            return $this;
        }

        public function getHeader()
        {
            return $this->headers;
        }

        public function getLength()
        {
            return $this->length;
        }

        public function unsetResponse()
        {
            $this->status = 200;
            $this->headers = array();
            $this->length = 0;
            $this->body = '';

            return $this;
        }

        public function is($type = 'empty')
        {
            switch ($type) {
                case 'empty':
                    return in_array($this->status, array(201, 204, 304));
                case 'informational':
                    return $this->status >= 100 && $this->status < 200;
                case 'ok':
                    return $this->status === 200;
                case 'successful':
                    return $this->status >= 200 && $this->status < 300;
                case 'redirect':
                    return in_array($this->status, array(301, 302, 303, 307));
                case 'redirection':
                    return $this->status >= 300 && $this->status < 400;
                case 'forbidden':
                    return $this->status === 403;
                case 'notFound':
                    return $this->status === 404;
                case 'clientError':
                    return $this->status >= 400 && $this->status < 500;
                case 'serverError':
                    return $this->status >= 500 && $this->status < 600;
                default:
                    Throw new \Exception('Invalid var value');
            }

        }

        public function xmlEncode($data, $domElement = NULL, $domDocument = NULL) {
            if (is_null($domDocument)) {
                $domDocument = new DOMDocument;
                $domDocument->formatOutput = true;

                $rootNode = $domDocument->createElement('entries');
                $domDocument->appendChild($rootNode);

                $this->xmlEncode($data, $rootNode, $domDocument);

                echo @$domDocument->saveXML();
            } else {
                if (is_array($data)) {
                    foreach ($data as $k => $v) {
                        if (is_int($k)) {
                            $nodeName = 'entry';
                        } else {
                            $nodeName = $k;
                        }
                        $node = $domDocument->createElement($nodeName);
                        $domElement->appendChild($node);
                        $this->xmlEncode($v, $node, $domDocument);
                    }
                } else {
                    $newNode = $domDocument->createTextNode($data);

                    $domElement->appendChild($newNode);
                }
            }
        }

        public function write($body, $replace = FALSE)
        {
            (($replace === TRUE) ? $this->body .= $body : $this->body = $body);

            $this->length = strlen($this->body);

            return $this;
        }

        public function sendHeader()
        {
            foreach($this->headers as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        header($key . ': ' . $v, false);
                    }
                } else {
                    header($key . ': ' . $value);
                }
            }

            return $this;
        }

        public function send()
        {
            if (ob_get_length() > 0) {
                ob_end_clean();
            }

            if (!headers_sent()) {
                $this->sendHeader();
            }

            exit($this->body);
        }

        public function sendResponse($data, $code = 200, $encode = TRUE, $replace = FALSE)
        {
            $encodedData = (($encode === TRUE) ? (($this->type === 'json') ? json_encode($data) : $this->xmlEncode($data)) : $data);

            $this->status($code)
                 ->header('content-Type', 'application/json')
                 ->write($json, $replace)
                 ->send();
        }

    }