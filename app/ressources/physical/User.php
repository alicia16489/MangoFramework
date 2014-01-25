<?php
namespace ressources\physical;
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
  }
}