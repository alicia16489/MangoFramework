<?php

    namespace utils;

    Class Analysis Extends htmlPattern
    {
        protected $fullContent = array();
        protected $builtContent = array();
        protected $reflectionClass;

        protected function buildContent()
        {
            foreach ($this->fullContent as $mainKey => $fullContent) {
                // using powerful reflection class
                if (!empty($fullContent['header']['namespace'])) {
                    $this->reflectionClass = new \ReflectionClass(substr($fullContent['footer'], 0, strpos($fullContent['footer'], '/')) . '\\' . ucfirst(substr($fullContent['footer'], strpos($fullContent['footer'], '/') + 1, strpos($fullContent['footer'], '.') - 6)));
                } else {
                    $this->reflectionClass = new \ReflectionClass(ucfirst(substr($fullContent['footer'], strpos($fullContent['footer'], '/') + 1, strpos($fullContent['footer'], '.') - 6)));
                }

                // infos
                if ($this->reflectionClass->isTrait()) {

                } else if ($this->reflectionClass->isInterface()) {

                } else {
                    $this->builtContent[$mainKey]['infos']['shortClassName'] = $this->reflectionClass->getShortName();
                    $this->builtContent[$mainKey]['infos']['longClassName'] = (!empty($fullContent['header']['className'])) ? $fullContent['header']['className'] : NULL;
                    $this->builtContent[$mainKey]['infos']['classType'] = (($this->reflectionClass->isAbstract() === TRUE) ? 'abstract' : (($this->reflectionClass->isFinal() === TRUE) ? 'final' : 'normal'));
                    if($this->reflectionClass->getParentClass() !== FALSE) {
                        $this->builtContent[$mainKey]['infos']['parentClassName'] = substr($this->reflectionClass->getParentClass()->name, strrpos($this->reflectionClass->getParentClass()->name, '\\') + 1);
                    }
                    $this->builtContent[$mainKey]['infos']['isChild'] = (!empty($fullContent['header']['className'])) ? $fullContent['header']['className'] : NULL;
                }

                $this->builtContent[$mainKey]['infos']['namespace'] = $this->reflectionClass->getNamespaceName();
                $this->builtContent[$mainKey]['infos']['filePath'] = $this->reflectionClass->getFileName();
                $this->builtContent[$mainKey]['infos']['extension'] = substr($this->builtContent[$mainKey]['infos']['filePath'], strrpos($this->builtContent[$mainKey]['infos']['filePath'], '.') + 1);
                $this->builtContent[$mainKey]['infos']['fileName'] = substr($this->builtContent[$mainKey]['infos']['filePath'], strrpos($this->builtContent[$mainKey]['infos']['filePath'], '\\') + 1);

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
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['visbility'] = (!empty($properties[6]) ? trim($properties[6]) : 'public');
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['isStatic'] = ($properties[5] === 'static' || $properties[3] === 'static') ? TRUE : FALSE;

                                        if (!empty($properties[3])) {
                                            $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['scope'] = trim($properties[4]);
                                        }

                                        if ($property === 'method') {
                                            if (strpos($properties[10], '{') && strpos($properties[10], '}')) {
                                                $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]) ,0, -2);
                                            } else if (strpos($properties[10], '{')) {
                                                $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['name'] = substr(trim($properties[10]) ,0, -1);
                                            } else {
                                                $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($properties[10]);
                                            }
                                        }
                                    }

                                    if (preg_match('#^((@?type.+)((attribute) +(public|private|protected) +(static +)?(.*)))$#', $info, $attrType) === 1) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['type'] = trim($attrType[7]);

                                    }

                                    if (preg_match('#^@?type.+$#', $info, $methodType) === 1) {
                                        $delimitDescrKey = $fouKey - 1;
                                    }

                                    if (preg_match('#^((@?name)(.)?(.+))$#', $info, $name) === 1) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['name'] = trim($name[2]);
                                    }

                                    if (preg_match('#^@?param( ?: ?)?(([a-zA-Z]+) (\$[a-zA-Z0-9]+)(.+)?)$#', $info, $param) === 1) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['type'] = trim($param[3]);
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['name'] = trim($param[4]);
                                        if (!empty($param[5])) {
                                            $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['param'][$fouKey]['description'] = trim($param[5]);
                                        }
                                    }

                                    if (preg_match('#^((@?return)(.)?(.+))$#', $info, $return) === 1) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['return'] = trim($return[4]);
                                    }

                                    if (!empty($delimitDescrKey)) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['description'] = '';
                                        for ($i = 1; $i < $delimitDescrKey; $i++) {
                                            $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['description'] .= " " . $analys[$i];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            var_dump($this->builtContent);

        }

        protected function run()
        {
            if (!empty($this->fullContent)) {
                $this->buildContent();
            }
        }
    }