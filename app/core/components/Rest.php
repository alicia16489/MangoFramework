<?php

namespace core\components;

use Illuminate\Database\QueryException;

use core\App;
use models;

class Rest extends Controller
{
    private static $class;

    public function beforeMain()
    {
        parent::beforeMain();
        self::$class = 'models\\' . str_replace('Controller', '', str_replace('controllers\\', '', get_called_class()));
        self::$response->setType('json');
    }

    private function getMethod($const)
    {
        $method = $const;
        $pos = strrpos($method, '::');
        $method = substr($method, $pos + 2);
        return $method;
    }

    public function index()
    {
        $class = self::$class;
        $result = $class::All();
        $index = array();

        if (is_object($result)) {

            foreach ($result as $object) {
                $index[] = $object->getAttributes();
            }

            // set the response data default
            self::$response->setData($index, 'default');
        }
    }

    public function get($id)
    {
        $class = self::$class;
        $result = $class::find($id);

        if (!is_object($result)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        } else {
            $data = $result->getAttributes();
        }

        // set the response data default
        self::$response->setData($data, 'default');
    }

    public function post()
    {
        $post = App::$container['post'];
        $class = self::$class;
        $object = new $class();
        $table = str_replace('models\\', '', strtolower($class) . 's');
        $schemaManager = App::$container['Database']->getSchemaManager();
        $listTableColumns = $schemaManager->listTableColumns($table);

        foreach ($post as $column => $value) {
            if (!array_key_exists($column, $listTableColumns)) {
                self::$response->setData(array(
                    'state' => 'attribute not found',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'attribute' => $column
                ), 'default');
                return;
            } else {
                $object->$column = $value;
            }
        }

        try {
            $object->save();
            self::$response->setData(array(
                'state' => 'succeful',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $object->getAttributes()['id']
            ));
        } catch (QueryException $e) {
            self::$response->setData(array(
                'state' => 'unsucceful',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'Exception message' => $e->getMessage()
            ));
        }
    }

    public function put($id)
    {
        $post = App::$container['post'];
        $class = self::$class;
        $result = $class::find($id);
        $data = array();

        if (!is_object($result)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        } else {
            $table = str_replace('models\\', '', strtolower($class) . 's');
            $schemaManager = App::$container['Database']->getSchemaManager();
            $listTableColumns = $schemaManager->listTableColumns($table);

            foreach ($post as $column => $value) {
                if (!array_key_exists($column, $listTableColumns)) {
                    self::$response->setData(array(
                        'state' => 'attribute not found',
                        'controller' => self::$controller,
                        'method' => self::getMethod(__METHOD__),
                        'attribute' => $column
                    ), 'default');
                    return;
                } else {
                    $result->$column = $value;
                }
            }

            $data = array(
                'state' => 'succeful',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $result->getAttributes()['id']
            );
            $result->save();
        }

        self::$response->setData($data, 'default');
    }

    public function delete($id)
    {
        $class = self::$class;
        $result = $class::find($id);

        if (!is_object($result)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        } else {
            $result->delete();
            $data = array(
                'state' => 'succeful',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        }

        self::$response->setData($data, 'default');
    }

    public function complexe()
    {
        $class = self::$class;
        $options = App::$container['ComplexeOptions'];
        $first = true;
        $models = array();
        $DB = App::$container['Database']->getConnection();
        $operators = array(
            '<=','>=','<','>','='
        );

        foreach($options as $option)
        {
            if($option['action'] == 'occur' && $option['cond'][0] == '='){
                if($first){
                    $model = $class::where($option['column'],$option['cond'][0],substr($option['cond'],1));

                }
                else{
                    $model = $model->where($option['column'],$option['cond'][0],substr($option['cond'],1));
                }
                $first = false;
            }
            else if($option['action'] == 'occur' && $option['cond'][0] == '~'){
                if($first){
                    $model = $class::where($option['column'],'LIKE','%'.substr($option['cond'],1).'%');

                }
                else{
                    $model = $model->where($option['column'],'LIKE','%'.substr($option['cond'],1).'%');
                }
                $first = false;
            }
            else if($option['action'] == 'length'){
                if($first){
                    $model = $class::whereRaw('CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1));

                }
                else{
                    $model = $model->whereRaw('CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1));
                }
                $first = false;
            }
            else if($option['action'] == 'compare'){

                $i =0;
                $myOp = $operators[$i];
                while(strpos($option['cond'],$operators[$i]) === false)
                {
                    $i++;
                    $myOp = $operators[$i];
                }

                if($first){
                    $model = $class::where($option['column'],$myOp,str_replace($myOp,'',$option['cond']));

                }
                else{
                    $model = $model->where($option['column'],$myOp,str_replace($myOp,'',$option['cond']));
                }
                $first = false;
            }
        }
        $model = $model->get();

        foreach ($model as $object)
        {
            $models[] = $object->getAttributes();
        }

        self::$response->setData($models, 'default');

    }
}