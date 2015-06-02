MySQL wrapper for MySQLi
========================

This collection of MySQL functions is trying to be a drop in replacement for the native PHP MySQL extension.

The return values of the MySQL functions are aimed to behave the same way as the native MySQL functions. 
For example the function mysql_fetch_assoc will use MySQLi method fetch_assoc but instead of returning null in case there
are no more rows in the resultset it will return false just like the behaviour of mysql_fetch_assoc.