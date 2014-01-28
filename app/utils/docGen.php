<?php

    namespace utils;

    Class FilesystemException extends \Exception {}

    Class docGen
    {
        protected $filePaths = array();
        public $prettyMode = FALSE;
        public $docPath = 'doc.php';

        public function __construct($filePaths, $docPattern = NULL)
        {
            if (is_array($filePaths) && !empty($filePaths)) {
                $this->filePaths = $filePaths;
            } else {
                Throw New \Exception('Invalid var type : $filePath must be an array and not be empty');
            }
        }

        public function cleanPattern($char, $content)
        {
            if (!is_array($char)) {
                $contentClean = str_replace($char, '\\' . $char, $content);
            } else {
                $contentClean = str_replace($char, '', $content);
            }

            return $contentClean;
        }

        public function appendContent($content)
        {
            $searchTrim = array(' ', "\t", "\n", "\r", "\0", "\x0B");
            $searchClean = array('*', '{', '}', '(', ')', '[', ']', '|', '$', '.', '#', '?', '^');

            $contentClean = $this->cleanPattern($searchTrim, $content);

            foreach ($searchClean as $s) {
                $contentClean = $this->cleanPattern($s, $contentClean);
            }

            $pattern = "#" . $contentClean . "#i";
            $docContent = str_replace($searchTrim, '', file_get_contents($this->docPath));

            if (preg_match($pattern, $docContent) !== 1) {
                file_put_contents($this->docPath, $content, FILE_APPEND);
            }
        }

        public function buildHeader($headers)
        {
            $headerContent = '';

            foreach ($headers as $key => $header) {
                if ($key === 'namespace') {
                    $namespace = " in\n" . substr($header, 0, strlen($header)-1) . "\n";
                } else if ($key === 'className') {
                    if (isset($namespace)) {
                        $headerContent .= $header . $namespace . "\n---------------------\n\n";
                    } else {
                        $headerContent .= $header . "\n\n---------------------\n\n";
                    }
                }
            }

            return $headerContent;
        }

        public function buildMethodAnalysis($analysis, $count)
        {
            $finalContent = '';
            $method = NULL;
            $attr = NULL;

            if ($count === 0 && !is_null($attr) && $attr === TRUE) {
                $finalContent .= "\n---ATTRIBUTES---\n\n";
            } else if ($count === 0 && !is_null($method) && $method === TRUE) {
                $finalContent .= "\n---METHODS---\n\n";
            } else if ($count > 0 && !is_null($method) && $method === TRUE && $attr === FALSE) {
                $finalContent .= "\n---METHODS---\n\n";
            }

            foreach ($analysis as $key => $analys) {
                if ($key === 0) {
                    $finalContent .= "/**\n";
                } else if ($key === count($analysis) - 1) {
                    if (preg_match('#(public|protected|private|static|var)\s\$[a-zA-Z]+#', $analys)) {
                        if (empty($attr)) {
                            $attr = TRUE;
                        }

                        if (preg_match('#array#', $analys) === 1) {
                            $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . ")\n\n";
                        } else {
                            $finalContent .= " */\n" . substr($analys, strpos($analys, 'p'), strlen($analys)-1) . "\n\n";
                        }
                    } else if (preg_match('#(public|protected|private|static)\s(function)\s[a-zA-Z]+#', $analys) === 1) {
                        if (empty($method)) {
                            if (empty($attr)) {
                                $attr = FALSE;
                            }

                            $method = TRUE;
                        }

                        $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . " {}\n\n";
                    } else if (preg_match('#(function)\s[a-zA-Z]+#', $analys) === 1) {
                        if (empty($method)) {
                            if (empty($attr)) {
                                $attr = FALSE;
                            }

                            $method = TRUE;
                        }

                        $finalContent .= " */\n" . substr($analys, strpos($analys, 'f')) . " {}\n\n";
                    }
                } else {
                    $finalContent .= " * " . trim($analys) . "\n";
                }
            }

            return $finalContent;
        }

        public function  buildFooter($file)
        {
            $footerContent = "\n---END OF FILE : " . $file . "---\n\n\n";

            return $footerContent;
        }

        public function createDoc($fileContent, $file, $startKey = NULL, $endKey = NULL, $count = 0)
        {
            if (!empty($this->pattern)) {
                $pattern = $this->pattern;
            } else {
                $pattern = "#\r\n|\r|\n#";
            }

            $contents = preg_split($pattern, $fileContent);
            $contents = array_map('trim', $contents);
            $mediumContent = '';
            $startKeys = array();
            $endKeys = array();
            $headers = array();

            foreach($contents as $key => $content) {
                if (preg_match('#^/(\*)+$#', $content) === 1) {
                    array_push($startKeys, $key);
                }

                if (preg_match('#^(\*)+/$#', $content) === 1) {
                    array_push($endKeys, $key + 1);
                }

                if (preg_match('#^(?:abstract|final)\s(?:class)|interface+#i', $content) === 1) {
                    $headers['className'] = $content;
                }

                if(preg_match('#^namespace+#i', $content) === 1) {
                    $headers['namespace'] = $content;
                }
            }

            if (is_null($startKey) && is_null($endKey)) {
                foreach ($startKeys as $key => $value) {
                    $this->createDoc($fileContent, $file, $value, $endKeys[$key], $count);
                    $count++;
                }
            }

            for ($i = $startKey+1; $i <= $endKey; $i++) {
                $mediumContent .= $contents[$i];
            }

            $analysis = preg_split('#\*#', $mediumContent);

            // headers
            if (!empty($headers)) {
                if ($count === 0) {
                    $headerContent = $this->buildHeader($headers);
                    $this->appendContent($headerContent);
                }
            }

            $finalContent = $this->buildMethodAnalysis($analysis, $count);

            // analysis
            if (count($analysis) > 1) {
                $this->appendContent($finalContent);
            }

            // footer
            if (count($startKeys) === $count) {
                $footerContent = $this->buildFooter($file);
                $this->appendContent($footerContent);
            }
        }

        public function create()
        {
            foreach ($this->filePaths as $v) {
                if (file_exists($v)) {
                    if (FALSE !== ($content = file_get_contents($v))) {
                        $this->createDoc($content, $v);
                    } else {
                        Throw New \FilesystemException('File ' . $v . ' can\'t be read');
                    }
                } else {
                    Throw New \FilesystemException('File ' . $v . ' doesn\'t exist');
                }
            }
        }
    }