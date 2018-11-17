'use strict';
$('body').addClass('hide-sidebar');

var isEmpty = function (value, trim) {
    return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
};

var Queue = {
    handleClick: function () {
        var self = this;
        //ตารางคิวรอ
        $('#tb-waiting tbody').on('click', 'tr td a', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr"),
                url = $(this).attr("data-url"),
                table = $('#tb-waiting').DataTable();
            if (tr.hasClass("child") && typeof table.row(tr).data() === "undefined") {
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key"); //que_ids
            var data = table.row(tr).data();
            var counter_name = self.getCounterName();
            // เรียกคิว
            if ($(this).hasClass("on-calling")) {
                swal({
                    title: 'เรียกคิว ' + data.que_num + ' ?',
                    text: counter_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableWaiting();
                                    self.reloadTableCalling();
                                    socket.emit('on-call', $.extend({event_on: 'table_wait'}, response)); //sending data
                                    /*$('li.tab-wait,#tab-wait,li.tab-hold,#tab-hold').removeClass('active');
                                    $('li.tab-calling,#tab-calling').addClass('active');*/
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            } else if ($(this).hasClass("on-end")) {
                swal({
                    title: 'เสร็จสิ้น คิว ' + data.que_num + ' ?',
                    text: counter_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เสร็จสิ้น',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableWaiting();
                                    self.reloadTableQue();
                                    socket.emit('on-end', $.extend({event_on: 'table_wait'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            }
        });

        //รายการคิวกำลังเรียก
        $('#tb-calling tbody').on('click', 'tr td a', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr"),
                url = $(this).attr("data-url"),
                table = $('#tb-calling').DataTable();
            if (tr.hasClass("child") && typeof table.row(tr).data() === "undefined") {
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key"); //que_ids
            var data = table.row(tr).data();
            var counter_name = self.getCounterName();

            //เรียกคิวซ้ำ
            if ($(this).hasClass('on-recall')) {
                swal({
                    title: 'เรียกคิว ' + data.que_num + ' ?',
                    text: '',
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    socket.emit('on-call', $.extend({event_on: 'table_calling'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            } else if ($(this).hasClass('on-hold')) { // พักคิว
                swal({
                    title: 'พักคิว ' + data.que_num + ' ?',
                    text: '',
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'พักคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableCalling();
                                    self.reloadTableHold();
                                    self.reloadTableQue();
                                    socket.emit('on-hold', $.extend({event_on: 'table_calling'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            } else if ($(this).hasClass('on-end')) { // เสร็จสิ้น
                swal({
                    title: 'เสร็จสิ้น คิว ' + data.que_num + ' ?',
                    text: '',
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เสร็จสิ้น',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableCalling();
                                    self.reloadTableQue();
                                    socket.emit('on-end', $.extend({event_on: 'table_calling'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            }
        });

        //รายการพักคิว
        $('#tb-hold tbody').on('click', 'tr td a', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr"),
                url = $(this).attr("data-url"),
                table = $('#tb-hold').DataTable();
            if (tr.hasClass("child") && typeof table.row(tr).data() === "undefined") {
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key"); //que_ids
            var data = table.row(tr).data();
            var counter_name = self.getCounterName();

            //เรียกคิวซ้ำ
            if ($(this).hasClass('on-recall')) {
                swal({
                    title: 'เรียกคิว ' + data.que_num + ' ?',
                    text: '',
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableHold();
                                    self.reloadTableCalling();
                                    self.reloadTableQue();
                                    socket.emit('on-call', $.extend({event_on: 'table_hold'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            } else if ($(this).hasClass('on-end')) { // เสร็จสิ้น
                swal({
                    title: 'เสร็จสิ้น คิว ' + data.que_num + ' ?',
                    text: '',
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><p>' + counter_name + '</p>' + '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เสร็จสิ้น',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + url,
                                dataType: "json",
                                data: {
                                    data: data, //Data in column Datatable
                                    modelProfile: modelProfile,
                                    formData: formData,
                                },
                                success: function (response) {
                                    self.reloadTableHold();
                                    self.reloadTableQue();
                                    socket.emit('on-end', $.extend({event_on: 'table_hold'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    self.ajaxAlertError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        swal.close();
                    }
                });
            }
        });
    },
    getCounterName: function () {
        var counter_name = '';
        var elm = $('#tbserviceprofile-counter_service_id');
        if (!isEmpty(elm.select2('data'))) {
            counter_name = elm.select2('data')[0]['text'];
        }
        return counter_name;
    },
    getProfileName: function () {
        var profile_name = '';
        var elm = $('#tbserviceprofile-service_profile_id');
        if (!isEmpty(elm.select2('data'))) {
            profile_name = elm.select2('data')[0]['text'];
        }
        return profile_name;
    },
    ajaxAlertError: function (textStatus, errorThrown) {
        swal({
            type: 'error',
            title: textStatus,
            text: errorThrown,
            showConfirmButton: false,
            timer: 1500
        });
    },
    reloadTableWaiting: function () {
        var table = $('#tb-waiting').DataTable();
        table.ajax.reload();
    },
    reloadTableCalling: function () {
        var table = $('#tb-calling').DataTable();
        table.ajax.reload();
    },
    reloadTableHold: function () {
        var table = $('#tb-hold').DataTable();
        table.ajax.reload();
    },
    reloadTableQue: function () {
        var table = $('#tb-que-list').DataTable();
        table.ajax.reload();
    },
    checkFormData: function (res) {
        if (res.formData.service_profile_id == formData.service_profile_id &&
            res.formData.counter_service_id == formData.counter_service_id) {
            return true;
        } else {
            return false;
        }
    },
    init: function () {
        var self = this;
        self.handleClick();
    }
};

Queue.init();

//Socket Events
$(function () {
    socket.on('register', (res) => { //อกกบัตรคิว
        if (jQuery.inArray(res.modelQue.service_id, formData.service_id) !== -1) {
            toastr.warning('#' + res.modelQue.que_num + '<p><i class="fa fa-user"></i> ' + res.modelQue.pt_name + '</p>', 'คิวใหม่!', {
                "timeOut": 5000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
            Queue.reloadTableWaiting();
        }
    }).on('on-call', (res) => { // เรียกคิว
        if (res.event_on === 'table_wait') {
            Queue.reloadTableWaiting(); // โหลดข้อมูลคิวรอเรียก
        }
        if (Queue.checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            if (res.event_on === 'table_wait') {
                Queue.reloadTableCalling(); // โหลดข้อมูลคิวกำลังเรียก
            } else if (res.event_on === 'table_hold') {
                Queue.reloadTableCalling(); // โหลดข้อมูลคิวกำลังเรียก
                Queue.reloadTableHold(); // โหลดข้อมูลพักคิว
            }
        }
    }).on('on-hold', (res) => { // พักคิว
        if (Queue.checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            Queue.reloadTableCalling(); // โหลดข้อมูลคิวกำลังเรียก
            Queue.reloadTableHold(); // โหลดข้อมูลพักคิว
        }
    }).on('on-end', (res) => { // เสร็จสิ้นคิว
        if (res.event_on === 'table_wait') { // ถ้าเสร็จสิ้นจากคิวรอเรียก
            Queue.reloadTableWaiting(); // โหลดข้อมูลคิวรอเรียก
        }
        if (Queue.checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            if (res.event_on === 'table_calling') {
                Queue.reloadTableCalling(); // โหลดข้อมูลคิวกำลังเรียก
            } else if (res.event_on === 'table_hold') {
                Queue.reloadTableCalling(); // โหลดข้อมูลคิวกำลังเรียก
                Queue.reloadTableHold(); // โหลดข้อมูลพักคิว
            }
        }
    })
});

// Function for collapse hpanel
$('.showhide-panel').on('click', function (event) {
    event.preventDefault();
    var hpanel = $(this).closest('div.hpanel');
    var icon = $(this).find('i:first');
    var body = hpanel.find('div.panel-body');
    var footer = hpanel.find('div.panel-footer');
    body.slideToggle(300);
    footer.slideToggle(200);

    if (icon.hasClass('fa-chevron-up')) {
        var profile_name = Queue.getProfileName();
        var counter_name = Queue.getCounterName();
        $('#hpanel-title').html('<span class="badge badge-success">' + profile_name + '&nbsp;,' + counter_name + '</span>');
    } else {
        $('#hpanel-title').html('');
    }

    // Toggle icon from up to down
    icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    hpanel.toggleClass('').toggleClass('panel-collapse');
    setTimeout(function () {
        hpanel.resize();
        hpanel.find('[id^=map-]').resize();
    }, 50);
});

$('#tb-waiting tbody').on('click', 'tr', function () {
    var table = $('#tb-waiting').DataTable();
    if ($(this).hasClass('success')) {
        $(this).removeClass('success');
    } else {
        table.$('tr.success').removeClass('success');
        $(this).addClass('success');
    }
});
$('#tb-calling tbody').on('click', 'tr', function () {
    var table = $('#tb-calling').DataTable();
    if ($(this).hasClass('success')) {
        $(this).removeClass('success');
    } else {
        table.$('tr.success').removeClass('success');
        $(this).addClass('success');
    }
});
$('#tb-hold tbody').on('click', 'tr', function () {
    var table = $('#tb-hold').DataTable();
    if ($(this).hasClass('success')) {
        $(this).removeClass('success');
    } else {
        table.$('tr.success').removeClass('success');
        $(this).addClass('success');
    }
});