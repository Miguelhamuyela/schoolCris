<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Classes\Logger;
use App\Helpers\SchoolYearHelper;
use App\Models\Log;
use App\Models\Schoolyear;
use App\Models\Course;
use App\Models\Classe;
use App\Models\Grade;
use App\Models\CourseClasseGradeStudentSchoolyear;
use App\Models\CourseGradeGroup;
use App\Services\NotificationService;
use App\Services\StudentPhotoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log as LoggerFacade;

class StudentController extends Controller
{
    private $Logger;
    private $notificationService;
    private $studentPhotoService;

    public function __construct()
    {
        $this->Logger = new Logger();
        $this->notificationService = new NotificationService();
        $this->studentPhotoService = new StudentPhotoService();
    }
    
    public function index()
    {
        $response['schoolyears'] = Schoolyear::OrderBy('id', 'Desc')->get();
        $response['students'] = Student::OrderBy('id', 'Desc')->get();
        $response['courses'] = \App\Models\Course::OrderBy('id', 'Desc')->get();
        $response['classes'] = \App\Models\Classe::OrderBy('id', 'Desc')->get();
        $response['grades'] = \App\Models\Grade::OrderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Lista de Alunos');
        return view('admin.student.list.index', $response)->with('success', '1');
    }

    public function create()
    {
        $response['total'] = Student::withTrashed()->count();
        $response['courses'] = Course::OrderBy('id', 'Desc')->get();
        $response['classes'] = Classe::OrderBy('id', 'Desc')->get();
        $response['grades'] = Grade::OrderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Inscrição e Matrícula de Aluno');
        return view('admin.student.create.index', $response);
    }

    public function store(Request $request)
    {
        $data = $this->validate(
            $request,
            [
                // Dados do aluno
                'name' => 'required',
                'nBi' => 'required|unique:students,nBi,' . $request->id . ',id',
                'contact' => 'required',
                'contactAlter' => 'required',
                'email' => 'required|email',
                'dateBirth' => 'required',
                'father' => 'required',
                'mother' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                
                // Dados da matrícula
                'fk_courses_id' => 'required',
                'fk_classes_id' => 'required',
                'fk_grades_id' => 'required',
                'season' => 'required',
                'details' => 'required',
                'registration_status' => 'required',
            ],
            [
                // Mensagens de erro - aluno
                'name.required' => 'O campo Nome deve ser preenchido',
                'nBi.required' => 'O campo BI deve ser preenchido',
                'nBi.unique' => 'Este BI já está cadastrado',
                'contact.required' => 'O campo Contacto deve ser preenchido',
                'contactAlter.required' => 'O campo Contacto Alternativo deve ser preenchido',
                'email.required' => 'O campo E-mail deve ser preenchido',
                'email.email' => 'O E-mail é invalido',
                'dateBirth.required' => 'O campo Data de Nascimento deve ser preenchido',
                'father.required' => 'O campo Nome do Pai deve ser preenchido',
                'mother.required' => 'O campo Nome da Mâe deve ser selecionado',
                'image.image' => 'O arquivo deve ser uma imagem',
                'image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif',
                'image.max' => 'A imagem não pode ter mais de 2MB',
                
                // Mensagens de erro - matrícula
                'fk_courses_id.required' => 'O campo Curso deve ser selecionado',
                'fk_classes_id.required' => 'O campo Turma deve ser selecionado',
                'fk_grades_id.required' => 'O campo Classe deve ser selecionado',
                'season.required' => 'O campo Turno deve ser selecionado',
                'details.required' => 'O campo Detalhe deve ser preenchido',
                'registration_status.required' => 'O status da matrícula deve ser selecionado',
            ]
        );

        $data['schoolyear'] = SchoolYearHelper::getCurrentSchoolYearName();
        $data['fk_schoolyears_id'] = SchoolYearHelper::getCurrentSchoolYearId();

        $Exists = Student::where('name', $data['name'])->exists();

        if ($Exists) {
            return redirect()->back()->with('students_exist', '1');
        }

        // Processar a imagem (se fornecida)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                $data['image'] = $this->studentPhotoService->store($request->file('image'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao processar a imagem: ' . $e->getMessage());
            }
        }
        
        // Criar o aluno (sem nProcess ainda)
        $studentData = [
            'name' => $data['name'],
            'nProcess' => null, // será atribuído após a matrícula
            'nBi' => $data['nBi'],
            'contact' => $data['contact'],
            'contactAlter' => $data['contactAlter'],
            'email' => $data['email'],
            'dateBirth' => $data['dateBirth'],
            'schoolyear' => $data['schoolyear'],
            'father' => $data['father'],
            'mother' => $data['mother'],
            'image' => $data['image'] ?? null,
        ];
        
        $student = Student::create($studentData);
        
        // Criar a matrícula
        $registrationData = [
            'fk_students_id' => $student->id,
            'fk_courses_id' => $data['fk_courses_id'],
            'fk_classes_id' => $data['fk_classes_id'],
            'fk_grades_id' => $data['fk_grades_id'],
            'fk_schoolyears_id' => $data['fk_schoolyears_id'],
            'season' => $data['season'],
            'details' => $data['details'],
            'status' => $data['registration_status'],
        ];
        
        $registration = CourseClasseGradeStudentSchoolyear::create($registrationData);

        // ============================================================
        // Atribuir Número de Processo automático (A1, B2, ...)
        // ============================================================
        // 1. Buscar ou criar o grupo (letra) para Curso + Classe
        $group = CourseGradeGroup::firstOrCreate(
            [
                'fk_courses_id' => $data['fk_courses_id'],
                'fk_grades_id'  => $data['fk_grades_id'],
            ],
            [
                'letter' => CourseGradeGroup::nextAvailableLetter(),
            ]
        );

        // 2. Contar quantos alunos já têm esse prefixo de letra
        $count = Student::where('nProcess', 'like', $group->letter . '%')
            ->whereNotNull('nProcess')
            ->count();

        // 3. Atribuir o número único ao aluno
        $student->update(['nProcess' => $group->letter . ($count + 1)]);
        // ============================================================

        // Enviar notificação
        $this->notificationService->notifyNewStudent($student);

        $this->Logger->log('info', 'Cadastrou Aluno e Matrícula - Nº Processo: ' . $student->nProcess);
        return redirect()
            ->route('admin.student.show', $student->id)
            ->with('create', '1')
            ->with('student_card_url', route('pdf.studentCard', $registration->id));
    }

    public function show($id)
    {
        try {
            $response['student'] = Student::findOrFail($id);
            $response['registration'] = CourseClasseGradeStudentSchoolyear::with(['courses', 'classes', 'grades', 'schoolyears'])
                ->where('fk_students_id', $id)
                ->latest('id')
                ->first();
            
            $this->Logger->log('info', 'Visualizou o Aluno');
            return view('admin.student.details.index', $response);
        } catch (\Exception $e) {
            LoggerFacade::error('Erro ao mostrar aluno: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar os detalhes do aluno.');
        }
    }

    public function edit($id)
    {
        $response['student'] = Student::find($id);
        $response['registration'] = CourseClasseGradeStudentSchoolyear::where('fk_students_id', $id)->first();
        $response['courses'] = Course::OrderBy('id', 'Desc')->get();
        $response['classes'] = Classe::OrderBy('id', 'Desc')->get();
        $response['grades'] = Grade::OrderBy('id', 'Desc')->get();
        $this->Logger->log('info', 'Editar Aluno');
        return view('admin.student.edit.index', $response);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate(
            $request,
            [
                // Dados do aluno
                'name' => 'required',
                'nProcess' => 'required',
                'nBi' => 'required|unique:students,nBi,' . $id . ',id',
                'contact' => 'required',
                'contactAlter' => 'required',
                'email' => 'required|email',
                'dateBirth' => 'required',
                'father' => 'required',
                'mother' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                
                // Dados da matrícula (se existir)
                'fk_courses_id' => 'nullable',
                'fk_classes_id' => 'nullable',
                'fk_grades_id' => 'nullable',
                'season' => 'nullable',
                'details' => 'nullable',
                'registration_status' => 'nullable',
                'registration_id' => 'nullable',
            ],
            [
                // Mensagens de erro - aluno
                'name.required' => 'O campo Nome deve ser preenchido',
                'nProcess.required' => 'O campo Nº de Processo deve ser preenchido',
                'nBi.required' => 'O campo BI deve ser preenchido',
                'nBi.unique' => 'Este BI já está cadastrado',
                'contact.required' => 'O campo Contacto deve ser preenchido',
                'contactAlter.required' => 'O campo Contacto Alternativo deve ser preenchido',
                'email.required' => 'O campo E-mail deve ser preenchido',
                'email.email' => 'O E-mail é invalido',
                'dateBirth.required' => 'O campo Data de Nascimento deve ser preenchido',
                'father.required' => 'O campo Nome do Pai deve ser preenchido',
                'mother.required' => 'O campo Nome da Mâe deve ser selecionado',
                'image.image' => 'O arquivo deve ser uma imagem',
                'image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif',
                'image.max' => 'A imagem não pode ter mais de 2MB',
            ]
        );

        $student = Student::find($id);

        // Processar a imagem (se fornecida)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                $data['image'] = $this->studentPhotoService->store($request->file('image'), $student->image);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao processar a imagem: ' . $e->getMessage());
            }
        } else {
            // Manter a imagem atual
            $data['image'] = $student->image;
        }

        // Ano lectivo automático (preserva o original na edição)
        $data['schoolyear'] = $student->schoolyear ?? SchoolYearHelper::getCurrentSchoolYearName();
        $data['fk_schoolyears_id'] = $request->input('fk_schoolyears_id', SchoolYearHelper::getCurrentSchoolYearId());

        // Atualizar dados do aluno
        $studentData = [
            'name' => $data['name'],
            'nProcess' => $data['nProcess'],
            'nBi' => $data['nBi'],
            'contact' => $data['contact'],
            'contactAlter' => $data['contactAlter'],
            'email' => $data['email'],
            'dateBirth' => $data['dateBirth'],
            'schoolyear' => $data['schoolyear'],
            'father' => $data['father'],
            'mother' => $data['mother'],
            'image' => $data['image'],
        ];
        
        $student->update($studentData);
        
        // Atualizar ou criar a matrícula se os dados foram fornecidos
        if ($request->filled('fk_courses_id') && $request->filled('fk_classes_id')) {
            $registrationData = [
                'fk_students_id' => $student->id,
                'fk_courses_id' => $data['fk_courses_id'],
                'fk_classes_id' => $data['fk_classes_id'],
                'fk_grades_id' => $data['fk_grades_id'],
                'fk_schoolyears_id' => $data['fk_schoolyears_id'],
                'season' => $data['season'],
                'details' => $data['details'],
                'status' => $data['registration_status'],
            ];
            
            if ($request->filled('registration_id')) {
                // Atualizar matrícula existente
                $registration = CourseClasseGradeStudentSchoolyear::find($data['registration_id']);
                if ($registration) {
                    $registration->update($registrationData);
                }
            } else {
                // Criar nova matrícula
                CourseClasseGradeStudentSchoolyear::create($registrationData);
            }
        }

        $this->Logger->log('info', 'Atualizou os dados do Aluno');
        return redirect()->route('admin.student.show', $id)->with('edit', '1');
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        
        // Primeiro excluir registros relacionados
        CourseClasseGradeStudentSchoolyear::where('fk_students_id', $id)->delete();
        
        // Depois excluir o aluno
        $student->delete();
        
        $this->Logger->log('info', 'Removeu o Aluno');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'O Aluno foi excluído.']);
        } else {
            return redirect()->back()->with('destroy', '1');
        }
    }

    /**
     * Exibe o formulário para upload de foto do aluno
     */
    public function photoForm($id)
    {
        $response['student'] = Student::find($id);
        
        if (!$response['student']) {
            return redirect()->back()->with('error', 'Aluno não encontrado');
        }
        
        $this->Logger->log('info', 'Acessou formulário de foto do aluno');
        return view('admin.student.photo.index', $response);
    }

    /**
     * Processa o upload de foto do aluno
     */
    public function uploadPhoto(Request $request, $id)
    {
        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif',
            'image.max' => 'A imagem não pode ter mais de 2MB',
        ]);
        
        $student = Student::find($id);
        
        if (!$student) {
            return redirect()->back()->with('error', 'Aluno não encontrado');
        }
        
        try {
            // Se não houver imagem, apenas retornar
            if (!$request->hasFile('image')) {
                return redirect()->route('admin.student.show', $id)->with('info', 'Nenhuma foto selecionada.');
            }
            
            $imageFile = $request->file('image');
            
            // Verificar se o arquivo é válido
            if (!$imageFile->isValid()) {
                return redirect()->back()->with('error', 'Arquivo inválido. Tente novamente.');
            }

            $imageName = $this->studentPhotoService->store($imageFile, $student->image);
            $student->update(['image' => $imageName]);

            $this->Logger->log('info', 'Enviou foto do aluno');
            return redirect()->route('admin.student.show', $id)->with('edit', '1');

            // Processar a imagem - redimensionar para tamanho adequado mantendo o aspecto
            if (extension_loaded('gd')) {
                $image = ImageFactory::make($imageFile);
                
                // Redimensionar para 300px na largura mantendo a proporção (sem distorcer)
                $image->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Gerar um nome único para o arquivo
                $imageName = time() . '_' . uniqid() . '.jpg';
                
                // Caminho para salvar a imagem
                $path = storage_path('app/public/images');
                
                // Garantir que o diretório existe
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                
                // Salvar a imagem com qualidade 80% (boa qualidade, arquivo menor)
                $image->save($path . '/' . $imageName, 80);
            } else {
                // Fallback caso GD não esteja disponível
                $fileExt = $imageFile->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $fileExt;
                $path = storage_path('app/public/images');
                
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                
                $imageFile->move($path, $imageName);
            }
            
            // Excluir a imagem antiga, se existir
            if ($student->image && file_exists($path . '/' . $student->image)) {
                @unlink($path . '/' . $student->image);
            }
            
            // Atualizar o registro do aluno com a nova imagem
            $student->update(['image' => $imageName]);
            
            $this->Logger->log('info', 'Enviou foto do aluno');
            return redirect()->route('admin.student.show', $id)->with('edit', '1');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar a imagem: ' . $e->getMessage());
        }
    }

    public function image($filename)
    {
        $filename = basename($filename);
        $path = storage_path('app/public/images/' . $filename);

        if (!file_exists($path)) {
            $path = public_path('dashboard/assets/img/undraw_profile.svg');
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
