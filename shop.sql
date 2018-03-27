# 分类表 shop_categories  
# 一表三级分类
CREATE TABLE `shop_categories` (
`id`  int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id' ,
`c_id`  int UNSIGNED NOT NULL COMMENT '分类id' ,
`parent_id`  int UNSIGNED NOT NULL COMMENT '父类id' ,
`name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分类名' ,
`disabled`  tinyint NOT NULL DEFAULT 0 COMMENT '0：有效、1：无效' ,
PRIMARY KEY (`id`)
);
# 属性表 shop_attributes
# 一个分类下有多个商品属性对应

# 规格表  shop_specifications

# 规格值表 shop_specification_values
# 一个规格下有多个值

# 分类规格表 shop_categories_specifications
# 一个分类对应多个规格，一个规格可以属于多个分类

# 商品表 shop_goods
# 属于分类，包含属性和规格

# 商品属性表 shop_goods_attributes
# 商品和属性的关联表

# 商品规格表 shop_goods_specifications
# 商品和规格的关联表

# 分组表 shop_groups
