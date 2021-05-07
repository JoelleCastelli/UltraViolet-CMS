<?php


namespace App\Core\Traits;

trait ModelsTrait
{
    public function setId($id) : bool
    {
        $this->id = $id;

        $object = $this->populate($this->id);
        $column = get_class_vars(get_class($this));

        if($object)
        {
            foreach ($column as $key => $value) {
                $this->$key = $object->$key; // assign each value to the current object
            }

            return true;

        } else {
            return false;
        }
    }

}
