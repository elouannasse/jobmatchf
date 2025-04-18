@php
    $isEditPage = (bool)$user->id;
    $title = $isEditPage ? 'Modifier' : 'Ajouter';
@endphp

    <!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Utilisateur</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Utilisateur</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content content-full content-boxed">
    <!-- New Post -->
    <form class="js-validation" action="{{ $isEditPage ? route('user.update', $user) : route('user.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf

        @if($isEditPage)
            @method('PUT')
        @endif

        <div class="block mb-0">
            <div class="block-header block-header-default">
                <a class="btn btn-alt-secondary" href="{{ route('user.index') }}">
                    <i class="fa fa-arrow-left me-1"></i> Liste
                </a>
                <div class="block-options">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" value="" id="etat"
                               name="etat" @checked(old('etat', $user->etat))>
                        <label class="form-check-label" for="etat">Activer l'utilisateur</label>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <div class="row justify-content-center push">
                    <div class="col-md-10">

                        <div class="d-md-flex gap-md-2">
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    name="name"
                                    label="Nom"
                                    placeholder="Entrez le nom de l'utilisateur"
                                    :value="$user->name"
                                />
                            </div>
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    name="prenom"
                                    label="Prénom"
                                    placeholder="Entrez le prénom de l'utilisateur"
                                    :value="$user->prenom"
                                />
                            </div>
                        </div>

                        <div class="d-md-flex gap-md-2">
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    type="email"
                                    name="email"
                                    label="Email"
                                    placeholder="Entrez l'email de l'utilisateur"
                                    :value="$user->email"
                                />
                            </div>
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    type="tel"
                                    name="tel"
                                    label="Téléphone"
                                    placeholder="Entrez le numéro de l'utilisateur"
                                    :value="$user->tel"
                                    :required="false"
                                />
                            </div>
                        </div>

                        <div class="d-md-flex gap-md-2">
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    type="password"
                                    name="password"
                                    label="Mot de passe"
                                    placeholder="Entrez le mot de passe de l'utilisateur"
                                    :required="!$isEditPage"
                                />
                            </div>
                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    type="password"
                                    name="password_confirmation"
                                    label="Confirmer mot de passe"
                                    placeholder="Confirmer mot de passe de l'utilisateur"
                                    :required="!$isEditPage"
                                />
                            </div>
                        </div>

                        <div class="d-md-flex gap-md-2">
                            <div class="col-md-6 mb-4 col-12">
                                <label class="form-label" for="role_id">Roles<span class="text-danger">*</span></label>
                                <select class="form-select form-control" name="role_id" id="role_id">
                                    @foreach ($roles as $role)
                                        <option
                                            value="{{ $role->id }}"
                                            @selected(old('role_id', $user->role_id) == $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4 col-12">
                                <x-input
                                    name="adresse"
                                    label="Adresse"
                                    placeholder="Entrez l'adresse de l'utilisateur"
                                    :value="$user->adresse"
                                />
                            </div>
                        </div>
                        <!-- Conteneur où les champs nom_entreprise et description_entreprise seront ajoutés -->
                        <div id="dynamic-fields-container"
                             data-recruteur-role-id="{{ $roles->firstWhere('name', 'Recruteur')->id }}"
                             data-old-nom-entreprise="{{ old('nom_entreprise', $user->nom_entreprise ?? '') }}"
                             data-old-description-entreprise="{{ old('description_entreprise', $user->description_entreprise ?? '') }}"
                             data-type-entreprise-id="{{ old('type_entreprise_id', $user->type_entreprise_id ?? '') }}"
                             data-types-entreprise="{{ $typesEntreprise->toJson() }}"
                             data-errors="{{ json_encode($errors->toArray(), JSON_THROW_ON_ERROR) }}">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content bg-body-light">
            <div class="row justify-content-center push">
                <div class="col-md-10">
                    <button type="submit" class="btn btn-md btn-alt-primary">
                        <i class="fa fa-check opacity-50 me-1">
                        </i> {{ $isEditPage ? 'Modifier' : 'Enregistrer'  }}
                    </button>
                </div>
            </div>
        </div>

    </form>
    <!-- END New Post -->
</div>
<!-- END Page Content -->

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role_id')
            const dynamicFieldsContainer = document.getElementById('dynamic-fields-container')
            const recruteurRoleId = dynamicFieldsContainer.dataset.recruteurRoleId
            const nomEntreprise = dynamicFieldsContainer.dataset.oldNomEntreprise
            const descriptionEntreprise = dynamicFieldsContainer.dataset.oldDescriptionEntreprise
            const typesEntreprise = JSON.parse(dynamicFieldsContainer.dataset.typesEntreprise)
            const typeEntrepriseId = dynamicFieldsContainer.dataset.typeEntrepriseId
            const errors = JSON.parse(dynamicFieldsContainer.dataset.errors || '{}');

            function createFormField({type = 'text', name, label, placeholder, value = '', options = {}}) {
                const div = document.createElement('div')
                div.classList.add('col-12', 'mb-4')

                const fieldLabel = document.createElement('label')
                fieldLabel.classList.add('form-label')
                fieldLabel.innerHTML = label

                let field
                if (type === 'textarea') {
                    field = document.createElement('textarea')
                    field.rows = options.rows || 4
                    field.textContent = value
                } else if (type === 'select') {
                    fieldLabel.innerHTML += '<span class="text-danger">*</span>'
                    field = document.createElement('select')
                    field.classList.add('form-select', 'js-select2')
                    field.id = name

                    const emptyOption = document.createElement('option')
                    emptyOption.textContent = "Veuillez choisir un type d'entreprise..."
                    field.setAttribute('required', '')
                    field.appendChild(emptyOption)

                    options.options.forEach((option) => {
                        const opt = document.createElement('option')
                        opt.value = option.id
                        opt.textContent = option.name
                        opt.selected = option.id == value // Sélectionne la valeur ancienne si disponible
                        field.appendChild(opt)
                    })
                } else {
                    fieldLabel.innerHTML += '<span class="text-danger">*</span>'
                    field = document.createElement('input')
                    field.setAttribute('required', '')
                    field.type = type
                    field.value = value
                }

                field.name = name
                field.placeholder = placeholder
                field.classList.add('form-control')

                div.append(fieldLabel, field)

                // Gérer les erreurs pour le champ
                console.log(errors[name])
                if (errors[name]) {
                    field.classList.add('is-invalid')
                    const errorDiv = document.createElement('div')
                    errorDiv.classList.add('invalid-feedback')
                    errorDiv.innerHTML = errors[name].join('<br>')
                    div.appendChild(errorDiv)
                }

                return div
            }

            function toggleRecruteurFields() {
                dynamicFieldsContainer.innerHTML = ''

                if (roleSelect.value === recruteurRoleId) {
                    const nomEntrepriseField = createFormField({
                        name: 'nom_entreprise',
                        label: "Nom de l'entreprise",
                        placeholder: "Entrez le nom de l'entreprise",
                        value: nomEntreprise
                    })
                    const descriptionEntrepriseField = createFormField({
                        type: 'textarea',
                        name: 'description_entreprise',
                        label: "Description de l'entreprise",
                        placeholder: "Entrez la description de l'entreprise",
                        value: descriptionEntreprise,
                        options: {rows: 4}
                    })
                    const typeEntrepriseField = createFormField({
                        type: 'select',
                        name: 'type_entreprise_id',
                        label: "Type d'entreprise",
                        placeholder: "Sélectionnez le type d'entreprise",
                        value: typeEntrepriseId,
                        options: {options: typesEntreprise}
                    })
                    dynamicFieldsContainer.append(nomEntrepriseField, typeEntrepriseField, descriptionEntrepriseField)
                }

                // Initialisation de Select2 sur le nouveau champ select
                setTimeout(() => {
                    $('.js-select2').select2({width: '100%'})
                }, 100) // Ajout d'un délai pour s'assurer que le DOM est entièrement prêt
            }

            // Appel initial pour afficher les champs si nécessaire
            toggleRecruteurFields()

            // Ajout de l'écouteur d'événement sur le changement de sélection
            roleSelect.addEventListener('change', toggleRecruteurFields)
        })
    </script>
@endpush

