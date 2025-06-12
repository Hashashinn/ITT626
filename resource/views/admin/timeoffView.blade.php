@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Time Off</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Time Off</a></li>

        </ol>
    </div>
@endsection

@section('content')
    <div class="row" id="timeoffJs">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="addForm">
                        <table class="table table-striped">
                            <input type="hidden" value="{{ Auth::user()->location->lat ?? '' }}" id="lat">
                            <input type="hidden" value="{{ Auth::user()->location->lng ?? '' }}" id="lng">
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
                                <td id="demo"></td>
                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <td>:</td>
                                <td>
                                    <textarea name="remarks" id="remarks" rows="" cols="20" @if ($timeoff && $timeoff->timeoff_start) disabled @endif></textarea>
                                    @if ($timeoff && $timeoff->timeoff_start)
                                        <!-- You can add a message or additional content here for users when the textarea is disabled -->
                                        <p>If you have any urgent updates, please contact your supervisor or manager directly.</p>
                                    @elseif ($timeoff && $timeoff->timeoff_end)
                                    @endif
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Half Day?</td>
                                <td>:</td>
                                <td><input type="checkbox" value="Half Day" name="remark"></td>
                            </tr> --}}
                            {{-- <tr id="lampiran" style="display:none">
                                <td>Attachment</td>
                                <td>:</td>
                                <td>
                                    <input type="file" name="file" id="attachment" capture="user" accept="image/*">
                                </td>
                            </tr> --}}
                        </table>
                        <div class="text-center">
                            @if ($timeoff)
                                @if (!$timeoff->timeoff_start)
                                    <button class="btn btn-large btn-success" id="saveBtn">Start Time Off</button>
                                @elseif(!$timeoff->timeoff_end)
                                    <button data-id="{{ $timeoff->id }}" class="btn btn-large btn-danger" id="punchOutBtn" disabled>End Time Off</button>
                                @endif
                            @else
                                <button class="btn btn-large btn-success" id="saveBtn">Start Time Off</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
