@extends('layouts.master')

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>


        </ol>
    </div>
@endsection

@section('content')
    <div class="row" id="timeoffListJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/timeoff/search" method="POST">
                        @csrf
                        <div class="row">
                            @if (!in_array(Auth::user()->role->role_id, [4, 1]))
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="departmentFilter" class="form-label">Department:</label>
                                        <select id="departmentFilter" name="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @php
                                                $departments = getAllDepartment();
                                            @endphp
                                            @foreach ($departments as $department)
                                                <option {{ $department->id == $department_id ? 'selected' : '' }}
                                                    value="{{ $department->id }}">
                                                    {{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (!in_array(Auth::user()->role->role_id, [1]))
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="locationFilter" class="form-label">Location:</label>
                                        <select id="locationFilter" name="location_id" class="form-control">
                                            <option value="">Select Location</option>
                                            @php
                                                $locations = getAllLocation();
                                            @endphp
                                            @foreach ($locations as $location)
                                                <option {{ $location->id == $location_id ? 'selected' : '' }}
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
                            @endif
                        </div>
                    </form>
                    <table id="timeofTable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Location</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Remarks</th>
                                <th>Lat</th>
                                <th>lng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($timeoffs)
                                @foreach ($timeoffs as $timeoff)
                                    <tr>
                                        <td>{{ $timeoff->created_at ?? '-' }}</td>
                                        <td>{{ $timeoff->user->name ?? '-' }}</td>
                                        <td>{{ $timeoff->user->department->name ?? '-' }}</td>
                                        <td>{{ $timeoff->user->location->location_name ?? '-' }}</td>
                                        <td>{{ $timeoff->timeoff_start ?? '-' }}</td>
                                        <td>{{ $timeoff->timeoff_end ?? '-' }}</td>
                                        <td>{{ $timeoff->remarks ?? '-' }}</td>
                                        <td>{{ $timeoff->lat ?? '-' }}</td>
                                        <td>{{ $timeoff->lng ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
