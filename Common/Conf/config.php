<?php
return array(
	//'配置项'=>'配置值'

	//自定义模版常量信息
	'TMPL_PARSE_STRING'		=>	array(
							'__HOME__'	=>	__ROOT__ . '/Public/Home',
							'__ADMIN__'	=>	__ROOT__ . '/Public/Admin',
							'__PLUGINS__'	=>	__ROOT__ . '/Public/plugins'
						),

	//数据库的连接配置
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sp_',    // 数据库表前缀
);