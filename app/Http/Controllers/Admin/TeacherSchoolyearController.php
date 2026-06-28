<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherSchoolyearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacherSchoolyears = DB::table('docentes_anos_lectivos')
            ->join('docentes', 'docentes_anos_lectivos.teacher_id', '=', 'docentes.id')
            ->join('anos_lectivos', 'docentes_anos_lectivos.schoolyear_id', '=', 'anos_lectivos.id')
            ->select('docentes_anos_lectivos.*', 'docentes.name as teacher_name', 'anos_lectivos.name as schoolyear_name')
            ->paginate(15);
            
        return view('admin.teacherSchoolyear.index', compact('teacherSchoolyears'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = DB::table('docentes')->get();
        $schoolyears = DB::table('anos_lectivos')->get();
        
        return view('admin.teacherSchoolyear.create', compact('teachers', 'schoolyears'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:docentes,id',
            'schoolyear_id' => 'required|exists:anos_lectivos,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Check if this combination already exists
        $exists = DB::table('docentes_anos_lectivos')
            ->where('teacher_id', $request->teacher_id)
            ->where('schoolyear_id', $request->schoolyear_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Esta combinação de professor e ano letivo já existe!');
        }

        DB::table('docentes_anos_lectivos')->insert([
            'teacher_id' => $request->teacher_id,
            'schoolyear_id' => $request->schoolyear_id,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.teacherSchoolyear.list')->with('success', 'Professor-Ano Letivo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacherSchoolyear = DB::table('docentes_anos_lectivos')
            ->join('docentes', 'docentes_anos_lectivos.teacher_id', '=', 'docentes.id')
            ->join('anos_lectivos', 'docentes_anos_lectivos.schoolyear_id', '=', 'anos_lectivos.id')
            ->select('docentes_anos_lectivos.*', 'docentes.name as teacher_name', 'anos_lectivos.name as schoolyear_name')
            ->where('docentes_anos_lectivos.id', $id)
            ->first();
        
        if (!$teacherSchoolyear) {
            return redirect()->route('admin.teacherSchoolyear.list')->with('error', 'Professor-Ano Letivo não encontrado!');
        }

        return view('admin.teacherSchoolyear.show', compact('teacherSchoolyear'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacherSchoolyear = DB::table('docentes_anos_lectivos')->where('id', $id)->first();
        
        if (!$teacherSchoolyear) {
            return redirect()->route('admin.teacherSchoolyear.list')->with('error', 'Professor-Ano Letivo não encontrado!');
        }

        $teachers = DB::table('docentes')->get();
        $schoolyears = DB::table('anos_lectivos')->get();

        return view('admin.teacherSchoolyear.edit', compact('teacherSchoolyear', 'teachers', 'schoolyears'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:docentes,id',
            'schoolyear_id' => 'required|exists:anos_lectivos,id',
            'status' => 'required|in:active,inactive',
        ]);

        $teacherSchoolyear = DB::table('docentes_anos_lectivos')->where('id', $id)->first();
        
        if (!$teacherSchoolyear) {
            return redirect()->route('admin.teacherSchoolyear.list')->with('error', 'Professor-Ano Letivo não encontrado!');
        }

        // Check if this combination already exists (excluding current record)
        $exists = DB::table('docentes_anos_lectivos')
            ->where('teacher_id', $request->teacher_id)
            ->where('schoolyear_id', $request->schoolyear_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Esta combinação de professor e ano letivo já existe!');
        }

        DB::table('docentes_anos_lectivos')->where('id', $id)->update([
            'teacher_id' => $request->teacher_id,
            'schoolyear_id' => $request->schoolyear_id,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.teacherSchoolyear.list')->with('success', 'Professor-Ano Letivo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacherSchoolyear = DB::table('docentes_anos_lectivos')->where('id', $id)->first();
        
        if (!$teacherSchoolyear) {
            return redirect()->route('admin.teacherSchoolyear.list')->with('error', 'Professor-Ano Letivo não encontrado!');
        }

        DB::table('docentes_anos_lectivos')->where('id', $id)->delete();

        return redirect()->route('admin.teacherSchoolyear.list')->with('success', 'Professor-Ano Letivo excluído com sucesso!');
    }
} 
