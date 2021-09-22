<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait CommonDeleteOperation
{
    public function commonDelete(Request $request) {
        $responseData = [];
        $requestData = $request->all();

        $this->validate($request, $this->commonDeleteValidationRules());

        foreach($requestData as $data) {
            if(!in_array($data['model_name'], $this->deleteModels)) {
                return response(["message"=> "Model not found"], 404);
            }
        }

        foreach($requestData as $processable) {
            
            $modelQuery = ("App\\Models\\{$processable['model_name']}")::whereRaw('1 = 1');

            $modelQuery = $this->commonConditionQuery($modelQuery, $processable);
            
            //$modelQuery->dd();
            
            $responseData[$processable['nice_name']] = $modelQuery->delete();
        }
        return response($responseData, 200);
    }

    public function commonDeleteValidationRules() {
        return [
            '*.model_name' => 'required|string',
            '*.nice_name' => 'nullable|string',
            '*.conditions' => 'required|array',
            '*.conditions.*.key' => 'required|string',
            '*.conditions.*.value' => 'required|string',
            '*.conditions.*.condition_type' => 'required|string',
            '*.conditions.*.operator' => 'required|string',
        ];
    }
}
