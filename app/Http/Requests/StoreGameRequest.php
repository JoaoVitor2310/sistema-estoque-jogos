<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "reclamacao" => "boolean",
            "tipo_reclamacao_id" => "integer|min:1|max:4",
            "steamId" => "required",
            "tipo_formato_id" => "integer|min:1|max:7",
            "chaveRecebida" => "required", // identificar a plataforma depois
            "nomeJogo" => "required",
            "precoJogo" => ["required", "decimal:0,2"],
            "notaMetacritic" => "integer|min:0|max:100",
            "isSteam" => "boolean",
            "observacao" => ["string", "nullable"],
            "id_leilao_g2a" => "integer|min:1|max:4",
            "id_leilao_gamivo" => "integer|min:1|max:4",
            "id_leilao_kinguin" => "integer|min:1|max:4",
            "id_plataforma" => "integer|min:1|max:5",
            "precoCliente" => ["required", "decimal:0,2"],
            "chaveEntregue" => ["string", "nullable"],
            "valorPagoTotal" => "required",
            // "valorPagoIndividual" => "decimal:0,2",
            "vendido" => "boolean",
            "leiloes" => "integer|min:0",
            "quantidade" => "required",
            "devolucoes" => "boolean",
            "dataAdquirida" => ["required", "date"],
            "perfilOrigem" => ["required", "string"],
            "email" => "email",
            "qtdTF2" => "nullable", // A partir daqui é para valorPagoIndividual
            "somatorioIncomes" => "nullable",
            "primeiroIncome" => "nullable",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'statusCode' => 422,
            'message' => 'Dados inválidos',
            'errors' => $validator->errors(),
            'data' => []
        ], 422));
    }
}