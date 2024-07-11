<div class="mt-10 sm:mt-0">
    <x-form-section submit="">
        <x-slot name="title">
            {{ __('vCPU & vMemory Configuration') }}
        </x-slot>

        <x-slot name="description">
            {{ __('The configuration management page allows users to efficiently edit and modify virtual machine (VM) specifications tailored to their cloud computing needs. Users can adjust CPU and memory sizing parameters, setting both minimum and maximum thresholds to ensure optimal performance and resource allocation. Additionally, users have the flexibility to specify the associated costs for each configuration, enabling informed decision-making based on budget constraints and performance requirements. Through this intuitive interface, users can seamlessly fine-tune VM configurations, optimizing their cloud computing infrastructure for efficiency and cost-effectiveness.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">

                <div class="inline-flex py-2 ">
                    <div>
                        <x-input wire:model.live.debounced.300ms="search" placeholder="Search VM polices..." />
                    </div>
                    @if (count($selectedRows) > 0)
                        <div class="md:px-2 flex items-center">
                            <x-dropdown align="right">
                                <x-slot name="trigger">
                                    <span class="rounded-md">
                                        <div class="py-1">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition ease-in-out duration-150">
                                                Bulk Action

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </div>

                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <x-responsive-nav-link wire:click.prevent="confirmSelectedVmspecRemoval"
                                        href="#" class="text-sm">
                                        {{ __('Delete Selected') }}
                                    </x-responsive-nav-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
                <div class="bg-white sm:rounded-lg text-gray-500">
                    <x-table>
                        <x-slot name="head">
                            <x-table-head class="flex justify-start max-w-[1.25rem]">
                                <input type="checkbox" wire:model.live="selectedPageRow" />
                            </x-table-head>
                            <x-table-head sortable wire:click="sortBy('name')" :sortDirection="$sortField === 'name' ? $sortDirection : null"> Name </x-table-head>
                        
                            <x-table-head class="max-w-[1.25rem]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                      </svg>
                            </x-table-head>
                        </x-slot>
                        <x-slot name="body">
                            <x-table-row>
                                <x-table-cell>
                                    
                                </x-table-cell>
                                <x-table-cell> 
                                    <div>
                                        {{ config('vmspecs')['name']  }}
                                    </div>
                                    <div>
                                        {{-- vCPU setting - {{ config('vmspecs')['min_vcpu']  }}vCPU - {{ config('vmspecs')['max_vcpu']  }}vCPU | Incremental: {{ config('vmspecs')['inc_vcpu']  }} | Cost: ${{ config('vmspecs')['cost_vcpu']  }}/daily --}}
                                        vCPU setting - {{ config('vmspecs')['min_vcpu']  }}vCPU - {{ config('vmspecs')['max_vcpu']  }}vCPU | Incremental: [@foreach(config('vmspecs')['inc_vcpu'] as $inc){{ $inc }}{{ !$loop->last ? ',' : ']' }} @endforeach | Cost: ${{ config('vmspecs')['cost_vcpu']  }}/daily
                                    </div>
                                    <div>
                                        {{-- vMem setting - {{ config('vmspecs')['min_vmem']  }}GB - {{ config('vmspecs')['max_vmem']  }}GB | Incremental: {{ config('vmspecs')['inc_vmem']  }} | Cost: ${{ config('vmspecs')['cost_vmem']  }}/daily --}}
                                        vMem setting - {{ config('vmspecs')['min_vmem']  }}GB - {{ config('vmspecs')['max_vmem']  }}GB | Incremental: [@foreach(config('vmspecs')['inc_vmem'] as $inc){{ $inc }}{{ !$loop->last ? ',' : ']' }} @endforeach | Cost: ${{ config('vmspecs')['cost_vmem']  }}/daily
                                    </div>
                                    
                                </x-table-cell>
                               
                                <x-table-cell>
                                    <div class="flex item-center justify-center">
                                       
                                    </div>
                                </x-table-cell>
                            </x-table-row>

                            @forelse ($vmspecs as $vmspec)
                                <x-table-row>
                                    <x-table-cell>
                                        <input type="checkbox" wire:model.live="selectedRows"
                                            value="{{ $vmspec->id }}" id="{{ $vmspec->id }}" />
                                    </x-table-cell>
                                    <x-table-cell> 
                                        <div>
                                            {{ $vmspec->name }}
                                        </div>
                                        <div>

                                            vCPU setting - {{ $vmspec->min_vcpu }}vCPU - {{ $vmspec->max_vcpu }}vCPU | Incremental: {{ $vmspec->inc_vcpu }}
                                            | Cost: {{ $vmspec->cost_vcpu }}/daily
                                        </div>
                                        <div>
                                            vMem setting - {{ $vmspec->min_vmem }}GB - {{ $vmspec->max_vmem }}GB | Incremental: {{ $vmspec->inc_vmem }}
                                            | Cost: {{ $vmspec->cost_vmem }}/daily
                                        </div>
                                        
                                    </x-table-cell>
                                   
                                    <x-table-cell>
                                        <div class="flex item-center justify-center">
                                            <button class="inline-flex">
                                                <x-icon.edit :wireClick="'confirmManageVmspec(' . $vmspec->id . ')'" />
                                            </button>
                                            <button class="inline-flex ml-2">
                                                <x-icon.delete :wireClick="'confirmVmspecRemoval(' . $vmspec->id . ')'" />
                                            </button>
                                        </div>
                                    </x-table-cell>
                                </x-table-row>
                            @empty
                                <x-table-row>
                                    <td colspan="4">
                                        <div class="flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-300 ">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>

                                            <span class="py-4 px-4 font-medium text-gray-500 text-sm"> No record
                                                found!
                                            </span>
                                        </div>
                                    </td>
                                </x-table-row>
                            @endforelse
                        </x-slot>
                    </x-table>
                </div>
                <div class="py-2 bg-white sm:rounded-lg text-gray-500 inline-flex items-center">
                    <select wire:model.live="perPage" id="perPage"
                        class="text-sm bg-white border border-gray-300 rounded-md leading-tight focus:outline-none focus:border-gray-500 focus:bg-white">
                        @foreach (config('pagination.options') as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>


                    <x-label class="ml-2">Per Page</x-label>
                </div>
                <div class="py-1 bg-white sm:rounded-lg text-gray-500">
                    {{ $vmspecs->onEachSide(1)->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Added.') }}
            </x-action-message>

            <x-button wire:click="confirmVMPolicyAddition()">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                        clip-rule="evenodd" />
                </svg>


                {{ __('Add VM policy') }}
            </x-button>
        </x-slot>
    </x-form-section>

    <livewire:alert-banner />

    <!-- VMSpec Management Modal (Add)-->
    <x-dialog-modal wire:model.live="addingVMPolicy">
        <x-slot name="title">
            {{ __('Add VM Policy') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="addVMPolicyForm.name" value="{{ __('Name') }}" />
                            <x-input id="addVMPolicyForm.name" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.name" />
                            <x-input-error for="addVMPolicyForm.name" class="mt-2" />
                        </div>

                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for="" value="{{ __('vCPU Configuration') }}" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.min_vcpu" value="{{ __('Min vCPU') }}" />
                            <x-input id="addVMPolicyForm.min_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.min_vcpu" />
                            <x-input-error for="addVMPolicyForm.min_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.max_vcpu" value="{{ __('Max vCPU') }}" />
                            <x-input id="addVMPolicyForm.max_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.max_vcpu" />
                            <x-input-error for="addVMPolicyForm.max_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.inc_vcpu" value="{{ __('Inc vCPU') }}" />
                            <x-input id="addVMPolicyForm.inc_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.inc_vcpu" />
                            <x-input-error for="addVMPolicyForm.inc_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-4">
                            <x-label for="addVMPolicyForm.cost_vcpu" value="{{ __('Cost per vCPU (Daily)') }}" />

                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-input id="addVMPolicyForm.cost_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.cost_vcpu" />
                            <x-input-error for="addVMPolicyForm.cost_vcpu" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for=""
                                value="{{ __('vMemory Configuration') }}" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.min_vmem" value="{{ __('Min vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.min_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.min_vmem" />
                            <x-input-error for="addVMPolicyForm.min_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.max_vmem" value="{{ __('Max vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.max_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.max_vmem" />
                            <x-input-error for="addVMPolicyForm.max_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.inc_vmem" value="{{ __('Inc vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.inc_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.inc_vmem" />
                            <x-input-error for="addVMPolicyForm.inc_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-4">
                            <x-label for="addVMPolicyForm.cost_vmem" value="{{ __('Cost per GB (Daily))') }}" />

                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-input id="addVMPolicyForm.cost_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.cost_vmem" />
                            <x-input-error for="addVMPolicyForm.cost_vmem" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            {{-- <x-secondary-button wire:click="cancelAddingVmspec()" wire:loading.attr="disabled"> --}}
            <x-secondary-button wire:click="$toggle('addingVMPolicy')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="addVMPolicy()" wire:loading.attr="disabled">
                {{ __('Add') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <x-confirmation-modal wire:model.live="confirmingVmspecRemoval">
        <x-slot name="title">
            {{ __('Remove Vmspec') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this VM Policy?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingVmspecRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteVmspec()" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <x-confirmation-modal wire:model.live="confirmingSelectedVmspecRemoval">
        <x-slot name="title">
            {{ __('Remove Selected Vmspec') }}
        </x-slot>

        <x-slot name="content">
            {{ __('You have selected ' . count($selectedRows) . ' ' . Str::plural('Policy', count($selectedRows)) . '. Are you sure you would like to remove all the selected ' . Str::plural('Policy', count($selectedRows)) . '?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingSelectedVmspecRemoval')"
                wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

     <!-- Tier Management Modal (edit)-->
     <x-dialog-modal wire:model.live="managingVmspec">
        <x-slot name="title">
            {{ __('Manage VM Policy') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="addVMPolicyForm.name" value="{{ __('Name') }}" />
                            <x-input id="addVMPolicyForm.name" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.name" />
                            <x-input-error for="addVMPolicyForm.name" class="mt-2" />
                        </div>

                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for="" value="{{ __('vCPU Configuration') }}" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.min_vcpu" value="{{ __('Min vCPU') }}" />
                            <x-input id="addVMPolicyForm.min_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.min_vcpu" />
                            <x-input-error for="addVMPolicyForm.min_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.max_vcpu" value="{{ __('Max vCPU') }}" />
                            <x-input id="addVMPolicyForm.max_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.max_vcpu" />
                            <x-input-error for="addVMPolicyForm.max_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.inc_vcpu" value="{{ __('Inc vCPU') }}" />
                            <x-input id="addVMPolicyForm.inc_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.inc_vcpu" />
                            <x-input-error for="addVMPolicyForm.inc_vcpu" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-4">
                            <x-label for="addVMPolicyForm.cost_vcpu" value="{{ __('Cost per vCPU (Daily)') }}" />

                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-input id="addVMPolicyForm.cost_vcpu" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.cost_vcpu" />
                            <x-input-error for="addVMPolicyForm.cost_vcpu" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for=""
                                value="{{ __('vMemory Configuration') }}" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.min_vmem" value="{{ __('Min vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.min_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.min_vmem" />
                            <x-input-error for="addVMPolicyForm.min_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.max_vmem" value="{{ __('Max vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.max_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.max_vmem" />
                            <x-input-error for="addVMPolicyForm.max_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-label for="addVMPolicyForm.inc_vmem" value="{{ __('Inc vMem (GB)') }}" />
                            <x-input id="addVMPolicyForm.inc_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.inc_vmem" />
                            <x-input-error for="addVMPolicyForm.inc_vmem" class="mt-2" />
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-4">
                            <x-label for="addVMPolicyForm.cost_vmem" value="{{ __('Cost per GB (Daily))') }}" />

                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <x-input id="addVMPolicyForm.cost_vmem" type="text" class="mt-1 block w-full"
                                wire:model="addVMPolicyForm.cost_vmem" />
                            <x-input-error for="addVMPolicyForm.cost_vmem" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            {{-- <x-secondary-button wire:click="cancelAddingVmspec()" wire:loading.attr="disabled"> --}}
            <x-secondary-button wire:click="$toggle('managingVmspec')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateVMPolicy()" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
