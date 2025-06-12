@extends('layouts.master')

@section('css')
@endsection

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
    <div class="row" id="attendanceLogJs">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" id="awsUrl" value="{{ env('AWS_URL') }}">
                    <input type="hidden" id="searchData">
                    <input type="hidden" id="UserToken" value="{{ session('token') }}">
                    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">
                    <form id="filterForm" action="/attendance/search" method="POST">
                        @csrf
                        <input type="hidden" name="min" value="{{ $min ?? '' }}">
                        <input type="hidden" name="max" value="{{ $max ?? '' }}">
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <label for="min">Start Date:</label>
                                <input type="date" id="min" value="{{ $min ?? '' }}" name="min"
                                    class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="max">End Date:</label>
                                <input type="date" id="max" value="{{ $max ?? '' }}" name="max"
                                    class="form-control">
                            </div>
                            <input type="hidden" value="{{ $ontimeCount ?? 0 }}" id="ontimeCount">
                            <input type="hidden" value="{{ $lateCount ?? 0 }}" id="lateCount">
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
                                                    value="{{ $department->id }}">{{ $department->name }}</option>
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
                            @endif

                            <div class="col-md-4 mt-2">
                                <br>
                                <button id="applyFilter" class="btn btn-primary">Search</button>

                            </div>
                        </div>


                    </form>
                    <input type="hidden" value="{{ $totalOntime ?? 0 }}" id="totalOntime">
                    <input type="hidden" value="{{ $totalLate ?? 0 }}" id="totalLate">
                    <input type="hidden" value="{{ $totalEmployee ?? 0 }}" id="totalEmployee">
                    <input type="hidden" value="{{ $departmentName ?? 0 }}" id="departmentName">
                    <input type="hidden" value="{{ $locationName ?? 0 }}" id="locationName">
                    <input type="hidden" value="{{ Auth::user()->company->address ?? '-' }}" id="companyAdd">
                    <input type="hidden" value="{{ Auth::user()->company->company_name ?? '-' }}" id="companyName">


                    <table id="attendanceTable" class="table table-striped table-bordered dt-responsive nowrap">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary" id="pdf">PDF</button>
                                <button class="btn btn-success" id="csv">CSV</button>
                            </div>
                            <br><br>
                        </div>
                        {{-- <table id="attendanceTable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;"> --}}
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Department</th>
                                <th>Name</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Status</th>
                                <th>Attachment</th>
                                <th>lat</th>
                                <th>lng</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($attendances && $attendances->count() > 0)
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->attendance_date ?? '-' }}</td>
                                        <td>{{ $attendance->location->location_name ?? '-' }}</td>
                                        <td>{{ $attendance->user->department->name ?? '-' }}</td>
                                        <td>{{ $attendance->user->name ?? '-' }}</td>
                                        <td>{{ $attendance->attendance_timein ?? '-' }}</td>
                                        <td>{{ $attendance->attendance_timeout ?? '-' }}</td>
                                        @if ($attendance->attendance_timein <= '08:30:00')
                                            <td class="text-center bg-success text-white">On Time</td>
                                        @else
                                            <td class="text-center bg-danger text-white">Late</td>
                                        @endif

                                        <td class="text-center">
                                            @if ($attendance->attachment)
                                                <a target="_blank"
                                                    href="{{env('AWS_URL'). $attendance->attachment ?? '' }}"> <i
                                                        class="ti-eye"></i> </a>
                                            @endif
                                        <td>{{ $attendance->lat ?? '-' }}</td>
                                        <td>{{ $attendance->lng ?? '-' }}</td>
                                        </td>
                                        <td><button class="btn btn-info"data-id="{{ $attendance->id }}"
                                                id="outsideBtn">Activity Outside
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if ($attendances && $attendances->count() > 0)
                        <div class="float-left">
                            Showing {{ $attendances->FirstItem() }} - {{ $attendances->LastItem() }} of
                            {{ $attendances->total() }}
                        </div>
                        <br>
                        <div class="float-right">
                            {{ $attendances->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    @include('includes.outsideModal')
@endsection
