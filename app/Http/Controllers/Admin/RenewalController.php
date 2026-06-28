<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClasseGradeStudentSchoolyear;
use App\Models\Student;
use App\Models\Schoolyear;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Classe;
use Illuminate\Http\Request;
use App\Classes\Logger;
use App\Helpers\SchoolYearHelper;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }

    public function index(Request $request)
    {
        try {
            // Se é uma solicitação GET sem parâmetros, apenas exibir o formulário de filtros
            if ($request->method() == 'GET' && !$request->has('schoolyear_old') && 
                !$request->has('course') && !$request->has('grade')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                $response['courses'] = Course::OrderBy('id', 'Desc')->get();
                $response['grades'] = Grade::OrderBy('id', 'Desc')->get();
                return view('admin.renewal.index', $response);
            }

            // Verificar se o curso foi fornecido (obrigatório)
            if (!$request->has('course') || empty($request->course)) {
                return back()->with('error', 'Por favor, selecione um curso para continuar.');
            }

            // Inicializar resposta com os parâmetros fornecidos
            $response = [
                'schoolyear_old' => $request->schoolyear_old,
                'course' => $request->course,
                'grade' => $request->grade,
            ];

            // Buscar dados dos cursos e classes para exibir nos filtros
            $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
            $response['courses'] = Course::OrderBy('id', 'Desc')->get();
            $response['grades'] = Grade::OrderBy('id', 'Desc')->get();

            // Buscar alunos matriculados no ano letivo anterior
            $query = CourseClasseGradeStudentSchoolyear::with(['schoolyears', 'classes', 'courses', 'grades', 'students'])
                ->whereNull('deleted_at');

            // Aplicar filtros conforme fornecidos
            if ($request->has('schoolyear_old') && !empty($request->schoolyear_old)) {
                $query->whereHas('schoolyears', function($q) use ($request) {
                    $q->where('name', $request->schoolyear_old);
                });
            }

            if ($request->has('course') && !empty($request->course)) {
                $query->where('fk_courses_id', $request->course);
            }

            if ($request->has('grade') && !empty($request->grade)) {
                $query->where('fk_grades_id', $request->grade);
            }

            $response['registrations'] = $query->get();

            if ($response['registrations']->isEmpty()) {
                return back()->with('error', 'Nenhum aluno encontrado com os filtros selecionados');
            }

            // Calcular próximo grau/classe para cada aluno
            $courseId = $request->course;
            $course = Course::find($courseId);
            $response['course_name'] = $course ? $course->name : '';

            // Próximo ano letivo determinado automaticamente
            $response['next_schoolyear'] = SchoolYearHelper::getNextSchoolYearName();

            return view('admin.renewal.index', $response);
        } catch (\Exception $e) {
            \Log::error('Erro ao exibir página de renovação: ' . $e->getMessage());
            return back()->with('error', 'Erro ao processar a solicitação: ' . $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'required|integer',
                'new_schoolyear' => 'required|string',
                'new_grade_ids' => 'required|array',
                'new_grade_ids.*' => 'required|integer',
                'new_classe_ids' => 'required|array',
                'new_classe_ids.*' => 'required|integer',
                'course_id' => 'required|integer',
            ]);

            $renewedCount = 0;
            // Verifica se o ano letivo já existe ou cria um novo com o campo 'details'
            $schoolyear = Schoolyear::firstOrCreate(
                ['name' => $data['new_schoolyear']],
                ['details' => 'Ano letivo criado automaticamente pelo sistema de confirmação de alunos']
            );

            foreach ($data['student_ids'] as $index => $studentId) {
                // Verificar se o checkbox de renovação foi marcado
                if (!isset($request->renew[$studentId])) {
                    continue;
                }

                $gradeId = $data['new_grade_ids'][$index];
                $classeId = $data['new_classe_ids'][$index];
                $courseId = $data['course_id'];

                // Verificar se já existe uma matrícula para este aluno neste ano letivo
                $existingRegistration = CourseClasseGradeStudentSchoolyear::where('fk_students_id', $studentId)
                    ->where('fk_schoolyears_id', $schoolyear->id)
                    ->where('fk_courses_id', $courseId)
                    ->where('fk_grades_id', $gradeId)
                    ->where('fk_classes_id', $classeId)
                    ->first();

                if (!$existingRegistration) {
                    // Criar nova matrícula
                    CourseClasseGradeStudentSchoolyear::create([
                        'fk_students_id' => $studentId,
                        'fk_schoolyears_id' => $schoolyear->id,
                        'fk_courses_id' => $courseId,
                        'fk_grades_id' => $gradeId,
                        'fk_classes_id' => $classeId,
                        'season' => 'Regular',
                        'status' => 'Confirmado',
                        'details' => 'Confirmação automática de matrícula para o próximo ano letivo',
                        'is_confirmation' => true
                    ]);

                    $renewedCount++;
                }
            }

            DB::commit();
            $this->Logger->log('info', 'Confirmação de alunos: ' . $renewedCount . ' alunos confirmados');
            
            // Redirecionar para a lista de alunos confirmados com os parâmetros
            return redirect()->route('admin.renewal.confirmed', [
                'schoolyear' => $data['new_schoolyear'],
                'course' => $data['course_id']
            ])->with('success', 'Confirmação de alunos concluída com sucesso! ' . $renewedCount . ' alunos confirmados para o próximo ano letivo.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao processar confirmação de alunos: ' . $e->getMessage());
            return back()->with('error', 'Erro ao processar confirmação: ' . $e->getMessage());
        }
    }

    public function getNextGrade($gradeId)
    {
        // Buscar próximo grau com base no atual
        $currentGrade = Grade::find($gradeId);
        if (!$currentGrade) {
            return null;
        }

        // Lógica para determinar o próximo grau
        // Por exemplo, se os graus são sequenciais por número (10ª classe, 11ª classe, etc.)
        $nextGradeNumber = intval($currentGrade->name) + 1;
        $nextGrade = Grade::where('name', 'like', $nextGradeNumber . '%')->first();

        return $nextGrade;
    }
    
    public function confirmedList(Request $request)
    {
        try {
            $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
            $response['courses'] = Course::OrderBy('id', 'Desc')->get();
            
            // Se não houver filtros selecionados, apenas mostra a tela de filtros
            if (!$request->has('schoolyear') || !$request->has('course')) {
                return view('admin.renewal.confirmed', $response);
            }
            
            // Buscar o ano letivo selecionado
            $schoolyear = Schoolyear::where('name', $request->schoolyear)->first();
            if (!$schoolyear) {
                return back()->with('error', 'Ano letivo não encontrado');
            }
            
            // Buscar o curso selecionado
            $course = Course::find($request->course);
            if (!$course) {
                return back()->with('error', 'Curso não encontrado');
            }
            
            // Buscar os alunos confirmados para o ano letivo e curso selecionados
            $query = CourseClasseGradeStudentSchoolyear::with(['schoolyears', 'classes', 'courses', 'grades', 'students'])
                ->where('fk_schoolyears_id', $schoolyear->id)
                ->where('fk_courses_id', $request->course)
                ->where('details', 'like', '%Confirmação automática%')
                ->whereNull('deleted_at');
                
            // Aplicar filtro adicional de classe se fornecido
            if ($request->has('grade') && !empty($request->grade)) {
                $query->where('fk_grades_id', $request->grade);
            }
            
            $confirmedStudents = $query->get();
            
            if ($confirmedStudents->isEmpty()) {
                return back()->with('error', 'Nenhum aluno confirmado encontrado com os filtros selecionados');
            }
            
            $response['confirmedStudents'] = $confirmedStudents;
            $response['schoolyear'] = $request->schoolyear;
            $response['course_name'] = $course->name;
            $response['count'] = $confirmedStudents->count();
            
            if ($request->has('grade') && !empty($request->grade)) {
                $grade = Grade::find($request->grade);
                $response['grade_name'] = $grade ? $grade->name : '';
            }
            
            return view('admin.renewal.confirmed', $response);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao listar alunos confirmados: ' . $e->getMessage());
            return back()->with('error', 'Erro ao listar alunos confirmados: ' . $e->getMessage());
        }
    }
} 