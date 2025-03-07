<x-app-layout>

    <style>
        .filament-alert {
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);  /* Soft shadow */
    }
    </style>


    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="flex font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Devices') }}
            </h2>

            <div class="flex justify-between">
                <a href="{{ route('devices') }}">
                    <x-primary-button>Add Device</x-primary-button>
                </a>
            </div>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 p-2">Name</th>
                            <th class="border border-gray-300 p-2">Number</th>
                            <th class="border border-gray-300 p-2">Description</th>
                            <th class="border border-gray-300 p-2">Status</th>
                            <th class="border border-gray-300 p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($devices as $device)
                        <tr>
                            <td class="border border-gray-300 p-2">{{ $device->device_name }}</td>
                            <td class="border border-gray-300 p-2">{{ $device->phone_number }}</td>
                            <td class="border border-gray-300 p-2">{{ $device->description }}</td>
                            @if($device->status === 'Connected')
                            <td class="border border-gray-300 text-green-600 p-2">{{ $device->status }}</td>
                            @else
                            <td class="border border-gray-300 text-red-500 p-2">{{ $device->status }}</td>
                            @endif
                            <td class="border border-gray-300 p-2 text-center">

                                <a href="{{ route('qr.code', $device->id) }}" class="px-2">
                                   Scan <i class="fa fa-qrcode"></i>
                                </a>

                                <a href="{{ route('edit.device', $device->id) }}" class="text-blue-500 px-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button type="button" class="text-red-500 px-2 delete-btn" data-id="{{ $device->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <!-- Delete Form (Hidden) -->
                                <form id="delete-form-{{ $device->id }}" action="{{ route('delete.device', $device->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let deviceId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    customClass: {
                        popup: 'filament-alert'  // Filament styling
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("delete-form-" + deviceId).submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: "top-end",  // Upper right corner
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                width: "auto",
                customClass: {
                    popup: 'filament-alert'  // Filament-styled notification
                }
            });
        @endif
    });
</script>
