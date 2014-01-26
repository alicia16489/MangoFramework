<?php

    namespace core\components;

    class Response
    {
        protected $status = 200;
        protected $headers = array();
        protected $body;
        protected $length = NULL;
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

        public function __construct() {}

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

        // TO DO : MAKE IT WORK
        public function xmlEncode($data, $simpleXmlElement = NULL, $file = NULL)
        {
            if (is_null($simpleXmlElement)) {
                $simpleXmlElement = new \SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");

                $this->xmlEncode($data, $simpleXmlElement);

                return $simpleXmlElement->asXML();
            } else {
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        if (!is_numeric($key)) {
                            $node = $simpleXmlElement->addChild($key);
                            $this->xmlEncode($value, $node, $file);
                        } else {
                            $node = $simpleXmlElement->addChild('item' . $key);
                            $this->xmlEncode($value, $node, $file);
                        }
                    } else {
                        $simpleXmlElement->addChild($key, $value);
                    }
                }
            }
        }

        public function write($body, $replace = FALSE)
        {
            (($replace === TRUE) ? $this->body .= $body : $this->body = $body);

            $this->length = strlen($this->body);

            return $this;
        }

        public function cache($expires)
        {
            if ($expires === FALSE) {
                $this->headers['Expires'] = 'Thu, 20 Aug 1992 02:17:31 GMT';
                $this->headers['Cache-Control'] = array(
                    'no-store, no-cache, must-revalidate',
                    'post-check=0, pre-check=0',
                    'max-age=0'
                );

                $this->headers['Pragma'] = 'no-cache';
            } else {
                $expires = is_int($expires) ? $expires : strtotime($expires);
                $this->headers['Expires'] = gmdate('D, d M Y H:i:s', $expires) . ' GMT';
                $this->headers['Cache-Control'] = 'max-age='.($expires - time());
            }

            return $this;
        }

        public function jsonEncodeUTF8($data)
        {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);

            return $json;
        }

        public function send($html = FALSE)
        {
            if (ob_get_length() > 0) {
                ob_end_clean();
            }

            if ($html === FALSE) {
                if (!headers_sent()) {
                    foreach($this->headers as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $v) {
                                header($key . ': ' . $v, false);
                            }
                        } else {
                            header($key . ': ' . $value);
                        }
                    }
                }
            } else {
                $this->type = 'html';
            }

            exit($this->body);
        }

        public function sendResponse($data, $code = 200, $encode = TRUE, $replace = FALSE, $type = "json")
        {
            $this->type = $type;

            $encodedData = (($encode === TRUE) ? (($this->type === 'json' || $this->type === 'html') ? $this->jsonEncodeUTF8($data) : $this->xmlEncode($data)) : $data);

            if ($this->type === 'json') {
                $contentType = 'application/json';
            } else if ($this->type === 'html') {
                $contentType = 'text/html';
            } else if ($this->type === 'xml') {
                $contentType = 'text/xml';
            }

            $this->status($code)
                 ->header('content-Type', $contentType . ' ; charset=utf-8')
                 ->write($encodedData, $replace)
                 ->send();
        }
    }