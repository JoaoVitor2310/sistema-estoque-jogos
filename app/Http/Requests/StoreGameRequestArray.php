<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreGameRequestArray extends FormRequest
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
            "games" => "required|array", // Garante que 'games' é um array
            "games.*.color" => "nullable", // Cor da linha na tabela
            "games.*.tipo_reclamacao_id" => "integer|min:1|max:4",
            "games.*.steamId" => ["string", "nullable"],
            "games.*.tipo_formato_id" => "integer|min:1|max:7",
            "games.*.chaveRecebida" => "required", // identificar a plataforma depois
            "games.*.nomeJogo" => "required",
            "games.*.precoJogo" => ["required", "decimal:0,2"],
            "games.*.notaMetacritic" => "integer|min:0|max:100",
            "games.*.isSteam" => "boolean",
            "games.*.observacao" => ["string", "nullable"],
            "games.*.id_leilao_g2a" => "integer|min:1|max:4",
            "games.*.id_leilao_gamivo" => "integer|min:1|max:4",
            "games.*.id_leilao_kinguin" => "integer|min:1|max:4",
            "games.*.id_plataforma" => "integer|min:1|max:5",
            "games.*.precoCliente" => ["required", "decimal:0,2"],
            "games.*.chaveEntregue" => ["string", "nullable"],
            "games.*.valorPagoTotal" => ["string", "nullable"],
            "games.*.vendido" => "boolean",
            "games.*.leiloes" => "integer|min:0",
            "games.*.quantidade" => "required",
            "games.*.devolucoes" => "boolean",
            "games.*.dataAdquirida" => ["required", "date"],
            "games.*.perfilOrigem" => ["required", "string"],
            "games.*.email" => ["nullable", "email"],
            "games.*.qtdTF2" => ["required", "decimal:0,2"], // A partir daqui é para valorPagoIndividual
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