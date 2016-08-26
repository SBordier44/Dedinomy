<?php
$steps = [
	[
		'name' => $this->language['step_1_name'],
		'fields' => [
			[
				'type' => 'info',
				'value' => $this->language['step_1_value1']
			],
			[
				'type' => 'language',
				'label' => 'Language',
				'name' => 'language'
			]
		]
	],
	[
		'name' => $this->language['step_2_name'],
		'fields' => [
			[
				'type' => 'info',
				'value' => $this->language['step_2_value1']
			],
			[
				'type' => 'info',
				'value' => $this->language['step_2_value2']
			],
			[
				'type' => 'php-config',
				'label' => $this->language['step_2_label3'],
				'items' => [
					'php_version' => ['>=5.6', $this->language['PHP_Version'].' >= 5.6'],
					'register_globals' => false,
					'safe_mode' => false
				]
			],
			[
				'type' => 'php-modules',
				'label' => $this->language['step_2_label4'],
				'items' => [
					'pdo_mysql' => [true, 'PDO MySQL'],
					'curl'=> [true, 'PHP Curl']
				]
			],
			[
				'type' => 'file-permissions',
				'label' => $this->language['step_2_label5'],
				'items' => [
					'../Temp/' => 'write',
					'../Temp/Smarty/' => 'write',
					'../Temp/Smarty/cache/' => 'write',
					'../Temp/Smarty/templates_c/' => 'write',
					'../Temp/Updater/' => 'write',
					'../Temp/Updater/Cache/' => 'write',
					'../Config/' => 'write'
				]
			]
		]
	],
	[
		'name' => $this->language['step_3_name'],
		'fields' => [
			[
				'type' => 'info',
				'value' => $this->language['step_3_value1']
			],
			[
				'type' => 'info',
				'value' => $this->language['step_3_value2']
			],
			[
				'label' => $this->language['step_3_label3'],
				'name' => 'db_hostname',
				'type' => 'text',
				'default' => 'localhost',
				'validate' => [
					['rule' => 'required']
				]
			],
			[
				'label' => $this->language['step_3_label4'],
				'name' => 'db_username',
				'type' => 'text',
				'default' => '',
                'highlight_on_error' => true,
				'validate' => [
					['rule' => 'required']
				]
			],
			[
				'label' => $this->language['step_3_label5'],
				'name' => 'db_password',
				'type' => 'text',
				'default' => '',
                'highlight_on_error' => true,
				'validate' => [
					['rule' => 'required']
				]
			],
			[
				'label' => $this->language['step_3_label6'],
				'name' => 'db_name',
				'type' => 'text',
				'default' => '',
				'highlight_on_error' => true,
				'validate' => [
					['rule' => 'required']
				]
			]
		]
	],
	[
		'name' => $this->language['step_4_name'],
		'fields' => [
			[
				'type' => 'info',
				'value' => $this->language['step_4_value1']
			]
		],
		'callbacks' => [
			['name' => 'installSQL']
		]
	],
	[
		'name'=>'Configuration',
		'fields'=> [
			[
				'type'=>'info',
				'value'=>'<span style="color:dimgrey; font-weight:bold">Création des identifiants de l\'administrateur</span>'
			],
			[
				'label'=>'Nom d\'utilisateur',
				'type'=>'text',
				'default'=>'',
				'name'=>'adm_username',
				'highlight_on_error'=>true,
				'validate'=> [
					['rule'=>'required']
				]
			],
			[
				'label'=>'Mot de passe',
				'type'=>'text',
				'default'=>'',
				'name'=>'adm_password',
				'highlight_on_error'=>true,
				'validate'=> [
					['rule'=>'required']
				]
			],
            [
                'label'=>'Votre E-Mail',
                'type'=>'text',
                'default'=>'',
                'name'=>'adm_mail',
                'highlight_on_error'=>true,
                'validate'=> [
                    ['rule'=>'required'],
                    ['rule'=>'valid_email']
                ]
            ],
			[
				'type'=>'info',
				'value'=>''
			],
			[
				'type'=>'info',
				'value'=>'<span style="color:dimgrey; font-weight:bold">Configuration du script</span>'
			],
			[
				'label'=>'Nom de votre site',
				'type'=>'text',
				'default'=>'',
				'name'=>'adm_sitename',
				'highlight_on_error'=>true,
				'validate'=> [
					['rule'=>'required']
				]
			]
		],
		'callbacks'=> [
			[
				'name' => 'updateSQL',
        		'execute' => 'after'
			]
		]
	],
	[
		'name' => $this->language['step_5_name'],
		'fields' => [
			[
				'type' => 'info',
				'value' => $this->language['step_5_value1'],
			],
			[
				'type' => 'info',
				'value' => $this->language['step_5_value2'].'<a href="../dashboard" target="_blank">Administration</a>'
            ],
			[
				'type' => 'info',
				'value' => $this->language['step_5_value3']
			]

		],
        'callbacks'=> [
            [
                'name' => 'endInstall',
                'execute' => 'before'
            ]
        ]
	]
];