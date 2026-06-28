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
use App\Services\StudentPhotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LoggerFacade;

class CourseClasseGradeStudentSchoolyearController extends Controller
{
    private $Logger;
    private $studentPhotoService;

    public function __construct()
    {
        $this->Logger = new Logger();
        $this->studentPhotoService = new StudentPhotoService();
    }

    public function index(Request $request)
    {
        $response['schoolyears'] = Schoolyear::orderBy('id', 'Desc')->get();
        $response['classes'] = Classe::orderBy('id', 'Desc')->get();
        $response['courses'] = Course::orderBy('id', 'Desc')->get();
        $response['grades'] = Grade::orderBy('id', 'Desc')->get();

        $filterSchoolyear = $request->get('schoolyear');
        $filterCourse = $request->get('course');
        $filterClass = $request->get('class');
        $filterGrade = $request->get('grade');

        $query = CourseClasseGradeStudentSchoolyear::with(['students', 'schoolyears', 'courses', 'classes', 'grades'])
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('is_confirmation', false)
                    ->orWhereNull('is_confirmation');
            });

        if ($filterSchoolyear) {
            $query->where('fk_schoolyears_id', $filterSchoolyear);
        }
        if ($filterCourse) {
            $query->where('fk_courses_id', $filterCourse);
        }
        if ($filterClass) {
            $query->where('fk_classes_id', $filterClass);
        }
        if ($filterGrade) {
            $query->where('fk_grades_id', $filterGrade);
        }

        $response['students'] = $query->get()->map(function ($item) {
            return (object) [
                'id' => $item->students->id ?? null,
                'name' => $item->students->name ?? 'N/A',
                'nProcess' => $item->students->nProcess ?? 'N/A',
                'nBi' => $item->students->nBi ?? 'N/A',
                'schoolyear' => $item->schoolyears->name ?? 'N/A',
                'course' => $item->courses->name ?? 'N/A',
                'class' => $item->classes->name ?? 'N/A',
                'grade' => $item->grades->name ?? 'N/A',
                'contact' => $item->students->contact ?? 'N/A',
            ];
        });

        $response['filterSchoolyear'] = $filterSchoolyear;
        $response['filterCourse'] = $filterCourse;
        $response['filterClass'] = $filterClass;
        $response['filterGrade'] = $filterGrade;

        $this->Logger->log('info', 'Lista de Alunos Matriculados');
        return view('admin.courseClasseGradeStudentSchoolyear.list.index', $response);
    }

    public function create()
    {
        $response['courses'] = Course::orderBy('id', 'Desc')->get();
        $response['classes'] = Classe::orderBy('id', 'Desc')->get();
        $response['grades'] = Grade::orderBy('id', 'Desc')->get();
        $response['students'] = Student::orderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Matricular Aluno');
        return view('admin.courseClasseGradeStudentSchoolyear.create.index', $response);
    }

    public function store(Request $request)
    {
        $data = $this->validate(
            $request,
            [
                'fk_courses_id' => 'required',
                'fk_classes_id' => 'required',
                'fk_grades_id' => 'required',
                'fk_students_id' => 'required',
                'season' => 'required',
                'details' => 'required',
                'status' => 'required',
                'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'fk_courses_id.required' => 'O campo Curso deve ser selecionado',
                'fk_classes_id.required' => 'O campo Turma deve ser selecionado',
                'fk_grades_id.required' => 'O campo Classe deve ser selecionado',
                'fk_students_id.required' => 'O campo Aluno deve ser selecionado',
                'season.required' => 'O campo Turno deve ser selecionado',
                'details.required' => 'O campo Detalhe deve ser selecionado',
                'status.required' => 'O campo Status da MatrÃ­cula deve ser selecionado',
                'student_image.image' => 'O arquivo deve ser uma imagem',
                'student_image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif',
                'student_image.max' => 'A imagem nÃ£o pode ter mais de 2MB',
            ]
        );

        $data['fk_schoolyears_id'] = SchoolYearHelper::getCurrentSchoolYearId();

        CourseClasseGradeStudentSchoolyear::create($data);

        if ($request->hasFile('student_image')) {
            try {
                $student = Student::find($data['fk_students_id']);
                $imageFile = $request->file('student_image');

                if ($student && $imageFile && $imageFile->isValid()) {
                    $imageName = $this->studentPhotoService->store($imageFile, $student->image);
                    $student->update(['image' => $imageName]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao processar a imagem: ' . $e->getMessage());
            }
        }

        $this->Logger->log('info', 'Matriculou um Aluno');
        return redirect()->back()->with('create', '1');
    }

    public function show($id)
    {
        try {
            $response['courseClasseGradeStudentSchoolyear'] = CourseClasseGradeStudentSchoolyear::find($id);

            if (!$response['courseClasseGradeStudentSchoolyear']) {
                return back()->with('not_found', '1');
            }

            $student = $response['courseClasseGradeStudentSchoolyear']->students;
            if ($student) {
                $hasImage = !empty($student->image) &&
                    file_exists(storage_path('app/public/images/' . $student->image));

                $response['hasStudentImage'] = $hasImage;
                $response['studentImagePath'] = $hasImage
                    ? route('admin.student.image', $student->image)
                    : asset('dashboard/assets/img/undraw_profile.svg');
            }

            $this->Logger->log('info', 'Visualizou MatrÃ­cula');
            return view('admin.courseClasseGradeStudentSchoolyear.details.index', $response);
        } catch (\Exception $e) {
            LoggerFacade::error('Erro ao mostrar matrÃ­cula: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar os detalhes da matrÃ­cula.');
        }
    }

    public function edit($id)
    {
        $response['courses'] = Course::orderBy('id', 'Desc')->get();
        $response['classes'] = Classe::orderBy('id', 'Desc')->get();
        $response['grades'] = Grade::orderBy('id', 'Desc')->get();
        $response['students'] = Student::orderBy('id', 'Desc')->get();
        $response['courseClasseGradeStudentSchoolyear'] = CourseClasseGradeStudentSchoolyear::find($id);
        $this->Logger->log('info', 'Editar Matricula');
        return view('admin.courseClasseGradeStudentSchoolyear.edit.index', $response);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate(
            [
                'fk_courses_id' => 'required',
                'fk_classes_id' => 'required',
                'fk_grades_id' => 'required',
                'fk_students_id' => 'required',
                'fk_schoolyears_id' => 'required',
                'season' => 'required',
                'details' => 'required',
                'status' => 'required',
                'student_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'fk_courses_id.required' => 'O campo Curso deve ser selecionado',
                'fk_classes_id.required' => 'O campo Turma deve ser selecionado',
                'fk_grades_id.required' => 'O campo Classe deve ser selecionado',
                'fk_students_id.required' => 'O campo Aluno deve ser selecionado',
                'fk_schoolyears_id.required' => 'O campo Ano Lectivo deve ser selecionado',
                'season.required' => 'O campo Turno deve ser selecionado',
                'details.required' => 'O campo Detalhe deve ser selecionado',
                'status.required' => 'O campo Status da MatrÃ­cula deve ser selecionado',
                'student_image.image' => 'O arquivo deve ser uma imagem',
                'student_image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif',
                'student_image.max' => 'A imagem nÃ£o pode ter mais de 2MB',
            ]
        );

        $registration = CourseClasseGradeStudentSchoolyear::find($id);
        $registration->update($data);

        if ($request->hasFile('student_image')) {
            try {
                $student = Student::find($data['fk_students_id']);
                $imageFile = $request->file('student_image');

                if ($student && $imageFile && $imageFile->isValid()) {
                    $imageName = $this->studentPhotoService->store($imageFile, $student->image);
                    $student->update(['image' => $imageName]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao processar a imagem: ' . $e->getMessage());
            }
        }

        $this->Logger->log('info', 'Atualizou a Matricula');
        return redirect()->route('admin.courseClasseGradeStudentSchoolyear.show', $id)->with('edit', '1');
    }

    public function destroy($id)
    {
        CourseClasseGradeStudentSchoolyear::find($id)->delete();
        $this->Logger->log('info', 'Removeu a Matricula');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'A MatrÃ­cula foi excluÃ­da.']);
        }

        return redirect()->back()->with('destroy', '1');
    }

    public function getStudent($nProcess)
    {
        $students = Student::where('nProcess', $nProcess)->first();
        return response()->json($students);
    }

    public function getRegistration($nProcess)
    {
        $students = Student::where('nProcess', $nProcess)->first();

        if (!$students) {
            return response()->json(null);
        }

        $studentExists = CourseClasseGradeStudentSchoolyear::where('fk_students_id', $students->id)->first();

        if ($studentExists) {
            return response()->json($students);
        }

        return response()->json(null);
    }

    public function getNProcess($student)
    {
        $nProcess = $student;
        return response()->json($nProcess);
    }
}
