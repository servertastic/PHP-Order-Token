# Servertastic Order Web Form
This is a standalone web order form for use with the Servertastic Reseller API

## Installation

Clone files to the desired location on your website with the "www" folder being the directory root for the form, You may want to rename this to something else.

If you use composer in your application, require "guzzlehttp/guzzle" in your composer.json file and modify the autoloader source path in "includes/header.php"

Web form uses a custom class called "STOrderManager.php" this will be required at the top of "includes/header.php". If you move this file to your own classes folder in your project. ensure the source path still links to the file in header.php.