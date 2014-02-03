<?php

namespace core\components;

class Builder
{
  public function physicalRessource($class)
  {

    if(!file_exists('./app/ressources/physical/'.$class.'Ressource.php')){
      $handle = fopen('./app/ressources/physical/'.$class.'Ressource.php','w');
      $class .= 'Ressource';
$text = <<<EOT
<?php

namespace ressources\\physical;
use core\components\Controller;
use core\App;

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

  public function physicalModel($class)
  {

    if(!file_exists('./app/models/'.$class.'.php')){

      $handle = fopen('./app/models/'.$class.'.php','w');
      $text = <<<EOT
<?php

namespace models;

class $class
{

}
EOT;

      fwrite($handle,$text);
    }
  }

  public function physicalList($array)
  {
    $handle = fopen('./app/ressources/physical/list.php','w');
    $strArray = 'array(';

    foreach($array as $key => $entity)
    {
      if($key != 0)
        $strArray .= ',';
      $strArray .= '"'.$entity.'"';
    }

    $strArray .= ')';

    $text = <<<EOT
<?php

return $strArray;
EOT;

    fwrite($handle,$text);
  }

}