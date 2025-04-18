@extends('layouts.form')

@section('content')
    <!-- Page Content -->
    <div class="content content-boxed">

        <!-- Formulaire de candidature -->
        <h2 class="content-heading fs-lg">
            <i class="fa fa-plus text-success me-1"></i>
            Vous postulez à l'offre intitulée : {{ $offre->title }}
        </h2>
        <form action="{{ route('candidature.store', $offre) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="block block-rounded">
                <!-- Informations de la candidature -->
                <div class="block-content block-content-full pb-0">
                    <h2 class="content-heading">{{ __('Informations de la candidature') }}</h2>
                    <div class="row">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Veuillez fournir tous les documents nécessaires à votre candidature
                            </p>
                        </div>
                        <div class="col-lg-8 offset-lg-1">

                            <div class="mb-4">
                                <x-input name="cv"
                                         type="file"
                                         label="CV"
                                         accept=".pdf"
                                         required/>
                            </div>
                            <!-- Zone d'aperçu du PDF -->
                            <div class="mb-4">
                                <embed id="pdf-preview-cv" src="#" type="application/pdf"
                                       style="display:none; width:100%; height:400px;">
                            </div>
                            <div class="mb-4">
                                <x-input name="lettre_motivation"
                                         type="file"
                                         label="Lettre de motivation"
                                         accept=".pdf"
                                         required/>
                            </div>
                            <!-- Zone d'aperçu du PDF -->
                            <div>
                                <embed id="pdf-preview-lettre_motivation" src="#" type="application/pdf"
                                       style="display:none; width:100%; height:400px;">
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fin des informations de la candidature -->

                <!-- Bouton de soumission -->
                <div class="block-content block-content-full pt-0">
                    <div class="row mt-3">
                        <div class="col-lg-6 offset-lg-4">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-paper-plane opacity-50 me-1"></i> Soumettre la candidature
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Fin du bouton de soumission -->
            </div>
        </form>
        <!-- Fin du formulaire de candidature -->
    </div>
    <!-- Fin du contenu de la page -->
@endsection

@section('js')
    @parent
    <script>
        const previewPDF = (event, name) => {
            const preview = document.getElementById(`pdf-preview-${name}`);
            const [file] = event.target.files;

            if (file && file.type === "application/pdf") {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block'; // Afficher l'aperçu
            } else if (file.type !== "application/pdf") {
                preview.src = "#"
                preview.style.display = 'none'; // Cacher l'aperçu
            }
        }

        document.getElementById('cv').addEventListener('change', function (event) {
            previewPDF(event, 'cv');
        });
        document.getElementById('lettre_motivation').addEventListener('change', function (event) {
            previewPDF(event, 'lettre_motivation');
        });
    </script>
@endsection
