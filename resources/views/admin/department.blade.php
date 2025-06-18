@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Department</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Department</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Department List</a></li>

        </ol>
    </div>
@endsection
@section('button')
    <a id="addBtn" href="#" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i
            class="mdi mdi-plus mr-2"></i>Add</a>
@endsection

@section('content')
    <div class="row" id="departmentJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a id="addBtn" href="#" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i
                                class="mdi mdi-plus mr-2"></i>Add</a>
                    </div>
                    <br>
                    <br>
                    <table id="departmentTable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th data-priority="1">Department</th>
                                <th data-priority="1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <a href="#" id="editBtn" data-id="{{ $department->id }}"
                                             class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                        <a href="#" id="deleteBtn" data-id="{{ $department->id }}"
                                            class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i>
                                            Delete</a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('includes.edit_delete_department')
@endsection
