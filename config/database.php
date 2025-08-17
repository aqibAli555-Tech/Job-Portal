<?php

use Illuminate\Support\Str;

return [
    
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    
    'default' => 'mysql',
    
    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
    
    'connections' => [
        
        'mysql' => [
            'driver' => 'mysql',
            'host' =>  '127.0.0.1',
			'port' =>  3306,
            'database' => 'hungryforjobsnew',
            'username' => 'root',
            'password' => '',
            'unix_socket' =>  '',
            'charset' => 'utf8mb4',
            'collation' =>  'utf8mb4_unicode_ci',
            'prefix' => '',
			'prefix_indexes' => true,
            'strict' =>  false,
			'engine' => 'InnoDB',
			'options' => extension_loaded('pdo_mysql') ? array_filter([
				\PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
				\PDO::ATTR_EMULATE_PREPARES => true,
			]) : [],
			'dump' => [
				'dump_binary_path' => env('DB_DUMP_BINARY_PATH', ''), // only the path, so without 'mysqldump' or 'pg_dump'
				'use_single_transaction', // perform dump using a single transaction.
				'timeout' => 60 * 10, // 10 minute timeout
			]
        ],
    
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */
    
    'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer body of commands than a typical key-value system
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [
	
		'client' =>  'predis',
	
		'options' => [
			'cluster' => 'predis',
			'prefix' => Str::slug( 'laravel', '_').'_database_',
		],
	
		'default' => [
			'host' => '127.0.0.1',
			'password' =>  null,
			'port' => 6379,
			'database' => 0,
		],
	
		'cache' => [
			'host' =>  '127.0.0.1',
			'password' => null,
			'port' => 6379,
			'database' =>  1,
		],

	],

];
