<?php

    namespace utils;

    Class FilesystemException extends \Exception {}

    Class docGen
    {
        protected $filePaths = array();

        public function __construct($filePaths, $docPattern = NULL)
        {
            if (is_array($filePaths) && !empty($filePaths)) {
                $this->filePaths = $filePaths;
            } else {
                Throw New \Exception('Invalid var type : $filePath must be an array and not be empty');
            }
        }

        public function appendContent($content, $path)
        {
            $searchTrim = array(' ', "\t", "\n", "\r", "\0", "\x0B");
            $contentTrim = str_replace($searchTrim, '', $content);
            $contentTrim = str_replace('*', '\*', $contentTrim);
            $contentTrim = str_replace('{', '\{', $contentTrim);
            $contentTrim = str_replace('}', '\}', $contentTrim);
            $contentTrim = str_replace(')', '\)', $contentTrim);
            $contentTrim = str_replace('(', '\(', $contentTrim);
            $contentTrim = str_replace(']', '\]', $contentTrim);
            $contentTrim = str_replace('[', '\[', $contentTrim);
            $contentTrim = str_replace('|', '\|', $contentTrim);
            $contentTrim = str_replace('$', '\$', $contentTrim);

            $pattern = "#" . $contentTrim . "#i";

            $docContent = str_replace($searchTrim, '', file_get_contents($path));

            if (preg_match($pattern, $docContent) !== 1) {
                file_put_contents($path, $content, FILE_APPEND);
            }
        }

        public function buildOutput($analysis)
        {
            $finalContent = '';

            foreach($analysis as $key => $analys) {
                if ($key === 0) {
                    $finalContent .= "/**\n";
                } else if ($key === count($analysis) - 1) {
                    $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . " {}\n\n\n";
                } else {
                    $finalContent .= " * " . trim($analys) . "\n";
                }
            }

            return $finalContent;
        }

        public function createDoc($fileContent, $path, $startKey = NULL, $endKey = NULL)
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

            foreach($contents as $key => $content) {
                if (preg_match('#^/(\*)+$#', $contents[$key]) === 1) {
                    array_push($startKeys, $key);
                }

                if (preg_match('#^(\*)+/$#', $contents[$key]) === 1) {
                    array_push($endKeys, $key + 1);
                }
            }

            if (is_null($startKey) && is_null($endKey)) {
                foreach ($startKeys as $key => $value) {
                    $this->createDoc($fileContent, $path, $value, $endKeys[$key]);
                }
            }

            for ($i = $startKey+1; $i <= $endKey; $i++) {
                $mediumContent .= $contents[$i];
            }

            $analysis = preg_split('#\*+#', $mediumContent);

            $finalContent = $this->buildOutput($analysis);

            if (count($analysis) > 1) {
                $this->appendContent($finalContent, $path);
            }
        }

        public function create($path = 'doc.php')
        {
            foreach ($this->filePaths as $v) {
                if (file_exists($v)) {
                    if (FALSE !== ($content = file_get_contents($v))) {
                        $this->createDoc($content, $path);
                    } else {
                        Throw New \FilesystemException('File ' . $v . ' can\'t be read');
                    }
                } else {
                    Throw New \FilesystemException('File ' . $v . ' doesn\'t exist');
                }
            }
        }
    }