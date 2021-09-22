<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CommonStoreOperation
{
    public function commonStore(Request $request) {
        $responseData = [];
        $requestData = $request->all();

        $this->validate($request, $this->commonStoreValidationRules());

        foreach($requestData as $data) {
            if(!in_array($data['model_name'], $this->storeModels)) {
                return response(["message"=> "Model not found"], 404);
            }
        }
        try {
            DB::transaction(function() use ($requestData, &$responseData) {
                foreach($requestData as $processable) {
                    $response = [];
                    foreach($processable['data'] as $data) {
                        $model = ("App\\Models\\{$processable['model_name']}")::create($data);
                        array_push($response, $model);
                    }
                    $responseData[$processable['nice_name']] = $response;
                }
            });
            return response($responseData, 201);
        } catch(\Exception $e) {
            report($e);
            return response(['message' => 'Something went wrong'], 400);
        }
    }

    public function commonStoreValidationRules() {
        return [
            '*.model_name' => 'required|string',
            '*.nice_name' => 'nullable|string',
            '*.data' => 'required|array',
        ];
    }
}    