<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Onde redirecionar os usuários após o login.
     *
     * @var string
     */
    protected $redirectTo = '/feed';

    /**
     * Cria uma nova instância do controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Use o campo "name" para autenticação.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }

    /**
     * Tentativa de login utilizando `Hash::check`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        Log::info('Tentativa de login.', [
            'name' => $request->input('name'),
            'ip' => $request->ip(),
        ]);

        $credentials = $this->credentials($request);

        // Recuperar o usuário com base nas credenciais fornecidas
        $user = $this->guard()->getProvider()->retrieveByCredentials($credentials);

        if ($user) {
            Log::debug('Usuário encontrado.', [
                'user_id' => $user->id,
                'name' => $user->name,
            ]);

            // Verificar a senha usando `Hash::check`
            if (Hash::check($request->input('password'), $user->password)) {
                Log::info('Senha verificada com sucesso.', [
                    'user_id' => $user->id,
                ]);

                // Autenticar o usuário
                return $this->guard()->login($user, $request->filled('remember'));
            }

            Log::warning('Falha de autenticação: Senha não corresponde.', [
                'user_id' => $user->id,
            ]);
        } else {
            Log::warning('Usuário não encontrado para as credenciais fornecidas.', [
                'name' => $credentials['name'],
            ]);
        }

        return false;
    }

    /**
     * Resposta em caso de falha no login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        Log::warning('Falha no login.', [
            'name' => $request->input('name'),
            'ip' => $request->ip(),
        ]);

        return redirect()->back()
            ->withInput($request->only('name', 'remember'))
            ->withErrors([
                'login' => 'Usuário ou senha incorretos.',
            ]);
    }

    /**
     * Redirecionar após login com mensagem de sucesso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info('Usuário autenticado com sucesso.', [
            'user_id' => $user->id,
            'name' => $user->name,
            'ip' => $request->ip(),
        ]);

        $request->session()->flash('success', 'Login realizado com sucesso!');
        return redirect()->intended($this->redirectPath());
    }
}
