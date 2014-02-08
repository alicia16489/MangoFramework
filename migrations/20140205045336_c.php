<?php

use Phpmig\Migration\Migration;

class C extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $this->get('schema')->table('users',function($table){
            $table->dropColumn('created_at','updated_at');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
