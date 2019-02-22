var app = new Vue({
    el: '#app',
    data: {
        profile: null
    },
    methods: {
        onConfirm: function() {
            var self = this;
            if(self.profile){
                var table = $('#tb-que-list').DataTable();
                swal({
                    title: 'พิมพ์บัตรคิว',
                    input: 'select',
                    inputOptions: select2Options,
                    inputPlaceholder: 'เลือกชื่อบริการ',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
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
                                    profile: self.profile,
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
                                    clearContainer();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    clearContainer();
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
                    }else{
                    }
                });   
            }
        },
        onCancel: function () {
            clearContainer();
        }
    }
});

getSelectedPC = () => {
    return $("#tbdevice-device_name").val();
};
clearContainer = () => {
    $('.citizenId').html('');
    $('.card-name').html('');
    $('.first-name-en').html('');
    $('.last-name-en').html('');
    $('.birthday').html('');
    $('.birthday-en').html('');
    $('.address').html('');
    $('#card-image').attr('src', '');
    $('.id-card-container').hide();
    app.profile = null;
};
$(function() {
    var deviceContainer = $('.device-container');
    socket.on('card-inserted', function (res) {// เสียบบัตร
        var com_name = getSelectedPC(); 
        if (com_name === res.com_name) { // ถ้าชื่อคอมตรงกัน
            $('.progress').show();
            clearContainer();
        }
    }).on('read-smart-card', function (res) {// อ่านบัตรเสร็จ
        var com_name = getSelectedPC();
        if (com_name === res.com_name) {
            $.ajax({
                url: baseUrl + '/app/kiosk/decode-data',
                type: 'POST',
                data: res,
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.progress').hide();
                    swal({
                        type: 'error',
                        title: textStatus,
                        text: errorThrown,
                    });
                },
                success: function (response) {
                    if (response.success === true) {
                        app.profile = response.personal;
                        $('#modelscan-card_id').val(response.personal.citizenId);
                        $('.citizenId').html(response.personal.citizen_id);
                        $('.card-name').html(response.personal.full_name);
                        $('.first-name-en').html(response.personal.first_name_en);
                        $('.last-name-en').html(response.personal.last_name_en);
                        $('.birthday').html(response.personal.birthdate_th);
                        $('.birthday-en').html(response.personal.birthdate_en);
                        $('.address').html(response.personal.address);
                        $('#card-image').attr('src', response.personal.photo);
                        $('.id-card-container').show();
                    } else {
                        swal('Oops...', res.message, 'error');
                    }
                    $('.progress').hide();
                },
            });
        }
    }).on('register', function (res) { // ลงทะเบียน, พิมพ์บัตรคิว
        if (modelServiceGroup.service_group_id == res.modelServiceGroup.service_group_id) {
            var table = $('#tb-que-list').DataTable();
            table.ajax.reload();
            if(app.profile !== null && res.modelQue.id_card === app.profile.citizenId){
                clearContainer();
            }
        }
    }).on('DEVICE_CONNECTED', function (res) {
        var com_name = getSelectedPC(); 
        if(res.comName === com_name){
            deviceContainer.html('Device ('+res.comName+') Connected!').removeClass('text-danger').addClass('text-success');
        }
        $.ajax({
            url: baseUrl + '/app/kiosk/create-device',
            type: 'POST',
            data: {device_name: res.comName},
            dataType: 'json',
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: 'error',
                    title: textStatus,
                    text: errorThrown,
                });
            },
        });
    }).on('DEVICE_DISCONNECTED', function (res) {
        var com_name = getSelectedPC();
        if(res.comName === com_name){
            deviceContainer.html('Device not Connecting!').addClass('text-danger').removeClass('text-success');
        }
    }).on('CHECK_DEVICE_SUCCESS', function (res) {
        var com_name = getSelectedPC();
        if(res.comName === com_name){
            deviceContainer.html('Device ('+res.comName+') Connected!').removeClass('text-danger').addClass('text-success');
        }
    }).on('CARD_REMOVED', function (res) {
        var com_name = getSelectedPC();
        if(res.comName === com_name){
            clearContainer();
        }
    });
});