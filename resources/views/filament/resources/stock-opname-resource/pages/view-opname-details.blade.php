<x-filament-panels::page>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">Detail Opname untuk: {{ $record->description ?? 'Tanpa Deskripsi' }}</h2>

        {{ $this->table }}
    </x-filament::card>
</x-filament-panels::page>
