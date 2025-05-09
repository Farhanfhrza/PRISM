<div class="flex flex-wrap gap-1">  
    @foreach($details as $detail)  
        @if($detail->stationery)  
            <span class="badge bg-primary-500 text-white px-2 py-1 text-xs rounded">  
                {{ $detail->stationery->name }} ({{ $detail->amount }})  
            </span>  
        @endif  
    @endforeach  
</div>