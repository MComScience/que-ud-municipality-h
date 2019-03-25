<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 18/3/2562
 * Time: 22:00
 */
?>
<div id="right-sidebar" class="animated fadeInRight">
    <div class="pull-right" style="padding: 5px">
        <p>
            <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-danger m-b-md">
                <i class="pe pe-7s-close"></i>
            </button>
        </p>
    </div>
    <ul class="nav nav-menu-more" style="margin-top: 40px;border-top: 1px solid #e5e5e5;">
        <li><a href="#"><i class="fa fa-circle-o"></i> ตั้งค่า</a></li>
    </ul>
    <div class="form-group">
        <select2 :options="profileOptions"
                 id="select2-profile"
                 v-on:change-selection="onChangeSelection">
            <option value>เซอร์วิสโปรไฟล์</option>
        </select2>
    </div>
</div>
