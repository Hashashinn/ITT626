<!-- Edit -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h4 class="modal-title"><b><span class="department_id">Edit Role</span></b></h4>
            <form id="editForm">
                <div class="modal-body text-left">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Role</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                        <input type="hidden" id="dId">
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button id="updateBtn" class="btn btn-success btn-flat" name="edit"><i
                        class="fa fa-check-square-o"></i>
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

                <h4 class="modal-title "><span class="Role_id">Delete Role</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form class="form-horizontal" id="deleteForm">
                <div class="modal-body">
                    @csrf
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_Role_name" id="roleNameDelete"></h2>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                            class="fa fa-close"></i> Close</button>
                    <button type="submit" id="deleteBtn" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i>
                        Delete</button>
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
            <h4 class="modal-title"><b>Add Role</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Role</label>
                            <input type="text" class="form-control" placeholder="Enter Role Name" id="name"
                                name="name" required />
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input type="text" class="form-control" placeholder="Enter Slug Name" id="slug"
                                name="slug" required />
                        </div>
                    
                    <div class="form-group">
                        <div>
                            <button id="saveBtn" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <button  class="btn btn-secondary waves-effect m-l-5"
                                data-dismiss="modal">
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

{{-- excel --}}
<div class="modal fade" id="excelModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">

                <h4 class="modal-title "><span class="Role_id">Bulk Upload Role</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div>
                    <a href="/storage/template/Roles.xlsx">
                        <p>Template</p>
                    </a>

                    <form id="excelForm">
                        @csrf
                        <input type="file" class="form-control" name="excel">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" id="submitExcelBtn" class="btn btn-primary btn-flat"><i
                        class="fa fa-file"></i> Upload</button>
            </div>
        </div>
    </div>
</div>
