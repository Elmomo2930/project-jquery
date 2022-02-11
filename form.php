<!-- add/edit form modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add-Update/User <i class="fa fa-user-circle-o" aria-hidden="true"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addform" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Procedency:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-place-of-worship" aria-hidden="true"></i></span>
                            </div>
                            <input type="text" class="form-control" id="procedency" name="procedency" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Age:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-images" aria-hidden="true"></i></span>
                            </div>
                            <input type="text" class="form-control" id="age" name="age" required="required" maxLength="2" minLength="2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Photo:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon1">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="photo" id="userphoto">
                                <label class="custom-file-label" for="userphoto">choose photo</label>
                            </div> 
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close it</button>
                    <button type="submit" class="btn btn-success" id="addButton">Register</button>
                    <input type="hidden" name="action" value="adduser">
                    <input type="hidden" name="userid" id="userid" value="">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add/edit form modal end -->