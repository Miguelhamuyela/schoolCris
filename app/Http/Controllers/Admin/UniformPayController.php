<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Logger;
use App\Helpers\SchoolYearHelper;
use App\Http\Controllers\Controller;
use App\Models\CourseClasseGradeStudentSchoolyear;
use App\Models\Schoolyear;
use App\Models\Student;
use App\Models\UniformPay;
use Illuminate\Http\Request;

class UniformPayController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }
    public function index()
    {
        $response['uniformPays'] = UniformPay::OrderBy('id', 'Desc')->get();
        $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();

        $this->Logger->log('info', 'Lista de Pagamentos de Uniformes');
        return view('admin.uniformPay.list.index', $response)->with('success', '1');
    }


    public function create()
    {
        $response['students'] = Student::orderBy('id', 'desc')->get();
        $response['registrations'] = CourseClasseGradeStudentSchoolyear::orderBy('id', 'desc')->get();

        $this->Logger->log('info', 'Criar Pagamento de Uniforme');
        return view('admin.uniformPay.create.index', $response);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'price' => 'required|integer',
            'state' => 'required',
            'fk_students_id' => 'required'
        ], [
            'price.required' => 'O campo do preço é obrigatório',
            'state.required' => 'O campo do Estado é obrigatório',
            'fk_students_id.required' => 'O campo do Aluno é obrigatório',
            'price.integer' => 'O campo de preço não pode conter caracteres não numéricos',
        ]);

        $data['fk_schoolyears_id'] = SchoolYearHelper::getCurrentSchoolYearId();

        UniformPay::create($data);

        $this->Logger->log('info', 'Cadastrou Pagamento de Uniforme');
        return redirect()->back()->with('create', '1');
    }

    public function show($id)
    {
        $response['uniformPay'] = UniformPay::find($id);

        $this->Logger->log('info', 'Detalhes do Pagamento de Uniforme');
        return view('admin.uniformPay.details.index', $response);
    }

    public function edit($id)
    {
        $response['schoolyears'] = Schoolyear::OrderBy('id','Desc')->get();
        $response['registrations'] = CourseClasseGradeStudentSchoolyear::orderBy('id', 'desc')->get();
        $response['uniformPay'] = UniformPay::find($id);

        $this->Logger->log('info', 'Editar Pagamento de Uniforme');
        return view('admin.uniformPay.edit.index', $response);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'price' => 'required|integer',
            'state' => 'required',
            'fk_schoolyears_id' => 'required',
            'fk_students_id' => 'required'
        ], [
            'price.required' => 'O campo do preço é obrigatório',
            'state.required' => 'O campo do Estado é obrigatório',
            'fk_students_id.required' => 'O campo do Aluno é obrigatório',
            'fk_schoolyears_id.required' => 'O campo do Ano Lectivo é obrigatório',
            'price.integer' => 'O campo de preço não pode conter caracteres não numéricos',
        ]);

        UniformPay::find($id)->update($data);

        $this->Logger->log('info', 'Atualizou o Pagamento de Uniforme');
        return redirect()->route('admin.uniformPay.show', $id)->with('edit', '1');
    }

    public function destroy($id)
    {
        UniformPay::find($id)->delete();
        $this->Logger->log('info', 'Eliminou o Pagamento de Uniforme');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'O Pagamento de Uniforme foi excluído.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }
}
