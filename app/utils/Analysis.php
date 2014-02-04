<?php

    namespace utils;

    Class Analysis Extends htmlPattern
    {
        protected $fullContent = array();
        protected $docType = 'html';

        private $builtArray = array();
        private $reflectionClass;

        // comming soon
        private function buildTemplatePDF()
        {

        }

        // comming soon
        private function buildTemplateXML()
        {

        }

        // Waiting for template
        private function buildTemplateHTML()
        {
            $builtArray = var_export($this->builtArray, TRUE);
            $htmlTag = var_export($this->tag, TRUE);
            $cssProperties = var_export($this->style, TRUE);
            $meta = var_export($this->meta, TRUE);

            $docHtmlContent = <<<EOT
<?php

    \$builtArrayInject = $builtArray;
    \$meta = $meta;
    \$cssProperties = $cssProperties;
    \$htmlTag = $htmlTag;

    foreach(\$builtArrayInject as \$mainKey => \$fileParsed) {
        echo 'file : ' . \$mainKey = \$builtArrayInject[\$mainKey]['infos']['filePath'] . '<br />';
    }
EOT;
            var_dump($this->builtArray);
            file_put_contents('doc.php', $docHtmlContent);
        }

        private function buildArray()
        {
            foreach ($this->fullContent as $mainKey => $fullContent) {
                // using powerful reflection class
                $this->reflectionClass = new \ReflectionClass($fullContent['header']['fullClassName']);

                // infos
                if ($this->reflectionClass->isTrait()) {

                } else if ($this->reflectionClass->isInterface()) {

                } else {
                    $this->builtArray[$mainKey]['infos']['shortClassName'] = $this->reflectionClass->getShortName();
                    $this->builtArray[$mainKey]['infos']['longClassName'] = (!empty($fullContent['header']['longClassName'])) ? $fullContent['header']['longClassName'] : NULL;
                    $this->builtArray[$mainKey]['infos']['classType'] = (($this->reflectionClass->isAbstract() === TRUE) ? 'abstract' : (($this->reflectionClass->isFinal() === TRUE) ? 'final' : 'normal'));
                    if($this->reflectionClass->getParentClass() !== FALSE) {
                        $this->builtArray[$mainKey]['infos']['parentClassName'] = substr($this->reflectionClass->getParentClass()->name, strrpos($this->reflectionClass->getParentClass()->name, '\\') + 1);
                    }
                    $this->builtArray[$mainKey]['infos']['isChild'] = (!empty($this->reflectionClass->getParentClass()->name)) ? TRUE : NULL;
                }

                $this->builtArray[$mainKey]['infos']['namespace'] = $this->reflectionClass->getNamespaceName();
                $this->builtArray[$mainKey]['infos']['filePath'] = $this->reflectionClass->getFileName();
                $this->builtArray[$mainKey]['infos']['extension'] = substr($this->builtArray[$mainKey]['infos']['filePath'], strrpos($this->builtArray[$mainKey]['infos']['filePath'], '.') + 1);
                $this->builtArray[$mainKey]['infos']['fileName'] = substr($this->builtArray[$mainKey]['infos']['filePath'], strrpos($this->builtArray[$mainKey]['infos']['filePath'], '\\') + 1);

                // analysis
                foreach ($fullContent as $secKey => $analysis) {
                    if ($secKey === 'analysis') {
                        foreach($analysis as $thiKey => $analys) {
                            //var_dump($analys);

                            foreach ($analys as $fouKey => $info) {
                                $info = trim($info);
                                if (preg_match('#^\s$#', $info) === 0) {
                                    if (preg_match('#^(method|attribute)$#', $info, $type)) {
                                        $property = $type[0];
                                    }

                                    if (preg_match('#^(((static)? ?(abstract|final)? ?(static)? ?(public|private|protected)? (abstract|final)? ?(static)?) ?(function)? +(.+))$#', $info, $properties)) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['visbility'] = (!empty($properties[6]) ? trim($properties[6]) : 'public');
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['isStatic'] = ($properties[5] === 'static' || $properties[3] === 'static') ? TRUE : FALSE;

                                        if (!empty($properties[3])) {
                                            $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['scope'] = trim($properties[4]);
                                        }

                                        if ($property === 'method') {
                                            if (strpos($properties[10], '{') && strpos($properties[10], '}')) {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]) ,0, -2);
                                            } else if (strpos($properties[10], '{')) {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]) ,0, -1);
                                            } else {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($properties[10]);
                                            }
                                        }
                                    }

                                    if (preg_match('#^((@?type.+)((attribute) +(public|private|protected) +(static +)?(.*)))$#', $info, $attrType) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['type'] = (trim($attrType[7] === 'Array()') ? strtolower(substr(trim($attrType[7]) , 0, -2)) : trim($attrType[7]));
                                    }

                                    if (preg_match('#^@?type.+$#', $info, $methodType) === 1) {
                                        if ($analys[$fouKey - 1] === '') {
                                            $delimitDescrKey = $fouKey - 1;
                                        } else {
                                            $delimitDescrKey = $fouKey;
                                        }
                                    }

                                    if (preg_match('#^((@?name)(.)?(.+))$#', $info, $name) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($name[4]);
                                    }

                                    if (preg_match('#^@?param( ?: ?)?(([a-zA-Z]+) (\$[a-zA-Z0-9]+)(.+)?)$#', $info, $param) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['type'] = trim($param[3]);
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['name'] = trim($param[4]);
                                        if (!empty($param[5])) {
                                            $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['description'] = trim($param[5]);
                                        }
                                    }

                                    if (preg_match('#^((@?return)(.)?(.+))$#', $info, $return) === 1) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['return'] = trim($return[4]);
                                    }

                                    if (!empty($delimitDescrKey)) {
                                        $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] = '';
                                        for ($i = 1; $i < $delimitDescrKey; $i++) {
                                            if ($i === 1) {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] .= $analys[$i];
                                            } else {
                                                $this->builtArray[$mainKey]['analysis'][$property][$thiKey]['description'] .= " " . $analys[$i];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        protected function process()
        {
            if (!empty($this->fullContent)) {
                $this->buildArray();
                if (!empty($this->builtArray)) {
                    if (in_array($this->docType, ['html', 'xml', 'pdf'])) {
                        $this->{'buildTemplate' . strtoupper($this->docType)}();
                    }
                }
            }
        }
    }

?>