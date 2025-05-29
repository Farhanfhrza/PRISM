<?php
namespace App\Filament\Resources\RequestsResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\RequestsResource;
use Illuminate\Routing\Router;


class RequestsApiService extends ApiService
{
    protected static string | null $resource = RequestsResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
