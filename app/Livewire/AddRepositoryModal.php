<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\AddRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

use function redirect;
use function route;

class AddRepositoryModal extends Component
{
    #[Validate('required|string')]
    public string $name = '';

    public function save(): ?RedirectResponse
    {
        $this->validate();
        try {
            $repository = (new AddRepository)($this->name);

            return redirect(route('repositories.show', ['name' => $repository->name]))->success("$repository->name added.");
        } catch (Throwable $e) {
            $this->addError('name', $e->getMessage());

            return null;
        }
    }

    public function resetForm(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.add-repository-modal');
    }
}
