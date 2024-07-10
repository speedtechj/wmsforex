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
          $record->booking->receiveraddress->barangayphil->name.' '. 
          $record->booking->receiveraddress->cityphil->name.' '.
          $record->booking->receiveraddress->provincephil->name
       }}</dd>
        </div>
      </dl>
    </div>
  </div>
