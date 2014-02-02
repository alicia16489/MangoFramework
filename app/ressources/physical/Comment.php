<?php
namespace ressources\physical;
class Comment
{
  public function index()
  {
    echo "Ressource : Comment , method : index ";

    return array("salut");
  }

  public function get($id)
  {
    echo "Ressource : Comment , method : get, id :".$id;
  }
}