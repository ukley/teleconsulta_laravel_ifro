@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="title h4 mb-4">
        <span>Agendamento de Consulta</span>
        <a class="ms-2" href="#">#</a>
    </h3>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Lista Pacientes</div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input class="form-control" id="nome" type="text" placeholder="Buscar">
                        <button class="btn btn-info" type="button" onclick="buscarParaAgendar()">Buscar</button>
                    </div>
                    <div id="resultado"></div>
                </div>
            </div>


            <form action="/agenda_consulta" method="post" class="mt-4">
                @csrf
                <div class="mb-3">
                    <input class="form-control" name="nome_medico" type="hidden" value="Eurico Ribeiro Dr">
                    <input class="form-control" name="paciente" type="hidden" value="{{ $dados_paciente->UsuarioNome ?? '' }}">
                    <input class="form-control" name="cpf" type="hidden" value="{{ $dados_paciente->UsuarioCpf ?? '' }}">
                    <input class="form-control" type="hidden" name="cnes" value="{{ $dados_paciente->UsuarioId ?? '' }}">
                </div>
                <div class="field">
                    <label class="label">Nome Paciente</label>
                    <div class="control">
                    <input class="form-control" name="paciente" type="text" value="{{ $dados_paciente[0]->UsuarioNome ?? '' }}" >
                    </div>
                </div>

                <div class="field">
                    <label class="label">Cpf</label>
                    <div class="control">
                        <input class="form-control" name="cpf" type="text" value="{{ $dados_paciente[0]->UsuarioCpf ?? '' }}" >
                    </div>
                </div>

                <div class="field">
                    <label class="label">Usuario Cnes </label>
                    <div class="control">
                    <input class="form-control" type="text" name="UsuarioId" value="{{ $dados_paciente[0]->UsuarioId ?? '' }}" >
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Paciente</label>
                    <input class="form-control" name="email" type="email" placeholder="Obrigatório para recebimento do link, data e horário da teleconsulta">
                </div>

                <div class="mb-3">
                    <label class="form-label">Celular</label>
                    <input class="form-control" type="text" name="celular" placeholder="Obrigatório para envio de SMS do link, data e horário da teleconsulta">
                </div>

                <div class="mb-3">
                    <label class="form-label">Selecione o Médico</label>
                    <select class="form-select" name="tipo_consulta">
                        <option>Médico</option>
                        <option value="111">Dr Sobral</option>
                        <option value="222">Dr Socrates</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data Selecionada</label>
                    <input class="form-control" name="data_consulta" id="calendario-element" type="date" required placeholder="Data Consulta">
                </div>

                <div class="mb-3">
                    <label class="form-label">Horário Consulta</label>
                    <input class="form-control" name="horario_consulta" id="calendario-element" type="time" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo Consulta</label>
                    <select class="form-select" name="tipo_consulta">
                        <option>Tipo Consulta</option>
                        <option value="nova">Nova Consulta</option>
                        <option value="retorno">Retorno</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary" type="submit">Agendar</button>
                    <button class="btn btn-secondary" type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    function buscarParaAgendar() {
        // Get the input value
        const nome = $('#nome').val();
        console.log(nome);

        // Send the AJAX request
        $.ajax({
            url: '/search',
            method: 'GET',
            data: { nome: nome },
            success: function(response) {
                $('#resultado').empty();
                response.forEach(element => {
                    console.log(element.UsuarioNome);
                    $('#resultado').append('<a class="panel-block is-active" href="agenda_consulta?cpf=' + element.UsuarioCpf + '"><span class="panel-icon"><i class="fas fa-book" aria-hidden="true"></i></span>' + element.UsuarioNome + '</a>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Request failed. Status:', status);
            }
        });
    }
</script>
@endsection
