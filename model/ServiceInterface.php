<?php
namespace Service;
interface ServiceInterface{
    public function fetchAll();
    public function deleteOne($id);
    public function createOne($add);
    public function updateOne($id, $upd);
    public function updateBoolean($id, $field);
}