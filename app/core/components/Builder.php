<?php

namespace core\components;

class Builder
{
  public function physicalController($class)
  {

    if(!file_exists('./app/controllers/physical/'.$class.'Controller.php')){
      $handle = fopen('./app/controllers/physical/'.$class.'Controller.php','w');
      $model = $class;
      $class .= 'Controller';
$text = <<<EOT
<?php

namespace controllers\\physical;
use core\components\Rest;
use core\App;
use models\\$model;

class $class extends Rest
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
use core\components\BaseModel;

class User extends BaseModel{
    protected \$softDelete = true;
}
EOT;

      fwrite($handle,$text);
    }
  }

  public function physicalList($array)
  {
    $handle = fopen('./app/controllers/physical/list.php','w');
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