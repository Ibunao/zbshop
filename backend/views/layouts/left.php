<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>趣途</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
       <!--  <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '后台管理', 'options' => ['class' => 'header']],
                    [
                        'label' => '商品管理',
                        'icon' => 'file-code-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '商品列表', 'icon' => 'file-code-o', 'url' => ['/goods/index'],],
                            ['label' => '添加商品', 'icon' => 'dashboard', 'url' => ['/goods/create'],],
                            ['label' => '分类设置', 'icon' => 'dashboard', 'url' => ['/cate/index'],],
                            ['label' => '首页管理', 'icon' => 'dashboard', 'url' => ['/homepage/index'],],
                            ['label' => '更改库存', 'icon' => 'dashboard', 'url' => ['/product/index'],],
                            // [
                            //     'label' => '待添加',
                            //     'icon' => 'circle-o',
                            //     'url' => '#',
                            //     'items' => [
                            //         ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                            //         [
                            //             'label' => 'Level Two',
                            //             'icon' => 'dashboard',
                            //             'url' => '#',
                            //             'items' => [
                            //                 ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                            //                 ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                            //             ],
                            //         ],
                            //     ],
                            // ],
                        ],
                    ],
                    [
                        'label' => '订单管理',
                        'icon' => 'file-code-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '订单列表', 'icon' => 'file-code-o', 'url' => ['/orders/index'],],
                        ],
                    ],
                    [
                        'label' => '代理商管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '代理商审核', 'icon' => 'file-code-o', 'url' => ['/agent/index'],],
                            ['label' => '代理商用户', 'icon' => 'dashboard', 'url' => ['/customer/index'],],
                            // [
                            //     'label' => '待添加',
                            //     'icon' => 'circle-o',
                            //     'url' => '#',
                            //     'items' => [
                            //         ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                            //         [
                            //             'label' => 'Level Two',
                            //             'icon' => 'circle-o',
                            //             'url' => '#',
                            //             'items' => [
                            //                 ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                            //                 ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                            //             ],
                            //         ],
                            //     ],
                            // ],
                        ],
                    ],
                    [
                        'label' => '其他管理',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => '图文消息', 'icon' => 'file-code-o', 'url' => ['/wchat/index'],],
                        ],
                    ],
                ],
            ]
        ) ?>
<!--         <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '商品编辑', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?> -->
    </section>

</aside>
