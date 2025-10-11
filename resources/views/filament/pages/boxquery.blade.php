<x-filament-panels::page>

 <x-filament-panels::form wire:submit="search" wire:keydown.enter="search">
   
   
        {{ $this->form }}
    </x-filament-panels::form>
    
   
    
    <div>{{ $this->table }}</div>

     
</x-filament-panels::page>
