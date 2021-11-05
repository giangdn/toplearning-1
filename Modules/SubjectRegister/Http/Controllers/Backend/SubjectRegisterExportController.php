<?php

namespace Modules\SubjectRegister\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SubjectRegister\Entities\SubjectRegister;
use App\Models\Categories\Unit;
use App\Exports\SubjectRegisterExport;

class SubjectRegisterExportController extends Controller
{
    public function export(Request $request)
    {
        $search = $request->export_search;
        $unit = $request->export_unit;
        return (new SubjectRegisterExport($search, $unit))->download('danh_sach_chuyen_de_dang_ky_'. date('d_m_Y') .'.xlsx');
    }
}
