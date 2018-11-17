$(function() {
    socket.on('card-inserted', function (res) {// เสียบบัตร
        if (com_name === res.com_name) { // ถ้าชื่อคอมตรงกัน
            $('.progress').show();
        }
    }).on('read-smart-card', function (res) {// อ่านบัตรเสร็จ
        if (com_name === res.profile.com_name) {
            $('.progress').hide();
            var table = $('#tb-que-list').DataTable();
            swal({
                title: 'ออกบัตรคิว',
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                    '<p><img src="' + res.profile.img_encode + '" class="img-responsive center-block" width="80px"/></p>' +
                    '<p><i class="fa fa-user"></i> ' + res.profile.th_fullname + '</p>' +
                    '<p><i class="fa fa-angle-double-down"></i></p><p>' + modelServiceGroup.service_group_name + '</p>',
                input: 'select',
                //type: 'question',
                inputOptions: select2Options,
                inputPlaceholder: 'เลือกชื่อบริการ',
                showCancelButton: true,
                confirmButtonText: 'พิมพ์บัตรคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    return !value && 'กรุณาเลือกชื่อบริการ!'
                },
                preConfirm: function (value) {
                    return new Promise(function (resolve) {
                        $.ajax({
                            url: baseUrl + '/app/kiosk/register',
                            type: 'POST',
                            data: {
                                profile: res.profile,
                                service_id: value,
                                modelServiceGroup: modelServiceGroup
                            },
                            dataType: 'json',
                            success: function (res) {
                                if (res.success === true) {
                                    table.ajax.reload();
                                    socket.emit('register', res);
                                    window.open(res.url, "myPrint", "width=800, height=600");
                                    resolve();
                                } else {
                                    swal('Oops...', res.message, 'error');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    type: 'error',
                                    title: textStatus,
                                    text: errorThrown,
                                });
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {
                    swal.close();
                } else {
                }
            });
        }
    }).on('register', function (res) { // ลงทะเบียน, พิมพ์บัตรคิว
        if (modelServiceGroup.service_group_id == res.modelServiceGroup.service_group_id) {
            dt_tbquelist.ajax.reload();
        }
    });
});