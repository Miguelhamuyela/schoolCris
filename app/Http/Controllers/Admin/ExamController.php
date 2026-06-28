<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new \App\Classes\Logger();
    }

    public function index()
    {
        $response['exams'] = \App\Models\Exam::OrderBy('id','Desc')->get();
        $this->Logger->log('info', 'Lista de Exames');
        return view('admin.exam.list.index', $response);
    }

    public function create()
    {
        $this->Logger->log('info', 'Criar Exame');
        $response['teachers'] = \App\Models\Teacher::all();
        $response['classes'] = \App\Models\Classe::all();
        $response['courses'] = \App\Models\Course::all();
        $response['grades'] = \App\Models\Grade::all();
        return view('admin.exam.create.index', $response);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'season' => 'required',
            'duration' => 'required',
            'start' => 'required',
            'end' => 'required',
            'fk_teachers_id' => 'required',
            'fk_classes_id' => 'required',
            'fk_courses_id' => 'required',
            'fk_grades_id' => 'required',
        ]);

        \App\Models\Exam::create($data);
        $this->Logger->log('info', 'Cadastrou Exame');
        return redirect()->back()->with('create', '1');
    }

    public function show($id)
    {
        $response['exam'] = \App\Models\Exam::find($id);
        $this->Logger->log('info', 'Detalhes do Exame');
        return view('admin.exam.details.index', $response);
    }

    public function edit($id)
    {
        $response['exam'] = \App\Models\Exam::find($id);
        $response['teachers'] = \App\Models\Teacher::all();
        $response['classes'] = \App\Models\Classe::all();
        $response['courses'] = \App\Models\Course::all();
        $response['grades'] = \App\Models\Grade::all();
        $this->Logger->log('info', 'Editar Exame');
        return view('admin.exam.edit.index', $response);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'season' => 'required',
            'duration' => 'required',
            'start' => 'required',
            'end' => 'required',
            'fk_teachers_id' => 'required',
            'fk_classes_id' => 'required',
            'fk_courses_id' => 'required',
            'fk_grades_id' => 'required',
        ]);

        \App\Models\Exam::find($id)->update($data);
        $this->Logger->log('info', 'Atualizou Exame');
        return redirect()->route('admin.exam.show', $id)->with('edit', '1');
    }

    public function destroy($id)
    {
        \App\Models\Exam::find($id)->delete();
        $this->Logger->log('info', 'Eliminou Exame');
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'O Exame foi excluído.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }
}
