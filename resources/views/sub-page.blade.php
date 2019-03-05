@extends('layouts.public', ['active' => $sub->page->slug->slug, 'subactive' => $sub->slug->slug])
@section('sidebar')
    <p>
        Trasversale srl opera nell'ambito della formazione e dell'apprendimento
        ed ha acquisito durante la sua attività, iniziata nel 1992, una solida
        esperienza nel fornire strumenti per sviluppare competenze a livello
        personale e professionale, curando e valorizzando il capitale umano
        nelle aziende, tra professionisti, per le famiglie.
    </p>
@endsection
@section('content')
    <div>
        <main-content
            modules="{{ $sub->modules }}">

        </main-content>
    </div>
@endsection
