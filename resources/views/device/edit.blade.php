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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Device') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <form id="deviceForm" action="{{ route('update.device', $device->id) }}" method="POST">
            @method('PUT')
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @csrf
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="number" value="Number*" />
                    <x-text-input id="phone_number" name="phone_number" class="mt-1 block w-full" value="{{ $device->phone_number }}" />
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="device_name" value="Name*" />
                    <x-text-input id="device_name" name="device_name" class="mt-1 block w-full" value="{{ $device->device_name }}" />
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="description " value="Description" />
                    <x-text-input id="description" name="description" class="mt-1 block w-full" value="{{ $device->description }}" />
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-start">
                        <x-primary-button class="mt-4">Update Device</x-primary-button>
                </div>
            </div>
        </div>
    </div>
</form>
</x-app-layout>


<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("deviceForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent normal form submission

        let formData = new FormData(this); // Get form data
        let formAction = this.action; // Get form action URL

        fetch(formAction, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": 'application/json '
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000,
                    width: "auto",
                    customClass: {
                       popup: 'filament-alert'
                    }
                });

            setTimeout(() => {
                window.location.href = data.redirect_url; // Redirect to device list page
            }, 2000);

                // Clear form after success
                document.getElementById("messageForm").reset();
            } else {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Failed to update device!",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "auto",
                    customClass: {
                       popup: 'filament-alert'
                    }
                });
            }
        })
        .catch(error => {
            error.text().then(text => {
                console.error("Error:", text);
            })
            Swal.fire({
                toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Failed to update device!",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "auto",
                    customClass: {
                       popup: 'filament-alert'
                    }
            });
        });
    });
</script>

