<?php

namespace App\Livewire;

use App\Models\AppService;
use App\Rules\AppServiceExistsForTeam;
use Livewire\Component;

use Laravel\Jetstream\Jetstream;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class AppServiceManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $managingImport;
    public $commonApps;
    public $confirmingSelectedAppServiceRemoval = false;
    public $confirmingAppServiceRemoval = false;
    public $AppServiceId;
    public $managingAppService = false;
    public $appServiceForm = [
        'name' => '',
        'vendor' => '',
        'version' => '',
        'cost' => '',
        'cost_type' => '',
        'min_disk' => '',
        'min_vcpu' => '',
        'min_vmem' => '',
        'published' => true,
    ];
    public $addingAppService;

    public function mount($id)
    {
        $this->team = Jetstream::newTeamModel()->findOrFail($id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }
    }

    public function getAppServicesProperty()
    {
        return AppService::where('team_id', $this->team->id)
        ->where(function($query){
            $query->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('vendor', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('version', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('platform', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('core_type', 'like', '%' . strtolower($this->search) . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $appServices = $this->appServices;
        return view('manage.app-service.app-service-manager', ['team' => $this->team, 'appServices' => $appServices]);
    }

    public function confirmImport()
    {
        // dd('here');
        $this->managingImport = true;
        // $this->availablAppServicees = config('departments.name');
        $this->commonApps = config('appservices');
        // $this->selectedDepartments = $this->commonDepartments;
        // dd($this->commonApps);
    }

    public function importApp()
    {
        $i = 0;
        foreach ($this->commonApps as $appName => $appEditions) {
            foreach ($appEditions as $appEdition => $appConfig) {
                foreach ($appConfig as $appName => $app) {
                    $appServiceExists = AppService::where('name', trim($appName))
                        ->where('team_id', $this->team->id)
                        ->exists();

                    if (!$appServiceExists) {
                        // dd($appName, $appEdition, $appName, $appEditions, $app['cost']);
                        $appService = new AppService();
                        $appService->team_id = $this->team->id;
                        $appService->name = trim($appName);
                        $appService->vendor = $appEdition;
                        $appService->version = trim($appName);
                        $appService->published = true;
                        $appService->cost = $app['cost'];
                        $appService->cost_type = $app['cost_type'];
                        $appService->min_disk = $app['min_disk'];
                        $appService->min_vcpu = $app['min_vcpu'];
                        $appService->min_vmem = $app['min_vmem'];
                        $appService->platform = $app['platform'];
                        $appService->created_by = Auth()->id();
                        $appService->updated_by = Auth()->id();
                        $appService->save();
                        $i++;
                    }
                }
            }
        }
        if ($i === 0) {
            $data = ['style' => 'info', 'message' => 'No Application Service imported.'];
            $this->dispatch('showBanner', $data);
        } else {
            $data = ['style' => 'success', 'message' => $i . ' Applicatin Services imported successfully.'];
            $this->dispatch('showBanner', $data);
        }

        $this->managingImport = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function updatedSelectedRows()
    {
        $row = $this->appServices->pluck('id')->map(function ($id) {
            return (string) $id;
        });
        if (count($this->selectedRows) === count($row)) {
            $this->selectedPageRow = true;
        } else {
            $this->reset(['selectedPageRow']);
        }
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->appServices->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedPageRow', 'selectedRows']);
        }
    }

    // Reset properties or perform actions when moving to a new page
    public function updatingPage($page)
    {
        // Reset any properties or perform actions here
        // For example, you can reset a property like this:
        $this->reset(['selectedPageRow', 'selectedRows']);
    }

    public function confirmSelectedAppServiceRemoval()
    {
        $this->confirmingSelectedAppServiceRemoval = true;
    }

    public function deleteSelectedRows()
    {
        AppService::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedAppServiceRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Application Service deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function confirmAppServiceRemoval($id)
    {
        $this->confirmingAppServiceRemoval = true;
        $this->AppServiceId = $id;
    }

    public function deleteAppService()
    {
        $AppService = AppService::find($this->AppServiceId);
        if ($AppService) {
            // If the Os record exists, delete it
            $AppService->delete();
            $this->confirmingAppServiceRemoval = false;
            $data = ['style' => 'success', 'message' => 'Application Service deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmManageAppService($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->AppServiceId = $id;
        $appService = AppService::find($id);
        $this->managingAppService = true;

        if ($appService) {
            // If the department record exists, retrieve information
            $this->appServiceForm['id'] = $id;
            $this->appServiceForm['name'] = $appService->name;
            $this->appServiceForm['vendor'] = $appService->vendor;
            $this->appServiceForm['version'] = $appService->version;
            $this->appServiceForm['cost'] = $appService->cost;
            $this->appServiceForm['cost_type'] = $appService->cost_type;
            $this->appServiceForm['min_disk'] = $appService->min_disk;
            $this->appServiceForm['min_vcpu'] = $appService->min_vcpu;
            $this->appServiceForm['min_vmem'] = $appService->min_vmem;
            $this->appServiceForm['platform'] = $appService->platform;
            $this->appServiceForm['published'] = $appService->published == 1 ? true : false;
        } else {
            return;
        }
    }

    public function updateAppService()
    {
        $appService = AppService::find($this->AppServiceId);

        if ($appService->name !== $this->appServiceForm['name']) {
            $this->validate(
                [
                    'appServiceForm.name' => ['required', 'min:3', 'max:255', new AppServiceExistsForTeam($this->team->id)],
                ],
                [
                    'appServiceForm.name.required' => 'The operating system name is required.',
                    'appServiceForm.name.min' => 'The operating system name must be at least 3 characters.',
                    'appServiceForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                ],
            );
        }

        $this->validate(
            [
                'appServiceForm.version' => ['required', 'min:3', 'max:255'],
                'appServiceForm.cost' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                'appServiceForm.min_disk' => ['required', 'integer', 'gt:0'],
                'appServiceForm.min_vcpu' => ['required', 'integer', 'gt:0'],
                'appServiceForm.min_vmem' => ['required', 'integer', 'gt:0'],
            ],
            [
                'appServiceForm.name.required' => 'The operating system name is required.',
                'appServiceForm.name.min' => 'The operating system name must be at least 3 characters.',
                'appServiceForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                'appServiceForm.version.required' => 'The operating system version is required.',
                'appServiceForm.version.min' => 'The operating system version must be at least 3 characters.',
                'appServiceForm.version.max' => 'The operating system version may not be greater than 255 characters.',
                'appServiceForm.cost.required' => 'The operating system cost is required.',
                'appServiceForm.cost.numeric' => 'The operating system cost must be numeric.',
                'appServiceForm.cost.gt' => 'The operating system cost must be greater than 0.',
                'appServiceForm.cost.regex' => 'The operating system cost can have maximum 4 decimals.',
                'appServiceForm.min_disk.required' => 'The operating system disk size is required.',
                'appServiceForm.min_disk.numeric' => 'The operating system disk size must be integer.',
                'appServiceForm.min_disk.gt' => 'The operating system disk size must be greater than 0.',
                'appServiceForm.min_vcpu.required' => 'The operating system minimum vCPU is required.',
                'appServiceForm.min_vcpu.numeric' => 'The operating system minimum vCPU must be integer.',
                'appServiceForm.min_vcpu.gt' => 'The operating system minimum vCPU must be greater than 0.',
                'appServiceForm.min_vmem.required' => 'The operating system minimum vMEM is required.',
                'appServiceForm.min_vmem.numeric' => 'The operating system minimum vMEM must be integer.',
                'appServiceForm.min_vmem.gt' => 'The operating system minimum vMEM must be greater than 0.',
            ],
        );

        $appService->name = trim($this->appServiceForm['name']);
        $appService->vendor = $this->appServiceForm['vendor'];
        $appService->version = trim($this->appServiceForm['version']);
        $appService->cost = $this->appServiceForm['cost'];
        $appService->cost_type = $this->appServiceForm['cost_type'];
        $appService->min_disk = $this->appServiceForm['min_disk'];
        $appService->min_vcpu = $this->appServiceForm['min_vcpu'];
        $appService->min_vmem = $this->appServiceForm['min_vmem'];
        $appService->platform = $this->appServiceForm['platform'];
        $appService->published = $this->appServiceForm['published'];
        $appService->team_id = $this->team->id;
        $appService->updated_by = Auth()->id();
        $appService->save();

        $data = ['style' => 'success', 'message' => 'Application Service updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingAppService = false;

        // $this->render();
    }

    public function confirmAppServiceAddition()
    {
        $this->resetPage();
        $this->resetErrorBag();
        // $this->OsId = $id;
        // $os = OperatingSystem::find($id);

        $this->appServiceForm['name'] = '';
        $this->appServiceForm['vendor'] = 'MySQL';
        $this->appServiceForm['version'] = '';
        $this->appServiceForm['cost'] = '';
        $this->appServiceForm['cost_type'] = 'Core';
        $this->appServiceForm['min_disk'] = '';
        $this->appServiceForm['min_vcpu'] = '';
        $this->appServiceForm['min_vmem'] = '';
        $this->appServiceForm['platform'] = 'Linux';
        $this->appServiceForm['published'] = true;

        $this->addingAppService = true;
    }

    public function addAppService()
    {
        $this->validate(
            [
                'appServiceForm.name' => ['required', 'min:3', 'max:255', new AppServiceExistsForTeam($this->team->id)],
                'appServiceForm.version' => ['required', 'min:3', 'max:255'],
                'appServiceForm.cost' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                'appServiceForm.min_disk' => ['required', 'integer', 'gt:0'],
                'appServiceForm.min_vcpu' => ['required', 'integer', 'gt:0'],
                'appServiceForm.min_vmem' => ['required', 'integer', 'gt:0'],
            ],
            [
                'appServiceForm.name.required' => 'The operating system name is required.',
                'appServiceForm.name.min' => 'The operating system name must be at least 3 characters.',
                'appServiceForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                'appServiceForm.name.required' => 'The operating system name is required.',
                'appServiceForm.name.min' => 'The operating system name must be at least 3 characters.',
                'appServiceForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                'appServiceForm.version.required' => 'The operating system version is required.',
                'appServiceForm.version.min' => 'The operating system version must be at least 3 characters.',
                'appServiceForm.version.max' => 'The operating system version may not be greater than 255 characters.',
                'appServiceForm.cost.required' => 'The operating system cost is required.',
                'appServiceForm.cost.numeric' => 'The operating system cost must be numeric.',
                'appServiceForm.cost.gt' => 'The operating system cost must be greater than 0.',
                'appServiceForm.cost.regex' => 'The operating system cost can have maximum 4 decimals.',
                'appServiceForm.min_disk.required' => 'The operating system disk size is required.',
                'appServiceForm.min_disk.numeric' => 'The operating system disk size must be integer.',
                'appServiceForm.min_disk.gt' => 'The operating system disk size must be greater than 0.',
                'appServiceForm.min_vcpu.required' => 'The operating system minimum vCPU is required.',
                'appServiceForm.min_vcpu.numeric' => 'The operating system minimum vCPU must be integer.',
                'appServiceForm.min_vcpu.gt' => 'The operating system minimum vCPU must be greater than 0.',
                'appServiceForm.min_vmem.required' => 'The operating system minimum vMEM is required.',
                'appServiceForm.min_vmem.numeric' => 'The operating system minimum vMEM must be integer.',
                'appServiceForm.min_vmem.gt' => 'The operating system minimum vMEM must be greater than 0.',
            ],
        );

        $appService = new AppService();
        $appService->name = trim($this->appServiceForm['name']);
        $appService->vendor = $this->appServiceForm['vendor'];
        $appService->version = trim($this->appServiceForm['version']);
        $appService->cost = $this->appServiceForm['cost'];
        $appService->cost_type = $this->appServiceForm['cost_type'];
        $appService->min_disk = $this->appServiceForm['min_disk'];
        $appService->min_vcpu = $this->appServiceForm['min_vcpu'];
        $appService->min_vmem = $this->appServiceForm['min_vmem'];
        $appService->platform = $this->appServiceForm['platform'];
        $appService->published = $this->appServiceForm['published'];
        $appService->team_id = $this->team->id;
        $appService->created_by = Auth()->id();
        $appService->updated_by = Auth()->id();
        $appService->save();

        $data = ['style' => 'success', 'message' => 'Application Service added successfully.'];
        $this->dispatch('showBanner', $data);
        $this->addingAppService = false;

        // $this->render();
    }
}
