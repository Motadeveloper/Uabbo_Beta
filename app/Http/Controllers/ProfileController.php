<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Exibe o perfil do usuário.
     */
    public function show($name)
    {
        // Busca o usuário pelo campo 'name'
        $user = User::where('name', $name)->firstOrFail();

        // Retorna a view com os dados do usuário
        return view('perfil.index', ['user' => $user]);
    }

    /**
     * Altera a capa do perfil do usuário.
     */
    public function alterarCapa(Request $request, $id)
    {
        // Busca o usuário pelo ID
        $user = User::findOrFail($id);

        // Verifica se o usuário logado é o autor do perfil
        if (Auth::id() !== $user->id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Validação do arquivo
        $request->validate([
            'capa' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Caminho fixo para salvar a capa
        $caminhoCapa = "capas/{$user->id}/capa.jpg";

        // Salva ou substitui a capa do usuário
        if ($request->hasFile('capa')) {
            // Salva o arquivo no disco 'public'
            Storage::disk('public')->put($caminhoCapa, file_get_contents($request->file('capa')->getRealPath()));
        }

        // Atualiza o campo 'capa' no banco com o caminho público
        $user->capa = "/storage/" . $caminhoCapa;
        $user->save();

        // Retorna para o perfil com mensagem de sucesso
        return redirect()->back()->with('success', 'Capa alterada com sucesso!');
    }
}
