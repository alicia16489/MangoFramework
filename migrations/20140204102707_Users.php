<?php

use Phpmig\Migration\Migration;

class Users extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
      $this->get('schema')->create('users',function ($table){
        $table->increments('id');
        $table->string('email');
        $table->softDeletes();
      });
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
