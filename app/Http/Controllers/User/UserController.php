<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Config\HelperController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Underscore\Types\Object;
use Validator;

class UserController extends Controller
{
    private $moveTo = 'img/profile';

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::allExceptFirst()
            ->name($request->name)
            ->email($request->email)
            ->status($request->status)->paginate(config('helpers.qtdPerPag'));

        return view('backend.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $ret = $this->validAndUploadImg($request->file('user_avatar'));

        // Verificação se houve sucesso
        if (($ret->getData()->type <> "success") && ($ret->getData()->type <> "not found")) {
            return redirect()->back()->withErrors('Ocorreram erros no upload da imagem, verifique.');
        }

        // atribui a nova imagem a variavel e arruma a senha

        $request['photo'] = $ret->getData()->localImg;
        $request['password'] = bcrypt($request->password);

        // Salva
        $user = User::create($request->all());
        $user->assignRole('Usuário');

        // Retorna com mensagem
        session()->flash('success', 'Cadastro realizado com sucesso.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        // Ver o usuario para ser atualizado ou falhar se não existir
        $user = User::findOrFail($id);

        // Chama a função de validar e upload a imagem
        $ret = $this->validAndUploadImg($request->file('user_avatar'));

        // Verificação se houve sucesso
        if (($ret->getData()->type <> "success") && ($ret->getData()->type <> "not found")) {
            return redirect()->back()->withErrors('Ocorreram erros no upload da imagem, verifique.');
        }

        // atribui a nova imagem a variavel e arruma a senha
        if ($ret->getData()->localImg)
            $request['photo'] = $ret->getData()->localImg;

        // Se possui senha encrypta
        if (isset($request->password)) {
            $request['password'] = bcrypt($request->password);
        } else {
            $request['password'] = $user->password;
        }

        // Salva
        $user->update($request->all());

        // Retorna com mensagem
        session()->flash('success', 'Cadastro editado com sucesso.');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Verifica se usuário
        if ($id == 1) {
            abort(403);
        }

        // Pega o usuário e deleta
        $user = User::findOrFail($id);
        $user->delete();


        // Retorna e exibe mensagem
        $restore = "<a href='" . route('users.restore', $id) . "'>Desfazer</a>";
        session()->flash('success', "Usuário excluído com sucesso. $restore");
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        //when restoring a lot of entries
        if ($entries = Input::get('entries')) {
            $entries = explode(',', $entries);

            foreach ($entries as $entry) {
                User::withTrashed()->where('id', $entry)->restore();
            }

        } //when restoring 1 entry
        else {
            User::withTrashed()->where('id', $id)->restore();
        }

        session()->flash('success', 'Usuário(s) restaurado(s) com sucesso.');
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($id)
    {
        $new_password = str_random(8);

        $user = User::findOrFail($id);
        $user->password = bcrypt($new_password);
        $user->save();

        Mail::send('emails.users.password', ['new_password' => $new_password, 'user' => $user], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Alteração de Senha');
        });

        session()->flash('success', 'Senha alterada e enviada com sucesso.');
        return redirect()->back();
    }

    /**
     * @param $cod
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPermission($cod)
    {
        // Verifica se usuário
        if ($cod == 1) {
            abort(403);
        }

        // Pegar o usuário que vai ser editado as permissões
        $user = User::findOrFail($cod);

        //retornar a view
        return view('backend.user.partial.permission', compact('user'));
    }

    /**
     * @param Request $request
     * @param $cod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermission(Request $request, $cod)
    {
        // Pegar o usuário que terá as permissões atualizadas.
        $user = User::findOrFail($cod);

        // Preparar para deletar todas as permissões atuais deste usuário
        foreach ($user->permissions as $perm) {
            // Deletar todas as permissões
            $user->revokePermissionTo($perm->name);
        }

        // Adicionar as novas permissões para este usuário.
        if ($request->permissions)
            $user->givePermissionTo($request->permissions);

        // Adicionar mensagem e retornar
        session()->flash('success', 'Permissões atualizadas com sucesso.');
        return redirect()->back();

    }

    /**
     * @param UploadedFile|null $request
     * @return JsonResponse
     */
    protected function validAndUploadImg(UploadedFile $request = null): JsonResponse
    {
        // Verifica se existe
        if (!$request) {
            return response()->json(['msg' => 'Imagem não uploaded', 'type' => 'not found', 'localImg' => null]);
        }

        // Verifica se possui imagem
        if ($request->isValid()) {

            // Efetuar o upload da imagem
            $helper = new HelperController();

            // Chamar metodo
            $ret = $helper->moveImg($this->moveTo, $request);

            // Retorna o JSON
            return response()->json($ret->getData());
        }
    }

}
