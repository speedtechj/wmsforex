<div> 
<header class="fi-header-heading py-4">
        {{-- <h1 class="fi-header-heading text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl text-center">
            Scan Invoice</h1>
    </header> --}}
    <x-filament-panels::form wire:submit="create">
        {{ $this->form }}

        {{-- <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" 
        :full-width="$this->hasFullWidthFormActions()" 
       /> --}}
    </x-filament-panels::form>
   
    <x-filament::section
    class="my-4"
    {{-- icon="heroicon-o-inbox-stack"
    icon-size="md" --}}
>
    {{-- <x-slot name="heading">
       Skidding Details Information
    </x-slot> --}}

    {{$this->table}}
</x-filament::section>
</div>