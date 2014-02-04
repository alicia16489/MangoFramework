<?php

use Phpmig\Migration\Migration;

class UpUsers extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
      $this->get('schema')->table('users',function($table){
        $table->timestamps();
      });
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
