<x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Device') }} : {{ ucfirst($device->device_name) }}
            </h2>
    </x-slot>

    <div class="card m-9 border border-gray-300 p-9 rounded-lg">
        <h5 class="card-header"><strong> Session ID: </strong>{{ $device->device_name }}</h5>
        <div class="card-body">
            <h5 class="card-title"><strong> Token: </strong> {{ $device->token }}</h5>
        </div>
            <a href="{{ route('device.list') }}">
                <x-primary-button class="mt-4">Go Back</x-primary-button>
            </a>
      </div>


</x-app-layout>



