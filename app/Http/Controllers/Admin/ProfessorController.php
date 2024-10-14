<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Classes\Logger;
use App\Models\Log;
use App\Models\Professor;
use App\Models\Schoolyear;
use Carbon\Carbon;


class ProfessorController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }
    public function index()
    {

        $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
        $response['Professores'] = Professor::OrderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Lista de Alunos');
        return view('admin.professor.list.index', $response)->with('success', '1');

    }


    public function create()
    {

        $response['total'] = Professor::withTrashed()->count();
        $response['schoolyear'] = Schoolyear::OrderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Criar Aluno');
        return view('admin.professor.create.index', $response);

    }



    public function store(Request $request)
    {

        $data = $this->validate(
            $request,
            [
                'name' => 'required',
                'nProcess' => 'required',
                'nBi' => 'required|unique:professores,nBi,' . $request->id . ',id',
                'contact' => 'required',
                'contactAlter' => 'required',
                'email' => 'required|email',
                'dateBirth' => 'required',
                'schoolyear' => 'required',

                'father' => 'required',
                'mother' => 'required',
            ],


            [
                'name.required' => 'O campo Nome deve ser preenchido',
                'nProcess.required' => 'O campo Nº de Processo deve ser preenchido',
                'nBi.required' => 'O campo BI deve ser preenchido',
                'nBi.unique' => 'Este BI já está cadastrado',
                'contact.required' => 'O campo Contacto deve ser preenchido',
                'contactAlter.required' => 'O campo Contacto Alternativo deve ser preenchido',
                'email.required' => 'O campo E-mail deve ser preenchido',
                'email.email' => 'O E-mail é invalido',
                'dateBirth.required' => 'O campo Data de Nascimento deve ser preenchido',
                'schoolyear.required' => 'O campo do Ano Lectivo deve ser selecionado',
                'father.required' => 'O campo Nome do Pai  deve ser preenchido',
                'mother.required' => 'O campo Nome da Mâe deve ser selecionado',


            ]

        );

        $Exists = Professor::where('name', $data['name'])->exists();

        if ($Exists) {
            return redirect()->back()->with('Professores_exist', '1');
        }

        try {
            Professor::create($data);
        } catch (Exception $ex) {
            return $ex;
        }

        $this->Logger->log('info', 'Cadastrou Aluno');
        return redirect()->back()->with('create', '1');
    }




    public function show($id)
    {

        $response['professor'] = Professor::find($id);

        $this->Logger->log('info', 'Detalhes do Aluno');
        return view('admin.professor.details.index', $response);
    }



    public function edit($id)
    {
        $response['professor'] = Professor::find($id);
        $response['schoolyear'] = Schoolyear::OrderBy('id', 'Desc')->get();
        $response['total'] = Professor::withTrashed()->count();
        $this->Logger->log('info', 'Editar Aluno');
        return view('admin.professor.edit.index', $response);
    }


    public function update(Request $request, $id)
    {


        $data = $request->validate([
            'name' => 'required',
            'nProcess' => 'required',
            'nBi' => 'required',
            'contact' => 'required',
            'contactAlter' => 'required',
            'email' => 'required|email',
            'dateBirth' => 'required',
            'schoolyear' => 'required',
            'father' => 'required',
            'mother' => 'required',

        ],


        [

            'name.required' => 'O campo Nome deve ser preenchido',
            'nProcess.required' => 'O campo Nº de Processo deve ser preenchido',
            'nBi.required' => 'O campo BI deve ser preenchido',
            'nBi.unique' => 'Este BI já está cadastrado',
            'contact.required' => 'O campo Contacto deve ser preenchido',
            'contactAlter.required' => 'O campo Contacto Alternativo deve ser preenchido',
            'email.required' => 'O campo E-mail deve ser preenchido',
            'email.email' => 'O E-mail é invalido',
            'dateBirth.required' => 'O campo Data de Nascimento deve ser preenchido',
            'schoolyear.required' => 'O campo do Ano Lectivo deve ser selecionado',
            'father.required' => 'O campo Nome do Pai  deve ser preenchido',
            'mother.required' => 'O campo Nome da Mâe deve ser selecionado',

        ]);

        Professor::find($id)->update($data);
        $this->Logger->log('info', 'Atualizou o Aluno');
        return redirect()->route('admin.professor.show', $id)->with('edit', '1');
        
    }


    public function destroy($id)
    {

        $professor = Professor::find($id);

        // Verifica se o aluno está associado a outro registro
        if ($professor->schoolyears->count() > 0) {
            return redirect()->back()->with('professores_destroy_error', '1');
        }
        $professor->delete();
        $this->Logger->log('info', 'Eliminou o Aluno');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'O aluno foi excluído.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }
}
