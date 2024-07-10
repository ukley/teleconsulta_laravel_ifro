@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Atendimentos do Dia') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container-fluid">

                        <div class="row">

                           <div class="col-12 col-md-12 mb-4">
                                           <table class="table ">
                                               <thead>
                                                 <tr>
                                                   <th>Paciente</th>
                                                   <th>Data</th>
                                                   <th>Horário</th>

                                                   <th></th>
                                                   <th></th>

                                                 </tr>
                                               </thead>
                                               <tbody>
                                               @foreach($atendimentos as $horario)
                                                 <tr>
                                                    <td>{{$horario->paciente}}</td>
                                                   <td>{{$horario->data}}</td>
                                                   <td>{{$horario->horario}}</td>
                                                   <td>
                                                    <a href="{{ route('atendimento', ['id' => $horario->id]) }}">
                                                        <button type="button" class="btn btn-success">Atender</button>
                                                       </a>
                                                   </td>

                                                   <td>
                                                    <a href="{{ route('atendimento', ['id' => $horario->id]) }}">
                                                        <button type="button" class="btn btn-danger">Finalizar Atendimento</button>
                                                    </a>
                                                   </td>

                                                 </tr>
                                               @endforeach
                                               </tbody>
                                             </table>

                           </div>
                       </div>
                     </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
