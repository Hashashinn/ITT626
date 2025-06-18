@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Capture Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Capture Outside Activity</a></li>

        </ol>
    </div>
@endsection
{{-- @section('button')
    <a id="addBtn" href="#" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>
@endsection --}}

@section('content')
    <div class="row" id="attendanceOutsideJs">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <form id="addForm">
                            <input type="hidden" value="{{ Auth::user()->location->lat ?? '' }}" name="lat" id="lat">
                            <input type="hidden" value="{{ Auth::user()->location->lng ?? '' }}" name="lng" id="lng">
                            <input type="hidden"id="lat_user" name="lat">
                            <input type="hidden"id="lng_user"name="lng">
                            @csrf
                            <tr>
                                <td>Date</td>
                                <td>:</td>
                                <td>{{ date('d-m-Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td>Coordinate</td>
                                <td>:</td>
                                {{-- <td id="demo"></td> --}}
                                <td id="demo"></td>
                            </tr>
                            <tr>
                                <td>Attachment
                                    <span> (eg:png ,jpg, jpeg)</span>
                                </td>
                                <td>:</td>
                                <td>
                                    <input type="file" name="file" id="image-input" accept="image/*" style="display: none" disabled>
                                    <video id="camera-view" width="640" height="480" style="display: none;"></video>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                    {{-- <button id="captureImage" class="btn btn-primary">Capture Image</button> --}}
                                    {{-- <button id="openCamera" class="btn btn-primary">Open Camera</button> --}}
                                    <input type="file" name="file" id="attachment" capture="user" accept="image/*">
                                </td>
                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <td>:</td>
                                <td>
                                    <textarea name="remarks" id="remarks" rows="4" cols="20"required></textarea>
                                </td>
                            </tr>
                        </form>
                    </table>
                    <div class="text-center">
                        <button type="submit" class="btn btn-large btn-success" id="saveBtn">Punch In</button>
                        {{-- <button type="submit" data-id="{{ Auth::user()->id }}" class="btn btn-large btn-danger" id="punchOutBtn" disabled>Punch Out</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


