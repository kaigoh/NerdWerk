<?php

$this->config['authentication'] = [
    'domains' => [
        [
            "domain" => "root",
            "name" => "Root Domain",
        ],
    ],
    'groups' => [
        [
            "domain" => "root",
            "name" => "administrators",
            "permissions" => ["administrator"],
        ],
    ],
    'users' => [
        [
            "domain" => "root",
            "username" => "admin",
            "password" => "admin",
            "name" => "Administrator",
            "email" => "admin@yourserver.com",
            "permissions" => [], // Permissons are inherited from group membership
            "groups" => ["administrators"],
        ],
    ],
];