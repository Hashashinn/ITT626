<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\DepartmentServices;
use Illuminate\Http\Request;


class DepartmentController extends Controller
{

    private $ds;

    public function __construct(DepartmentServices $ds)
    {
        $this->ds = $ds;
    }

    public function index()
    {
        $data['departments'] = $this->ds->getAllDepartment();

        return view('admin.department', $data);
    }

    public function create()
    {
        return view('departments.create');
    }

    public function departmentStore(Request $r)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentStore($r);

        return response()->json($result);
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function departmentUpdate(Request $r, $id)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentUpdate($r, $id);

        return response()->json($result);
    }

    public function departmentDelete(Request $r, $id)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentDelete($r, $id);

        return response()->json($result);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function getDepartmentById($id = '')
    {
        $ds = new DepartmentServices;

        $result = $ds->getDepartmentById($id);

        return response()->json($result);
    }
}