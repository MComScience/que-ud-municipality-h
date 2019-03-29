$("body").toggleClass("hide-sidebar");
$('.normalheader, footer').hide();

const Select2 = Vue.component('select2', {
    props: ['options', 'value'],
    template: `<select class="form-control"><slot></slot></select>`,
    mounted: function () {
        var vm = this
        $(this.$el)
        // init select2
            .select2({
                data: vm.options,
                placeholder: 'เลือกรายการ...',
                allowClear: true,
                theme: "bootstrap",
                containerCssClass: ':all:'
            })
            // emit event on change.
            .on('change', function (event) {
                vm.$emit('change-selection', {elm: this, value: this.value, event: event})
            })
    },
    watch: {
        value: function (value) {
            // update value
            /*if (value) {
                $(this.$el)
                    .val(value)
                    .trigger('change')
            }*/

        },
        options: function (options) {
            // update options
            $(this.$el).empty().select2({
                data: options,
                placeholder: 'เลือกรายการ...',
                allowClear: true,
                theme: "bootstrap",
                containerCssClass: ':all:'
            }).val($(this.$el).val()).trigger('change');
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
})

var app = new Vue({
    el: '#app-mobile-page',
    data: {
        search: null,
        profileOptions: null,
        counterOptions: null,
        profileId: null,
        counterId: null,
        tblCalling: null,
        tblHold: null,
        tblWait: null,
        formData: null,
        modelProfile: null,
        dataWaiting: null,
        dataOnState: {
            name: '-',
            queueNumber: '',
            info: null,
            event: null
        },
        showAction: true,
    },
    methods: {
        onSubmit: function () {
            const $vm = this;
            let search = this.uppercaseSearch
            var searchResult = null;
            var $counter = $vm.getCounterLabel() || '';
            if ($vm.dataWaiting) {
                for (let index in $vm.dataWaiting) {
                    if (search === $vm.dataWaiting[index].que_num) {
                        searchResult = $vm.dataWaiting[index];
                        Swal.fire({
                            title: 'เรียกคิว ' + $vm.dataWaiting[index].que_num + ' ?',
                            text: $counter,
                            html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${$vm.dataWaiting[index].pt_name}</p>`,
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'เรียกคิว',
                            cancelButtonText: 'ยกเลิก',
                            confirmButtonColor: '#62cb31',
                            cancelButtonColor: '#e74c3c',
                            allowOutsideClick: false,
                            showLoaderOnConfirm: true,
                            preConfirm: function () {
                                return new Promise(function (resolve, reject) {
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/call-waiting?que_ids=" + $vm.dataWaiting[0].que_ids,
                                        dataType: "json",
                                        data: {
                                            data: $vm.dataWaiting[index], //Data in column Datatable
                                            modelProfile: $vm.modelProfile,
                                            formData: $vm.formData,
                                        },
                                        success: function (response) {
                                            $vm.dataOnState = $vm.updateObject($vm.dataOnState, {
                                                name: $vm.dataWaiting[index].pt_name,
                                                queueNumber: $vm.dataWaiting[index].que_num,
                                                info: $vm.dataWaiting[index],
                                                event: 'CALL_WAIT'
                                            });
                                            $vm.fetchDataWaiting();
                                            $vm.fetchDataCalling();
                                            searchResult = null;
                                            socket.emit('on-call', $.extend({event_on: 'table_wait'}, response)); //sending data
                                            resolve();
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            $vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                            },
                        }).then((result) => {
                            if (result.value) { //Confirm
                                Swal.close()
                            }
                        });
                        break;
                    }
                }
            }

            if ($vm.tblCalling && searchResult === null) {// รายการคิวกำลังเรียก
                $vm.tblCalling.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var $data = this.data();
                    if (search === $data.que_num) {
                        searchResult = $data;
                        Swal.fire({
                            type: 'warning',
                            title: $data.que_num,
                            html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${$data.pt_name}</p>`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false,
                            heightAuto: false,
                            footer: `
                                <div class="row">
                                    <div class="col-xs-6 text-center">
                                        <a href="javascript:void(0);" class="btn btn-info btn-lg btn-block btn-circle-swal btn-on-call-search">เรียกคิว</a>
                                        <a href="javascript:void(0);" class="btn btn-warning btn-lg btn-block btn-circle-swal btn-on-hold-search">พักคิว</a>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <a href="javascript:void(0);" class="btn btn-success btn-lg btn-block btn-circle-swal btn-on-end-search">เสร็จสิ้น</a>
                                        <a href="javascript:void(0);" class="btn btn-lg btn-danger btn-block btn-circle-swal" onclick="{Swal.close()}">ยกเลิก</a>
                                    </div>
                                </div>
                            `,
                            onOpen: function () {
                                $('.swal2-footer').css('display', 'block');
                                $('a.btn-on-call-search').on('click', function () {
                                    $vm.onCallSearch($data);
                                });
                                $('a.btn-on-hold-search').on('click', function () {
                                    $vm.onHoldSearch($data);
                                });
                                $('a.btn-on-end-search').on('click', function () {
                                    Swal.showLoading();
                                    $('.swal2-footer').hide();
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/end?caller_ids=" + $data.caller_ids,
                                        dataType: "json",
                                        data: {
                                            data: $data, //Data in column Datatable
                                            modelProfile: $vm.modelProfile,
                                            formData: $vm.formData,
                                        },
                                        success: function (response) {
                                            if ($data.que_num === $vm.dataOnState.queueNumber) {
                                                $vm.dataOnState = $vm.updateObject($vm.dataOnState, {
                                                    name: '-',
                                                    queueNumber: '',
                                                    info: null,
                                                    event: null
                                                });
                                            }
                                            $vm.fetchDataCalling();
                                            Swal.close();
                                            $('#modalSearch').modal('hide');
                                            socket.emit('on-end', $.extend({event_on: 'table_calling'}, response));
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            $vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                            }
                        });
                    }
                });
            }

            if ($vm.tblHold && searchResult === null) {//รายการคิวพัก
                $vm.tblHold.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    if (search === data.que_num) {
                        searchResult = data;
                        Swal.fire({
                            type: 'warning',
                            title: data.que_num,
                            html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${data.pt_name}</p>`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false,
                            heightAuto: false,
                            footer: `
                                <div class="row">
                                    <div class="col-xs-4 text-center" style="padding-right: 5px;padding-left: 5px;">
                                        <a href="javascript:void(0);" class="btn btn-info btn-lg btn-block btn-on-call-search">เรียกคิว</a>
                                    </div>
                                    <div class="col-xs-4 text-center" style="padding-right: 5px;padding-left: 5px;">
                                        <a href="javascript:void(0);" class="btn btn-success btn-lg btn-block btn-on-end-search">เสร็จสิ้น</a>
                                    </div>
                                    <div class="col-xs-4 text-center" style="padding-right: 5px;padding-left: 5px;">
                                        <a href="javascript:void(0);" class="btn btn-lg btn-block btn-danger btn-block" onclick="{Swal.close()}">ยกเลิก</a>
                                    </div>
                                </div>
                            `,
                            onOpen: function () {
                                $('.swal2-footer').css('display', 'block');
                                $('a.btn-on-call-search').on('click', function () {
                                    Swal.showLoading();
                                    $('.swal2-footer').hide();
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/recall?caller_ids=" + data.caller_ids,
                                        dataType: "json",
                                        data: {
                                            data: data, //Data in column Datatable
                                            modelProfile: $vm.modelProfile,
                                            formData: $vm.formData,
                                        },
                                        success: function (response) {
                                            $vm.dataOnState = $vm.updateObject($vm.dataOnState, {
                                                name: data.pt_name,
                                                queueNumber: data.que_num,
                                                info: data,
                                                event: 'RECALL_HOLD'
                                            });
                                            $vm.fetchDataCalling();
                                            $vm.fetchDataHold();
                                            Swal.close();
                                            $('#modalSearch').modal('hide');
                                            socket.emit('on-call', $.extend({event_on: 'table_hold'}, response));
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            $vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                                $('a.btn-on-end-search').on('click', function () {
                                    Swal.showLoading();
                                    $('.swal2-footer').hide();
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/end?caller_ids=" + data.caller_ids,
                                        dataType: "json",
                                        data: {
                                            data: data, //Data in column Datatable
                                            modelProfile: $vm.modelProfile,
                                            formData: $vm.formData,
                                        },
                                        success: function (response) {
                                            if (data.que_num === $vm.dataOnState.queueNumber) {
                                                $vm.dataOnState = $vm.updateObject($vm.dataOnState, {
                                                    name: '-',
                                                    queueNumber: '',
                                                    info: null,
                                                    event: null
                                                });
                                            }
                                            $vm.fetchDataHold();
                                            Swal.close();
                                            $('#modalSearch').modal('hide');
                                            socket.emit('on-end', $.extend({event_on: 'table_hold'}, response));
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            $vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                            }
                        });
                    }
                });
            }
            if (searchResult === null) {
                Swal.fire({
                    type: 'warning',
                    title: 'ไม่พบข้อมูล',
                    text: search,
                });
            }
        },
        fetchDataOptionProfile: function () {//ข้อมูลเซอร์วิสโปรไฟล์
            const vm = this;
            $.ajax({
                method: "GET",
                url: "/app/calling/data-profile-options",
                dataType: "json",
                success: function (response) {
                    vm.profileOptions = vm.mapDataOptions(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        fetchDataCounterOptions: function (profileId) {//ข้อมูลเคาท์เตอรื
            const vm = this;
            $.ajax({
                method: "GET",
                url: "/app/calling/data-counter-options",
                data: {
                    profileId: profileId
                },
                dataType: "json",
                success: function (response) {
                    vm.counterOptions = vm.mapDataOptions(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        fetchDataCallingOptions: function () {
            const vm = this;
            $.ajax({
                method: "GET",
                url: "/app/calling/data-calling-options",
                data: {
                    profileId: vm.profileId,
                    counterId: vm.counterId
                },
                dataType: "json",
                success: function (response) {
                    vm.formData = response.formData;
                    vm.modelProfile = response.modelProfile;
                    vm.fetchDataCalling()
                    vm.fetchDataHold()
                    vm.fetchDataWaiting()
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        fetchDataCalling: function () {
            const vm = this;
            $.ajax({
                method: "POST",
                url: "/app/calling/data-que-calling",
                data: {
                    data: vm.modelProfile,
                    formData: vm.formData
                },
                dataType: "json",
                success: function (response) {
                    vm.tblCalling.clear().draw();
                    vm.tblCalling.rows.add(response.data).draw();
                    $('.badge-count-calling').html(vm.tblCalling.data().count());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        fetchDataHold: function () {
            const vm = this;
            $.ajax({
                method: "POST",
                url: "/app/calling/data-que-hold",
                data: {
                    data: vm.modelProfile,
                    formData: vm.formData
                },
                dataType: "json",
                success: function (response) {
                    vm.tblHold.clear().draw();
                    vm.tblHold.rows.add(response.data).draw();
                    $('.badge-count-hold').html(vm.tblHold.data().count());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        fetchDataWaiting: function () {
            const vm = this;
            $.ajax({
                method: "POST",
                url: "/app/calling/data-que-wait",
                data: {
                    data: vm.modelProfile,
                },
                dataType: "json",
                success: function (response) {
                    vm.dataWaiting = response.data;
                    vm.tblWait.clear().draw();
                    vm.tblWait.rows.add(response.data).draw();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        mapDataOptions: function (options) {
            const dataOptions = [];
            for (let key in options) {
                dataOptions.push({id: key, text: options[key]});
            }
            return dataOptions;
        },
        onChangeSelection: function (e) {
            const vm = this
            if (!e.value && e.elm.id === 'select2-profile') {
                vm.profileId = null;
                vm.counterOptions = null;
            }
            if (!e.value && e.elm.id === 'select2-counter') {
                vm.counterId = null;
                vm.counterOptions = null;
            }
            if (e.value && e.elm.id === 'select2-profile') {
                vm.profileId = e.value;
                vm.fetchDataCounterOptions(e.value);
            } else if (e.value && e.elm.id === 'select2-counter') {
                vm.counterId = e.value;
                vm.fetchDataCallingOptions();
            }
            vm.dataOnState = vm.updateObject(vm.dataOnState, {
                name: '-',
                queueNumber: '',
                info: null,
                event: null
            });
        },
        getProfileLabel: function () {
            const vm = this
            let label = '-';
            if (vm.profileId) {
                for (let index in vm.profileOptions) {
                    if (vm.profileId === vm.profileOptions[index].id) {
                        label = vm.profileOptions[index].text;
                        break;
                    }
                }
            }
            return label;
        },
        getCounterLabel: function () {
            const vm = this
            let label = '-';
            if (vm.counterId) {
                for (let index in vm.counterOptions) {
                    if (vm.counterId === vm.counterOptions[index].id) {
                        label = vm.counterOptions[index].text;
                        break;
                    }
                }
            }
            return label;
        },
        initDataTableWait: function () {
            const vm = this;
            vm.tblWait = $('#tbl-wait').DataTable({
                "dom": "<'row'<'col-sm-12'f>><'row'<'col-xs-12 col-sm-12 col-md-12 padding-zero'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "ค้นหา...",
                },
                "autoWidth": false,
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                "info": false,
                "bLengthChange": false,
                "data": [],
                "ordering": false,
                "columns": [
                    {
                        data: null,
                        defaultContent: '<i class="pe-7s-user pe-3x"></i>',
                        className: 'text-center vertical-align-middle border-top-none',
                        /*render: function (data, type, row, meta) {
                            return (meta.row + 1);
                        },*/
                        orderable: false
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return '<h4 class="font-blue-sharp">' + data.que_num + '</h4><p>' + data.pt_name + ' </p>';
                        },
                        className: '',
                        orderable: false
                    },
                    {
                        data: null,
                        defaultContent: '<button class="btn btn-default"><i class="glyphicon glyphicon-option-vertical"></i></button>',
                        orderable: false,
                        className: 'text-center vertical-align-middle'
                    },
                ],
                //"info": false
            });
        },
        initDataTableCalling: function () {
            const vm = this;
            vm.tblCalling = $('#tbl-calling').DataTable({
                "dom": "<'row'<'col-sm-12'f>><'row'<'col-xs-12 col-sm-12 col-md-12 padding-zero'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "ค้นหา...",
                },
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "info": false,
                "bLengthChange": false,
                "data": [],
                "ordering": false,
                "columns": [
                    {
                        data: null,
                        defaultContent: '<i class="pe-7s-user pe-3x"></i>',
                        className: 'text-center vertical-align-middle border-top-none',
                        /*render: function (data, type, row, meta) {
                            return (meta.row + 1);
                        },*/
                        orderable: false
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return '<h4 class="font-blue-sharp">' + data.que_num + '</h4><p>' + data.pt_name + ' </p>';
                        },
                        className: '',
                        orderable: false
                    },
                    {
                        data: null,
                        defaultContent: '<button class="btn btn-default"><i class="glyphicon glyphicon-option-vertical"></i></button>',
                        orderable: false,
                        className: 'text-center vertical-align-middle'
                    },
                    /*{
                        data: 'service_group_name',
                        orderable: false
                    }*/
                ],
                //"info": false
            });
        },
        initDataTableHold: function () {
            const vm = this;
            vm.tblHold = $('#tbl-hold').DataTable({
                "dom": "<'row'<'col-sm-12'f>><'row'<'col-xs-12 col-sm-12 col-md-12 padding-zero'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "ค้นหา...",
                },
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "info": false,
                "bLengthChange": false,
                "data": [],
                "ordering": false,
                "columns": [
                    {
                        data: null,
                        defaultContent: '<i class="pe-7s-user pe-3x"></i>',
                        className: 'text-center vertical-align-middle border-top-none',
                        /*render: function (data, type, row, meta) {
                            return (meta.row + 1);
                        },*/
                        orderable: false
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return '<h4 class="font-blue-sharp">' + data.que_num + '</h4><p>' + data.pt_name + ' </p>';
                        },
                        className: '',
                        orderable: false
                    },
                    {
                        data: null,
                        defaultContent: '<button class="btn btn-default"><i class="glyphicon glyphicon-option-vertical"></i></button>',
                        orderable: false,
                        className: 'text-center vertical-align-middle'
                    },
                    /*{
                        data: 'service_group_name',
                        orderable: false
                    }*/
                ],
                //"info": false
            });
        },
        onCallNext: function () {
            const vm = this
            if (vm.dataWaiting && vm.dataWaiting.hasOwnProperty(0)) {
                var $counter = vm.getCounterLabel() || '';
                Swal.fire({
                    title: 'เรียกคิว ' + vm.dataWaiting[0].que_num + ' ?',
                    text: $counter,
                    html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${vm.dataWaiting[0].pt_name}</p>`,
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#62cb31',
                    cancelButtonColor: '#e74c3c',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: "/app/calling/call-waiting?que_ids=" + vm.dataWaiting[0].que_ids,
                                dataType: "json",
                                data: {
                                    data: vm.dataWaiting[0], //Data in column Datatable
                                    modelProfile: vm.modelProfile,
                                    formData: vm.formData,
                                },
                                success: function (response) {
                                    vm.dataOnState = vm.updateObject(vm.dataOnState, {
                                        name: vm.dataWaiting[0].pt_name,
                                        queueNumber: vm.dataWaiting[0].que_num,
                                        info: vm.dataWaiting[0],
                                        event: 'CALL_WAIT'
                                    });
                                    vm.fetchDataWaiting();
                                    vm.fetchDataCalling();
                                    socket.emit('on-call', $.extend({event_on: 'table_wait'}, response)); //sending data
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    vm.handleAjaxError(textStatus, errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) { //Confirm
                        Swal.close()
                    }
                });
            } else {
                Swal.fire({
                    type: 'warning',
                    title: 'Oops!',
                    text: 'ไม่พบรายการคิว',
                });
            }
        },
        onRecall: function () {
            const vm = this
            if (vm.tblCalling && !vm.isEmpty(vm.dataOnState.info)) {
                var $counter = vm.getCounterLabel() || '';
                vm.tblCalling.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    if (vm.dataOnState.queueNumber === data.que_num) {
                        Swal.fire({
                            title: 'เรียกคิว ' + data.que_num + ' ?',
                            text: $counter,
                            html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${data.pt_name}</p>`,
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'เรียกคิว',
                            cancelButtonText: 'ยกเลิก',
                            confirmButtonColor: '#62cb31',
                            cancelButtonColor: '#e74c3c',
                            allowOutsideClick: false,
                            showLoaderOnConfirm: true,
                            preConfirm: function () {
                                return new Promise(function (resolve, reject) {
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/recall?caller_ids=" + data.caller_ids,
                                        dataType: "json",
                                        data: {
                                            data: data, //Data in column Datatable
                                            modelProfile: vm.modelProfile,
                                            formData: vm.formData,
                                        },
                                        success: function (response) {
                                            vm.dataOnState = vm.updateObject(vm.dataOnState, {
                                                name: data.pt_name,
                                                queueNumber: data.que_num,
                                                info: data,
                                                event: 'RECALL_CALLING'
                                            });
                                            socket.emit('on-call', $.extend({event_on: 'table_calling'}, response));
                                            resolve();
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                            },
                        }).then((result) => {
                            if (result.value) { //Confirm
                                Swal.close()
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    type: 'warning',
                    title: 'Oops!',
                    text: 'ไม่พบรายการคิว',
                });
            }
        },
        onHold: function () {
            const vm = this
            if (vm.tblCalling && !vm.isEmpty(vm.dataOnState.info)) {
                var $counter = vm.getCounterLabel() || '';
                vm.tblCalling.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    if (vm.dataOnState.queueNumber === data.que_num) {
                        Swal.fire({
                            title: 'พักคิว ' + data.que_num + ' ?',
                            text: $counter,
                            html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${data.pt_name}</p>`,
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'พักคิว',
                            cancelButtonText: 'ยกเลิก',
                            confirmButtonColor: '#62cb31',
                            cancelButtonColor: '#e74c3c',
                            allowOutsideClick: false,
                            showLoaderOnConfirm: true,
                            preConfirm: function () {
                                return new Promise(function (resolve, reject) {
                                    $.ajax({
                                        method: "POST",
                                        url: "/app/calling/hold?caller_ids=" + data.caller_ids,
                                        dataType: "json",
                                        data: {
                                            data: data, //Data in column Datatable
                                            modelProfile: vm.modelProfile,
                                            formData: vm.formData,
                                        },
                                        success: function (response) {
                                            vm.dataOnState = vm.updateObject(vm.dataOnState, {
                                                name: '-',
                                                queueNumber: '',
                                                info: null,
                                                event: null
                                            });
                                            vm.fetchDataCalling();
                                            vm.fetchDataHold();
                                            socket.emit('on-hold', $.extend({event_on: 'table_calling'}, response));
                                            resolve();
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            vm.handleAjaxError(textStatus, errorThrown);
                                        }
                                    });
                                });
                            },
                        }).then((result) => {
                            if (result.value) { //Confirm
                                Swal.close()
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    type: 'warning',
                    title: 'Oops!',
                    text: 'ไม่พบรายการคิว',
                });
            }
        },
        onEnd: function () {
            const vm = this
            if (!vm.isEmpty(vm.dataOnState.info)) {
                var $counter = vm.getCounterLabel() || '';
                if (vm.tblCalling) {
                    vm.tblCalling.rows().every(function (rowIdx, tableLoop, rowLoop) {
                        var data = this.data();
                        if (vm.dataOnState.queueNumber === data.que_num) {
                            Swal.fire({
                                title: 'เสร็จสิ้น คิว ' + data.que_num + ' ?',
                                text: $counter,
                                html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${data.pt_name}</p>`,
                                type: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'เสร็จสิ้น',
                                cancelButtonText: 'ยกเลิก',
                                confirmButtonColor: '#62cb31',
                                cancelButtonColor: '#e74c3c',
                                allowOutsideClick: false,
                                showLoaderOnConfirm: true,
                                preConfirm: function () {
                                    return new Promise(function (resolve, reject) {
                                        $.ajax({
                                            method: "POST",
                                            url: "/app/calling/end?caller_ids=" + data.caller_ids,
                                            dataType: "json",
                                            data: {
                                                data: data, //Data in column Datatable
                                                modelProfile: vm.modelProfile,
                                                formData: vm.formData,
                                            },
                                            success: function (response) {
                                                vm.dataOnState = vm.updateObject(vm.dataOnState, {
                                                    name: '-',
                                                    queueNumber: '',
                                                    info: null,
                                                    event: null
                                                });
                                                vm.fetchDataCalling();
                                                socket.emit('on-end', $.extend({event_on: 'table_calling'}, response));
                                                resolve();
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                vm.handleAjaxError(textStatus, errorThrown);
                                            }
                                        });
                                    });
                                },
                            }).then((result) => {
                                if (result.value) { //Confirm
                                    Swal.close()
                                }
                            });
                            return true;
                        }
                    });
                }
                if (vm.tblHold) {
                    vm.tblHold.rows().every(function (rowIdx, tableLoop, rowLoop) {
                        var data = this.data();
                        if (vm.dataOnState.queueNumber === data.que_num) {
                            Swal.fire({
                                title: 'เสร็จสิ้น คิว ' + data.que_num + ' ?',
                                text: $counter,
                                html: `<p>${$counter}</p><p><i class="fa fa-user"></i> ${data.pt_name}</p>`,
                                type: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'เสร็จสิ้น',
                                cancelButtonText: 'ยกเลิก',
                                confirmButtonColor: '#62cb31',
                                cancelButtonColor: '#e74c3c',
                                allowOutsideClick: false,
                                showLoaderOnConfirm: true,
                                preConfirm: function () {
                                    return new Promise(function (resolve, reject) {
                                        $.ajax({
                                            method: "POST",
                                            url: "/app/calling/end?caller_ids=" + data.caller_ids,
                                            dataType: "json",
                                            data: {
                                                data: data, //Data in column Datatable
                                                modelProfile: vm.modelProfile,
                                                formData: vm.formData,
                                            },
                                            success: function (response) {
                                                vm.dataOnState = vm.updateObject(vm.dataOnState, {
                                                    name: '-',
                                                    queueNumber: '',
                                                    info: null,
                                                    event: null
                                                });
                                                vm.fetchDataHold();
                                                socket.emit('on-end', $.extend({event_on: 'table_hold'}, response));
                                                resolve();
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                vm.handleAjaxError(textStatus, errorThrown);
                                            }
                                        });
                                    });
                                },
                            }).then((result) => {
                                if (result.value) { //Confirm
                                    Swal.close()
                                }
                            });
                            return true;
                        }
                    });
                }
            } else {
                Swal.fire({
                    type: 'warning',
                    title: 'Oops!',
                    text: 'ไม่พบรายการคิว',
                });
            }
        },
        updateObject: function (oldObject, updatedProperties) {
            return {
                ...oldObject,
                ...updatedProperties,
            };
        },
        handleAjaxError: function (textStatus, errorThrown) {
            Swal.fire({
                type: 'error',
                title: textStatus,
                text: errorThrown,
            });
        },
        isEmpty: function (value, trim) {
            return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
        },
        onCallSearch: function (data) {
            const vm = this;
            Swal.showLoading();
            $('.swal2-footer').hide();
            $.ajax({
                method: "POST",
                url: "/app/calling/recall?caller_ids=" + data.caller_ids,
                dataType: "json",
                data: {
                    data: data, //Data in column Datatable
                    modelProfile: vm.modelProfile,
                    formData: vm.formData,
                },
                success: function (response) {
                    vm.dataOnState = vm.updateObject(vm.dataOnState, {
                        name: data.pt_name,
                        queueNumber: data.que_num,
                        info: data,
                        event: 'RECALL_CALLING'
                    });
                    Swal.close();
                    $('#modalSearch').modal('hide');
                    socket.emit('on-call', $.extend({event_on: 'table_calling'}, response));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        onHoldSearch: function (data) {
            const vm = this;
            Swal.showLoading();
            $('.swal2-footer').hide();
            $.ajax({
                method: "POST",
                url: "/app/calling/hold?caller_ids=" + data.caller_ids,
                dataType: "json",
                data: {
                    data: data, //Data in column Datatable
                    modelProfile: vm.modelProfile,
                    formData: vm.formData,
                },
                success: function (response) {
                    if (data.que_num === vm.dataOnState.queueNumber) {
                        vm.dataOnState = vm.updateObject(vm.dataOnState, {
                            name: '-',
                            queueNumber: '',
                            info: null,
                            event: null
                        });
                    }
                    vm.fetchDataCalling();
                    vm.fetchDataHold();
                    Swal.close();
                    $('#modalSearch').modal('hide');
                    socket.emit('on-hold', $.extend({event_on: 'table_calling'}, response));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    vm.handleAjaxError(textStatus, errorThrown);
                }
            });
        },
        toggleAction() {
            const vm = this
            vm.showAction = !vm.showAction;
            if(!vm.showAction){
                $('.panel-body-calling').css('width', '70px').css('height', '70px');
            } else {
                $('.panel-body-calling').css('width', '').css('height', '');
            }
        }
    },
    components: {
        Select2
    },
    mounted: function () {
        this.fetchDataOptionProfile()
        this.initDataTableWait()
        this.initDataTableCalling()
        this.initDataTableHold()
    },
    computed: {
        // a computed getter
        uppercaseSearch: function () {
            // `this` points to the vm instance
            return this.search.toUpperCase();
        }
    }
});

$('#modalSearch').on('hidden.bs.modal', function (e) {
    app.search = '';
}).on('show.bs.modal', function (event) {
    app.search = '';
    setTimeout(() => {
        app.$refs.input.focus()
    }, 500)
});

$('#tbl-wait tbody').on('click', 'tr', function () {
    var table = $('#tbl-wait').DataTable();
    $('#tbl-wait tbody').find('tr.info').removeClass('info');
    $(this).toggleClass('info');
    var idx = table
        .row(this)
        .index();
    var rows = table.rows(idx).data();
    if (rows.hasOwnProperty(0)) {
        app.search = rows[0].que_num;
        app.onSubmit()
    }
});

$('#tbl-calling tbody').on('click', 'tr', function () {
    var table = $('#tbl-calling').DataTable();
    $('#tbl-calling tbody').find('tr.info').removeClass('info');
    $(this).toggleClass('info');
    var idx = table
        .row(this)
        .index();
    var rows = table.rows(idx).data();
    if (rows.hasOwnProperty(0)) {
        app.search = rows[0].que_num;
        app.onSubmit()
    }
});

$('#tbl-hold tbody').on('click', 'tr', function () {
    var table = $('#tbl-hold').DataTable();
    $('#tbl-hold tbody').find('tr.info').removeClass('info');
    $(this).toggleClass('info');
    var idx = table
        .row(this)
        .index();
    var rows = table.rows(idx).data();
    if (rows.hasOwnProperty(0)) {
        app.search = rows[0].que_num;
        app.onSubmit()
    }
});

checkFormData = (res) => {
    if (!app.isEmpty(app.formData)) {
        if (res.formData.service_profile_id == app.formData.service_profile_id &&
            res.formData.counter_service_id == app.formData.counter_service_id) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

$(function () {
    socket.on('register', (res) => { //อกกบัตรคิว
        if (app.formData.service_id.includes(res.modelQue.service_id.toString()) && !app.isEmpty(app.formData)) {
            toastr.warning('#' + res.modelQue.que_num + '<p><i class="fa fa-user"></i> ' + res.modelQue.pt_name + '</p>', 'คิวใหม่!', {
                "timeOut": 5000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
            app.fetchDataWaiting();
        }
    }).on('on-call', (res) => { // เรียกคิว
        if (res.event_on === 'table_wait') {
            app.fetchDataWaiting(); // โหลดข้อมูลคิวรอเรียก
        }
        if (checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            if (res.event_on === 'table_wait') {
                app.fetchDataCalling(); // โหลดข้อมูลคิวกำลังเรียก
            } else if (res.event_on === 'table_hold') {
                app.fetchDataCalling(); // โหลดข้อมูลคิวกำลังเรียก
                app.fetchDataHold(); // โหลดข้อมูลพักคิว
            }
        }
    }).on('on-hold', (res) => { // พักคิว
        if (checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            app.fetchDataCalling(); // โหลดข้อมูลคิวกำลังเรียก
            app.fetchDataHold(); // โหลดข้อมูลพักคิว
            if (res.data.que_num === app.dataOnState.queueNumber) {
                app.dataOnState = app.updateObject(app.dataOnState, {
                    name: '-',
                    queueNumber: '',
                    info: null,
                    event: null
                });
            }
        }
    }).on('on-end', (res) => { // เสร็จสิ้นคิว
        if (res.event_on === 'table_wait') { // ถ้าเสร็จสิ้นจากคิวรอเรียก
            app.fetchDataWaiting(); // โหลดข้อมูลคิวรอเรียก
        }
        if (checkFormData(res)) { // ถ้าเป็นเซอร์วิสโปรไฟล์และจุดบริการเดียวกัน
            if (res.event_on === 'table_calling') {
                app.fetchDataCalling(); // โหลดข้อมูลคิวกำลังเรียก
            } else if (res.event_on === 'table_hold') {
                app.fetchDataCalling(); // โหลดข้อมูลคิวกำลังเรียก
                app.fetchDataHold(); // โหลดข้อมูลพักคิว
            }
            if (res.data.que_num === app.dataOnState.queueNumber) {
                app.dataOnState = app.updateObject(app.dataOnState, {
                    name: '-',
                    queueNumber: '',
                    info: null,
                    event: null
                });
            }
        }
    }).on('on-show-display', (res) => {
        if (!app.isEmpty(app.formData)) {
            if (res.artist.formData.service_profile_id == app.formData.service_profile_id &&
                res.artist.formData.counter_service_id == app.formData.counter_service_id) {
                toastr.warning('<i class="fa fa-user"></i> ' + res.artist.modelQue.pt_name + '</p>', 'กำลังเรียกคิว ' + res.title, {
                    "timeOut": 3000,
                    "positionClass": "toast-top-right",
                    "progressBar": true,
                    "closeButton": true,
                });
            }
        }
    });
});
