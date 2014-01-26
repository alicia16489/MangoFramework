<?php

    namespace utils;

    Class FilesystemException extends \Exception {}

    Class docGen
    {
        protected $filePaths = array("./htmlPattern.php");

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
            $pattern = "#[" . $content . "]#";

            if (!preg_match($pattern, file_get_contents($path))) {
                file_put_contents($path, $content, FILE_APPEND);
            }
        }

        public function getAnalysis($fileContent, $startKey = NULL, $endKey = NULL)
        {
            if (!empty($this->pattern)) {
                $pattern = $this->pattern;
            } else {
                $pattern = "#\r\n|\r|\n#";
            }

            $contents = preg_split($pattern, $fileContent);
            $contents = array_map('trim', $contents);
            $finalContent = '';
            $mediumContent = '';
            $startKeys = array();
            $endKeys = array();

            foreach($contents as $key => $content) {
                if ($contents[$key] === '/**') {
                    array_push($startKeys, $key);
                }

                if ($contents[$key] === '*/') {
                    array_push($endKeys, $key + 1);
                }
            }

            if (is_null($startKey) && is_null($endKey)) {
                foreach ($startKeys as $key => $value) {
                    $this->getAnalysis($fileContent, $value, $endKeys[$key]);
                }
            }

            for ($i = $startKey+1; $i <= $endKey; $i++) {
                $mediumContent .= $contents[$i];
            }

            $analysis = preg_split('#[*]#', $mediumContent);

            foreach($analysis as $key => $analys) {
                if ($key === 0) {
                    $finalContent .= "/**\n";
                } else if ($key === count($analysis) - 1) {
                    $finalContent .= " */\n" . substr($analys, strpos($analys, 'p')) . " {}\n\n\n";
                } else {
                    $finalContent .= " * " . trim($analys) . "\n";
                }
            }

            if (count($analysis) > 1) {
                $this->appendContent($finalContent, 'doc.php');
            }
        }

        public function create()
        {
            foreach ($this->filePaths as $v) {
                if (file_exists($v)) {
                    if (FALSE !== ($content = file_get_contents($v))) {
                        $this->getAnalysis($content);
                    } else {
                        Throw New \FilesystemException('File ' . $v . ' can\'t be read');
                    }
                } else {
                    Throw New \FilesystemException('File ' . $v . ' doesn\'t exist');
                }
            }
        }
    }