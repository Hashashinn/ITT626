@extends('layouts.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Capture Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Capture Attendance</a></li>

        </ol>
    </div>
@endsection
{{-- @section('button')
    <a id="addBtn" href="#" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>
@endsection --}}

@section('content')
    <div class="row" id="attendanceJs">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning" id="messageSpan">
                        {{ 'Please wait while we fetching your location...' }}
                    </div>
                    @if (session()->has('successMessage'))
                        <div class="alert alert-success">
                            {{ session('successMessage') }}
                        </div>
                    @endif

                    @if (session()->has('errorMessage'))
                        <div class="alert alert-danger">
                            {{ session('errorMessage') }}
                        </div>
                    @endif
                    @if ($attendance)
                        @if (!$attendance->attendance_timeout)
                            <form id="punchOutForm">
                                <table class="table table-striped">
                                    <input type="hidden" value="{{ Auth::user()->location->lat ?? '' }}" id="lat">
                                    <input type="hidden" value="{{ Auth::user()->location->lng ?? '' }}" id="lng">
                                    <input type="hidden"id="lat_user" name="lat">
                                    <input type="hidden"id="lng_user" name="lng">
                                    @csrf
                                    <tr>
                                        <td>Date</td>
                                        <td>:</td>
                                        <td>{{ date('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Coordinate</td>
                                        <td>:</td>
                                        <td id="demo"></td>
                                    </tr>
                                    <tr>
                                        <td>Half Day?</td>
                                        <td>:</td>
                                        <td><input type="checkbox" value="Half Day" name="remark"></td>
                                    </tr>
                                    <tr id="lampiran" style="display:none">
                                        <td>Attachment</td>
                                        <td>:</td>
                                        <td>
                                            <input type="file" name="file" id="attachment" capture="user"
                                                accept="image/*">
                                        </td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <button type="button" data-id="{{ $attendance->id }}" class="btn btn-large btn-danger"
                                        id="punchOutBtn" disabled>Punch Out</button>
                                </div>
                            </form>
                        @else
                            You have already punch out for today.
                        @endif
                    @else
                        <form action="/attendanceStore" method="POST" enctype="multipart/form-data" id="addForm">
                            <table class="table table-striped">
                                <input type="hidden" value="{{ Auth::user()->location->lat ?? '' }}" id="lat">
                                <input type="hidden" value="{{ Auth::user()->location->lng ?? '' }}" id="lng">
                                <input type="hidden"id="lat_user" name="lat">
                                <input type="hidden"id="lng_user" name="lng">
                                @csrf
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{ date('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>Coordinate</td>
                                    <td>:</td>
                                    <td id="demo"></td>
                                </tr>
                                <tr>
                                    <td>Half Day?</td>
                                    <td>:</td>
                                    <td><input type="checkbox" value="Half Day" name="remark"></td>
                                </tr>
                                <tr id="lampiran" style="display:none">
                                    <td>Attachment</td>
                                    <td>:</td>
                                    <td>
                                        <input type="file" name="file" id="attachment" capture="user"
                                            accept="image/*">
                                    </td>
                                </tr>
                            </table>
                            <div class="text-center">
                                <button type="submit" class="btn btn-large btn-success" id="punchInBtn">Punch In</button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
