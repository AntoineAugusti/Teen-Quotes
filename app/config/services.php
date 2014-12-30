<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => getenv('MAILGUN_DOMAIN'),
		'secret' => getenv('MAILGUN_SECRET'),
		'pubkey' => getenv('MAILGUN_PUBKEY'),
	],

	'mandrill' => [
		'secret' => getenv('MANDRILL_SECRET'),
	],

	'stripe' => [
		'model'  => 'TeenQuotes\Users\Models\User',
		'secret' => '',
	],

	'mailchimp' => [
		'secret' => getenv('MAILCHIMP_SECRET')
	],

	'easyrec' => [
		'apiKey'   => getenv('EASYREC_APIKEY'),
		'tenantID' => 'teenquotes_'.App::environment()
	],

	'twitter' => [
		'client_id'     => getenv('TWITTER_CLIENT_ID'),
		'client_secret' => getenv('TWITTER_CLIENT_SECRET'),
		'redirect'      => 'http://'.Config::get('app.domain').'/auth/twitter'
	],
];