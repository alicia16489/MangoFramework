<?php

    namespace utils;

    Class DocGen Extends Analysis
    {
        protected $filePaths = array();
        public $prettyMode = FALSE;
        public $docPath = 'doc.php';
        public $analysis;

        public function __construct($filePaths)
        {
            if (is_array($filePaths) && !empty($filePaths)) {
                $this->filePaths = $filePaths;
            } else {
                Throw New \Exception('Invalid var type : $filePath must be an array and not be empty');
            }

            $this->analysis = new Analysis();
        }

        /**
         * Escape all specials characters of the $content
         *
         * @type: method
         * @param: array $char content special chars
         * @param: string $content string to escape specials chars
         * @return: string $contentClean string escaped
         */
        public function cleanPattern($char, $content)
        {
            if (!is_array($char)) {
                $contentClean = str_replace($char, '\\' . $char, $content);
            } else {
                $contentClean = str_replace($char, '', $content);
            }

            return $contentClean;
        }

        /**
         * Append all analysis structured in the documentation file
         *
         * @type: method
         * @param: string $content content to clean and to append in doc file
         * @return: void
         */
        public function appendContent($content)
        {
            if (!file_exists($this->docPath)) {
                if (FALSE !== ($doc = fopen($this->docPath, 'w+'))) {
                    fclose($doc);
                }
            }

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

        /**
         * Build the structure to represent the header of each parsed file
         * in the comming documentation file
         *
         * @type: method
         * @param: array $headers contain the namespace and class name of each file parsed
         * @return: string $headerContent structured header to append
         */
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

        /**
         * Build the structure to represent the analysis of each parsed file
         * in the comming documentation file
         *
         * @type: method
         * @param: array $analysis contain the analysis of each parsed file
         * @return: string $finalContent structured analysis to append
         */
        public function buildAnalysis($analysis)
        {
            $finalContent = '';

            foreach ($analysis as $key => $analys) {
                if ($key === 0) {
                    $finalContent .= "/**\n";
                } else if ($key === count($analysis) - 1) {
                    if (preg_match('#(public|protected|private|static|var)\s\$[a-zA-Z]+#', $analys, $attr)) {

                        if (preg_match('#array\($#', $analys) === 1) {
                            $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . ")\n\n";
                        } else {
                            $finalContent .= " */\n" . substr($analys, strpos($analys, 'p'), strlen($analys)-1) . "\n\n";
                        }
                    } else if (preg_match('#(public|protected|private|static)\s(function)\s[a-zA-Z]+#', $analys) === 1) {
                        $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . " {}\n\n";
                    } else if (preg_match('#(function)\s[a-zA-Z]+#', $analys) === 1) {
                        $finalContent .= " */\n" . substr($analys, strpos($analys, 'f')) . " {}\n\n";
                    }
                } else {
                    $finalContent .= " * " . trim($analys) . "\n";
                }
            }

            return $finalContent;
        }

        /**
         * Build the structure to represent the analysis of each parsed file
         * in the comming documentation file
         *
         * @type: method
         * @param: array $analysis contain the analysis of each parsed file
         * @return: string $finalContent structured analysis to push in an array
         */
        public function buildAnalysisToArray($analysis)
        {
            $finalContent = '';

            foreach ($analysis as $key => $analys) {
                if ($key === 0) {
                    if (preg_match('#function#', $analysis[count($analysis) - 1])) {
                        $finalContent .= "method";
                    } else {
                        $finalContent .= "attribute";
                    }
                } else if ($key === count($analysis) - 1) {
                    if (preg_match('#((public|protected|private)|(static)|(var))\s\$[a-zA-Z]+#', $analys)) {
                        if (preg_match('#array\($#', $analys) === 1) {
                            $finalContent .= " *" . substr($analys, strpos($analys, 'p')) . ")";
                        } else {
                            $finalContent .= " *" . substr($analys, strpos($analys, 'p'), strlen($analys)-1);
                        }
                    } else if (preg_match('#^(public|protected|private|static)\s(function)\s[a-zA-Z]+#', $analys) === 1) {
                        $finalContent .= " *" . substr($analys, strpos($analys, 'p')) . "";
                    } else if (preg_match('#^(function)\s[a-zA-Z]+#', $analys) === 1) {
                        $finalContent .= " *" . substr($analys, strpos($analys, 'f')) . "";
                    }
                } else {
                    $finalContent .= " * " . trim($analys) . "\n";
                }
            }

            return $finalContent;
        }

        /**
         * Build the structure to represent the footer of each parsed file
         * in the comming documentation file
         *
         * @type: method
         * @param: string $file contain the file name of each parsed file
         * @return: string $footerContent structured footer to append
         */
        public function buildFooter($file)
        {
            $footerContent = "\n---END OF FILE : " . $file . "---\n\n\n";

            return $footerContent;
        }

        /**
         * Parse all file to get their analysis and call
         * methods to build the structure of the comming documentation file
         *
         * @type: method
         * @param: string $fileContent result of file_get_content for each file to parse
         * @param: string $file file's name to parse
         * @param: int $startKey key of each starting analysis contained in an array
         * @param: int $endKey key of each ending analysis contained in an array
         * @return: void
         */
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

                if (preg_match('#^(((?:abstract|final)\s(?:class))|interface|class).+#i', $content) === 1) {
                    $headers['className'] = $content;
                }

                if (preg_match('#^namespace+#i', $content) === 1) {
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

            $formattedAnalysis = preg_split('#\*#', $this->buildAnalysisToArray($analysis));
            $formattedAnalysis = array_map('trim', $formattedAnalysis);

            // headers
            if (!empty($headers)) {
                if ($count === 0) {
                    //$headerContent = $this->buildHeader($headers);
                    $this->analysis->fullContent[(int)$this->fileNumber]["header"] = $headers;
                    //$this->appendContent($headerContent);
                }
            }

            //$finalContent = $this->buildAnalysis($analysis);

            // analysis
            if (count($analysis) > 1) {
                //$this->appendContent($finalContent);
                $this->analysis->fullContent[(int)$this->fileNumber]["analysis"][$count] = $formattedAnalysis;
            }

            // footer
            if (count($startKeys) === $count) {
                //$footerContent = $this->buildFooter($file);
                $this->analysis->fullContent[(int)$this->fileNumber]['footer'] = trim($file);
                //$this->appendContent($footerContent);
            }
        }

        /**
         * Foreach all file set to parse and call createDoc
         *
         * @type: method
         * @param: string $file contain the file name of each parsed file
         * @return: string $footerContent structured footer to append
         */
        public function create()
        {
            foreach ($this->filePaths as $key => $v) {
                if (file_exists($v)) {
                    if (FALSE !== ($content = file_get_contents($v))) {
                        $this->fileNumber = $key;
                        $this->createDoc($content, $v);
                    } else {
                        Throw New  \Exception('File ' . $v . ' can\'t be read');
                    }
                } else {
                    Throw New \Exception('File ' . $v . ' doesn\'t exist');
                }
            }

            $this->analysis->build();
        }
    }