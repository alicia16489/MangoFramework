<?php

namespace core\components;

class Builder
{
  public function physicalResource($class)
  {

    if(!file_exists('./app/resources/physical/'.$class.'Resource.php')){
      $handle = fopen('./app/resources/physical/'.$class.'Resource.php','w');
      $class .= 'Resource';
$text = <<<EOT
<?php

namespace resources\\physical;
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
    $handle = fopen('./app/resources/physical/list.php','w');
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