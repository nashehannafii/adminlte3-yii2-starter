<?php

namespace app\helpers;

use Yii;
use yii\helpers\Url;

/**
 * Css helper class.
 */
class MenuHelper
{
    public static function getMenuItems()
    {

        $menuItems = [];

        // $currentRoute = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;


        $menuItems[] = ['label' => 'SITE', 'header' => true];
        if (!Yii::$app->user->isGuest) {
            $menuItems[] = [
                'label' => 'Dashboard',
                'icon' => 'tachometer-alt',
                'badge' => '<span class="right badge badge-danger">New</span>'
            ];
        }
        // $menuItems[] = [
        //     'label' => 'Starter Pages',
        //     'icon' => 'tachometer-alt',
        //     'badge' => '<span class="right badge badge-info">2</span>',
        //     'items' => [
        //         ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
        //         ['label' => 'Contact', 'url' => ['site/contact'], 'iconStyle' => 'far'],
        //         ['label' => 'Inactive Page', 'iconStyle' => 'far'],
        //     ]
        // ];
        $menuItems[] = [
            'label' => 'Login',
            'url' => ['site/login'],
            'icon' => 'sign-in-alt',
            'visible' => Yii::$app->user->isGuest
        ];

        if (!Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'MASTER', 'header' => true];
            $menuItems[] = [
                'label' => 'Rack Level',
                'icon' => 'bars',
                'url' => ['rack-level'],
                'badge' => '<span class="right badge badge-danger">New</span>'
            ];
            $menuItems[] = [
                'label' => 'Gii',
                'icon' => 'file-code',
                'url' => ['/gii'],
                'target' => '_blank',
                'visible' => Yii::$app->user->can('theCreator')
            ];
            $menuItems[] = [
                'label' => 'Debug',
                'icon' => 'bug',
                'url' => ['/debug'],
                'target' => '_blank',
                'visible' => Yii::$app->user->can('theCreator')
            ];
            $menuItems[] = [
                'label' => 'User',
                'icon' => 'users',
                'url' => ['user'],
                // 'target' => '_blank',
                // 'visible' => Yii::$app->user->can('theCreator')
            ];
        }
        // $menuItems[] = ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true];
        // $menuItems[] = ['label' => 'Level1'];
        // $menuItems[] = [
        //     'label' => 'Level1',
        //     'items' => [
        //         ['label' => 'Level2', 'iconStyle' => 'far'],
        //         [
        //             'label' => 'Level2',
        //             'iconStyle' => 'far',
        //             'items' => [
        //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
        //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
        //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
        //             ]
        //         ],
        //         ['label' => 'Level2', 'iconStyle' => 'far']
        //     ]
        // ];
        // $menuItems[] = ['label' => 'LABELS', 'header' => true];
        // $menuItems[] = ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'];
        // $menuItems[] = ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'];
        // $menuItems[] = ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'];


        return $menuItems;
    }

    // public static function getTopMenus()
    // {
    //     $menuItems = [];
    //     $list_apps = [];
    //     if (!Yii::$app->user->isGuest) {
    //         $key = Yii::$app->params['jwt_key'];
    //         $session = Yii::$app->session;
    //         if ($session->has('token')) {
    //             $token = $session->get('token');
    //             try {
    //                 $decoded = \Firebase\JWT\JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
    //                 foreach ($decoded->apps as $d) {
    //                     $list_apps[] = [
    //                         'template' => '<a target="_blank" href="{url}">{label}</a>',
    //                         'label' => $d->app_name,
    //                         'url' => $d->app_url . $token
    //                     ];
    //                 }
    //             } catch (\Exception $e) {
    //                 // return Yii::$app->response->redirect(Yii::$app->params['sso_login']);
    //             }
    //         }
    //     }

    //     if (!Yii::$app->user->isGuest) {

    //         $menuItems[] = [
    //             'template' => '<a id="list_notif" href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="lnr lnr-alarm"></i> <span class="badge bg-danger" id="count-notif"></span></a>',
    //             'label' => '',
    //             // 'submenuTemplate' => "\n<ul class='dropdown-menu notifications'>\n{items}\n</ul>\n",
    //             // 'items' => $list_apps

    //         ];

    //         $menuItems[] = [
    //             'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-layers"></i> <span>{label}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>',
    //             'label' => 'Your apps',
    //             'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
    //             'items' => $list_apps

    //         ];

    //         $menuItems[] = [
    //             'template' => '<a id="access_role" href="{url}" class="icon-menu">{label}</a>',
    //             'label' => ' Peran: <strong>' . Yii::$app->user->identity->access_role . '</strong>',
    //             'url' => ['user/change']
    //             // 'submenuTemplate' => "\n<ul class='dropdown-menu notifications'>\n{items}\n</ul>\n",
    //             // 'items' => $list_apps

    //         ];

    //         $menuItems[] = [
    //             'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-user"></i> <span>{label}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>',
    //             'label' => Yii::$app->user->identity->display_name,
    //             'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
    //             'items' => [
    //                 [
    //                     'template' => '<a href="{url}">{label}</a>',
    //                     'label' => '<i class="fa fa-user"></i>My Profile',
    //                     'url' => ['user/profil']
    //                 ],
    //                 [
    //                     'template' => '<a href="{url}">{label}</a>',
    //                     'label' => '<i class="fa fa-users"></i> Change Role',
    //                     'url' => ['user/change']
    //                 ],
    //                 [
    //                     'template' => '<a href="{url}">{label}</a>',
    //                     'label' => '<i class="fa fa-cogs"></i> Setting',
    //                     'url' => ['simak-matakuliah/setting'],
    //                     'visible' => Yii::$app->user->can('baak')

    //                 ],
    //                 [
    //                     'template' => '<a href="{url}">{label}</a>',
    //                     'label' => '<i class="fa fa-cogs"></i> Setting Jadwal',
    //                     'url' => ['simak-settings/index'],
    //                     'visible' => Yii::$app->user->can('akademik')

    //                 ],
    //                 [
    //                     'template' => '<a href="{url}">{label}</a>',
    //                     'label' => '<i class="fa fa-list"></i> Log',
    //                     'url' => ['log-catatan/index'],
    //                     'visible' => Yii::$app->user->can('theCreator')

    //                 ],
    //                 [
    //                     'template' => '<a href="{url}" data-method="POST">{label}</a>',
    //                     'label' => '<i class="fa fa-sign-out"></i>Sign Out',
    //                     'url' => ['site/logout']
    //                 ]
    //             ]

    //         ];
    //     }


    //     return $menuItems;
    // }

    public static function getUserMenus()
    {
        $menuItems = [];

        if (!Yii::$app->user->isGuest) {


            $menuItems[] = [
                'template' => '<a data-widget="pushmenu" href="{url}" role="button" class="nav-link">{label}</a>',
                'label' => '<i class="fas fa-bars"></i>',
                'url' => '#'
            ];
        }


        return $menuItems;
    }
}
