<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        return Produto::all();
    }

    public function show($id)
    {
        return Produto::findOrFail($id);
    }

    public function store(Request $request)
{
    $data = $request->all();

    // Se receber vários produtos
    if (isset($data[0])) {
        $produtos = [];
        foreach ($data as $item) {
            $validated = validator($item, [
                'nome' => 'required|string',
                'preco' => 'required|numeric',
                'estoque' => 'required|integer',
            ])->validate();

            $produtos[] = Produto::create($validated);
        }
        return response()->json($produtos, 201);
    }

    // Se receber só um produto
    $validated = $request->validate([
        'nome' => 'required|string',
        'preco' => 'required|numeric',
        'estoque' => 'required|integer',
    ]);

    return Produto::create($validated);
}

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $data = $request->validate([
            'nome' => 'sometimes|string',
            'preco' => 'sometimes|numeric',
            'estoque' => 'sometimes|integer',
        ]);

        $produto->update($data);

        return $produto;
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return response()->json(['message' => 'Produto deletado com sucesso']);
    }
}
