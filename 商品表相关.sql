# 分类表 shop_categories  
# 一表三级分类
CREATE TABLE `shop_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `parent_id` int(10) unsigned NOT NULL COMMENT '父类id',
  `name` varchar(60) NOT NULL COMMENT '分类名',
  `disabled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：有效、1：无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 属性表 shop_attributes
# 一个分类下有多个商品属性对应
CREATE TABLE `shop_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性id',
  `c_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `name` varchar(60) NOT NULL COMMENT '属性名',
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0：有效、1：无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 规格表  shop_specifications
CREATE TABLE `shop_specifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性id',
  `name` varchar(60) NOT NULL COMMENT '规格名',
  `need_img` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0：不需要图片、1：需要图片',
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0：有效、1：无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 规格值表 shop_specification_values
# 一个规格下有多个值
CREATE TABLE `shop_specification_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格值id',
  `name` varchar(30) NOT NULL COMMENT '规格值名',
  `p_id` int(10) unsigned NOT NULL COMMENT '所属的规格id',
  `disabled` tinyint(3) unsigned NOT NULL COMMENT '0：有效、1：无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# 分类规格表 shop_categories_specifications
# 一个分类对应多个规格，一个规格可以属于多个分类
CREATE TABLE `shop_categories_specifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `c_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `s_id` int(10) unsigned NOT NULL COMMENT '规格id',
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0：有效、1：无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 商品表 shop_goods
# 属于分类，包含属性和规格
CREATE TABLE `shop_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `c_id` int(10) unsigned NOT NULL COMMENT '所属分类id',
  `g_id` int(10) unsigned NOT NULL COMMENT '分组id',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `spec` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:统一规格、1:多规格',
  `wx_price` decimal(10,0) unsigned NOT NULL COMMENT '微信价格',
  `market_price` decimal(10,0) unsigned NOT NULL COMMENT '市场价',
  `stores` int(10) unsigned NOT NULL COMMENT '库存量',
  `barcode` varchar(60) NOT NULL COMMENT '商品条码',
  `image` varchar(255) NOT NULL COMMENT '主图',
  `desc` tinyint(3) unsigned NOT NULL COMMENT '商品描述0：不描述、1：描述，描述信息看关联表',
  `limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '是否限购0:不限购、其它数值为限购',
  `location` varchar(60) NOT NULL DEFAULT '' COMMENT '生产地或者发货地',
  `is_bill` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发票0:不开、1：开',
  `is_ repair` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否保修0:不保修、1:保修',
  `is_on` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否上架0:不上架、1:上架',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 商品属性表 shop_goods_attributes
# 商品和属性的关联表
CREATE TABLE `shop_goods_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `a_id` int(10) unsigned NOT NULL COMMENT '属性id',
  `avalue` varchar(30) NOT NULL COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 商品规格表 shop_goods_specifications
# 商品和规格的关联表
CREATE TABLE `shop_goods_specifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `s_v_ids` int(10) unsigned NOT NULL COMMENT '规格值id组合，如 10:30 表示黄色:xl',
  `image` varchar(60) NOT NULL DEFAULT '' COMMENT '该规格组合的图片',
  `price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '该规格组合的价格',
  `store` int(10) unsigned NOT NULL COMMENT '库存',
  `disabled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效0:有效、1:无效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 商品附加图片表
CREATE TABLE `shop_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `image` varchar(120) NOT NULL COMMENT '图片地址',
  `g_id` int(10) unsigned NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 分组表 shop_groups
CREATE TABLE `shop_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组id',
  `name` varchar(30) NOT NULL COMMENT '组名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

