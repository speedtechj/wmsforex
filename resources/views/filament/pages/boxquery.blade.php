<x-filament-panels::page>

<form wire:submit.prevent="search"  >
        {{ $this->form }}
    </form>
  
   <div>
        {{ $this->table }}
    </div>

    
</x-filament-panels::page>

