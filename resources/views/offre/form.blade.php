@php
    $isEditPage = (bool)$offre->id;
     $title = $isEditPage ? 'Modifier' : 'Ajouter';
@endphp
    <!-- Page Content -->
<div class="content content-boxed">

    <!-- Post Job form -->
    <h2 class="content-heading fs-lg">
        <i class="fa fa-plus text-success me-1"></i>
        @if($isEditPage)
            {{ __('Edit job :name', ['name' => $offre->title]) }}
        @else
            {{ __('Post a new Job') }}
        @endif
    </h2>
    <form action="{{ $isEditPage ? route('offre.update', $offre) : route('offre.store') }}" method="POST">
        @csrf

        @if($isEditPage)
            @method('PUT')
        @endif

        <div class="block block-rounded">
            <!-- Job Information section -->
            <div class="block-content block-content-full">
                <h2 class="content-heading">{{ __('Job Information') }}</h2>
                <div class="row items-push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            {{ __('Please fill all the required Job information')}}
                        </p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="mb-4">
                            <x-input
                                name="title"
                                label="Titre de l'offre"
                                placeholder="Entrez le titre de l'offre"
                                :value="$offre->title"
                            />
                        </div>
                        <div class="mb-4">
                            <x-input
                                name="salaire_proposer"
                                type="number"
                                min="0"
                                maxlength="10"
                                label="Salaire proposé (en FCFA)"
                                placeholder="Entrez le salaire proposé"
                                :value="$offre->salaire_proposer"
                            />
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <x-input
                                    name="date_debut"
                                    label="Date début"
                                    placeholder="Date du début"
                                    class="js-flatpickr"
                                    :value="$offre->date_debut?->format('Y-m-d')"
                                    data-alt-input="true" data-date-format="Y-m-d"
                                    data-alt-format="j F Y"
                                />
                            </div>
                            <div class="col-6">
                                <x-input
                                    name="date_fin"
                                    label="Date fin"
                                    placeholder="Date de fin"
                                    class="js-flatpickr"
                                    :value="$offre->date_fin?->format('Y-m-d')"
                                    data-alt-input="true" data-date-format="Y-m-d"
                                    data-alt-format="j F Y"
                                />
                            </div>
                        </div>
                        <div class="mb-4">
                            <x-textarea
                                name="description"
                                label="Description"
                                placeholder="Entrez la description de l'offre"
                            >{{ old('description', $offre->description) }}</x-textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Job Information section -->

            <!-- Job Meta section -->
            <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
            <!-- For more info and examples you can check out https://github.com/select2/select2 -->
            <div class="block-content block-content-full">
                <h2 class="content-heading">{{ __('Job Meta') }}</h2>
                <div class="row items-push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            {{ __('A few extra meta fields and we are almost done') }}
                        </p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="mb-4">
                            <label class="form-label" for="post-type">Type de l'offre</label>
                            <select class="js-select2 form-select" id="post-type" name="type_offre"
                                    style="width: 100%;" data-placeholder=" Choisir le type de l'offre...">
                                <option></option>
                                <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                @foreach ($typeOffres as $type => $label)
                                    <option value="{{ $type }}"
                                        @selected(old('type_offre', $offre->type_offre) == $type)
                                    >{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="post-category">Category</label>
                            <select class="js-select2 form-select" id="post-category" name="category_id"
                                    style="width: 100%;" data-placeholder="Choisir la catégorie...">
                                <option></option>
                                <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected(old('category_id', $offre->category_id) == $category->id)
                                    >{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Job Meta section -->

            <!-- Submit Form -->
            <div class="block-content block-content-full pt-0">
                <div class="row mb-4">
                    <div class="col-lg-6 offset-lg-5">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-plus opacity-50 me-1"></i> {{__('Post new Job')}}
                        </button>
                    </div>
                </div>
            </div>
            <!-- END Submit Form -->
        </div>
    </form>
    <!-- END Post Job Form -->
</div>
<!-- END Page Content -->

@section('css-plugins')
    @parent
    <!-- Stylesheets -->
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection

@section('js')
    @parent
    <!-- Page JS Helpers (flatpickr plugin) -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        setTimeout(() => {
            $('.js-flatpickr').flatpickr();
        }, 100)
    </script>
@endsection
