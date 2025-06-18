@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Employees</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Employees</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Employees List</a></li>

        </ol>
    </div>
@endsection

@section('button')
@endsection

@section('content')
    <div class="row" id="employeeJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/employee/search" method="POST">
                        @csrf
                        <div class="row">
                            @if (!in_array(Auth::user()->role->role_id, ['4', '1']))
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="departmentFilter" class="form-label">Department:</label>
                                        <select id="departmentFilter" name="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @php
                                                $departments = getAllDepartment();
                                            @endphp
                                            @foreach ($departments as $department)
                                                <option {{ $department_id == $department->id ? 'selected' : '' }}
                                                    value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="locationFilter" class="form-label">Location:</label>
                                    <select id="locationFilter" name="location_id" class="form-control">
                                        <option value="">Select Location</option>
                                        @php
                                            $locations = getAllLocation();
                                        @endphp
                                        @foreach ($locations as $location)
                                            <option {{ $location_id == $location->id ? 'selected' : '' }}
                                                value="{{ $location->id }}">{{ $location->location_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="float-right">
                        @if (in_array(Auth::user()->role->role_id, [2, 3]))
                            <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i
                                    class="mdi mdi-plus mr-2"></i>Add</a>
                            <a id="excelBtn" href="#" data-toggle="modal" class="btn btn-success btn-sm btn-flat"><i
                                    class="mdi mdi-plus mr-2"></i>Upload By Excel</a>
                        @endif
                    </div>
                    <br>
                    <br>
                    <input id="totalEmployee" value="{{ $totalEmployee ?? 0 }}" type="hidden">
                    <table id="employeeTable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th data-priority="1">Employee ID</th>
                                <th data-priority="2">Name</th>
                                <th data-priority="3">Position</th>
                                <th data-priority="3">Department</th>
                                <th data-priority="3">Schedule</th>
                                <th data-priority="4">Location</th>
                                <th data-priority="4">Email</th>
                                <th data-priority="6">Member Since</th>
                                <th data-priority="7">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id ?? '-' }}</td>
                                    <td>{{ $employee->name ?? '-' }}</td>
                                    <td>{{ $employee->position ?? '-' }}</td>
                                    <td>{{ $employee->department->name ?? '-' }}</td>
                                    <td>{{ $employee->schedule->time_in ?? '-' }} -
                                        {{ $employee->schedule->time_out ?? '-' }}</td>
                                    <td>{{ $employee->location->location_name ?? '-' }}</td>
                                    <td>{{ $employee->email ?? '-' }}</td>
                                    <td>{{ $employee->created_at ?? '-' }}</td>
                                    <td>
                                        @if (in_array(Auth::user()->role->role_id, [2, 3]))
                                            <a id="editBtn" data-id="{{ $employee->id }}" data-toggle="modal"
                                                class="btn btn-success btn-sm edit btn-flat text-white"><i
                                                    class='fa fa-edit'></i> Edit</a>
                                            <a id="deleteBtn" data-id="{{ $employee->id }}" data-toggle="modal"
                                                class="btn btn-danger btn-sm delete btn-flat text-white"><i
                                                    class='fa fa-trash'></i>
                                                Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('includes.add_employee')
@endsection


@section('script')
    <!-- Responsive-table-->
@endsection
