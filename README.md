# Servertastic Order Web Form
This is a standalone web order form for use with the Servertastic Reseller API Order Tokens. Forms are dynamically generated depending on the product purchased.

## Release Status

This is currently an beta release. We have done internal testing but as always this needs to be used in some real world environments to see any issues.

## Support

Support for is only available via the issue tracker on Github. We are not providing support via our helpdesk at this time.

## Installation

Clone files to the desired location on your website with the "www" folder being the directory root for the form, You may want to rename this to something else.

If you use composer in your application, require "guzzlehttp/guzzle" in your composer.json file and modify the autoloader source path in "includes/header.php"

Web form uses a custom class called "STOrderManager.php" this will be required at the top of "includes/header.php". If you move this file to your own classes folder in your project. ensure the source path still links to the file in header.php.

## Test Mode

The script is currently set to Test Mode on download. This means it will only work with tokens generated within the test system.

Within STOrderManager.php you can change the `$this->TEST_MODE = true;` to `$this->TEST_MODE = false;` when you want to work with live tokens.