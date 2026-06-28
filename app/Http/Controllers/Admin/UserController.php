<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\Logger;
use App\Models\Log;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }


    public function index()
    {
        $query = User::orderBy('id', 'Desc');

        if (request()->has('nBi') && !empty(request('nBi'))) {
            $nBi = request('nBi');
            $emails = [];

            $emails = \App\Models\Teacher::where('nBi', 'like', "%{$nBi}%")->pluck('email')->toArray();
            $emails = array_merge($emails, \App\Models\Student::where('nBi', 'like', "%{$nBi}%")->pluck('email')->toArray());

            $query->whereIn('email', array_unique($emails));
        }

        $response['users'] = $query->get();
        $this->Logger->log('info', 'Lista de Usuários');
        return view('admin.user.list.index', $response);
    }

    public function create()
    {
        $response['roles'] = Role::where('name', '!=', 'Área de Gestão')->get();
        $this->Logger->log('info', 'Criar Usuário');
        return view('admin.user.create.index', $response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'O campo nome deve ser preenchido.',
            'email.required' => 'O campo email deve ser preenchido.',
            'email.email' => 'O campo email deve ser um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'roles.required' => 'Selecione um nível de acesso.',
            'image.image' => 'O arquivo selecionado deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif.',
            'image.max' => 'A imagem não pode ter mais de 2MB.',
        ]);
        if ($request->password != $request->password_confirmation) {
            return redirect()->back()->with('');
        } else {
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = $request->file('image')->store('users', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imagePath,
            ]);

            $user->roles()->attach($request->roles);
            $this->Logger->log('info', 'Cadastrou Usuário');

            return redirect()->back()->with('create', '1');
        }

    }

    public function show($id)
    {
        $response['user'] = User::find($id);
        $this->Logger->log('info', 'Detalhes do Usuário');
        return view('admin.user.details.index', $response);
    }

    public function edit($id)
    {
        $response['user'] = User::find($id);
        $response['roles'] = Role::where('name', '!=', 'Área de Gestão')->get();
        $this->Logger->log('info', 'Editar Usuário');
        return view('admin.user.edit.index', $response);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'O campo nome deve ser preenchido.',
            'email.required' => 'O campo email deve ser preenchido.',
            'email.email' => 'O campo email deve ser um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'roles.required' => 'Selecione um nível de acesso.',
            'image.image' => 'O arquivo selecionado deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif.',
            'image.max' => 'A imagem não pode ter mais de 2MB.',
        ]);
        if ($request->password != $request->password_confirmation) {
            return redirect()->back()->with('');
        } else {
            
            $user = User::find($id);
            $dataToUpdate = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $dataToUpdate['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $dataToUpdate['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($dataToUpdate);
            
            $user->roles()->sync($request->roles);
        $this->Logger->log('info', 'Atualizou o Usuário');

            return redirect()->route('admin.user.show',$id)->with('edit', '1');
        }

    }



    public function destroy($id)
    {
        User::find($id)->delete();
        $this->Logger->log('info', 'Eliminou o Usuário');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'O User foi excluído.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }


}
