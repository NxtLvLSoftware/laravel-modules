<?php

declare(strict_types=1);

return [
	".editorconfig",
	".gitattributes",
	".gitignore",
	"config" => [],
	"database" => [
		".gitignore",
		"factories" => [],
		"migrations" => [],
		"seeds" => [
			"DatabaseSeeder.php"
		]
	],
	"routes" => [
		"web.php",
		"api.php"
	],
	"src" => [
		"Console" => [],
		"Http" => [
			"Controllers" => [
				"Controller.php"
			],
			"Middleware" => [],
		],
		"Models" => [],
		"Providers" => [],
	],
	"resources" => [
		"js" => [
			"module.js"
		],
		"lang" => [
			"en" => []
		],
		"sass" => [
			"module.sass"
		],
		"views" => [],
	],
	"stubs" => []
];