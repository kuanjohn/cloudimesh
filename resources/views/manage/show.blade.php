<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->currentTeam->name }}
        </h2>
    </x-slot>

    <div>

        <div class="max-w-7xl mx-auto py-6 sm:px-4 lg:px-6">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <x-application-logo class="block h-24 w-auto" />

                <h1 class="mt-8 text-2xl font-medium text-gray-900">
                    Hi, {{ Auth::user()->name }},

                </h1>
                <h2>
                    Welcome to {{ Auth::user()->currentTeam->name }}'s Tenant! 
                </h2>

                <p class="mt-6 text-gray-500 leading-relaxed">
                    Cloudimesh empowers organizations to embark on their cloud journey within minutes. With us, you have the freedom to tailor your cloud environment and control costs according to your needs. Let's begin! Customize your cloud effortlessly using the menu options above
                </p>
            </div>
        </div>

    </div>
</x-admin-layout>
