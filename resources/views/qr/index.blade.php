<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scan QR Code') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center mt-16 space-x-8">
        <!-- QR Code -->
        <div>
            @if($qrCodeImage)
                <div id="qrImage" class="w-64 h-64 border border-gray-300 rounded-lg shadow-md">
                    {!! $qrCodeImage !!}
                </div>
            @else
                <p class="text-red-500">Failed to generate QR Code.</p>
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
            fetch("{{ route('check.status', $device->id) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "AUTHENTICATED") {
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: "Authenticated!",
                            showConfirmButton: false,
                            timer: 2000
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
