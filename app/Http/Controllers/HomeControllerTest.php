<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\HomeController;
use App\Models\Horario;
use Illuminate\Http\Request;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_agendamentos()
    {
        $horario = Horario::factory()->create(['data' => '2024-07-12']);

        $response = $this->get(route('home.index'));

        $response->assertViewIs('home');
        $response->assertViewHas('atendimentos', [$horario]);
    }

    public function test_index_method_returns_string_without_agendamentos()
    {
        $response = $this->get(route('home.index'));

        $response->assertSeeText('sem agenda');
    }

    public function test_cadastrarProntuario_method_creates_prontuario_and_returns_view()
    {
        $horario = Horario::factory()->create();
        $request = Request::create(route('home.cadastrarProntuario', ['id' => $horario->id]), 'POST', [
            'descricao' => 'Test description',
            'id_horario' => $horario->id,
        ]);

        $response = $this->app->handle($request);

        $this->assertDatabaseHas('prontuarios', [
            'descricao' => 'Test description',
            'id_agenda' => $horario->id,
            'id_medico' => 1234,
            'cnes' => $horario->id,
        ]);

        $response->assertViewIs('listar_atendimentos');
    }

    public function test_cadastrarProntuario_method_returns_view_with_agendas()
    {
        $horario = Horario::factory()->create();
        $request = Request::create(route('home.cadastrarProntuario', ['id' => $horario->id]), 'GET');

        $response = $this->app->handle($request);

        $response->assertViewIs('agenda_consulta');
        $response->assertViewHas('agendas', $horario);
    }

    public function test_agendaConsulta_method_returns_view_with_dados_paciente()
    {
        $cpf = '123456789';
        $request = Request::create(route('home.agendaConsulta', ['cpf' => $cpf]), 'GET');

        $response = $this->app->handle($request);

        $response->assertViewIs('agenda_consulta');
        $response->assertViewHas('dados_paciente');
    }

    public function test_agendaConsulta_method_creates_horario_and_returns_view()
    {
        $request = Request::create(route('home.agendaConsulta'), 'POST', [
            'paciente' => 'John Doe',
            'nome_medico' => 'Dr. Smith',
            'email' => 'john@example.com',
            'data_consulta' => '2024-07-12',
            'tipo_consulta' => 'Teleconsulta',
            'horario_consulta' => '10:00 AM',
            'UsuarioId' => 1,
        ]);

        $response = $this->app->handle($request);

        $this->assertDatabaseHas('horarios', [
            'paciente' => 'John Doe',
            'email' => 'john@example.com',
            'data' => '2024-07-12',
            'nome_medico' => 'Dr. Smith',
            'tipo_consulta' => 'Teleconsulta',
            'horario' => '10:00 AM',
            'UsuarioId' => 1,
        ]);

        $response->assertViewIs('agenda_consulta');
    }

    public function test_buscarPaciente_method_returns_json_response()
    {
        $nome = 'John';
        $request = Request::create(route('home.buscarPaciente', ['nome' => $nome]), 'GET');

        $response = $this->app->handle($request);

        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            '*' => [
                'UsuarioNome',
                'UsuarioCpf',
            ],
        ]);
    }
}
