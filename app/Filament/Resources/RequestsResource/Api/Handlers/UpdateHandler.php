<?php
namespace App\Filament\Resources\RequestsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RequestsResource;
use App\Filament\Resources\RequestsResource\Api\Requests\UpdateRequestsRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = RequestsResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Requests
     *
     * @param UpdateRequestsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateRequestsRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}