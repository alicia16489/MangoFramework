<?php

    // this class is an example for documentation generator class

    namespace utils;

    Abstract Class HtmlPattern
    {
        /**
         * Mapped events.
         *
         * @var array
         */
        public static $tag = array(
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
         * @var array
         */
        public static $style = array(
            'class-name-or-id-name' => array(
                'border' => '1px solid black',
                'color' => '#FF0000',
            ),
        );

        /**
         * Merge an array with one of htmlPattern class
         *
         * @type: method
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
         * @type: method
         * @param: void
         * @return: void
         */
        public function test()
        {

        }
    }