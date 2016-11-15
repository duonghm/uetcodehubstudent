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