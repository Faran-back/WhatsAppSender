<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="text-lg font-semibold text-green-500">
                        Welcome to the Messaging App Dashboard!
                    </div>

                    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                             What You Can Do with WA Sender!
                        </h1>

                        <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                            <li class="flex items-center">
                                ✅ <span class="ml-2">Manage multiple devices from one dashboard</span>
                            </li>
                            <li class="flex items-center">
                                ✅ <span class="ml-2">Send bulk messages without saving contacts</span>
                            </li>
                            <li class="flex items-center">
                                ✅ <span class="ml-2">Track device connections status</span>
                            </li>
                        </ul>
                    </div>






                </div>
            </div>
        </div>
    </div>
</x-app-layout>
