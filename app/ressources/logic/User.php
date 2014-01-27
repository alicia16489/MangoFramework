<?php
namespace ressources\logic;
class User
{
  public function index()
  {
    echo "Ressource logic : User , method : index ";

  }

  public function get($id)
  {
    echo "Ressource : User , method : get, id :".$id;
  }
}