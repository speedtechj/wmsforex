{{-- @filamentStyles
@vite('resources/css/app.css')  --}}
<div class="bg-white-900 w-full">
    <div class="p-4">
      <h3 class="text-base font-semibold leading-7 text-white-900">Invoice Information</h3>
    </div>
    <div class="mt-2 border-t border-white-100">
      <dl class="divide-y divide-gray-100">
        <div class="px-2 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Sender name</dt>
          <dd class=" text-sm leading-6 text-white-700 sm:col-span-2">{{$record->booking->sender->full_name}}</dd>
          <dt class="text-sm font-medium leading-6 text-white-900">Address</dt>
          <dd class=" text-sm leading-6 text-white-700 sm:col-span-2"> {{$record->booking->senderaddress->address}}
             {{$record->booking->senderaddress->provincecan->name.' '.
             $record->booking->senderaddress->citycan->name .' '.
             $record->booking->senderaddress->postal_code}}
        </dd>
        </div>
        <div class="px-2 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Receiver Name</dt>
          <dd class=" text-sm leading-6 text-white-700 sm:col-span-2 ">{{$record->booking->receiver->full_name}}</dd>
          <dt class="text-sm font-medium leading-6 text-white-900">Address</dt>
          <dd class=" text-sm leading-6 text-white-700 sm:col-span-2 ">{{
          $record->booking->receiveraddress->address.' '. 
          $record->booking->receiveraddress->barangayphil->name 
       }}</dd>
        <dt class="text-sm font-medium leading-6 text-white-900"></dt>
        <dd class=" text-sm leading-6 text-white-700 sm:col-span-2 ">{{
        $record->booking->receiveraddress->cityphil->name.' '.
        $record->booking->receiveraddress->provincephil->name
     }}</dd>
        </div>
        @if ($record->booking->payment_balance > 0)
        <div class="px-2 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-white-900">Payment Balance</dt>
            <dd class=" text-sm leading-6 text-white-700 sm:col-span-2 ">
                {{"$".round(floatval($record->booking->payment_balance) / 100, precision:2)}}
          </div>
        @endif
        
        @isset($record->booking->agent->full_name)
        <div class="px-2 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-white-900">Pick-up By</dt>
            <dd class=" text-sm leading-6 text-white-700 sm:col-span-2 ">{{$record->booking->agent->full_name}}</dd>
          </div>
        @endisset
            
      
        
      </dl>
    </div>
  </div>
