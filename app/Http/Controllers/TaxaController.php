<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFeeRequest;
use App\Traits\HttpResponses;
use App\Models\Taxas;

use Illuminate\Http\Request;
use Inertia\Inertia;
class TaxaController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function showMarketPlaceFees(Request $request)    {
         $limit = $request->query('limit', 100);  // Valor padrão de 100 para buscar tudo
         $offset = $request->query('offset', 0);  // Valor padrão de 0 para procurar desde o primeiro
 
         // Busca os registros utilizando limit e offset
         $taxas = Taxas::orderBy('id', 'asc')->limit($limit)->offset($offset)->get();
 
         is_object($taxas) ? $taxas = $taxas->toArray() : $taxas; // Garante que sempre será um array, mesmo que tenha só um elemento
 
        //  return $this->response(200, 'Taxas encontrados com sucesso.', $taxas);

        return Inertia::render('Taxas', [
            'taxas' => $taxas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeMarketPlaceFee(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "reclamacao" => "boolean",
            "tipo_reclamacao_id" => "integer|min:1|max:4",
            "steamId" => "required",
            "tipo_formato_id" => "integer|min:1|max:7",
            "chaveRecebida" => "required", // identificar a plataforma depois
            "nomeJogo" => "required",
            "precoJogo" => ["required", "decimal:0,2"],
            "notaMetacritic" => "integer|min:0|max:100",
        ]);

        if ($validator->fails()) {
            return $this->error(422, 'Dados inválidos', $validator->errors());
        }

        $data = $validator->getData();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeRequest $request, string $id)
    {
        $fee = Taxas::select('*')->where('id', $id)->first();
        if (!$fee)
            return $this->error(404, 'Taxa não encontrada');

        $data = $request->validated();

        $result = Taxas::where('id', $id)->update($data);

        if (!$result)
            return $this->error(500, 'Erro interno ao atualizar taxa');

        $fee['preco'] = $data['preco'];

        return $this->response(200, 'Taxa atualizada com sucesso', $fee);

        // return to_route('fees'); // Inertia
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
