@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Employee History</a></li>


        </ol>
    </div>
@endsection

@section('content')
    <div class="row" id="employeeHistoryListJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/historySearch" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <label for="min">Employee Name :</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Please Select</option>
                                    @php
                                        $users = getAllUsers();
                                    @endphp
                                    @foreach ($users as $user)
                                        <option {{ $user_id == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name ?? '-' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="department_id">Filter by Department:</label>
                                <select id="department_id" name="department_id" class="form-control">
                                    <option value="" disabled selected>Select Department</option>
                                    @php
                                        $departments = getAllDepartment();
                                    @endphp
                                    @foreach ($departments as $department)
                                        <option {{ $department_id == $department->id ? 'selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary mt-4">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="historyTable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th data-priority="3">Name</th>
                                        <th data-priority="3">Department</th>
                                        <th data-priority="6">Location</th>
                                        <th data-priority="7">Schedule</th>
                                        <th data-priority="1">Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($historys as $history)
                                        <tr>
                                            <td>{{ $history->user->name ?? '-' }}</td>
                                            <td>{{ $history->department->name ?? '-' }}</td>
                                            <td>{{ $history->location->location_name ?? '-' }}
                                            <td>{{ $history->schedule->time_in ?? '-' }} - {{ $history->schedule->time_out ?? '-' }}</td>
                                            <td>{{ $history->created_at ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('scripts')
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables and Buttons scripts -->
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
@endsection
