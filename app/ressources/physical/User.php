<?php
namespace ressources\physical;
use \core\App;
class User
{
  public function index()
  {
    echo "Ressource : User , method : index ";

    return array("salut");
  }

  public function get($id)
  {
    echo "Ressource : User , method : get, id :".$id;
    var_dump(App::$container);
  }

  public function put($id)
  {
    echo "Ressource : User , method : put, id :".$id;
  }

  public function post()
  {
    echo "Ressource : User , method : post";
  }

  public function delete($id)
  {
    echo "Ressource : User , method : delete, id :".$id;
  }
}