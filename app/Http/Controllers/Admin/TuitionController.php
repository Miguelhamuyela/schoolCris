<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Logger;
use App\Helpers\SchoolYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Course;
use App\Models\CourseClasseGradeStudentSchoolyear;
use App\Models\Grade;
use App\Models\Schoolyear;
use App\Models\Student;
use App\Models\Tuition;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }
    public function index(Request $request)
    {
        $response['schoolyears'] = Schoolyear::orderBy('id', 'Desc')->get();
        $response['courses'] = Course::orderBy('id', 'Desc')->get();
        $response['classes'] = Classe::orderBy('id', 'Desc')->get();
        $response['grades'] = Grade::orderBy('id', 'Desc')->get();

        $filterSchoolyear = $request->get('schoolyear');
        $filterCourse = $request->get('course');
        $filterClass = $request->get('class');
        $filterGrade = $request->get('grade');
        $filterMonth = $request->get('month');
        $filterState = $request->get('state');

        $query = Tuition::with(['students', 'schoolyears'])
            ->orderBy('id', 'Desc');

        if ($filterSchoolyear) {
            $query->where('fk_schoolyears_id', $filterSchoolyear);
        }
        if ($filterMonth) {
            $query->where('month', $filterMonth);
        }
        if ($filterState) {
            $query->where('state', $filterState);
        }

        $tuitions = $query->get();

        if ($filterCourse || $filterClass || $filterGrade) {
            $registrationQuery = CourseClasseGradeStudentSchoolyear::query();
            if ($filterCourse) {
                $registrationQuery->where('fk_courses_id', $filterCourse);
            }
            if ($filterClass) {
                $registrationQuery->where('fk_classes_id', $filterClass);
            }
            if ($filterGrade) {
                $registrationQuery->where('fk_grades_id', $filterGrade);
            }
            $filteredStudentIds = $registrationQuery->pluck('fk_students_id')->unique()->toArray();
            $tuitions = $tuitions->whereIn('fk_students_id', $filteredStudentIds);
        }

        $tuitions->load(['students', 'schoolyears']);
        $registrationIds = $tuitions->pluck('fk_students_id')->unique()->toArray();
        $registrations = CourseClasseGradeStudentSchoolyear::with(['courses', 'classes', 'grades'])
            ->whereIn('fk_students_id', $registrationIds)
            ->get()
            ->groupBy('fk_students_id');

        $tuitions->transform(function ($tuition) use ($registrations) {
            $regs = $registrations->get($tuition->fk_students_id);
            $reg = $regs && $regs->isNotEmpty() ? $regs->first() : null;
            $tuition->course_name = $reg && $reg->courses ? $reg->courses->name : 'N/A';
            $tuition->class_name = $reg && $reg->classes ? $reg->classes->name : 'N/A';
            $tuition->grade_name = $reg && $reg->grades ? $reg->grades->name : 'N/A';
            return $tuition;
        });

        $response['tuitions'] = $tuitions;
        $response['filterSchoolyear'] = $filterSchoolyear;
        $response['filterCourse'] = $filterCourse;
        $response['filterClass'] = $filterClass;
        $response['filterGrade'] = $filterGrade;
        $response['filterMonth'] = $filterMonth;
        $response['filterState'] = $filterState;

        $this->Logger->log('info', 'Lista de Propinas');
        return view('admin.tuition.list.index', $response)->with('success', '1');
    }


    public function create()
    {
        $response['students'] = Student::orderBy('id', 'desc')
            ->get();

        $response['registrations'] = CourseClasseGradeStudentSchoolyear::orderBy('id', 'desc')
            ->get();

        $this->Logger->log('info', 'Criar Propina');
        return view('admin.tuition.create.index', $response);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'month' => 'required',
            'price' => 'required|numeric',
            'fine'  => 'nullable|numeric',
            'state' => 'required',
            'fk_students_id' => 'required'
        ], [
                'month.required' => 'O campo do mês é obrigatório',
                'price.required' => 'O campo do preço é obrigatório',
                'state.required' => 'O campo do Estado é obrigatório',
                'fk_students_id.required' => 'O campo do Aluno é obrigatório',
                'price.numeric' => 'O campo de preço deve ser um número',
                'fine.numeric'  => 'O campo de multa deve ser um número',
            ]);

        $data['fk_schoolyears_id'] = SchoolYearHelper::getCurrentSchoolYearId();

        // Default to 0 if fine is not provided
        if (!isset($data['fine'])) {
            $data['fine'] = 0;
        }

        Tuition::create($data);

        $this->Logger->log('info', 'Cadastrou Propina');
        return redirect()->back()->with('create', '1');
    }

    public function show($id)
    {
        $response['tuition'] = Tuition::find($id);

        $this->Logger->log('info', 'Detalhes da Propina');
        return view('admin.tuition.details.index', $response);
    }

    public function edit($id)
    {
        $response['schoolyears'] = Schoolyear::OrderBy('id','Desc')->get();
        $response['registrations'] = CourseClasseGradeStudentSchoolyear::orderBy('id', 'desc')
            ->get();
        $response['tuition'] = Tuition::find($id);

        $this->Logger->log('info', 'Editar Propina');
        return view('admin.tuition.edit.index', $response);
    }

    public function update(Request $request, $id)
    {

        $data = $request->validate([
            'month' => 'required',
            'price' => 'required|numeric',
            'fine'  => 'nullable|numeric',
            'state' => 'required',
            'fk_schoolyears_id' => 'required',
            'fk_students_id' => 'required'
        ], [
                'month.required' => 'O campo do mês é obrigatório',
                'price.required' => 'O campo do preço é obrigatório',
                'state.required' => 'O campo do Estado é obrigatório',
                'fk_students_id.required' => 'O campo do Aluno é obrigatório',
                'fk_schoolyears_id.required' => 'O campo do Ano Lectivo é obrigatório',
                'price.numeric' => 'O campo de preço deve ser um número',
                'fine.numeric'  => 'O campo de multa deve ser um número',
            ]);

        // Default to 0 if fine is not provided
        if (!isset($data['fine'])) {
            $data['fine'] = 0;
        }


        Tuition::find($id)->update($data);

        $this->Logger->log('info', 'Atualizou a Propina');
        return redirect()->route('admin.tuition.show',$id)->with('edit', '1');
    }

    public function destroy($id)
    {
        $tuition = Tuition::find($id);

        // Verifica se a propina está associada a outro registro
        if ($tuition->students->count() > 0) {
            return redirect()->back()->with('propinas_destroy_error', '1');
        }
        Tuition::find($id)->delete();
        $this->Logger->log('info', 'Eliminou a Propina');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'A Propina foi excluída.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }
}
