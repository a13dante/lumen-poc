<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait CommonIndexOperation
{
    public function commonIndex(Request $request) {
        $responseData = [];
        $requestData = $request->all();

        $this->validate($request, $this->commonIndexValidationRules());

        foreach($requestData as $data) {
            if(!in_array($data['model_name'], $this->indexModels)) {
                return response(["message"=> "Model not found"], 404);
            }
        }

        foreach($requestData as $processable) {
            
            $modelQuery = ("App\\Models\\{$processable['model_name']}")::select($processable['fields']);

            $modelQuery = $this->commonConditionQuery($modelQuery, $processable);

            $modelQuery = $this->commonSortQuery($modelQuery, $processable);

            if($processable['paginate']) {
                $responseData[$processable['nice_name']] = $modelQuery->limit($processable['limit'])
                                                            ->offset($processable['offset'])->get();
            } else {
                $responseData[$processable['nice_name']] = $modelQuery->get();
            }
        }
        return response($responseData, 200);
    }

    public function commonIndexValidationRules() {
        return [
            '*.model_name' => 'required|string',
            '*.nice_name' => 'nullable|string',
            '*.fields' => 'required|array',
            '*.conditions' => 'nullable|array',
            '*.conditions.*.key' => 'required|string',
            '*.conditions.*.value' => 'required|string',
            '*.conditions.*.condition_type' => 'required|string',
            '*.conditions.*.operator' => 'required|string',
            '*.sort_by' => 'nullable|array',
            '*.sort_by.*.key' => 'required|string',
            '*.sort_by.*.value' => 'required|string',
            '*.paginate' => 'required|boolean',
            '*.limit' => 'nullable|integer',
            '*.offset' => 'nullable|integer',
        ];
    }
}
