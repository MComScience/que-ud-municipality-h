var Que = {
    reloadDisplay: function () {
        var table = $('#tb-display').DataTable();
        table.ajax.reload();
    },
    reloadLastq: function () {
        var table = $('#tb-lastq').DataTable();
        table.ajax.reload();
    },
    reloadHold: function () {
        var table = $('#tb-hold').DataTable();
        table.ajax.reload();
    },
    removeRow: function (res) {
        var table = $('#tb-display').DataTable();
        table.row('#' + res.modelQue.que_num).remove().draw();
    },
    addRows: function(){
        var table = $('#tb-display').DataTable();
        if (table.rows().data().length < config.page_length) {
            for (i = table.rows().data().length; i < config.page_length; i++) {
                table.row.add( {
                    "counter_number": "<span class=\"\">-</span>",
                    "counter_service_call_number": "-",
                    "data": [],
                    "que_number": "-",
                } ).draw();
            }
        }
    },
    blink: function (res) { //สั่งกระพริบ
        if (config.que_column_length > 1) {
            $('span.' + res.title + ', .' + res.artist.modelCounterService.counter_service_call_number).modernBlink({
                duration: 1000,
                iterationCount: 7,
                auto: true
            });
        } else {
            $('.' + res.title).modernBlink({
                duration: 1000,
                iterationCount: 7,
                auto: true
            });
        }
    },
};

//Socket Events
$(function () {
    socket.on('on-show-display', (res) => { //เรียกคิว
        if (jQuery.inArray(res.artist.modelCaller.counter_service_id.toString(), counters) !== -1 &&
            jQuery.inArray(res.artist.modelQue.service_id.toString(), services) !== -1) {
            Que.reloadDisplay();
            Que.reloadLastq();

            //ถ้าเป็นคิวที่เรียกที่รายการพักคิว
            if (res.artist.event_on === 'table_hold' && config.show_que_hold == 1) {
                var table = $('#tb-hold').DataTable();
                table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    if (jQuery.inArray(res.artist.modelQue.que_num.toString(), data.data) !== -1) { //ถ้ามีรายการคิวที่แสดงให้โหลดข้อมูลใหม่
                        Que.reloadHold();
                    }
                });
            }
            setTimeout(function () {
                Que.blink(res); //สั่งกระพริบ
            }, 1000);
        }
    }).on('on-hold', (res) => {
        if (jQuery.inArray(res.modelCaller.counter_service_id.toString(), counters) !== -1 &&
            jQuery.inArray(res.modelQue.service_id.toString(), services) !== -1) {
            if (config.show_que_hold == 1) {
                Que.reloadHold();
            }
            Que.removeRow(res);
            $('.' + res.modelQue.que_num).html('-');
            Que.addRows();
        }
    }).on('update-display', (res) => {
        if (res.model.display_ids == config.display_ids) {
            location.reload();
        }
    }).on('on-end', (res) => {
        if (jQuery.inArray(res.modelCaller.counter_service_id.toString(), counters) !== -1 &&
            jQuery.inArray(res.modelQue.service_id.toString(), services) !== -1) {
            if (config.show_que_hold == 1) {
                Que.reloadHold();
            }
            Que.removeRow(res);
            $('.' + res.modelQue.que_num).html('-');
            Que.addRows();
            //$('.' + res.modelQue.que_num).remove();
        }
    });
});

$(document).ready(function(){
    if($(".clock-display")[0]){
        var a = new Date();
        a.setDate(a.getDate()),
        setInterval(function(){
            var a=(new Date()).getSeconds();
            $(".time__sec").html((a<10?"0":"")+a);
        },1e3);
        setInterval(function(){
            var a=(new Date()).getMinutes();
            $(".time__min").html((a<10?"0":"")+a);
        },1e3);
        setInterval(function(){
            var a=(new Date()).getHours();
            $(".time__hours").html((a<10?"0":"")+a);
        },1e3);
    }
});