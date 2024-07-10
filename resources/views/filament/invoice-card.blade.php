{{-- @filamentStyles
@vite('resources/css/app.css')  --}}
<div class="bg-white-900 m-10">
    <div class="px-4 sm:px-0">
      <h3 class="text-base font-semibold leading-7 text-white-900">Invoice Information</h3>
      <p class="mt-1 max-w-2xl text-sm leading-6 text-white-500">Sender Infromation.</p>
    </div>
    <div class="mt-6 border-t border-white-100">
      <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Full name</dt>
          <dd class="mt-1 text-sm leading-6 text-white-700 sm:col-span-2 sm:mt-0">{{$record->booking->sender->full_name ?? 'no record'}}</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Address</dt>
          <dd class="mt-1 text-sm leading-6 text-white-700 sm:col-span-2 sm:mt-0">
            {{$record->booking->senderaddress->address}}</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Email address</dt>
          <dd class="mt-1 text-sm leading-6 text-white-700 sm:col-span-2 sm:mt-0">margotfoster@example.com</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-white-900">Salary expectation</dt>
          <dd class="mt-1 text-sm leading-6 text-white-700 sm:col-span-2 sm:mt-0">$120,000</dd>
        </div>
      </dl>
    </div>
  </div>
