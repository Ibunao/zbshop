<?php
return [
	// 分类，初期为配置文件的形式。
    'categories' => json_decode(file_get_contents("./catelist.json"), true),
    [
    	1 => [
    		'name' => '户外运动',
    		'level' => 1,
    		'parentId' => 0,
    	],
    	2 => [
    		'name' => '冲浪/滑水/翻版',
    		'level' => 2,
    		'parentId' => 1,
    	],
    	3 => [
    		'name' => '鼻头',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	4 => [
    		'name' => '冲浪板',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	5 => [
    		'name' => '冲浪板背带',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	6 => [
    		'name' => '舵',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	7 => [
    		'name' => '帆板',
    		'level' => 3,
    		'parentId' => 2,
    	],
    	100 => [
    		'name' => '户外装备',
    		'level' => 1,
    		'parentId' => 0,
    	],
    	101 => [
    		'name' => '户外服装',
    		'level' => 2,
    		'parentId' => 100,
    	],
    	102 => [
    		'name' => '冲锋衣裤',
    		'level' => 3,
    		'parentId' => 101,
    	],
    	103 => [
    		'name' => '功能性内衣裤',
    		'level' => 3,
    		'parentId' => 101,
    	],
    	200 => [
    		'name' => '户外活动',
    		'level' => 1,
    		'parentId' => 0,
    	],
    	201 => [
    		'name' => '同城活动',
    		'level' => 2,
    		'parentId' => 200,
    	],
    	202 => [
    		'name' => '漂流',
    		'level' => 3,
    		'parentId' => 201,
    	],
        203 => [
            'name' => '滑雪',
            'level' => 3,
            'parentId' => 201,
        ],
        204 => [
            'name' => '温泉',
            'level' => 3,
            'parentId' => 201,
        ],
    ],
];