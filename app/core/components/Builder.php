<?php

namespace core\components;

class Builder
{
  public function physical($name)
  {
    echo "build physical..";
    $class = ucfirst($name);

    $handle = fopen("./ressources/physical/".$class.".php","w");
$text = <<<EOT
<?php

namespace ressources\\physical;
use core\components\Controller;

class $class extends Controller
{
  public function index()
  {
    /**
    * If you need some treatment before the default behaviour
    * Insert your code here
    */

    /**
    * Comment this line to prevent the default behaviour
    */
    parent::index();
  }

  public function get(\$id)
  {
    /**
    * If you need some treatment before the default behaviour
    * Insert your code here
    */

    /**
    * Comment this line to prevent the default behaviour
    */
    parent::get(\$id);
  }

  public function post()
  {
    /**
    * If you need some treatment before the default behaviour
    * Insert your code here
    */

    /**
    * Comment this line to prevent the default behaviour
    */
    parent::post();
  }

  public function put(\$id)
  {
    /**
    * If you need some treatment before the default behaviour
    * Insert your code here
    */

    /**
    * Comment this line to prevent the default behaviour
    */
    parent::put(\$id);
  }

  public function delete (\$id)
  {
    /**
    * If you need some treatment before the default behaviour
    * Insert your code here
    */

    /**
    * Comment this line to prevent the default behaviour
    */
    parent::delete(\$id);
  }
}
EOT;


    fwrite($handle,$text);
  }

}