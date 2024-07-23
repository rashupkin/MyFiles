<?php

// for db
$_ENV["DB_NAME"] = "";
$_ENV["DB_PASS"] = "";
$_ENV["DB_USER"] = "";
$_ENV["DB_HOST"] = "";
// for file system
$_ENV["DIR"] = __DIR__;
$_ENV["MAIN_DIRECTORY"] = "app";
$_ENV["MAIN_FOLDER"] = "App";
$_ENV["TEMPLATE_FOLDER"] = "Templates";
$_ENV["SERVICES_DIRECTORIES"] = [
    "Core/Service",
    "Services"
];
$_ENV["REPOSITORIES_DIRECTORIES"] = [
    "Repository"
];
$_ENV["CONTROLLERS_DIRECTORIES"] = [
    "Controllers",
    "Controllers/Admin"
];
$_ENV["DB_DIRECTORY"] = "db_directory";
// for mail
$_ENV["MAIL_EMAIL"] = "";
$_ENV["MAIL_PASS"] = "";
$_ENV["MAIL_PORT"] = null;
$_ENV["MAIL_SMTP"] = "";
