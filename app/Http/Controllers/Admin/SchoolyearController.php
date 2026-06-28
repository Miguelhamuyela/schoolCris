<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schoolyear;
use App\Classes\Logger;

class SchoolyearController extends Controller
{
    private $Logger;

    public function __construct()
    {
        $this->Logger = new Logger();
    }

    public function index()
    {
        $response['schoolyears'] = Schoolyear::OrderBy('id','Desc')->get();

        $this->Logger->log('info', 'Lista de Anos Lectivos');
        return view('admin.schoolyear.list.index', $response)->with('success', '1');
    }

    public function show($id)
    {
        $response['schoolyear'] = Schoolyear::find($id);

        $this->Logger->log('info', 'Detalhes do Ano Lectivo');
        return view('admin.schoolyear.details.index', $response);
    }
}
