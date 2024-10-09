<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Plataforma;
use App\Models\Tipo_formato;
use App\Models\Tipo_leilao;
use App\Models\Tipo_reclamacao;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\Venda_chave_troca;
use App\Models\Fornecedor;
use App\Http\Helpers\Formulas;
use Inertia\Inertia;

class VendaChaveTrocaController extends Controller
{
    use HttpResponses;

    protected $formulas;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->formulas = new Formulas();
    }



    public function show(Request $request) // Renderiza a tela inicial
    {
        $limit = $request->query('limit', 100);  // Valor padrão de 100

        $games = Venda_chave_troca::with([
            'fornecedor',
            'tipoReclamacao',
            'tipoFormato',
            'leilaoG2A',
            'leilaoGamivo',
            'leilaoKinguin',
            'plataforma'
            // ])->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        ])->orderBy('id', 'desc')->paginate($limit);

        // $totalGames = Venda_chave_troca::count();
        $totalGames = $games->total();  // O paginate já retorna o total de registros
        $tiposFormato = Tipo_formato::all();
        $tiposLeilao = Tipo_leilao::all();
        $plataformas = Plataforma::all();
        $tiposReclamacao = Tipo_reclamacao::all();


        // is_object($games) ? $games = $games->toArray() : $games; // Garante que sempre será um array, mesmo que tenha só um elemento


        // Se for a primeira requisição (renderizar a página com Inertia.js)
        return Inertia::render('VendaChaveTroca', [
            // 'games' => $games,
            'games' => $games->items(), // Retorna apenas os itens da página atual
            'totalGames' => $totalGames,
            'tiposFormato' => $tiposFormato,
            'tiposLeilao' => $tiposLeilao,
            'plataformas' => $plataformas,
            'tiposReclamacao' => $tiposReclamacao,
            'pagination' => [
                'current_page' => $games->currentPage(),
                'last_page' => $games->lastPage(),
                'per_page' => $games->perPage(),
            ],
        ]);

        // return $this->response(200, 'Jogos encontrados com sucesso.', $jogos);
    }

    public function paginated(Request $request)// Não renderiza a tela inicial
    {
        $limit = $request->query('limit', 100);  // Valor padrão de 100
        // $offset = $request->query('offset', 0);  // Valor padrão de 0

        $games = Venda_chave_troca::with([
            'fornecedor',
            'tipoReclamacao',
            'tipoFormato',
            'leilaoG2A',
            'leilaoGamivo',
            'leilaoKinguin',
            'plataforma'
            // ])->orderBy('id', 'desc')->limit($limit)->offset($offset)->get();
        ])->orderBy('id', 'desc')->paginate($limit);

        // $totalGames = Venda_chave_troca::count();
        $totalGames = $games->total();  // O paginate já retorna o total de registros
        $tiposFormato = Tipo_formato::all();
        $tiposLeilao = Tipo_leilao::all();
        $plataformas = Plataforma::all();
        $tiposReclamacao = Tipo_reclamacao::all();


        // is_object($games) ? $games = $games->toArray() : $games; // Garante que sempre será um array, mesmo que tenha só um elemento


        return $this->response(200, 'Página de jogos atualizada com sucesso.', [
            'games' => $games,
            // 'games' => $games->items(), // Retorna apenas os itens da página atual
            'totalGames' => $totalGames,
            'tiposFormato' => $tiposFormato,
            'tiposLeilao' => $tiposLeilao,
            'plataformas' => $plataformas,
            'tiposReclamacao' => $tiposReclamacao,
            'pagination' => [
                'current_page' => $games->currentPage(),
                'last_page' => $games->lastPage(),
                'per_page' => $games->perPage(),
            ],
        ]);


        // return $this->response(200, 'Jogos encontrados com sucesso.', $jogos);
    }

    public function search(Request $request)
    {
        $filters = $request->except('page'); // Filtra todos os campos, exceto 'page'

        // Iniciando a consulta
        $query = Venda_chave_troca::with([
            'fornecedor',
            'tipoReclamacao',
            'tipoFormato',
            'leilaoG2A',
            'leilaoGamivo',
            'leilaoKinguin',
            'plataforma'
        ]);
    
        // Aplicando filtros se estiverem presentes no corpo da requisição
        foreach ($filters as $key => $value) {
            if ($value !== null) { // Verifica se o valor não é nulo
                $query->where($key, $value);
            }
        }
    
        // Paginação
        $limit = $filters['limit'] ?? 100;
        $games = $query->orderBy('id', 'desc')->paginate($limit);
    
        return $this->response(200, 'Resultados da pesquisa.', [
            'games' => $games,
            'totalGames' => $games->total(),
            'pagination' => [
                'current_page' => $games->currentPage(),
                'last_page' => $games->lastPage(),
                'per_page' => $games->perPage(),
            ],
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        $data = $request->validated();

        $data['id_fornecedor'] = $this->criarAdicionarFornecedor($data['perfilOrigem'], $data['tipo_reclamacao_id']);

        // Calcula as fórmulas
        $data = $this->calculateFormulas($data);

        try {
            $created = Venda_chave_troca::create($data);
            if ($created) {
                $fullGame = Venda_chave_troca::select('*')->where('id', $created->id)->with([
                    'fornecedor',
                    'tipoReclamacao',
                    'tipoFormato',
                    'leilaoG2A',
                    'leilaoGamivo',
                    'leilaoKinguin',
                    'plataforma'
                ])->first();
                return $this->response(201, 'Jogo cadastrado com sucesso', $fullGame);
            }

            return $this->error(400, 'Something went wrong!');
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);

            // Return a JSON response with the error message
            return $this->error(500, 'Erro interno ao cadastrar novo jogo', [$e->getMessage()]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGameRequest $request, string $id)
    {
        $game = Venda_chave_troca::select('*')->where('id', $id)->first();
        if (!$game)
            return $this->error(404, 'Jogo não encontrado');

        $data = $request->validated();

        // Lógica para fornecedores

        $fornecedorCadastrado = Fornecedor::select('*')->where('perfilOrigem', $game['perfilOrigem'])->first();

        $fornecedorEnviado = Fornecedor::select('*')->where('perfilOrigem', $data['perfilOrigem'])->first();
        if (!$fornecedorEnviado) { // Se não existe o fornecedor enviado, cria
            $data['id_fornecedor'] = $this->criarAdicionarFornecedor($data['perfilOrigem'], $data['tipo_reclamacao_id']);
            // Diminui uma reclamação do fornecedor cadastrado

            $fornecedorCadastrado->where('perfilOrigem', $game['perfilOrigem'])->update(['quantidade_reclamacoes' => $fornecedorCadastrado->quantidade_reclamacoes - 1]);
        } else {

            if ($fornecedorEnviado['id'] != $fornecedorCadastrado['id']) { // Comparar pra ver se é o mesmo fornecedor
                // Diminuir uma reclamação do fornedor cadastrado e adicionar para o enviado
                if ($fornecedorCadastrado->quantidade_reclamacoes > 0)
                    $fornecedorCadastrado->where('id', $fornecedorCadastrado['id'])->update(['quantidade_reclamacoes' => $fornecedorCadastrado->quantidade_reclamacoes - 1]);
                $fornecedorEnviado->where('id', $fornecedorEnviado['id'])->update(['quantidade_reclamacoes' => $fornecedorEnviado->quantidade_reclamacoes + 1]);
            } else { // Se for o mesmo, verifica se mudou de true para false e retira um
                if ($game['tipo_reclamacao_id'] == 1 && $data['tipo_reclamacao_id'] != 1) { // NÃO tinha reclamação e agora tem
                    $fornecedorEnviado->where('id', $fornecedorEnviado['id'])->update(['quantidade_reclamacoes' => $fornecedorEnviado->quantidade_reclamacoes + 1]);
                } else { // Tinha reclamação e agora não tem
                    if ($fornecedorEnviado->quantidade_reclamacoes > 0)
                        $fornecedorEnviado->where('id', $fornecedorEnviado['id'])->update(['quantidade_reclamacoes' => $fornecedorEnviado->quantidade_reclamacoes - 1]);
                }
                // return $this->response(200, 'caiu no else', [$game]);
            }

            $data['id_fornecedor'] = $fornecedorEnviado['id'];
        }

        // Calcula as fórmulas
        $data = $this->calculateFormulas($data);

        unset($data['qtdTF2']);
        unset($data['somatorioIncomes']);
        unset($data['primeiroIncome']);
        $result = Venda_chave_troca::where('id', $id)->update($data);

        if (!$result)
            return $this->error(500, 'Erro interno ao atualizar jogo');

        $game = Venda_chave_troca::select('*')->where('id', $id)->with([
            'fornecedor',
            'tipoReclamacao',
            'tipoFormato',
            'leilaoG2A',
            'leilaoGamivo',
            'leilaoKinguin',
            'plataforma'
        ])->first();

        return $this->response(200, 'Jogo atualizado com sucesso', $game);

        // return $this->response(200, 'Jogo atualizado com sucesso', $game);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Venda_chave_troca::select('*')->where('id', $id)->first();
        if (!$game)
            return $this->error(404, 'Jogo não encontrado');


        $result = Venda_chave_troca::where('id', $id)->delete();
        if (!$result)
            return $this->error(500, 'Erro interno ao deletar jogo');

        return $this->response(200, 'Jogo deletado com sucesso', $game);
    }

    public function destroyArray(Request $request)
    {
        $games = $request->input('games');
        if (!$games)
            return $this->error(404, 'Jogos não enviados', ['games' => 'Jogos não enviados']);
        // return $this->response(200, 'a', $jogos);
        foreach ($games as $game) {

            $item = Venda_chave_troca::select('*')->where('id', $game['id'])->first();
            if (!$item)
                return $this->error(404, 'Jogo não encontrado');

            $result = Venda_chave_troca::where('id', $game['id'])->delete();
            if (!$result)
                return $this->error(500, 'Erro interno ao deletar jogo');
        }

        return $this->response(200, 'Jogos deletados com sucesso', $games);
    }

    // Funções auxiliares

    private function criarAdicionarFornecedor($perfilOrigem, $reclamacao)
    {
        $fornecedor = Fornecedor::select('*')->where('perfilOrigem', $perfilOrigem)->first();

        if (!$fornecedor) { // Se não tiver o fornecedor, cria ele
            $newFornecedor['perfilOrigem'] = $perfilOrigem;
            if ($reclamacao != 1)
                $newFornecedor['quantidade_reclamacoes'] = 1; // Se tiver reclamação, adiciona +1
            $fornecedor = Fornecedor::create($newFornecedor);
            // return $this->error(400, $fornecedor);
        } else {
            // Existe o fornecedor, irá somar mais uma reclamação só se tiver mandado reclamação
            if ($reclamacao != 1) {
                $fornecedor->where('perfilOrigem', $perfilOrigem)->update(['quantidade_reclamacoes' => $fornecedor->quantidade_reclamacoes + 1]);
                // $fornecedor['quantidade_reclamacoes'] = $fornecedor->quantidade_reclamacoes;
            }
        }

        return $fornecedor->id;
    }

    private function calculateFormulas($data)
    {
        $data['precoVenda'] = $this->formulas->calcPrecoVenda($data['tipo_formato_id'], $data['id_plataforma'], $data['precoCliente']);

        $data['incomeReal'] = $this->formulas->calcIncomeReal($data['tipo_formato_id'], $data['id_plataforma'], $data['precoCliente'], $data['precoVenda'], $data['leiloes'], $data['quantidade']);

        $data['incomeSimulado'] = $this->formulas->calcIncomeSimulado($data['tipo_formato_id'], $data['id_plataforma'], $data['precoCliente'], $data['precoVenda']);

        $data['valorPagoIndividual'] = $this->formulas->calcValorPagoIndividual($data['qtdTF2'], $data['somatorioIncomes'], $data['primeiroIncome']); // CONFERIR

        $data['lucroRS'] = $this->formulas->calcLucroReal($data['incomeSimulado'], $data['valorPagoIndividual']);

        $data['lucroPercentual'] = $this->formulas->calcLucroPercentual($data['lucroRS'], $data['valorPagoIndividual']);

        $data['randomClassificationG2A'] = $this->formulas->classificacaoRandomG2A($data['precoJogo'], $data['notaMetacritic']);

        $data['randomClassificationKinguin'] = $this->formulas->classificacaoRandomKinguin($data['precoJogo'], $data['notaMetacritic']);

        return $data;
    }
}
