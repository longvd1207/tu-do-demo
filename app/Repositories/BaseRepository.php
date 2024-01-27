<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements BaseRepositoryInterface
{
    // model muốn tương tác
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }


    public function getAll()
    {
        return $this->model->where('is_delete', '=', 0)->get();
    }

    public function getAllNoneGet()
    {
        return $this->model->where('is_delete', '=', 0);
    }

    public function getById($id, $with = [])
    {
        return $this->model->where('is_delete', '=', 0)->with($with)->find($id);
    }


    public function getByField($field, $value)
    {
        return $this->model->where('is_delete', '=', 0)->where($field, '=', $value);
    }

    public function create($attributes)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->create($attributes);
            DB::commit();
            return $result;
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();
            $error["error"] = $exception->errorInfo;
            $error["request"] = $attributes;
            Log::error($error["error"]);
            Log::error($error["request"]);
            return false;
        }
    }

    public function createModel(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        return $model;
    }

    public function update($id, $attributes)
    {

        $result = $this->getById($id);
        if ($result) {
            DB::beginTransaction();
            try {
                $return = $result->update($attributes);
                DB::commit();
                return $return;
            } catch (\Illuminate\Database\QueryException $exception) {
                DB::rollBack();
                $error["error"] = $exception->errorInfo;
                Log::error($exception->errorInfo);
                Log::error($attributes);
                return false;
            }
        }
        return false;
    }

    public function updateT($id, $attributes)
    {
        $result = $this->model->where('is_delete', 0)->find($id);
        if ($result) {
            DB::beginTransaction();
            try {
                $result->update($attributes);
                DB::commit();
                return $result;
            } catch (\Illuminate\Database\QueryException $exception) {
                DB::rollBack();
                $error["error"] = $exception->errorInfo;
                Log::error($exception->errorInfo);
                Log::error($attributes);
                return false;
            }
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->getById($id);
        if ($result) {

            DB::beginTransaction();
            try {
                $result->fill(["is_delete" => 1, "deleted_at" => date("Y-m-d G:i:s")]);
                $result->save();
                $result->delete();
                DB::commit();
                return true;
            } catch (\Illuminate\Database\QueryException $exception) {
                DB::rollBack();
                $error["error"] = $exception->errorInfo;
                Log::error($exception->errorInfo);

                return false;
            }
        }
        return false;
    }


    //chuyển tiếng việt có dấu sang ko dấu
    function convert_name($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        return $str;
    }


    public function db_select($sql_select, $arr_variable)
    {
        try {
            return  DB::select($sql_select, $arr_variable);
        } catch (\Illuminate\Database\QueryException $exception) {
            $error["error"] = $exception->errorInfo;
            Log::error($exception->errorInfo);
            exit;
        }
    }

    public function db_update($sql_update, $arr_variable)
    {
        DB::beginTransaction();
        try {
            DB::update($sql_update, $arr_variable);

            DB::commit();

            return true;
        } catch (\Illuminate\Database\QueryException $exception) {

            DB::rollBack();

            $error["error"] = $exception->errorInfo;
            Log::error($exception->errorInfo);
            exit;
        }
    }

    public function db_insert($sql_select, $arr_variable)
    {
        DB::beginTransaction();
        try {
            DB::insert($sql_select, $arr_variable);
            DB::commit();
            return true;
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();
            $error["error"] = $exception->errorInfo;
            Log::error($exception->errorInfo);
            exit;
        }
    }

    public function paginateWhereLikeOrderBy(array $where, array $whereLike, $order_by = 'updated_at', $order = 'DESC', $current_page = null, $limit = null, $columns = array('*'))
    {
        $i = 0;
        $limit = is_null($limit) ? config('repository.pagination.limit', 10) : $limit;
        $current_page = is_null($current_page) ? config('repository.pagination.limit', 1) : $current_page;

        if (!empty($whereLike)) {
            $this->model = $this->model->where(function ($q) use ($whereLike, $i) {
                foreach ($whereLike as $fd => $val) {
                    if ($i == 0) {
                        $q->where($fd, 'LIKE', "%$val%");
                    } else {
                        $q->orWhere($fd, 'LIKE', "%$val%");
                    }
                    $i++;
                }
            });
        }

        if (!empty($where)) {
            $this->applyConditions($where);
        }
        $results = $this->model->orderBy($order_by, $order)->paginate($limit, $columns, 'page', $current_page);
        $this->setModel();
        return $results;
    }

    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                if (count($value) == 2) {
                    $condition = '=';
                    list($field, $val) = $value;
                } elseif (count($value) == 1) {
                    $field = $value[0];
                    $condition = null;
                    $val = null;
                } else {
                    list($field, $condition, $val) = $value;
                }
                //smooth input
                $condition = preg_replace('/\s\s+/', ' ', trim($condition));

                //split to get operator, syntax: "DATE >", "DATE =", "DAY <"
                $operator = explode(' ', $condition);
                if (count($operator) > 1) {
                    $condition = $operator[0];
                    $operator = $operator[1];
                } else $operator = null;
                switch (strtoupper($condition)) {
                    case 'IN':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereIn($field, $val);
                        break;
                    case 'NOTIN':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotIn($field, $val);
                        break;
                    case 'DATE':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereDate($field, $operator, $val);
                        break;
                    case 'DAY':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereDay($field, $operator, $val);
                        break;
                    case 'MONTH':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereMonth($field, $operator, $val);
                        break;
                    case 'YEAR':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereYear($field, $operator, $val);
                        break;
                    case 'EXISTS':
                        if (!($val instanceof \Closure)) throw new \Exception("Input {$val} must be closure function");
                        $this->model = $this->model->whereExists($val);
                        break;
                    case 'HAS':
                        if (!($val instanceof \Closure)) throw new \Exception("Input {$val} must be closure function");
                        $this->model = $this->model->whereHas($field, $val);
                        break;
                    case 'HASMORPH':
                        if (!($val instanceof \Closure)) throw new \Exception("Input {$val} must be closure function");
                        $this->model = $this->model->whereHasMorph($field, $val);
                        break;
                    case 'DOESNTHAVE':
                        if (!($val instanceof \Closure)) throw new \Exception("Input {$val} must be closure function");
                        $this->model = $this->model->whereDoesntHave($field, $val);
                        break;
                    case 'DOESNTHAVEMORPH':
                        if (!($val instanceof \Closure)) throw new \Exception("Input {$val} must be closure function");
                        $this->model = $this->model->whereDoesntHaveMorph($field, $val);
                        break;
                    case 'BETWEEN':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereBetween($field, $val);
                        break;
                    case 'BETWEENCOLUMNS':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereBetweenColumns($field, $val);
                        break;
                    case 'NOTBETWEEN':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotBetween($field, $val);
                        break;
                    case 'NOTBETWEENCOLUMNS':
                        if (!is_array($val)) throw new \Exception("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotBetweenColumns($field, $val);
                        break;
                    case 'RAW':
                        $this->model = $this->model->whereRaw($val);
                        break;
                    default:
                        if (empty($condition)) {
                            $this->model = $this->model->where($field);
                        } else {
                            $this->model = $this->model->where($field, $condition, $val);
                        }
                }
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}
