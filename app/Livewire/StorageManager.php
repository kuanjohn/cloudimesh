<?php

namespace App\Livewire;

use App\Models\Storage;
use App\Rules\StorageExistsForTeam;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;

use function Ramsey\Uuid\v1;

class StorageManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $managingStorage = false;
    public $storageId;
    public $storageForm = [
        'id' => '',
        'name' => '',
        'published' => true,
        'cost' => '',
        'created_at' => '',
        'updated_at' => '',
    ];
    public $confirmingSelectedStorageRemoval = false;
    public $addingStorage = false;
    public $confirmingStorageRemoval = false;


    public function mount($id)
    {
        $this->team = Jetstream::newTeamModel()->findOrFail($id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }
    }

    public function getStoragesProperty()
    {
        // dd($this->team);
        return Storage::where('team_id', $this->team->id)
        ->where(function($query){
            $query->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('type', 'like', '%' . strtolower($this->search) . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $storages = $this->storages;
        return view('manage.storage.storage-manager', ['team' => $this->team, 'storages' => $storages]);
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
        $row = $this->storages->pluck('id')->map(function ($id) {
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
            $this->selectedRows = $this->storages->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedPageRow', 'selectedRows']);
        }
    }


    public function confirmManageStorage($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->storageId = $id;
        $storage = Storage::find($id);
        $this->managingStorage = true;

        if ($storage) {
            // If the department record exists, retrieve information
            // $this->storageForm['id'] = $id;
            $this->storageForm['name'] = $storage->name;
            $this->storageForm['published'] = $storage->published == 1 ? true : false;
            // $this->creator = $storage->creator->name;
            // $this->updater = $storage->updater->name;
            // $this->storageForm['created_at'] = $storage->created_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
            // $this->storageForm['updated_at'] = $storage->updated_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
        } else {
            return;
        }
    }

    public function updateStorage()
    {
        $storage = Storage::find($this->storageId);

        if ($storage->name === $this->storageForm['name']) {
        } else {
            $this->validate(
                [
                    'storageForm.name' => ['required', 'min:3', 'max:255', new StorageExistsForTeam($this->team->id)],
                ],
                [
                    'storageForm.name.required' => 'The storage name is required.',
                    'storageForm.name.min' => 'The storage name must be at least 3 characters.',
                    'storageForm.name.max' => 'The storage name may not be greater than 255 characters.',
                    'storageForm.code.max' => 'The storage code may not be greater than 100 characters.',
                ],
            );
        }

        $storage->name = trim($this->storageForm['name']);
        $storage->published = $this->storageForm['published'];
        $storage->created_by = Auth()->id();
        $storage->save();

        $data = ['style' => 'success', 'message' => 'Tier updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingStorage = false;

        // $this->render();
    }

    public function deleteSelectedRows()
    {
        Storage::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedStorageRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Tier deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function confirmSelectedStorageRemoval()
    {
        $this->confirmingSelectedStorageRemoval = true;
    }

    public function confirmStorageAddition()
    {
        $this->resetErrorBag();
        $this->addingStorage = true;
        $this->storageForm['name'] = '';
        $this->storageForm['published'] = true;
    }

    public function addStorage()
    {
        $this->validate(
            [
                'storageForm.name' => ['required', 'min:3', 'max:255', new StorageExistsForTeam($this->team->id)],
                'storageForm.type' => ['required'],
                'storageForm.cost' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
            ],
            [
                'storageForm.name.required' => 'The storage name is required.',
                'storageForm.name.min' => 'The storage name must be at least 3 characters.',
                'storageForm.name.max' => 'The storage name may not be greater than 255 characters.',
                'storageForm.type.required' => 'The storage type is required.',
                'storageForm.cost.required' => 'The storage cost is required.',
                'storageForm.cost.numeric' => 'The storage cost must be numeric.',
                'storageForm.cost.gt' => 'The storage cost must be greater than 0.',
            ],
        );

        $storage = new Storage();
        // $storage->team_id = $this->team->id;
        $storage->name = trim($this->storageForm['name']);
        $storage->type = trim($this->storageForm['type']);
        $storage->cost = trim($this->storageForm['cost']);
        $storage->published = $this->storageForm['published'];
        $storage->team_id = $this->team->id;
        $storage->created_by = Auth()->id();
        $storage->updated_by = Auth()->id();
        $storage->save();

        $data = ['style' => 'success', 'message' => 'Tier created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->storageForm['name'] = '';
        $this->storageForm['cost'] = '';
        $this->storageForm['type'] = '';
        $this->storageForm['published'] = '';
        $this->addingStorage = false;
        $this->resetErrorBag();
    }

    public function confirmStorageRemoval($id)
    {
        $this->confirmingStorageRemoval = true;
        $this->storageId = $id;
    }

    public function deleteStorage()
    {
        $storage = Storage::find($this->storageId);
        if ($storage) {
            // If the Storage record exists, delete it
            $storage->delete();
            $this->confirmingStorageRemoval = false;
            $data = ['style' => 'success', 'message' => 'VM Policy deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }
}
