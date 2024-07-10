<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Prontuario;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return DB::connection('sqlsrv')->select('SELECT top 3 UsuarioNome,UsuarioCpf,UsuarioId FROM Usuario');

         // Obter a data atual
         //$data_atual = Carbon::today();

         // Consultar os agendamentos para a data específica (por exemplo, '2024-06-28')
         $agendamentos = Horario::where('data', '2024-07-12')->get();
         //return Horario::all();

         // Verificar se há agendamentos para essa data
         if ($agendamentos->count() > 0) {
             // Se houver agendamentos, preparar os dados para a view
             $dados = $agendamentos; // Não é necessário iterar se você já tem uma coleção

             // Renderizar a view 'listar_atendimentos' com os dados dos agendamentos
             return view('home', ['atendimentos' => $dados]);
         } else {
             // Se não houver agendamentos, passar um array vazio para a view
             return "sem agenda";
         }

       //return view('home');
    }

    public function cadastrarProntuario(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $agenda = Horario::find($id);

            $prontuarioNow = new Prontuario();
            $prontuarioNow->descricao = $request->input('descricao');
            $prontuarioNow->id_agenda = $request->input('id_horario');
            $prontuarioNow->id_medico = 1234; // crm
            $prontuarioNow->cnes = $id;
            $prontuarioNow->save();

            return view('listar_atendimentos');
        }

        if ($request->isMethod('get')) {
            $agendas = Horario::find($id);
            return view('agenda_consulta', ['agendas' => $agendas]);
        }
    }



    public function agendaConsulta(Request $request)
    {
        if ($request->isMethod('get')) {
            $cpf = $request->query('cpf');
            $dados_paciente = $this->getUserByCpf($cpf);
            if ($dados_paciente != null) {
                $userData = $dados_paciente->getData();
                return view('agenda_consulta', ['dados_paciente'=> $userData]);
            } else {
                $dados_paciente = [];
                // return view('agenda_consulta', ['dados_paciente'=> $dados_paciente]);
            }

        }

        if ($request->isMethod('post')) {
            //dd($request->all());
            $hash = bin2hex(random_bytes(16));
            $paciente = $request->input('paciente');
            $nome_medico = $request->input('nome_medico');
            $email = $request->input('email');
            $url_consulta = "https://meet.jit.si/" . $hash;
            $finalizada = true;
            $teleconsulta = true;
            $data = $request->input('data_consulta');
            $tipo_consulta = $request->input('tipo_consulta');
            $horario = $request->input('horario_consulta');

            $horario = Horario::create([
                'paciente' => $paciente,
                'email' => $email,
                'url_consulta' => $url_consulta,
                'finalizada' => $finalizada,
                'teleconsulta' => $teleconsulta,
                'id_agenda' => 1111,
                'retorno' => false,
                'data' => $data,
                'nome_medico' => $nome_medico,
                'tipo_consulta' => $tipo_consulta,
                'horario' => $horario,
                'UsuarioId' => $request->input('UsuarioId'),
                'sms' => '',
            ]);


            //$this->enviarEmail($paciente, $nome_medico, $data, $horario->horario, $horario->email, $horario->url_consulta);

            return view('agenda_consulta');
        }
    }

    private function carregarDadosPaciente($cpf)
    {
        // Implementar lógica para carregar dados do paciente
    }

    private function enviarEmail($paciente, $nome_medico, $data, $horario, $email, $url_consulta)
    {
        // Implementar lógica para enviar e-mail
        Mail::to($email)->send(new ConsultaMail($paciente, $nome_medico, $data, $horario, $url_consulta));
    }

    public function getUserByCpf($cpf)
    {
        //$usuarios = Usuario::where('UsuarioCpf', $cpf)->get(['UsuarioNome', 'UsuarioCpf', 'UsuarioId']);
        $usuarios = DB::connection('sqlsrv')->select("SELECT  UsuarioNome,UsuarioCpf,UsuarioId FROM Usuario WHERE UsuarioCpf= ?", [$cpf]);

        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'UsuarioNome' => $usuario->UsuarioNome,
                'UsuarioCpf' => $usuario->UsuarioCpf,
                'UsuarioId' => $usuario->UsuarioId
            ];
        }

        return response()->json($data);
    }

    public function agenda()
    {
        return view('agenda_consulta');
    }

    public function buscarPaciente(Request $request)
    {
        $nome = $request->query('nome');

        // Execute a consulta SQL com parâmetros
        $rows = DB::connection('sqlsrv')->select("SELECT TOP (5) * FROM Usuario WHERE UsuarioNome LIKE ? ORDER BY UsuarioId DESC",["$nome%"]);
        return $rows;

        // Processar os resultados
        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                "UsuarioNome" => $row->UsuarioNome,
                "UsuarioCpf" => $row->UsuarioCpf
            ];
        }

        // Retornar data como JSON
        return response()->json($data);
    }

    public function atendimento(Request $request, $id)
    {
        // Autenticação simplificada, ajuste conforme necessário
        if (auth()->check()) {
            // Mensagem de autenticação, pode ser usada para debugging
            \Log::info('Autenticado');

            // Obtendo os prontuários e atendimentos
            //$prontuarios = Prontuario::where('id_agenda', $id)->get();
            $atendimento = Horario::where('id', $id)->first();
            $prontuario = Prontuario::where('id_paciente', $atendimento->UsuarioId)->get();
            //dd($prontuario);
            return view('atendimento', ['atendimento' => $atendimento,'prontuarios' => $prontuario]);
        } else {
            // Redireciona para a página de login ou lança um erro de autenticação
            return redirect()->route('login'); // ou abort(403);
        }
    }

    public function getPronturario($usuarioId){
        $prontuario = Prontuario::where('id_paciente', $usuarioId)->get();
        return $prontuario;
    }

}
