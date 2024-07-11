<div>
    @if ($message)
        <div class="fixed bottom-0 right-0 mb-4 mr-4 max-w-sm z-50"
             {{-- x-data="{ show: {{ $showBanner ? 'true' : 'false' }} }" --}}
             x-data="{ show: true }"
             x-show="show"
             x-cloak
             {{-- x-init="setTimeout(() => show = false, {{ $sleepTimer }})" --}}
             x-init="setTimeout(() => $wire.hide(), {{ $sleepTimer ?? 5000 }})"
             >
            <div class="bg-white rounded-lg p-4 shadow-lg border border-gray-200
                        @if ($style === 'success') border-green-500 @elseif ($style === 'danger') border-red-500 @elseif ($style === 'information') border-blue-500 @elseif ($style === 'warning') border-yellow-500 @else border-gray-500 @endif">
                <div class="flex items-center justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="rounded-full p-3
                                    @if ($style === 'success') bg-green-500 @elseif ($style === 'danger') bg-red-500 @elseif ($style === 'information') bg-blue-500 @elseif ($style === 'warning') bg-yellow-500 @else bg-gray-500 @endif">
                            @if ($style === 'success')
                                {{-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-2.618a9 9 0 111.414 1.414A7 7 0 0017 15v1.5a2.5 2.5 0 01-2.5 2.5h-2a2.5 2.5 0 01-2.5-2.5V12h.001z" />
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                  </svg>
                                  
                            @elseif ($style === 'danger')
                                {{-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a1 1 0 011-1h7a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1v-8zm10-5a2 2 0 012 2v9a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h8z" />
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                  </svg>
                                  
                            @elseif ($style === 'information')
                                {{-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v4m0 0h.01" />
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                  </svg>
                                  
                            @elseif ($style === 'warning')
                                {{-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6m12 0a9 9 0 110-18 9 9 0 010 18zm0 0c1.181.5 2 1.743 2 3a2 2 0 01-2 2c-1.181 0-2 1.743-2 3" />
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                  </svg>
                                  
                            @else
                                {{-- <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                  </svg>
                                  
                            @endif
                        </div>
                        <div class="py-4 text-sm font-medium item-center" >
                            <p class="text-sm font-medium text-gray-800">{{ $message }}</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <button type="button" @click="$wire.hide" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 10l3.146-3.147a1 1 0 10-1.414-1.414L9.88 8.586l-3.147-3.147a1 1 0 00-1.414 1.414l3.147 3.147-3.147 3.146a1 1 0 101.414 1.414l3.147-3.146 3.146 3.146a1 1 0 001.414-1.414L11.707 10l3.147-3.147a1 1 0 10-1.414-1.414L10 8.293 6.854 5.146a1 1 0 00-1.414 1.414L8.586 10l-3.147 3.146a1 1 0 101.414 1.414L10 11.707l3.146 3.147a1 1 0 001.414-1.414L11.414 10l3.147-3.147a1 1 0 10-1.414-1.414L10.293 8l-3.147-3.147a1 1 0 10-1.414 1.414L8.586 10l-3.147 3.147a1 1 0 101.414 1.414L10 11.707l3.146 3.147a1 1 0 001.414-1.414L11.414 10l3.147-3.147a1 1 0 10-1.414-1.414L10.293 10z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
