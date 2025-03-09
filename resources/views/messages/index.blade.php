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
            {{ __('WhatsApp Sender') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <form id="messageForm" action="{{ url('send-message') }}" method="POST">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @csrf
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="device" value="Select Device*" />
                    <p class="p-2 text-sm text-red-400">Connected devices will be shown only!</p>

                    <select id="device" name="device" class="mt-1 block w-full border-gray-300 dark:border-gray-700
                        dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring
                        focus:ring-indigo-500 focus:ring-opacity-50">
                        <option value="" disabled selected>-- Select a Device --</option>
                        @foreach($devices as $device)
                        @if($device->status === 'Connected')
                            <option value="{{ $device->id }}">{{ $device->device_name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>


                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="message" value="Message*" />
                    <x-text-input id="message" name="message" class="mt-1 block w-full" />
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-input-label for="to" value="Phone*" />
                    <x-text-input id="to" name="phone" class="mt-1 block w-full" />
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-start">
                        <x-primary-button class="mt-4">Send Message</x-primary-button>
                </div>
            </div>
        </div>
    </div>
</form>
</x-app-layout>


<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   document.getElementById("messageForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent normal form submission

    let formData = new FormData(this);
    let formAction = this.action;

    fetch(formAction, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log("API Response:", data); // Debug in the console

        if (data.status === 'success') {
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

            document.getElementById("messageForm").reset();
        } else {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: data.error || "Unknown error",
                showConfirmButton: false,
                timer: 10000,
                width: "auto",
                customClass: {
                   popup: 'filament-alert'
                }
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "error",
            title: "Request failed. Check console for details.",
            showConfirmButton: false,
            timer: 5000,
            width: "auto",
            customClass: {
               popup: 'filament-alert'
            }
        });
    });
});

</script>

