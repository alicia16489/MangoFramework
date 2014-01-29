<?php

    namespace utils;

    Class Analysis extends htmlPattern
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

                //var_dump($this->reflectionClass->getStartLine());

                // header
                $this->builtContent[$mainKey]['header']['namespace'] = (!empty($fullContent['header']['namespace'])) ? substr($fullContent['header']['namespace'], 0, -1) : NULL;
                $this->builtContent[$mainKey]['header']['className'] = (!empty($fullContent['header']['className'])) ? $fullContent['header']['className'] : NULL;

                // analysis
                foreach ($fullContent as $secKey => $analysis) {
                    if ($secKey === 'analysis') {
                        foreach($analysis as $thiKey => $analys) {
                            foreach ($analys as $fouKey => $info) {
                                $info = trim($info);

                                if (preg_match('#^\s$#', $info) === 0) {
                                    if (preg_match('#^((@type.+)(attribute)).*$#', $info, $varType) === 1) {
                                        $this->builtContent[$mainKey]['analysis']['attribute'][$thiKey]['type'] = ((preg_match('#^@.+#', $varType[0]) !== FALSE) ? substr($varType[0], 1) : $varType[0]);
                                        $property = 'attribute';
                                    }

                                    if (preg_match('#^((@?type.+)(method)).*$#', $info, $methodType) === 1) {
                                        $this->builtContent[$mainKey]['analysis']['method'][$thiKey]['type'] = ((preg_match('#^@.+#', $methodType[0]) === 1) ? substr($methodType[0], 1) : $methodType[0]);
                                        $property = 'method';
                                    }

                                    if (preg_match('#^(?:@name.+)$#', $info, $name)) {
                                        $this->builtContent[$mainKey]['analysis'][$property][$thiKey]['name'] = ((preg_match('#^@.+#', $name[0]) === 1) ? substr($name[0], 1) : $name[0]);
                                    }

                                    if (preg_match('#^param#', $info) === 1) {

                                    }
                                }
                            }
                        }
                    }
                }
            }
            var_dump($this->builtContent);

        }

        protected function build()
        {
            if (!empty($this->fullContent)) {
                $this->buildContent();
            }
        }
    }