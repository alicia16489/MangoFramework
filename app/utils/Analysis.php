<?php

    namespace utils;

    Class Analysis extends htmlPattern
    {
        protected $fullContent = array();
        protected $menu = array();

        protected function build()
        {
            if (!empty($this->fullContent)) {
                $this->setMenu();
            }
        }

        protected function setMenu()
        {
            foreach ($this->fullContent as $mainKey => $content) {
                $this->menu[$mainKey]['title'] = $content['header'];

                foreach ($content['analysis'] as $analysisKey => $analysis) {
                    $this->menu[$mainKey]['content'][$analysisKey] = $analysis;
                }
            }
        }
    }