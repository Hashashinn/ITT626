<!-- Edit -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h4 class="modal-title"><b><span class="department_id">Edit Department</span></b></h4>
            <form id="editForm">
                <div class="modal-body text-left">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Department</label>
                        <input type="text" class="form-control" id="departmentName" name="name" required>
                        <input type="text" id="dId">
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button id="updateBtn" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
                    Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">

                <h4 class="modal-title "><span class="department_id">Delete department</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="form-horizontal" id="deleteForm">
                <div class="modal-body">
                    @csrf
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_department_name" id="departmentNameDelete"></h2>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" id="deleteBtn" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Department</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Department</label>
                            <input type="text" class="form-control" placeholder="Enter Derpartment Name" id="name" name="name" required />
                        </div>
                    </form>
                    <div class="form-group">
                        <div>
                            <button id="saveBtn" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <button type="reset" id="saveBtn" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
