<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait CommonShowOperation
{
    public function commonShow(Request $request) {
        $responseData = [];
        $requestData = $request->all();

        $this->validate($request, $this->commonShowValidationRules());

        foreach($requestData as $data) {
            if(!in_array($data['model_name'], $this->showModels)) {
                return response(["message"=> "Model not found"], 404);
            }
        }

        foreach($requestData as $processable) {
            
            $modelQuery = ("App\\Models\\{$processable['model_name']}")::select($processable['fields']);

            $modelQuery = $this->commonConditionQuery($modelQuery, $processable);

            $modelQuery = $this->commonSortQuery($modelQuery, $processable);
            
            $responseData[$processable['nice_name']] = $modelQuery->firstOrNew();
        }
        return response($responseData, 200);
    }

    public function commonShowValidationRules() {
        return [
            '*.model_name' => 'required|string',
            '*.nice_name' => 'nullable|string',
            '*.fields' => 'required|array',
            '*.conditions' => 'required|array',
            '*.conditions.*.key' => 'required|string',
            '*.conditions.*.value' => 'required|string',
            '*.conditions.*.condition_type' => 'required|string',
            '*.conditions.*.operator' => 'required|string',
            '*.sort_by' => 'nullable|array',
            '*.sort_by.*.key' => 'required|string',
            '*.sort_by.*.value' => 'required|string',
        ];
    }
}
