<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClasseGradeStudentSchoolyear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;
use App\Classes\Logger;
use App\Models\Log;
use App\Models\Schoolyear;
use App\Models\Student;
use App\Models\TeacherSubjectClasseRuleSchoolyear;
use Illuminate\Support\Facades\App;
use App\Models\Exam;
use App\Models\Transport;
use App\Models\TransportPay;
use App\Models\Tuition;
use App\Models\UniformPay;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }


    public function registration(Request $request)
    {
        try {
            // Se é uma solicitação GET sem parâmetros, apenas exibir o formulário
            if ($request->method() == 'GET' && !$request->has('schoolyear') && !$request->has('classe') && 
                !$request->has('course') && !$request->has('grade')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                $response['classes'] = \App\Models\Classe::OrderBy('id', 'Desc')->get();
                $response['courses'] = \App\Models\Course::OrderBy('id', 'Desc')->get();
                $response['grades'] = \App\Models\Grade::OrderBy('id', 'Desc')->get();
                return view('admin.pdf.registration', $response);
            }
            
            // Inicializar resposta com os parâmetros fornecidos
            $response = [
                'schoolyear' => $request->schoolyear,
                'classe' => $request->classe,
                'course' => $request->course,
                'grade' => $request->grade,
                'student_id' => $request->student_id,
            ];

            // Se temos um grade_id, precisamos obter o nome da classe para o relatório
            if (!empty($response['grade']) && is_numeric($response['grade'])) {
                $gradeModel = \App\Models\Grade::find($response['grade']);
                if ($gradeModel) {
                    $response['grade'] = $gradeModel->name;
                }
            }

            // Se temos um course_id, precisamos obter o nome do curso para o relatório
            if (!empty($response['course']) && is_numeric($response['course'])) {
                $courseModel = \App\Models\Course::find($response['course']);
                if ($courseModel) {
                    $response['course'] = $courseModel->name;
                }
            }

            // Iniciar a query base
            $query = CourseClasseGradeStudentSchoolyear::with(['schoolyears', 'classes', 'courses', 'grades', 'students']);
            
            // Aplicar filtros conforme fornecidos
            if ($request->has('schoolyear') && !empty($request->schoolyear)) {
                $query->whereHas('schoolyears', function($q) use ($request) {
                    $q->where('name', $request->schoolyear);
                });
            }
            
            if ($request->has('classe') && !empty($request->classe)) {
                $query->whereHas('classes', function($q) use ($request) {
                    $q->where('name', $request->classe);
                });
            }
            
            if ($request->has('course') && !empty($request->course)) {
                $query->where('fk_courses_id', $request->course);
            }
            
            if ($request->has('grade') && !empty($request->grade)) {
                $query->where('fk_grades_id', $request->grade);
            }
            
            // Garantir que só registros não excluídos sejam considerados
            $query->whereNull('deleted_at');
            
            // Se um aluno específico foi selecionado, filtra somente por ele
            if (!empty($response['student_id'])) {
                $query->where('fk_students_id', $response['student_id']);
                
                // Busca o aluno para exibir informações no relatório
                $student = Student::find($response['student_id']);
                if ($student) {
                    $response['student'] = $student;
                    
                    // Obtém o registro de matrícula do aluno
                    $registration = $query->first();
                    if ($registration) {
                        $response['registration'] = $registration;
                        
                        // Template específico para aluno individual
                        $pdf = PDF::loadview('pdf.registration.student_report', $response);
                    } else {
                        return back()->with('error', 'Não foi encontrada matrícula para este aluno com os filtros selecionados');
                    }
                } else {
                    return back()->with('error', 'Aluno não encontrado');
                }
            } else {
                // Consulta para todos os alunos com os filtros aplicados
                $registrations = $query->get();
                
                if ($registrations->isEmpty()) {
                    return back()->with('error', 'Nenhuma matrícula encontrada com os filtros selecionados');
                }
                
                $response['studentSchoolYear'] = $registrations;
                $pdf = PDF::loadview('pdf.registration.index', $response);
            }
            
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            
            // Gerar nome do arquivo com base nos filtros aplicados
            $filename = 'matriculas';
            if (!empty($response['schoolyear'])) $filename .= '_' . $response['schoolyear'];
            if (!empty($response['classe'])) $filename .= '_' . $response['classe'];
            if (!empty($response['grade'])) $filename .= '_' . $response['grade'];
            if (!empty($response['course'])) $filename .= '_' . $response['course'];
            $filename .= '.pdf';
            
            return $pdf->setPaper('a4', 'landscape')->stream($filename, ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório de matrículas: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }



    public function contract(Request $request)
    {
        $data = $request->validate([
            'schoolyear' => 'required',
        ], [
            'schoolyear.required' => 'O campo Ano Lectivo é obrigatório.',
        ]);

        //$exists = CourseClasseGradeStudentSchoolyear::find($id);
        $schoolyear = $data['schoolyear'];

        $response['teacherSchoolYear'] = TeacherSubjectClasseRuleSchoolyear::join('anos_lectivos', 'contratos.fk_schoolyears_id', '=', 'anos_lectivos.id')
            ->where('anos_lectivos.name', $schoolyear)
            ->whereNull('contratos.deleted_at')
            ->get();
        $pdf = PDF::loadview('pdf.contract.index', $response);
        return $pdf->setPaper('a4', 'landscape')->stream('pdf', ['Attachment' => 0]);
    }



    public function exam()
    {
        $response['exams'] = Exam::OrderBy('id', 'Desc')->get();

        $pdf = PDF::loadview('pdf.exam.index', $response);
        return $pdf->setPaper('a4', 'landscape')->stream('pdf', ['Attachment' => 0]);
    }


    public function student(Request $request)
    {
        try {
            // Se é uma solicitação GET sem parâmetros, apenas exibir o formulário
            if ($request->method() == 'GET' && !$request->has('schoolyear') && !$request->has('classe') && 
                !$request->has('course') && !$request->has('grade')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                $response['classes'] = \App\Models\Classe::OrderBy('id', 'Desc')->get();
                $response['courses'] = \App\Models\Course::OrderBy('id', 'Desc')->get();
                $response['grades'] = \App\Models\Grade::OrderBy('id', 'Desc')->get();
                return view('admin.pdf.student', $response);
            }
            
            // Inicializar resposta com os parâmetros fornecidos
            $response = [
                'schoolyear' => $request->schoolyear,
                'classe' => $request->classe,
                'course' => $request->course,
                'grade' => $request->grade,
                'student_id' => $request->student_id,
            ];

            // Se temos um grade_id, precisamos obter o nome da classe para o relatório
            if (!empty($response['grade']) && is_numeric($response['grade'])) {
                $gradeModel = \App\Models\Grade::find($response['grade']);
                if ($gradeModel) {
                    $response['grade'] = $gradeModel->name;
                }
            }

            // Se temos um course_id, precisamos obter o nome do curso para o relatório
            if (!empty($response['course']) && is_numeric($response['course'])) {
                $courseModel = \App\Models\Course::find($response['course']);
                if ($courseModel) {
                    $response['course'] = $courseModel->name;
                }
            }

            // Se um aluno específico foi selecionado, busca somente ele
            if (!empty($response['student_id'])) {
                $student = Student::find($response['student_id']);
                
                if ($student) {
                    $response['student'] = $student;
                    
                    // Buscar informações adicionais do aluno 
                    $registrationsQuery = CourseClasseGradeStudentSchoolyear::with(['courses', 'classes', 'grades', 'schoolyears'])
                        ->where('fk_students_id', $student->id)
                        ->whereNull('deleted_at');
                        
                    // Aplicar filtros se fornecidos
                    if (!empty($response['schoolyear'])) {
                        $registrationsQuery->whereHas('schoolyears', function($q) use ($response) {
                            $q->where('name', $response['schoolyear']);
                        });
                    }
                    
                    if (!empty($response['classe'])) {
                        $registrationsQuery->whereHas('classes', function($q) use ($response) {
                            $q->where('name', $response['classe']);
                        });
                    }
                    
                    if (!empty($response['course']) && is_numeric($request->course)) {
                        $registrationsQuery->where('fk_courses_id', $request->course);
                    }
                    
                    if (!empty($response['grade']) && is_numeric($request->grade)) {
                        $registrationsQuery->where('fk_grades_id', $request->grade);
                    }
                    
                    $response['registrations'] = $registrationsQuery->get();
                        
                    // Template específico para aluno individual
                    $pdf = PDF::loadview('pdf.student.student_report', $response);
                    $filename = 'aluno_' . $student->nProcess . '.pdf';
                } else {
                    return back()->with('error', 'Aluno não encontrado');
                }
            } else {
                // Consulta para todos os alunos com filtros
                $studentsQuery = Student::whereNull('deleted_at');
                
                // Aplicar filtros conforme fornecidos
                if (!empty($response['schoolyear'])) {
                    $studentsQuery->where('schoolyear', $response['schoolyear']);
                }
                
                // Se temos filtros de turma, curso ou classe, precisamos juntar com matrículas
                if (!empty($response['classe']) || !empty($request->course) || !empty($request->grade)) {
                    $studentsQuery->whereExists(function ($query) use ($response, $request) {
                        $query->select(\DB::raw(1))
                            ->from('matriculas')
                            ->whereRaw('matriculas.fk_students_id = alunos.id')
                            ->whereNull('matriculas.deleted_at');
                        
                        // Aplicar filtros nas matrículas
                        if (!empty($response['classe'])) {
                            $query->join('turmas', 'matriculas.fk_classes_id', '=', 'turmas.id')
                                  ->where('turmas.name', $response['classe']);
                        }
                        
                        if (!empty($request->course)) {
                            $query->join('cursos', 'matriculas.fk_courses_id', '=', 'cursos.id')
                                  ->where('cursos.id', $request->course);
                        }
                        
                        if (!empty($request->grade)) {
                            $query->join('niveis', 'matriculas.fk_grades_id', '=', 'niveis.id')
                                  ->where('niveis.id', $request->grade);
                        }
                    });
                }
                
                // Executar a query
                $students = $studentsQuery->get();
                
                if ($students->isEmpty()) {
                    return back()->with('error', 'Nenhum aluno encontrado com os filtros selecionados');
                }
                
                $response['students'] = $students;
                
                // Buscar as matrículas dos alunos para exibir no relatório
                $studentIds = $students->pluck('id')->toArray();
                $registrationsQuery = CourseClasseGradeStudentSchoolyear::with(['courses', 'classes', 'grades', 'schoolyears'])
                    ->whereIn('fk_students_id', $studentIds)
                    ->whereNull('deleted_at');
                    
                // Aplicar filtros se fornecidos
                if (!empty($response['schoolyear'])) {
                    $registrationsQuery->whereHas('schoolyears', function($q) use ($response) {
                        $q->where('name', $response['schoolyear']);
                    });
                }
                
                if (!empty($response['classe'])) {
                    $registrationsQuery->whereHas('classes', function($q) use ($response) {
                        $q->where('name', $response['classe']);
                    });
                }
                
                if (!empty($request->course)) {
                    $registrationsQuery->where('fk_courses_id', $request->course);
                }
                
                if (!empty($request->grade)) {
                    $registrationsQuery->where('fk_grades_id', $request->grade);
                }
                
                $response['registrations'] = $registrationsQuery->get();
                
                // Template para listagem de alunos
                $pdf = PDF::loadview('pdf.student.index', $response);
                
                // Gerar nome do arquivo com base nos filtros aplicados
                $filename = 'alunos';
                if (!empty($response['schoolyear'])) $filename .= '_' . $response['schoolyear'];
                if (!empty($response['classe'])) $filename .= '_' . $response['classe'];
                if (!empty($response['grade'])) $filename .= '_' . $response['grade'];
                if (!empty($response['course'])) $filename .= '_' . $response['course'];
                $filename .= '.pdf';
            }
                
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            
            return $pdf->setPaper('a4', 'landscape')->stream($filename, ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório de alunos: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }



    /** tuition */

    public function tuition(Request $request)
    {
        try {
            set_time_limit(180);

            if ($request->method() == 'GET' && !$request->has('schoolyear') && !$request->has('month')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                return view('admin.pdf.tuition', $response);
            }

            $response = [
                'schoolyear' => $request->schoolyear,
                'month' => $request->month,
                'pay' => $request->pay,
                'cancel' => $request->cancel,
                'pending' => $request->pending,
                'student_id' => $request->student_id,
            ];

            $query = Tuition::with(['students', 'schoolyears'])
                ->whereNull('deleted_at');

            if (!empty($response['schoolyear'])) {
                $schoolyear = Schoolyear::where('name', $response['schoolyear'])->first();
                if ($schoolyear) {
                    $query->where('fk_schoolyears_id', $schoolyear->id);
                }
            }

            if (!empty($response['month'])) {
                $query->where('month', $response['month']);
            }

            $query->limit(1000);

            if (!empty($response['student_id'])) {
                $query->where('fk_students_id', $response['student_id']);

                $student = Student::find($response['student_id']);
                if ($student) {
                    $response['student'] = $student;

                    $paymentQuery = Tuition::where('fk_students_id', $student->id)
                        ->whereNull('deleted_at')
                        ->orderBy('month', 'ASC');

                    if (!empty($response['schoolyear'])) {
                        $sy = Schoolyear::where('name', $response['schoolyear'])->first();
                        if ($sy) {
                            $paymentQuery->where('fk_schoolyears_id', $sy->id);
                        }
                    }

                    $response['payment_history'] = $paymentQuery->get();
                    $pdf = PDF::loadview('pdf.tuition.student_report', $response);
                } else {
                    return back()->with('error', 'Aluno não encontrado');
                }
            } else {
                $response['tuitions'] = $query->get();
                $pdf = PDF::loadview('pdf.tuition.index', $response);
            }

            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 100,
                'defaultPaperSize' => 'a4',
            ]);

            $filename = 'propinas';
            if (!empty($response['schoolyear'])) $filename .= '_' . $response['schoolyear'];
            if (!empty($response['month'])) $filename .= '_' . $response['month'];
            $filename .= '.pdf';

            return $pdf->setPaper('a4', 'landscape')->stream($filename, ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório de propinas: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }


    /** TuitionStudent */

    public function tuitionStudent($id)
    {
        try {
            // Aumentar o tempo limite de execução para 120 segundos
            set_time_limit(120);
            
            $response['tuition'] = Tuition::with('students', 'schoolyears')->find($id);
            if (!$response['tuition']) {
                return back()->with('error', 'Pagamento não encontrado');
            }
            
            // Gerar QR code com menos recursos
            $qrCode = QrCode::size(100)
                ->format('svg')
                ->generate(route('admin.tuition.show', $response['tuition']->id));
            
            $response['qrCode'] = $qrCode;
            
            // Otimizar carregamento do PDF
            $pdf = PDF::loadview('pdf.tuitionStudent.index', $response);
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 100,
                'defaultPaperSize' => 'a4',
            ]);
            
            // Diminuir o uso de memória
            $pdf->setPaper('a4', 'portrait');
            
            return $pdf->stream('Factura_' . $response['tuition']->id . '.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar factura de propina: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar factura: ' . $e->getMessage());
        }
    }


    /** UniformPayStudent */

    public function uniformPayStudent($id)
    {
        try {
            set_time_limit(120);

            $response['uniformPay'] = UniformPay::with('students', 'schoolyears')->find($id);
            if (!$response['uniformPay']) {
                return back()->with('error', 'Pagamento não encontrado');
            }

            // Gerar QR code
            $qrCode = QrCode::size(100)
                ->format('svg')
                ->generate(route('admin.uniformPay.show', $response['uniformPay']->id));

            $response['qrCode'] = $qrCode;

            $pdf = PDF::loadview('pdf.uniformPayStudent.index', $response);
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 100,
                'defaultPaperSize' => 'a4',
            ]);

            $pdf->setPaper('a4', 'portrait');

            return $pdf->stream('Factura_Uniforme_' . $response['uniformPay']->id . '.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar factura de uniforme: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar factura: ' . $e->getMessage());
        }
    }


    public function transportPay(Request $request)
    {
        try {
            // Se é uma solicitação GET sem parâmetros, apenas exibir o formulário
            if ($request->method() == 'GET' && !$request->has('schoolyear')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                return view('admin.pdf.transportPay', $response);
            }
            
            $data = $request->validate([
                'schoolyear' => 'required',
                'month' => 'required',
            ], [
                'schoolyear.required' => 'O campo Ano Lectivo é obrigatório.',
                'month.required' => 'O campo Mês é obrigatório.',
            ]);

            $response = [
                'schoolyear' => $request->schoolyear,
                'month' => $request->month,
                'pay' => $request->pay,
                'cancel' => $request->cancel,
                'pending' => $request->pending,
                'student_id' => $request->student_id,
            ];

            $schoolyear = Schoolyear::where('name', $response['schoolyear'])->first();
            if (!$schoolyear) {
                return back()->with('error', 'Ano lectivo não encontrado');
            }

            $query = TransportPay::where('fk_schoolyears_id', $schoolyear->id)
                ->where('month', $response['month'])
                ->whereNull('deleted_at');
            
            // Se um aluno específico foi selecionado, filtra somente por ele
            if (!empty($response['student_id'])) {
                $query->where('fk_students_id', $response['student_id']);
                
                // Busca o aluno para exibir informações no relatório
                $student = Student::find($response['student_id']);
                if ($student) {
                    $response['student'] = $student;
                    
                    // Consulta adicionais - histórico de pagamentos do aluno no ano letivo
                    $response['payment_history'] = TransportPay::where('fk_students_id', $student->id)
                        ->where('fk_schoolyears_id', $schoolyear->id)
                        ->whereNull('deleted_at')
                        ->orderBy('month', 'ASC')
                        ->get();
                    
                    // Template específico para aluno individual
                    $pdf = PDF::loadview('pdf.transportPay.student_report', $response);
                } else {
                    return back()->with('error', 'Aluno não encontrado');
                }
            } else {
                // Consulta padrão para todos os alunos
                $response['transports'] = $query->get();
                $pdf = PDF::loadview('pdf.transportPay.index', $response);
            }
            
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            
            $filename = !empty($response['student_id']) 
                ? 'transportes_aluno_' . $student->nProcess . '_' . $response['month'] . '_' . $response['schoolyear'] . '.pdf'
                : 'transportes_' . $response['month'] . '_' . $response['schoolyear'] . '.pdf';
                
            return $pdf->setPaper('a4', 'landscape')->stream($filename, ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório de transportes: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }


    public function transportPayStudent($id)
    {
        try {
            $response['transportPayStudent'] = TransportPay::find($id);
            
            if (!$response['transportPayStudent']) {
                return back()->with('error', 'Registro de transporte não encontrado');
            }

            $pdf = PDF::loadview('pdf.transportPayStudent.index', $response);
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            $this->Logger->log('info', 'Factura do Pagamento de Transporte do Aluno');
            return $pdf->setPaper('a4', 'landscape')->stream('transporte_aluno_'.$id.'.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar fatura de transporte: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar fatura: ' . $e->getMessage());
        }
    }

    public function studentPaymentsReport(Request $request)
    {
        try {
            if ($request->method() == 'GET' && !$request->has('student_id')) {
                $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
                return view('admin.pdf.student_payments', $response);
            }

            $data = $request->validate([
                'student_id' => 'required',
            ]);

            $student = Student::find($request->student_id);
            if (!$student) {
                return back()->with('error', 'Aluno não encontrado.');
            }

            $schoolyearId = $request->schoolyear_id;

            $response['student'] = $student;

            $response['tuitions'] = Tuition::where('fk_students_id', $student->id)
                ->when($schoolyearId, function ($q) use ($schoolyearId) {
                    return $q->where('fk_schoolyears_id', $schoolyearId);
                })
                ->whereNull('deleted_at')
                ->orderBy('month', 'ASC')
                ->get();

            $response['transportPays'] = TransportPay::where('fk_students_id', $student->id)
                ->when($schoolyearId, function ($q) use ($schoolyearId) {
                    return $q->where('fk_schoolyears_id', $schoolyearId);
                })
                ->whereNull('deleted_at')
                ->orderBy('month', 'ASC')
                ->get();

            $response['uniformPays'] = UniformPay::where('fk_students_id', $student->id)
                ->when($schoolyearId, function ($q) use ($schoolyearId) {
                    return $q->where('fk_schoolyears_id', $schoolyearId);
                })
                ->whereNull('deleted_at')
                ->get();

            if ($schoolyearId) {
                $response['schoolyear'] = Schoolyear::find($schoolyearId);
            }

            $totalPaid = ($response['tuitions']->where('state', 'Pago')->sum('price') +
                $response['tuitions']->where('state', 'Pago')->sum('fine') +
                $response['transportPays']->where('state', 'Pago')->sum('price') +
                $response['uniformPays']->where('state', 'Pago')->sum('price'));

            $totalPending = ($response['tuitions']->where('state', 'Pendente')->sum('price') +
                $response['tuitions']->where('state', 'Pendente')->sum('fine') +
                $response['transportPays']->where('state', 'Pendente')->sum('price') +
                $response['uniformPays']->where('state', 'Pendente')->sum('price'));

            $response['totalPaid'] = $totalPaid;
            $response['totalPending'] = $totalPending;

            $pdf = PDF::loadview('pdf.student_payments.index', $response);
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

            $filename = 'relatorio_pagamentos_' . $student->nProcess . '.pdf';
            return $pdf->setPaper('a4', 'portrait')->stream($filename, ['Attachment' => 0]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório de pagamentos: ' . $e->getMessage());
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

    public function transportDocumentation($id)
    {

        $transport = Transport::find($id);
        $path = public_path('storage/transport/' . $transport->documentation);
        //$path = asset('storage/'.$transport->documentation);
        //dd($path);

        if (!file_exists($path)) {
            return redirect()->back()->with('documentation_not_exist', '1');
        }
        return response()->download($path);
    }

    public function studentCard($id)
    {
        try {
            // Configuração para evitar timeout
            set_time_limit(30); // Tempo limite menor
            ini_set('memory_limit', '128M'); // Limitar uso de memória
            
            // Buscar dados
            $response['registration'] = CourseClasseGradeStudentSchoolyear::with(['students', 'courses', 'classes', 'grades', 'schoolyears'])->find($id);
            
            if (!$response['registration']) {
                return response()->json(['error' => 'Registro não encontrado'], 404);
            }
            
            // Verificar se existe a imagem do aluno
            $student = $response['registration']->students;
            $response['studentPhotoSrc'] = '';
            
            if (!empty($student->image)) {
                $imagePath = storage_path('app/public/images/' . $student->image);
                if (file_exists($imagePath)) {
                    // Redimensionar a foto para caber sem distorção (1.9cm x 2.5cm ~ 215x283 px)
                    $response['studentPhotoSrc'] = $this->resizeImage($imagePath, 215, 283);
                } else {
                    \Log::warning('Imagem do aluno não encontrada: ' . $imagePath);
                }
            }

            // Logo em base64
            $logoSrc = '';
            $logoPath = public_path('dashboard/assets/img/logo-sge-small.png');
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            $response['logoSrc'] = $logoSrc;
            
            // Carregar view do PDF com configuração simplificada
            $pdf = PDF::loadview('pdf.studentCard.index', $response);
            
            // Configurar PDF em A4 (estável no DomPDF)
            $pdf->setPaper('a4');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isFontSubsettingEnabled' => false // Desabilitar subsetting de fontes
            ]);
            
            $this->Logger->log('info', 'Cartão de Estudante Gerado');
            return $pdf->stream('cartao-estudante.pdf', ['Attachment' => 0]);
            
        } catch (\Exception $e) {
            // Registrar erro
            \Log::error('Erro ao gerar cartão: ' . $e->getMessage());
            
            // Em produção, retornar uma mensagem mais amigável
            if (app()->environment('production')) {
                return back()->with('error', 'Não foi possível gerar o cartão do estudante. Por favor, tente novamente mais tarde.');
            }
            
            // Em desenvolvimento, mostrar o erro completo
            return response()->json([
                'error' => 'Erro ao gerar o cartão: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    private function resizeImage($path, $maxW, $maxH)
    {
        if (!file_exists($path) || !extension_loaded('gd')) {
            return '';
        }

        $info = @getimagesize($path);
        if (!$info) return '';

        $sw = $info[0];
        $sh = $info[1];

        // Criar imagem de origem conforme o tipo
        switch ($info[2]) {
            case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($path); break;
            case IMAGETYPE_PNG:  $src = @imagecreatefrompng($path);  break;
            case IMAGETYPE_GIF:  $src = @imagecreatefromgif($path);  break;
            default: return '';
        }
        if (!$src) return '';

        // Calcular crop centered para o aspect ratio desejado
        $targetRatio = $maxW / $maxH;
        $srcRatio = $sw / $sh;

        if ($srcRatio > $targetRatio) {
            // Imagem mais larga - cortar laterais
            $cropW = (int)round($sh * $targetRatio);
            $cropH = $sh;
            $cropX = (int)round(($sw - $cropW) / 2);
            $cropY = 0;
        } else {
            // Imagem mais alta - cortar topo/base
            $cropW = $sw;
            $cropH = (int)round($sw / $targetRatio);
            $cropX = 0;
            $cropY = (int)round(($sh - $cropH) / 2);
        }

        $dst = imagecreatetruecolor($maxW, $maxH);
        if ($info[2] === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $maxW, $maxH, $cropW, $cropH);

        ob_start();
        imagepng($dst);
        $data = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return 'data:image/png;base64,' . base64_encode($data);
    }
}
