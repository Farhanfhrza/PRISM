<?php

namespace App\Filament\Resources\RequestsResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\RequestsResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\RequestsResource\Api\Transformers\RequestsTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = RequestsResource::class;


    /**
     * Show Requests
     *
     * @param Request $request
     * @return RequestsTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new RequestsTransformer($query);
    }
}
