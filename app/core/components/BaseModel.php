<?php

namespace core\components;

use core\App;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function __construct()
    {
        $table = str_replace('models\\', '', strtolower(get_called_class()) . 's');
        $schemaManager = App::$container['Database']->getSchemaManager();
        $listTableColumns = $schemaManager->listTableColumns($table);

        if (!array_key_exists('created_at', $listTableColumns) || !array_key_exists('updated_at', $listTableColumns))
            $this->timestamps = false;
    }
}