<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getAll();
    public function getById($id, $with = []);
    public function create($attributes);
    public function update($id, $attributes);
    public function delete($id);
    public function db_select($sql_select, $arr_variable);

    public function db_update($sql_update, $arr_variable);

    public function db_insert($sql_select, $arr_variable);
}
