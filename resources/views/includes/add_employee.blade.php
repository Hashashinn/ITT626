<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Employee</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="user[name]" required />
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="user[position]" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="user[email]">
                        </div>
                        <div class="form-group">
                            <label for="email">Role</label>
                            <select name="role[role_id]" id="" class="form-control">
                                @php
                                    $roles = getAllRoles();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Location / Branch</label>
                            <select name="user[location_id]" id="" class="form-control">
                                @php
                                    $locations = getAllLocation();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Department</label>
                            <select name="user[department_id]" class="form-control">
                                @php
                                    $departments = getAllDepartment();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Schedule</label>
                            <select name="user[schedule_id]" class="form-control">
                                @php
                                    $schedules = getAllSchedule();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->slug }}</option>
                                @endforeach
                            </select>
                        </div>
                     <div class="form-group">
                        <div>
                            <button id="saveBtn" type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                Cancel
                            </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Edit Employee</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form id="editForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="nameEdit" name="user[name]" required />
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="positionEdit" name="user[position]" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="emailEdit" name="user[email]">
                            <input type="hidden" id="empId">
                        </div>
                        <div class="form-group">
                            <label for="email">Role</label>
                            <select name="role[role_id]" id="roleEdit" class="form-control">
                                @php
                                    $roles = getAllRoles();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Location / Branch</label>
                            <select name="user[location_id]" id="locationEdit" class="form-control">
                                @php
                                    $locations = getAllLocation();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Department</label>
                            <select name="user[department_id]" id="departmentEdit" class="form-control">
                                @php
                                    $departments = getAllDepartment();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Schedule</label>
                            <select name="user[schedule_id]" id="scheduleEdit" class="form-control">
                                @php
                                    $schedules = getAllSchedule();
                                @endphp
                                <option value="">Please Select</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->slug }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <div class="form-group">
                        <div>
                            <button id="updateBtn" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- excel --}}
<div class="modal fade" id="excelModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">

                <h4 class="modal-title "><span class="Employee_id">Bulk Upload Employee</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div>
                    <a href="/storage/template/User.xlsx">
                        <p>Template</p>
                    </a>

                    <form id="excelForm">
                        @csrf
                        <input type="file" class="form-control" name="excel">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" id="submitExcelBtn" class="btn btn-primary btn-flat"><i class="fa fa-file"></i> Upload</button>
            </div>
        </div>
    </div>
</div>
