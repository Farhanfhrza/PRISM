<?php

namespace App\Filament\Resources\RequestsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RequestsResource;
use App\Filament\Resources\RequestsResource\Api\Requests\CreateRequestsRequest;

class CreateHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = RequestsResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Create Requests
     *
     * @param CreateRequestsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateRequestsRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->except('request_details'));

        $model->save();

        // Simpan request_details
        foreach ($request->input('request_details') as $detail) {
            $model->requestDetails()->create($detail);
        }

        return static::sendSuccessResponse($model->load('requestDetails'), "Successfully Create Resource");
    }
}
