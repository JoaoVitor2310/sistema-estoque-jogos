<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\Recursos;
use Inertia\Inertia;

class ResourceController extends Controller
{
    use HttpResponses;

    public function show(Request $request)
    {
        $resources = Recursos::orderBy('id', 'asc')->get();

        is_object($resources) ? $resources = $resources->toArray() : $resources; // Garante que sempre será um array, mesmo que tenha só um elemento

        //  return $this->response(200, 'resources encontrados com sucesso.', $resources);

        return Inertia::render('Resources', [
            'resources' => $resources,
        ]);
    }

    public function store(StoreResourceRequest $request)
    {
        $data = $request->validated();

        try {
            $created = Recursos::create($data);
            if ($created) {
                return $this->response(201, 'Recurso cadastrado com sucesso', $created);
            }

            return $this->error(400, 'Something went wrong!');
        } catch (\Exception $e) {
            \Log::error($e);

            // Return a JSON response with the error message
            return $this->error(500, 'Erro interno ao cadastrar recurso novo.', [$e->getMessage()]);
        }
    }
}