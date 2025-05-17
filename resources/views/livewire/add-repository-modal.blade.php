<flux:modal name="add-repository" class="md:w-96" wire:close="resetForm">
    <form wire:submit.debounce="save" class="space-y-6">
        <div>
            <flux:heading size="lg">Add repository</flux:heading>
            <flux:text class="mt-2">Include another Github repository</flux:text>
        </div>

        <flux:input wire:model="organization" label="Organization" placeholder="Github account name" autofocus />

        <flux:input wire:model="repository" label="Repository" placeholder="Repository name" />

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary">Add</flux:button>
        </div>
    </form>
</flux:modal>
