<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 21:51
 */

namespace homer\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Menu as BaseMenu;
use common\modules\menu\models\Menu as ModelMenu;

class Menu extends BaseMenu
{
    public $key;

    public $linkTemplate = '<a href="{url}"><span class="nav-label">{icon}{label}</span></a>';

    public $submenuTemplate = "\n<ul class='nav nav-second-level'>\n{items}\n</ul>\n";

    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $key = 'app-menus';
        $this->items = $session->has($key) && $session->get($key) ? $session->get($key) : null;
        if (Yii::$app->user->isGuest) {
            $mainMenus = ModelMenu::find()
                ->innerJoin('menu_category', 'menu.menu_category_id = menu_category.id')
                ->where([
                    'menu_category.title' => $this->key,
                    'menu.status' => 1,
                    'menu.parent_id' => null,
                ])
                ->andWhere('menu.auth_items LIKE :query')
                ->addParams([':query' => '%Guest%'])
                ->orderBy(['`sort`' => SORT_ASC])
                ->all();
            $parentMenus = ModelMenu::find()
                ->innerJoin('menu_category', 'menu.menu_category_id = menu_category.id')
                ->where([
                    'menu_category.title' => $this->key,
                    'menu.status' => 1,
                ])
                ->andWhere('menu.auth_items LIKE :query')
                ->addParams([':query' => '%Guest%'])
                ->andWhere('menu.parent_id IS NOT NULL')
                ->orderBy(['menu.`sort`' => SORT_ASC])
                ->all();
            $groupParents = ArrayHelper::index($parentMenus, null, 'parent_id');
            $this->items = $this->renderMenuItems($mainMenus, $groupParents);
        } elseif ($this->items == null) {
            $mainMenus = ModelMenu::find()
                ->innerJoin('menu_category', 'menu.menu_category_id = menu_category.id')
                ->where([
                    'menu_category.title' => $this->key,
                    'menu.status' => 1,
                    'menu.parent_id' => null,
                ])
                ->orderBy(['`sort`' => SORT_ASC])
                ->all();
            $parentMenus = ModelMenu::find()
                ->innerJoin('menu_category', 'menu.menu_category_id = menu_category.id')
                ->where([
                    'menu_category.title' => $this->key,
                    'menu.status' => 1,
                ])
                ->andWhere('menu.parent_id IS NOT NULL')
                ->orderBy(['menu.`sort`' => SORT_ASC])
                ->all();
            $groupParents = ArrayHelper::index($parentMenus, null, 'parent_id');
            $this->items = $this->renderMenuItems($mainMenus, $groupParents);

            $session->set($key, $this->items);
        }
    }

    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
            if (!empty($item['items'])) {
                $template = '<a href="{url}"><span class="nav-label">{icon}{label}</span><span class="fa arrow"></span></a>';
            }
            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => Yii::t('frontend', $item['label']),
                '{icon}' => isset($item['label']) ? Icon::show($item['icon'], ['style' => 'font-size:1.3em;']) : '',
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label'],
        ]);
    }

    private function renderMenuItems($mainMenus, $groupParents)
    {
        $items = [];
        foreach ($mainMenus as $key => $mainMenu) {
            $visible = false;
            $auth_items = !empty($mainMenu['auth_items']) ? Json::decode($mainMenu['auth_items']) : null;
            if ($auth_items && !Yii::$app->user->isGuest) {
                foreach ($auth_items as $item) {
                    if ($visible = Yii::$app->user->can($item)) {
                        break;
                    }
                }
            }
            $option = [
                'label' => $mainMenu['title'],
                'icon' => $mainMenu['icon'],
                'url' => [$mainMenu['router']],
                'visible' => Yii::$app->user->isGuest ? true : $visible,
            ];
            if (isset($groupParents[$mainMenu['id']])) {
                $option['items'] = $this->renderMenuItems($groupParents[$mainMenu['id']], $groupParents);
            }
            $items[] = $option;
        }
        return $items;
    }
}