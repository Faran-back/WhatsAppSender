<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scan QR Code') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center mt-16 space-x-8">
        <!-- QR Code -->
        <div>
            @if(isset($qrCodeImage))
            <img src="{{ $qrCodeImage }}" alt="QR Code">
        @else
            <p class="font-semibold">The QR is being Loaded.</p>
            <p class="font-semibold">Try Refreshing the page after few seconds</p>
        @endif
        </div>


        <!-- Instructions -->
        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md w-96">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">To use WhatsApp on your computer:</h3>
            <ul class="text-gray-700 dark:text-gray-200 text-md space-y-2">
                <li>📱 Open <strong>WhatsApp</strong> on your phone</li>
                <li>⚙️ Tap <strong>Menu</strong> (Android) or <strong>Settings</strong> (iPhone)</li>
                <li>🔗 Tap <strong>Linked Devices</strong></li>
                <li>📸 Scan the QR Code above</li>
            </ul>
        </div>
    </div>



    <!-- Include Filament Toaster -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function checkStatus() {
            fetch("{{ url('check-status/'. $device->id ) }}")
                .then(response => response.json())
                .then(data => {
                console.log("Device Status Response:", data);
                    if (data.status === 'Connected') {
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: "Connected!",
                            showConfirmButton: false,
                            timer: 5000
                        });

                        setTimeout(() => {
                            window.location.href = "{{ route('device.list') }}";
                        }, 1500);
                    }
                })
                .catch(error => console.error("Error checking status:", error));
        }

        setInterval(checkStatus, 5000); // Check every 5 seconds
    </script>
</x-app-layout>
