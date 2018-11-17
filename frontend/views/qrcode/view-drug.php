<style>
.table-hover>tbody>tr>.active:hover, .table-hover>tbody>.active:hover>td, .table-hover>tbody>.active:hover>th, .table-striped>tbody>tr.active:hover:nth-child(odd)>td, .table-striped>tbody>tr>.active:hover:nth-child(odd)>th {
    background-color: #e5e5e5 !important;
}
.table tr td.active {
    box-shadow: 2px 0 0 #737373 inset;
}
.table tr td.danger {
    box-shadow: 2px 0 0 #d73d32 inset;
}
.table tr td.warning {
    box-shadow: 2px 0 0 #f4b400 inset;
}
.table tr td.success {
    box-shadow: 2px 0 0 #53a93f inset;
}
.modal-title{
    font-size: 18px;
}
</style>
<div class="row">
    <div class="col-md-12 text-center">
        <div class="alert alert-success" role="alert"><?= $modelQue['pt_name'] ?></div>
    </div>
</div>
<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>
                เลขที่ใบสั่งยา
            </th>
            <th>
                วันที่
            </th>
            <th>
                HN
            </th>
            <th>
                ชื่อยา
            </th>
            <th>
                วิธีใช้ยา
            </th>
            <th>
                จำนวนยาที่ได้รับ
            </th>
            <th>
                หน่วย
            </th>
            <th>
                จ่ายเอง
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($query as $data): ?>    
            <tr>
                <td>
                    <?= $data['RX_NUM'] ?>
                </td>
                <td class="text-center">
                    <?= $data['RX_DATE'] ?>
                </td>
                <td>
                    <?= $data['hn'] ?>
                </td>
                <td>
                    <?= $data['DRUG_ANME'] ?>
                </td>
                <td>
                    <?= $data['SIG'] ?>
                </td>
                <td>
                    <?= $data['PT_NAME'] ?>
                </td>
                <td>
                    <?= $data['DRUG_UNIT'] ?>
                </td>
                <td>
                    <?= $data['RX_PAY'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>