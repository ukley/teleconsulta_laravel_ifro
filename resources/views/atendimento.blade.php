@extends('layouts.app')

@section('content')

<div class="container">

    <div class="py-5 text-center">
        <h2> {{ $atendimento->paciente }}</h2>
        <p class="lead">Below is an example form built entirely with Bootstrap's form controls.</p>
      </div>



      <div class="row">
        <div class="col-md-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
        <div class="card" style="width: 18rem;">

            <ul class="list-group list-group-flush">
                <li class="list-group-item">Adicionar Prontuário</li>

              <li class="list-group-item"><a href="{{ $atendimento->url_consulta }}">Teleconsulta</a></li>
              <li class="list-group-item">Prontuário Consolidado</li>
              <li class="list-group-item">Prescrição Digital</li>
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        @foreach ($prontuarios as $item)
        <h2>{{ \Carbon\Carbon::parse($item->created_at)->format('D, j M Y') }}</h2>
                    <p>{{ $item->descricao }}</p>
                    <p><a class="btn btn-secondary" href="#" role="button">Ver mais »</a></p><br>
        @endforeach
    </div>
</div>


    <style>
        body .popover {
           background-color: #f0ad4e;
           color: #ffffff;
           max-width: 600px;
       }
       </style>


         <script>
         $(function () {
           $('[data-toggle="popover"]').popover()
           });
         </script>


@endsection
