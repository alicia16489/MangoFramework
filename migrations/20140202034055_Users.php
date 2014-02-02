<?php

use Phpmig\Migration\Migration;

class Users extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
      $this->get('schema')->table('users', function ($table)
      {
        $table->dropColumn('email');
      });
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
