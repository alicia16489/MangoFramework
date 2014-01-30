<?php
namespace ressources\physical;
use \core\App;
class User
{
  public function before()
  {
    echo "BEFORE : User";
  }

  public function after()
  {
    echo "AFTER : User";
  }

  public function index()
  {
    echo "Ressource : User , method : index ";
  }

  public function get($id)
  {
    echo "Ressource : User , method : get, id :".$id;
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