<?php

use Illuminate\Support\Facades\Route;

/** Helper para rotas CRUD padronizadas */
if (!function_exists('crud')) {
    function crud($prefix, $controller) {
        $name = str_replace('admin/', 'admin.', $prefix);
        Route::get("/$prefix/list",    ['as' => "$name.list",    'uses' => "$controller@index"]);
        Route::get("/$prefix/create",  ['as' => "$name.create",  'uses' => "$controller@create"]);
        Route::post("/$prefix",        ['as' => "$name.store",   'uses' => "$controller@store"]);
        Route::get("/$prefix/show/{id}",   ['as' => "$name.show",    'uses' => "$controller@show"]);
        Route::get("/$prefix/edit/{id}",   ['as' => "$name.edit",    'uses' => "$controller@edit"]);
        Route::post("/$prefix/update/{id}", ['as' => "$name.update",  'uses' => "$controller@update"]);
        Route::delete("/$prefix/destroy/{id}", ['as' => "$name.destroy", 'uses' => "$controller@destroy"]);
    }
}

/** ========================================================================
 *  Rotas Autenticadas (comuns a todos os utilizadores logados)
 *  ======================================================================== */
Route::middleware('auth')->group(function () {

    // Sistema
    Route::get('/admin/home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
    Route::get('/admin/profile/{id}', ['as' => 'admin.profile', 'uses' => 'Auth\ProfileController@index']);
    Route::post('/admin/profile/{id}', ['as' => 'admin.profile.dados', 'uses' => 'Auth\ProfileController@dados']);
    Route::post('/admin/profile/password/{id}', ['as' => 'admin.profile.password', 'uses' => 'Auth\ProfileController@password']);
    Route::delete('/admin/profile/{id}', ['as' => 'admin.profile.destroy', 'uses' => 'Admin\UserController@destroy']);

    Route::get('admin/pagamentos/fatura/{code}', ['as' => 'admin.payments.invoice', 'uses' => 'Admin\PDFController@print']);

    /** ========================================================================
     *  Administradores (role: admin)
     *  ======================================================================== */
    Route::middleware(['admin'])->group(function () {
        crud('admin/user', 'Admin\UserController');
        crud('admin/transport', 'Admin\TransportController');
    });

    /** ========================================================================
     *  Registadores (role: registrador)
     *  ======================================================================== */
    Route::middleware(['registrador'])->group(function () {

        // --- Alunos ---
        crud('admin/student', 'Admin\StudentController');
        Route::get('/admin/student/photo/{id}', ['as' => 'admin.student.photo', 'uses' => 'Admin\StudentController@photoForm']);
        Route::post('/admin/student/photo/{id}', ['as' => 'admin.student.photo.upload', 'uses' => 'Admin\StudentController@uploadPhoto']);
        Route::get('/admin/student/image/{filename}', ['as' => 'admin.student.image', 'uses' => 'Admin\StudentController@image'])
            ->where('filename', '.*');

        // --- Docentes ---
        crud('admin/teacher', 'Admin\TeacherController');

        // --- Turmas ---
        crud('admin/classe', 'Admin\ClasseController');

        // --- Cursos ---
        crud('admin/course', 'Admin\CourseController');

        // --- Exames ---
        crud('admin/exam', 'Admin\ExamController');

        // --- Niveis ---
        crud('admin/grade', 'Admin\GradeController');

        // --- Anos Lectivos (auto-criados pelo sistema) ---
        Route::get('/admin/schoolyear/list',    ['as' => 'admin.schoolyear.list', 'uses' => 'Admin\SchoolyearController@index']);
        Route::get('/admin/schoolyear/show/{id}', ['as' => 'admin.schoolyear.show',  'uses' => 'Admin\SchoolyearController@show']);

        // --- Regras ---
        crud('admin/rule', 'Admin\RuleController');

        // --- Matriculas ---
        crud('admin/courseClasseGradeStudentSchoolyear', 'Admin\CourseClasseGradeStudentSchoolyearController');
        Route::get('/get/student/{nProcess}', ['as' => 'get.student', 'uses' => 'Admin\CourseClasseGradeStudentSchoolyearController@getStudent']);
        Route::get('/get/registration/{nProcess}', ['as' => 'get.registration', 'uses' => 'Admin\CourseClasseGradeStudentSchoolyearController@getRegistration']);
        Route::get('/get/nprocess/{student}', ['as' => 'get.nProcess', 'uses' => 'Admin\CourseClasseGradeStudentSchoolyearController@getNProcess']);

        // --- Contratos ---
        crud('admin/teacherSubjectClasseRuleSchoolyear', 'Admin\TeacherSubjectClasseRuleSchoolyearController');

        // --- Docentes-AnosLectivos ---
        crud('admin/teacherSchoolyear', 'Admin\TeacherSchoolyearController');

        // --- Curso-Disciplina-Nivel ---
        crud('admin/courseSubjectGrade', 'Admin\CourseSubjectGradeController');

        // --- Propinas ---
        crud('admin/tuition', 'Admin\TuitionController');

        // --- Pagamentos Transportes ---
        crud('admin/transportPay', 'Admin\TransportPayController');

        // --- Pagamentos Uniformes ---
        crud('admin/uniformPay', 'Admin\UniformPayController');

        // --- Mensagens Internas ---
        Route::get('/admin/messages', ['as' => 'admin.messages.index', 'uses' => 'Admin\MessageController@index']);
        Route::get('/admin/messages/sent', ['as' => 'admin.messages.sent', 'uses' => 'Admin\MessageController@sent']);
        Route::get('/admin/messages/create', ['as' => 'admin.messages.create', 'uses' => 'Admin\MessageController@create']);
        Route::post('/admin/messages', ['as' => 'admin.messages.store', 'uses' => 'Admin\MessageController@store']);
        Route::get('/admin/messages/{id}', ['as' => 'admin.messages.show', 'uses' => 'Admin\MessageController@show']);
        Route::delete('/admin/messages/{id}', ['as' => 'admin.messages.destroy', 'uses' => 'Admin\MessageController@destroy']);

        // --- Renovacao de Matricula ---
        Route::get('/admin/renewal', ['as' => 'admin.renewal.index', 'uses' => 'Admin\RenewalController@index']);
        Route::post('/admin/renewal/process', ['as' => 'admin.renewal.process', 'uses' => 'Admin\RenewalController@process']);
        Route::get('/admin/renewal/confirmed', ['as' => 'admin.renewal.confirmed', 'uses' => 'Admin\RenewalController@confirmedList']);

        // --- PDF / Relatorios ---
        Route::prefix('pdf')->name('pdf.')->group(function () {
            Route::get('/registration/', ['as' => 'registration', 'uses' => 'Admin\PDFController@registration']);
            Route::get('/contract/', ['as' => 'contract', 'uses' => 'Admin\PDFController@contract']);
            Route::get('/student', ['as' => 'student', 'uses' => 'Admin\PDFController@student']);
            Route::get('/tuition', ['as' => 'tuition', 'uses' => 'Admin\PDFController@tuition']);
            Route::get('/tuitionStudent/{id}', ['as' => 'tuitionStudent', 'uses' => 'Admin\PDFController@tuitionStudent']);
            Route::get('/uniformPayStudent/{id}', ['as' => 'uniformPayStudent', 'uses' => 'Admin\PDFController@uniformPayStudent']);
            Route::get('/transportPay', ['as' => 'transportPay', 'uses' => 'Admin\PDFController@transportPay']);
            Route::get('/transportPayStudent/{id}', ['as' => 'transportPayStudent', 'uses' => 'Admin\PDFController@transportPayStudent']);
            Route::get('/transportDocumentation/{id}', ['as' => 'transportDocumentation', 'uses' => 'Admin\PDFController@transportDocumentation']);
            Route::get('/studentCard/{id}', ['as' => 'studentCard', 'uses' => 'Admin\PDFController@studentCard']);
            Route::get('/studentPayments', ['as' => 'studentPayments', 'uses' => 'Admin\PDFController@studentPaymentsReport']);
        });
    });
});
