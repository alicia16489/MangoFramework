<?php

namespace core\components;

use core\app;

class ResponseException extends \Exception {}

class Response
{
    protected $status = NULL;
    protected $headers = array();
    protected $body;
    protected $length = NULL;
    protected $type = 'json';
    protected $data = NULL;
    protected $defaultData;
    protected $errorData = NULL;
    protected $prettyPrint = FALSE;
    protected $eraseBuffer = FALSE;
    protected $encodedErrorData = TRUE;

    protected $validType = array(
        "json",
        "xml",
        "html",
        "plain"
    );
    protected static $statusCodes = array(
        // Informational
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        102 => 'Processing',
        103 => 'Checkpoint',
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
        208 => 'Already Reported',
        209 => 'Content Returned',
        210 => 'Content Different',
        226 => 'IM Used',
        // Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        // Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
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
        511 => 'Network Authentication Required',
        520 => 'Web server is returning an unknown error',
    );

    public function __construct()
    {
        $config = App::$container['Config']->getResponse();
        if (!empty($config['type'])) {
            try {
                $this->setType($config['type']);
            } catch (\Exception $e) {
                $this->setType('html');
                var_dump($e);
            }
        }

        if (!empty($config['prettyPrintJSON']))
            $this->prettyPrint = $config['prettyPrintJSON'];

        if (!empty($config['eraseBuffer']))
            $this->eraseBuffer = $config['eraseBuffer'];
    }

    public function setStatus($code, $add = FALSE)
    {
        if (array_key_exists($code, self::$statusCodes)) {
            $this->status = $code;

        } else {
            if ($add === TRUE) {
                if (is_array($code)) {
                    foreach ($code as $kcode => $message) {
                        self::$statusCodes[$kcode] = $message;
                    }
                } else {
                    Throw New \ResponseException('Invalid var type : $code must be an array in this case');
                }
            } else {
                Throw New \ResponseException('Invalid status code');
            }
        }

        return $this;
    }

    public function setHeader($key, $value = NULL)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->headers[$k] = $v;
            }
        } else {
            $this->headers[$key] = $value;
        }

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setData($data,$type = null)
    {
        if ($type === 'default') {
            $this->defaultData = $data;
        } else {
            $this->data = $data;
        }

        return $this;
    }

    public function setPrettyJSON($bool)
    {
        $this->prettyPrint = $bool;

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
                Throw New \ResponseException('Invalid status code');
            }
        } else {
            return $code;
        }
    }

    public function getHeader()
    {
        return $this->headers;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function clear()
    {
        $this->status = NULL;
        $this->headers = array();
        $this->length = 0;
        $this->body = '';
        $this->type = 'json';
        $this->data = NULL;
        $this->errorData = NULL;
        $this->encodedErrorData = TRUE;
        $this->prettyPrint = FALSE;

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
                Throw new \ResponseException('Invalid var $type value: check "is" method to have the list');
        }
    }

    public function cache($expires)
    {
        if ($expires === FALSE) {
            $this->headers['Expires'] = 'Thu, 20 Aug 1992 02:17:31 GMT';
            $this->headers['Pragma'] = 'no-cache';
            $this->headers['Cache-Control'] = array(
                // only stock in nav, reload response from server, force cache to reconnect to server
                'no-store, no-cache, must-revalidate', 'proxy-revalidate',
                'no-transform', 'post-check=0, pre-check=0', 'max-age=0'
            );
        } else {
            $expires = is_int($expires) ? $expires : strtotime($expires);
            $this->headers['Expires'] = gmdate('D, d M Y H:i:s', $expires) . ' GMT';
            $this->headers['Cache-Control'] = 'max-age=' . ($expires - time());
        }

        return $this;
    }

    public function write($body, $replace = FALSE)
    {
        if ($replace === TRUE) {
            $this->body = $body;
        } else {
            $this->body .= $body;
        }

        $this->length = strlen($this->body);

        return $this;
    }

    public function xmlEncode($data, $simpleXmlElement = NULL, $file = NULL)
    {
        if (is_null($simpleXmlElement)) {
            $simpleXmlElement = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><root></root>");

            $this->xmlEncode($data, $simpleXmlElement, $file);

            if (!is_null($file)) {
                $simpleXmlElement->asXML($file);
            }

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
                    if (!is_numeric($key)) {
                        $simpleXmlElement->addChild($key, $value);
                    } else {
                        $simpleXmlElement->addChild('item' . $key, $value);
                    }
                }
            }
        }
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

    private function isDataError($data, $code = 400)
    {
        $this->errorData = TRUE;
        $this->code = $code;
        return $this->jsonEncodeUTF8($data);
    }

    public function sendHeaders()
    {
        header(
            $_SERVER['SERVER_PROTOCOL'] .
                ' ' . $this->status .
                ' ' . self::$statusCodes[$this->status],
            TRUE,
            $this->status
        );

        foreach ($this->headers as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    header($key . ': ' . $v, FALSE);
                }
            } else {
                header($key . ': ' . $value, TRUE);
            }
        }
    }

    public function send($die = TRUE)
    {
        if ($this->eraseBuffer === TRUE) {
            if (ob_get_length() > 0) {
                ob_end_clean();
            }
        }

        if (!headers_sent()) {
            $this->sendHeaders();
        }

        if ($die === FALSE) {
            echo($this->body);
        } else {
            die ($this->body);
        }
    }

    public function sendResponse($customParams = NULL)
    {
        $this->errorData = NULL;

        // default param
        $params = array(
            "code" => 200,
            "encode" => TRUE,
            "replace" => TRUE,
            "die" => FALSE,
            "xmlFile" => NULL,
            "htmlJSONEncode" => TRUE
        );

        $this->status = (!is_null($this->status) ? $this->status : $params['code']);

        // set default or custom if set
        if (!is_null($this->data) && !empty($this->data)) {
            $data = $this->data;
        } else {
            $data = $this->defaultData;
        }

        // merge default param with custom if set
        if (is_array($customParams) && !is_null($customParams)) {
            $params = array_merge($params, $customParams);
        }

        // set type of response formats
        if (in_array($this->type, $this->validType)) {
            $type = $this->type;
        } else {
            Throw new ResponseException("Invalid response format : must be 'json', 'xml' or 'html'");
        }

        // encode data
        if ($params['encode'] === TRUE) {
            if ($type === 'json') {
                $encodedData = $this->jsonEncodeUTF8($data);
            } else if ($type === 'html' || $type === 'plain') {
                if (is_array($data) && $params['htmlJSONEncode'] === TRUE) {
                    $encodedData = $this->jsonEncodeUTF8($data);
                } else if (is_array($data) && $params['htmlJSONEncode'] === FALSE) {
                    Throw new ResponseException('Invalid var type : $data can\'t be an array in html response mode');
                } else {
                    $encodedData = $data;
                }
            } else if ($type === 'xml') {
                $encodedData = $this->xmlEncode($data, NULL, $params['xmlFile']);
            }
        } else {
            $encodedData = $data;
        }

        // set MIME Type
        //|| ($this->errorData === TRUE && $this->encodedErrorData === TRUE)
        if ($this->type === 'json') {
            $contentType = 'application/json';
        } else if ($this->type === 'html') {
            $contentType = 'text/html';
        } else if ($this->type === 'plain') {
            $contentType = 'text/plain';
        } else if ($this->type === 'xml') {
            $contentType = 'application/xml';
        }

        // stop response if error when xml response type
        if ($this->errorData === TRUE || $type === 'xml') {
            $params['die'] = TRUE;
        }

        // send response
        $this->setStatus($this->status)
            ->setHeader('content-Type', $contentType . ' ; charset=utf-8')
            ->write($encodedData, $params['replace'])
            ->send($params['die']);
    }
}