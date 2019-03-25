<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 19/3/2562
 * Time: 9:23
 */
use homer\widgets\Icon;

$template = '<a href="{url}" class="page-scroll"><div class="icon">{icon}</div><div class="h1">{label}</div></a>';
$templateToggle = '<a href="{url}" class="page-scroll" data-toggle="tab"><div class="icon">{icon}</div><div class="h1">{label}</div></a>';
echo \homer\widgets\MobileMenu::widget([
    'items' => [
        [
            'label' => 'ค้นหา',
            'icon' => Icon::show('search', ['class' => 'pe-2x', 'framework' => Icon::PE7S]),
            'url' => 'javascript:void(0);',
            'template' => $template,
            'options' => ['data-toggle' => 'modal', 'data-target' => '#modalSearch']
        ],
        [
            'label' => 'เรียกคิว',
            'icon' => Icon::show('volume', ['class' => 'pe-2x', 'framework' => Icon::PE7S]),
            'url' => '#tab-1',
            'options' => ['class' => 'active'],
            'template' => $templateToggle,
        ],
        [
            'label' => 'คิวเรียก <span class="badge badge-success badge-count-calling">0</span>',
            'icon' => Icon::show('note2', ['class' => 'pe-2x', 'framework' => Icon::PE7S]),
            'url' => '#tab-2',
            'template' => $templateToggle,
        ],
        [
            'label' => 'คิวพัก <span class="badge badge-success badge-count-hold">0</span>',
            'icon' => Icon::show('note2', ['class' => 'pe-2x', 'framework' => Icon::PE7S]),
            'url' => '#tab-3',
            'template' => $templateToggle,
        ],
        [
            'label' => 'ตั้งค่า',
            'icon' => Icon::show('tools', ['class' => 'pe-2x', 'framework' => Icon::PE7S]),
            'url' => 'javascript:void(0);',
            'template' => '<a href="{url}" class="page-scroll right-sidebar-toggle" id="sidebar"><div class="icon">{icon}</div><div class="h1">{label}</div></a>',
        ],
    ],
    'options' => [
        //'class' => 'hidden-lg hidden-md',
    ],
    'encodeLabels' => false
]);