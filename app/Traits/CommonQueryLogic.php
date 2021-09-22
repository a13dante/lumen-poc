<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait CommonQueryLogic
{
    public function commonConditionQuery($modelQuery, $processable) 
    {       
            if(array_key_exists('conditions', $processable)) {

                foreach($processable['conditions'] as $condition) {
                    if($condition['condition_type']==='or') {
                        $modelQuery->orWhere($condition['key'], $condition['operator'], $condition['value']);
                    } else {
                        $modelQuery->where($condition['key'], $condition['operator'], $condition['value']);
                    }
                }
            }

        return $modelQuery;    
    }

    public function commonSortQuery($modelQuery, $processable)
    {
        if(array_key_exists('sort_by', $processable)) {

            foreach($processable['sort_by'] as $sortBy) {
                $modelQuery->orderBy($sortBy['key'], strtoupper($sortBy['value']));
            }
        }

        return $modelQuery;
    }
}