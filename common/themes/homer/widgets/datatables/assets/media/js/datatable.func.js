dtFunc = {
    initConfirm: function (e) {
        $(e + ' tbody').on('click', 'tr td a[data-method="post"]', function (event) {
            event.preventDefault();
            var elm = this;
            var url = $(elm).attr('href');
            var table = $('#' + $(this).closest('table').attr('id')).DataTable();
            var message = $(elm).attr('data-confirm');
            if (typeof url !== typeof undefined && url !== false || typeof url !== typeof undefined && url !== false) {
                swal({
                    title: '' + message + '',
                    text: "",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "ยืนยัน",
                    cancelButtonText: "ยกเลิก",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                type: $(elm).attr('data-method'),
                                url: $(elm).attr('href'),
                                success: function (data, textStatus, jqXHR) {
                                    table.ajax.reload();
                                    swal({
                                        type: "success",
                                        title: "ลบรายการสำเร็จ!",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    resolve();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal({
                                        type: "error",
                                        title: errorThrown,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                },
                                dataType: "json"
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) {
                        swal({
                            type: "success",
                            title: "ลบรายการสำเร็จ!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
            return false;
        });
    },
    initSelect2: function(api,col){
        $.each(col, function( index, value ) {
            api.columns(value.col).every( function () {
                var column = this;
                var id = 'select2-'+column.index() + '-' +api.table().node().id;
                var colheader = this.header();
                var placeholder = $(colheader).text().trim();
                $('<p></p>').appendTo( colheader );
                var select = $('<select id="'+id+'" class="dt-select2"><option value="" >All</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d.replace(/<[^>]+>/g, '')+'">'+d.replace(/<[^>]+>/g, '')+'</option>' )
                } );
                var select2Options = {"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":value.title,"language":"th","sizeCss":"input-sm"};
                if (jQuery('#'+id).data('select2')) {
                    jQuery('#'+id).select2('destroy');
                }
                jQuery.when(jQuery('#'+id).select2(select2Options)).done(initS2Loading(id,'select2Options'));
                $("#"+id + ",span.select2-container--bootstrap").addClass("input-sm");
            } );
        });
    },
    initColumnIndex: function(api){
        api.on( 'order.dt search.dt draw.dt', function () {
            api.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    },
};