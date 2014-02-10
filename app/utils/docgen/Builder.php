<?php

namespace utils\docgen;

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
                    margin-bottom: 5px;
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
                <h4>Classes<h4>
                <ul>
                    <?php

                        foreach (\$builtArrayInject as \$mainKey => \$fileParsed) {

                    ?>

                            <li>
                                <a href="#<?php echo strtolower(substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1))); ?>">
                                    <?php echo \$fileParsed['infos']['fileName']; ?>
                                </a>
                            </li>

                    <?php

                        }

                    ?>
                </ul>
            </nav>

            <section>
                <?php

                    foreach (\$builtArrayInject as \$mainKey => \$fileParsed) {

                ?>

                        <article class="files" id="<?php echo strtolower(substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1))); ?>">
                            <h4><?php echo substr(\$fileParsed['infos']['fileName'], 0, -(strlen(\$fileParsed['infos']['extension'])+1)); ?></h4>

                            <div>
                                <?php

                                    echo ('<div>');
                                    if (!empty(\$fileParsed['analysis']['attribute'])) {
                                        echo ('<span>Attributes: </span> <ul>');
                                        foreach (\$fileParsed['analysis']['attribute'] as \$attribute) {

                                ?>

                                        <li>
                                            <?php echo \$attribute['name']; ?>
                                            <ul>
                                                <li>
                                                    Description: <?php echo (!empty(\$attribute['description'])) ? \$attribute['description'] : 'none'; ?>
                                                </li>
                                                <li>
                                                    Visibility: <?php echo (!empty(\$attribute['visibility'])) ? \$attribute['visibility'] : 'none'; ?>
                                                </li>
                                                <li>
                                                    Static: <?php echo (\$attribute['isStatic'] === TRUE) ? 'yes' : 'no'; ?>
                                                </li>
                                                <li>
                                                    Type: <?php echo (!empty(\$attribute['type'])) ? \$attribute['type'] : 'none'; ?>
                                                </li>
                                            </ul>
                                        </li>

                                <?php

                                        }
                                        echo ('</ul>');
                                    } else {
                                        echo 'Attribute: none.';
                                    }
                                    echo ('</div>');

                                    echo ('<div>');
                                    if (!empty(\$fileParsed['analysis']['method'])) {
                                        echo ('<span>Methods: </span> <ul>');
                                        foreach (\$fileParsed['analysis']['method'] as \$method) {

                                ?>

                                            <li>
                                                <?php echo \$method['name']; ?>
                                                <ul>
                                                    <li>
                                                        Description: <?php echo (!empty(\$method['description'])) ? \$method['description'] : 'none'; ?>
                                                    </li>
                                                    <li>
                                                        Visibility: <?php echo (\$method['visibility']); ?>
                                                    </li>
                                                    <li>
                                                        Static: <?php echo (\$method['isStatic'] === TRUE) ? 'yes' : 'no'; ?>
                                                    </li>
                                                    <li>
                                                        <?php

                                                            if (!empty(\$method['param'])) {
                                                                echo ('<span>Arguments:</span><ul>');
                                                                foreach (\$method['param'] as \$param) {

                                                        ?>

                                                                    <li>
                                                                        <?php echo (\$param['name']); ?>
                                                                        <ul>
                                                                            <li>
                                                                                Description: <?php echo (!empty(\$param['description'])) ? \$param['description'] : 'none'; ?>
                                                                            </li>
                                                                            <li>
                                                                                Type: <?php echo (!empty(\$param['type'])) ? \$param['type'] : 'none'; ?>
                                                                            </li>
                                                                        </ul>
                                                                    </li>

                                                        <?php

                                                                }
                                                                echo ('</ul>');
                                                            } else {
                                                                echo ('Argument: none');
                                                            }

                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php

                                                            if (!empty(\$method['return'])) {
                                                                echo ('<span>Return:</span><ul>');
                                                                foreach (\$method['return'] as \$return) {

                                                        ?>

                                                                    <li>
                                                                        <?php echo (\$return['name']); ?>
                                                                        <ul>
                                                                            <li>
                                                                                Description: <?php echo (!empty(\$return['description'])) ? \$return['description'] : 'none'; ?>
                                                                            </li>
                                                                            <li>
                                                                                Type: <?php echo (!empty(\$return['type'])) ? \$return['type'] : 'none'; ?>
                                                                            </li>
                                                                        </ul>
                                                                    </li>

                                                        <?php

                                                                }
                                                                echo ('</ul>');
                                                            } else {
                                                                echo ('Return: void');
                                                            }

                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>


                                <?php

                                        }
                                        echo ('</ul>');
                                    } else {
                                        echo 'Method: none.';
                                    }
                                    echo ('</div>');

                                ?>
                            </div>
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