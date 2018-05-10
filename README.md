# WP-Turbo-Migrate
A Lightweight PHP Script that help developers perform WP site migrations easily.

# Getting Started
Steps to migrate your WordPress site using WP Turbo Migrate tool:
- Perform backups (if required)
- Pack current WordPress to zip file and download
- Transfer all WordPress files to new server
- Check .htaccess make sure the path is correct in the new server
- Set up new MySQL DB name, username and password
- Run Migration tool to convert the URLs
- Review the processed MySQL dump and import to the new database
- Assign the new MySQL DB name and access in wp-config.php

This migration tool is intended for basic WordPress sites. Please review the processed MySQL dump before publishing to live server. We will not be reponsible for any damage caused by the software.

Requirement:
PHP 5.5 or above

Tested on:
WordPress Version 4.9.5 or above

Please report any bugs or issues at: https://github.com/Iplussoft-Technologies/WP-Turbo-Migrate/issues

Need help migrating your WordPress site or have custom requests? Please consider purchasing our premium support at http://www.iplussoft.com
