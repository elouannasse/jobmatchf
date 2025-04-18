<section>
    <header>
        <h2 class="h4 mb-1">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="btn btn-danger bg-red-600 text-white mt-3"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <h2 class="h4 text-dark">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-muted">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" class="sr-only">{{ __('Password') }}</x-input-label>

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Password') }}"
                />

                @error('password', 'userDeletion')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn btn-danger bg-red-600 text-white ms-2">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
