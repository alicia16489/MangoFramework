<?php

namespace utils;

Abstract Class Builder
{
    protected function build($type = 'html', $analysis = array())
    {
        if ($type === 'html') {
            $builtArray = var_export($analysis, TRUE);

            $docHtmlContent = <<<EOT
<?php \$builtArrayInject = $builtArray; ?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta name="application-name" content="MangoDocGen">
        <meta name="description" content="API REST's Documentation">
        <meta name="Date" content="<?php echo date("D, j M Y G:i:s"); ?>">
        <title>Mango Documentation Generator</title>

        <style>
            header h3,
            section article h4
            {
                text-align: center;
            }

            nav
            {
                float: left;

                padding: 5px;
                border: 1px solid black;
            }

            section
            {
                float: left;
            }

            section article.files
            {
                margin-left: 5px;
                padding: 5px;

                border: 1px solid black;
            }

            footer
            {
                clear: both;
            }
        </style>
    </head>

    <body>
        <header>
            <h3>Mango Documentation Generator</h3>
        </header>

        <nav>
            <h4>Menu<h4>
            <?php

                foreach (\$builtArrayInject as \$mainKey => \$fileParsed) {
                    echo '<a href="#' . strtolower(substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1))) . '">' . \$fileParsed['infos']['fileName'] . '</a><br />';
                }

            ?>
        </nav>

        <section>
            <?php

                foreach (\$builtArrayInject as \$mainKey => \$fileParsed) {

            ?>

                    <article class="files" id="<?php echo strtolower(substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1))); ?>">
                        <h4><?php echo ucfirst(strtolower(substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1)))); ?></h4>
                        <div>
                    </article>

            <?php

                }

            ?>

            <aside>

            </aside>
        </section>

        <footer></footer>

    </body>
</html>
EOT;

            var_dump($analysis);
            file_put_contents('doc.php', $docHtmlContent);
        }
    }
}