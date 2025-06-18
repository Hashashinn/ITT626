@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Location</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Location</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Location List</a></li>

        </ol>
    </div>
@endsection
@section('button')
    {{-- <button id="trackLocationBtn" class="btn btn-primary btn-sm btn-flat">Track Location</button> --}}
@endsection

@section('content')
    <div class="row" id="locationJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a id="addBtn" href="#" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>
                        {{-- <a id="excelBtn" href="#" data-toggle="modal" class="btn btn-success btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Upload By Excel</a> --}}
                    </div>
                    <br>
                    <br>
                    <table id="locationTable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th data-priority="1">Id</th>
                                <th data-priority="1">Location Name</th>
                                <th data-priority="1">Latitude</th>
                                <th data-priority="1">Longitude</th>
                                <th data-priority="1">Status</th>
                                <th data-priority="1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr>

                                    <td>{{ $location->id }}</td>
                                    <td>{{ $location->location_name }}</td>
                                    <td>{{ $location->lat }}</td>
                                    <td>{{ $location->lng }}</td>
                                    <td>{{ $location->status }}</td>
                                    <td>
                                        <a href="#" id="editBtn" data-id="{{ $location->id }}" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                        <a href="#" id="deleteBtn" data-id="{{ $location->id }}" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i>
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
    @include('includes.edit_delete_location')
@endsection

@section('scripts')
@endsection
