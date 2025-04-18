<section>
    <header>
        <p class="h4 mb-1">
            {{ __('Profile Information') }}
        </p>

        <p class="text-muted">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')"/>
            <span class="text-danger">*</span>
            <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)"
                          required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div class="mb-3">
            <x-input-label for="prenom" value="Prénom"/>
            <span class="text-danger">*</span>
            <x-text-input id="prenom" name="prenom" type="text" class="form-control"
                          :value="old('prenom', $user->prenom)" required autocomplete="prenom"/>
            <x-input-error class="mt-2" :messages="$errors->get('prenom')"/>
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')"/>
            <span class="text-danger">*</span>
            <x-text-input id="email" name="email" type="email" class="form-control"
                          :value="old('email', $user->email)" required/>
            <x-input-error class="mt-2" :messages="$errors->get('email')"/>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <x-input-label for="tel" value="Téléphone"/>
            <x-text-input id="tel" name="tel" type="text" class="form-control"
                          :value="old('tel', $user->tel)" autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('tel')"/>
        </div>

        <div class="mb-3">
            <x-input-label for="adresse" value="Adresse"/>
            <x-text-input id="adresse" name="adresse" type="adresse" class="form-control"
                          :value="old('adresse', $user->adresse)" autocomplete="adresse"/>
            <x-input-error class="mt-2" :messages="$errors->get('adresse')"/>
        </div>

        @if($user->isRecruteur())
            <div class="mb-3">
                <x-input-label for="nom_entreprise" value="Nom de l'entreprise"/>
                <span class="text-danger">*</span>
                <x-text-input id="nom_entreprise" name="nom_entreprise" type="nom_entreprise"
                              class="form-control" :value="old('nom_entreprise', $user->nom_entreprise)"
                              required/>
                <x-input-error class="mt-2" :messages="$errors->get('nom_entreprise')"/>
            </div>

            <div class="mb-3">
                <x-input-label for="type_entreprise_id" value="Type d'entreprise"/>
                <select id="type_entreprise_id" name="type_entreprise_id" class="form-select js-select2"
                        data-placeholder="Choisir le type d'entreprise...">
                    <option></option>
                    @foreach ($typeEntreprises as $typeEntreprise)
                        <option value="{{ $typeEntreprise->id }}"
                            @selected(old('type_entreprise_id', $user->typeEntreprise?->id) == $typeEntreprise->id)>
                            {{ $typeEntreprise->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('type_entreprise_id')"/>
            </div>

            <div class="mb-3">
                <x-input-label for="description_entreprise" value="Description de l'entreprise"/>
                <textarea id="description_entreprise" name="description_entreprise"
                          type="description_entreprise" class="form-control" rows="4"
                >{{ old('description_entreprise', $user->description_entreprise) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description_entreprise')"/>
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>


            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-muted mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
