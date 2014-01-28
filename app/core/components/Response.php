<?php

    namespace core\components;

    class Response
    {
        protected $status = 200;
        protected $headers = array();
        protected $body;
        protected $length = NULL;
        protected $type = 'json';
        protected $data = NULL;
        protected $defaultData;
        public $encodedErrorData = TRUE;
        protected $errorData = NULL;
        public $prettyPrint = FALSE;
        protected $validType = array(
            "json",
            "xml",
            "html"
        );
        protected static $statusCodes = array(
            // Informational
            100 => '100 Continue',
            101 => '101 Switching Protocols',
            102 => 'Processing',
            118 => 'Connection timed out',
            // Success
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            210 => 'Content Different',
            226 => 'IM Used',
            // Redirection
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            310 => 'Too many Redirect',
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
            418 => 'I\'m a teapot', //RFC 2324
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Reserved for WebDAV advanced collections expired proposal',
            426 => 'Upgrade required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            456 => 'Unrecoverable Error',
            499 => 'client has closed connection',
            // Server Error
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not extended',
            520 => 'Web server is returning an unknown error',
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
                        Throw New \Exception('Invalid var type : $code must be an array in this case');
                    }
                } else {
                    Throw New \Exception('Invalid status code');
                }
            }

            return $this;
        }

        public function getStatus($code = NULL, $messageOnly = FALSE)
        {
            if (is_null($code)) {
                $code = $this->status;
            }

            if ($messageOnly === TRUE) {
                if (array_key_exists($code, self::$statusCodes)) {
                    return self::$statusCodes[$code];
                } else {
                    Throw New \Exception('Invalid status code');
                }
            } else {
                return $code;
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

        public function setType($type)
        {
            if (in_array($type, $this->validType)) {
                $this->type = $type;
            } else {
                $this->defaultData = "Invalid response format : must be 'json', 'xml' or 'html'";
            }
        }

        public function setData($data, $type = 'default')
        {
            if ($type === 'default') {
                $this->defaultData = $data;
            } else {
                $this->data = $data;
            }
        }

        public function setPrettyPrint($bool)
        {
            $this->prettyPrint = $bool;
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

        public function xmlEncode($data, $simpleXmlElement = NULL, $file = NULL)
        {
            if (is_null($simpleXmlElement)) {
                $simpleXmlElement = new \SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");

                $this->xmlEncode($data, $simpleXmlElement, $file);

                if (!is_null($file)) {
                    $simpleXmlElement->asXML($file);
                } else {
                    return $simpleXmlElement->asXML();
                }
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
            if ($this->prettyPrint === TRUE) {
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
            } else {
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
            }

            return $json;
        }

        public function sendHeader()
        {
            header('HTTP/1.0 '.$this->getStatus());

            foreach($this->headers as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        header($key . ': ' . $v, FALSE);
                    }
                } else {
                    header($key . ': ' . $value, TRUE);
                }
            }
        }

        private function isDataError($data)
        {
            $this->errorData = TRUE;
            return $this->jsonEncodeUTF8($data);
        }

        public function send($die = TRUE, $erasePrevBuffer = TRUE)
        {
            if ($erasePrevBuffer === TRUE) {
                if (ob_get_length() > 0) {
                    ob_end_clean();
                }
            }

            if (!headers_sent()) {
                $this->sendHeader();
            }

            if ($die === FALSE) {
                echo ($this->body);
            } else {
                die ($this->body);
            }
        }

        public function sendResponse($customParams = NULL)
        {
            $this->errorData = NULL;

            $params = array(
                "code" => 400,
                "encode" => TRUE,
                "replace" => FALSE,
                "die" => TRUE,
                "xmlFile" => NULL,
                "erasePrevBuffer" => TRUE
            );

            if (!is_null($this->data) && !empty($this->data)) {
                $data = $this->data;
            } else {
                $data = $this->defaultData;
            }

            if (is_array($customParams) && !is_null($customParams)) {
                $params = array_merge($params, $customParams);
            }

            if (in_array($this->type, $this->validType)) {
                $type = $this->type;
            } else {
                $data = "Invalid response format : must be 'json', 'xml' or 'html'";
            }

            if ($params['encode'] === TRUE) {
                if ($type === 'json') {
                    $encodedData =  $this->jsonEncodeUTF8($data);
                } else if ($type === 'html') {
                    if (!is_array($data)) {
                        $encodedData = $data;
                    } else {
                        $encodedData = $this->isDataError('Invalid var type : $data can\'t be an array in html response mode');
                    }
                } else if ($type === 'xml') {
                    $encodedData = $this->xmlEncode($data, NULL, $params['xmlFile']);
                }
            }

            if ($this->type === 'json' || ($this->errorData === TRUE && $this->encodedErrorData === TRUE)) {
                $contentType = 'application/json';
            } else if ($this->type === 'html') {
                $contentType = 'text/html';
            } else if ($this->type === 'xml') {
                $contentType = 'application/xml';
            }

            $this->status($params['code'])
                 ->header('content-Type', $contentType . ' ; charset=utf-8')
                 ->write($encodedData, $params['replace'])
                 ->send($params['die'], $params['erasePrevBuffer']);
        }
    }