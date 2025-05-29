<?php
namespace App\Filament\Resources\RequestsResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Requests;

/**
 * @property Requests $resource
 */
class RequestsTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
