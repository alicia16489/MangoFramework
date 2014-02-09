<?php

    namespace utils;

    Abstract Class Builder
    {
        protected function build($type = 'html', Array $analysis = array())
        {
            if ($type === 'html') {
                $builtArray = var_export($analysis, TRUE);

                $docHtmlContent = <<<EOT
<?php

    \$builtArrayInject = $builtArray;

    foreach(\$builtArrayInject as \$mainKey => \$fileParsed) {
        echo 'file : ' . \$mainKey = \$builtArrayInject[\$mainKey]['infos']['filePath'] . '<br />';
    }
EOT;

                var_dump($builtArray);
                file_put_contents('doc.php', $docHtmlContent);
            }
        }
    }