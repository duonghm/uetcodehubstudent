<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $('#frmChangePass').submit(function () {
            var _currentPass = $('#txtCurrentPass').val();
            var _newPass = $('#txtNewPass').val();
            var _confirmPass = $('#txtConfirm').val();
            if (_currentPass == "" || _newPass == "" || _confirmPass == "") {
                $('#msg')[0].innerText = "Please input all field";
                $('#msg')[0].style.color = '#f05f2a';
            } else if (_newPass != _confirmPass) {
                $('#msg')[0].innerText = "New password and Confirm password is not matched";
                $('#msg')[0].style.color = '#f05f2a';
            } else if(_newPass.length < 8){
                $('#msg')[0].innerText = "New password must have more than 8 character";
                $('#msg')[0].style.color = '#f05f2a';
            }else {
                $.ajax({
                    type: "POST",
                    url: "{{url('/changePassword')}}",
                    timeout: 5000,
                    data: {
                        currentPass: _currentPass,
                        newPass: _newPass
                    }
                    ,
                    success: function (data) {
                        if (data == 'OK') {
                            $('#msg')[0].innerText = "Change password sucess";
                            $('#msg')[0].style.color = '#008080';
                            setTimeout(function(){
                                        window.location.reload();
                            }, 500);
                        } else if (data == 'ERR_AUTH') {
                            $('#msg')[0].innerText = "Current password is not match with database";
                            $('#msg')[0].style.color = '#f05f2a';
                        } else{
                            $('#msg')[0].innerText = "Database error";
                            $('#msg')[0].style.color = '#f05f2a';
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(ajaxOptions);
                        alert(thrownError);
                    }
                });
            }
        });
    });
</script>


<div id="changePasswordModal" class="modal fade">
    <form id="frmChangePass" class="form-horizontal" role="form" onsubmit="return false">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Đổi mật khẩu </h4>
                </div>
                <!--dialog body-->
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input id="txtCurrentPass" type="password" class="form-control" aria-required="true"
                                           placeholder="Mật khẩu hiện tại">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                </div>
                            </div>
                        </div>
						<br/>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input id="txtNewPass" type="password" class="form-control" aria-required="true"
                                           placeholder="Mật khẩu mới">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input id="txtConfirm" type="password" class="form-control" aria-required="true"
                                           placeholder="Nhập lại 1 lần nữa">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <label id="msg" style="font-weight: bold; color: #f05f2a"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--dialog buttons-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"> Đổi </button>
                    <button type="button" class="btn default" data-dismiss="modal">Thoát</button>
                </div>
            </div>
        </div>
    </form>
</div>