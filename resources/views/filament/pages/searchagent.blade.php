<x-filament-panels::page>
 {{ $this->form }}
  @if ($scannedInvoices)
    <div class="mt-4">
        <h1 class="text-lg font-semibold">Scanned Invoice Details</h1><br>
         <h2 class="text-lg font-bold">Invoice Number: {{ $scannedInvoices->manual_invoice }}</h2><br>
        <h2 class="text-lg font-bold">Agent Name: {{ $scannedInvoices->agent->full_name }}</h2>

        <!-- Add more details as needed -->
    </div>
  @endif
</x-filament-panels::page>
