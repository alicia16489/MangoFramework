<?php

    namespace utils;

    Abstract Class HtmlPattern
    {
        /**
         * Mapped events.
         *
         * @type: attribute Array()
         * @name: $tag
         */
        public $tag = array(
            'doctype' => array(
                '<DOCTYPE html>'
            ),
            'html' => array(
                '<html>',
                '</html>'
            ),
            'head' => array(
                '<head>',
                '</head>'
            ),
            'body' => array(
                '<body>',
                '</body>'
            ),
            'div' => array(
                '<div>',
                '</div>'
            ),
            'span' => array(
                '<span>',
                '</span>'
            ),
            'p' => array(
                '<p>',
                '</p>'
            ),
            'table' => array(
                '<table>',
                '</table>'
            ),
            'tr' => array(
                '<tr>',
                '</div>'
            ),
            'td' => array(
                '<td>',
                '</td>'
            ),
            'h1' => array(
                '<h1>',
                '</h1>'
            ),
            'h2' => array(
                '<h2>',
                '</h2>'
            ),
            'h3' => array(
                '<h3>',
                '</h3>'
            ),
            'h4' => array(
                '<h4>',
                '</h4>'
            ),
            'h5' => array(
                '<h5>',
                '</h5>'
            ),
            'h6' => array(
                '<h6>',
                '</h6>'
            ),
            'section' => array(
                '<section>',
                '</section>'
            ),
            'header' => array(
                '<header>',
                '</header>'
            ),
            'nav' => array(
                '<div>',
                '</div>'
            ),
            'aside' => array(
                '<aside>',
                '</aside>'
            ),
            'footer' => array(
                '<footer>',
                '</footer>'
            ),
            'details' => array(
                '<details>',
                '</details>'
            ),
            'summary' => array(
                '<summary>',
                '</summary>'
            ),
        );

        public static $meta = array(
            'meta-name' => array(
                'charset' => 'utf-8',
                'content-Type' => 'text/html',
            )
        );

        /**
         * Style properties.
         *
         * @type: attribute static Array()
         * @name: $style
         */
        public static $style = array(
            'class-name-or-id-name' => array(
                'border' => '1px solid black',
                'color' => '#FF0000',
            ),
        );

        /**
         *  Merge an array with one of htmlPattern class
         *
         * @type: method public
         * @param: array $tab
         * @param: string $name
         * @return: void
         */
        public function addInTab($tab, $name)
        {
            if (!in_array($name, array('tag', 'style', 'meta'))) {
                Throw New \Exception('Invalid var $name value : must be "tag", "style" or "meta"');
            }

            if (is_array($tab)) {
                foreach ($tab as $k => $v) {
                    if (is_array($v)) {
                        continue;
                    } else {
                        Throw new \Exception('Invalid var type : $meta');
                    }
                }

                self::${$name} = array_merge(self::${$name}, $tab);
            } else {
                Throw new \Exception('Invalid var type : $meta');
            }
        }

        /**
         * Do nothing
         *
         * @type: method public
         * @param: void
         * @return: void
         */
        public function test()
        {

        }
    }