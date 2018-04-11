<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'wechatUrl' => 'http://wx.quutuu.com',
    'adminUrl' => 'http://admin.quutuu.com',
    'frontendUrl' => 'http://www.quutuu.com',
    
    // 分类，初期为配置文件的形式。
    'categories' => [
    	1 => [
    		'name' => '户外运动/登山野营/旅行装备',
    		'level' => 1,
    		'parentId' => 0,
    	],
    	2 => [
    		'name' => '户外服装',
    		'level' => 2,
    		'parentId' => 1,
    	],
    	3 => [
    		'name' => '冲锋衣裤',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	4 => [
    		'name' => '冲锋衣',
    		'level' => 4,
    		'parentId' => 3,
    	],
    	5 => [
    		'name' => '冲锋裤',
    		'level' => 4,
    		'parentId' => 3,
    	],
    	6 => [
    		'name' => '户外棉服',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	7 => [
    		'name' => '户外棉服',
    		'level' => 4,
    		'parentId' => 6,
    	],
    	7 => [
    		'name' => '户外棉裤',
    		'level' => 4,
    		'parentId' => 6,
    	],
    	8 => [
    		'name' => '户外鞋袜',
    		'level' => 2,
    		'parentId' => 1,
    	],
    	9 => [
    		'name' => '户外袜子',
    		'level' => 3,
    		'parentId' => 8,
    	],
    	10 => [
    		'name' => '户外鞋子',
    		'level' => 3,
    		'parentId' => 8,
    	],
    	11 => [
    		'name' => '运动鞋',
    		'level' => 1,
    		'parentId' => 0,
    	],
    	12 => [
    		'name' => '运动篮球鞋',
    		'level' => 2,
    		'parentId' => 11,
    	],
    	13 => [
    		'name' => '运动跑步鞋',
    		'level' => 2,
    		'parentId' => 11,
    	],
    ],
];
