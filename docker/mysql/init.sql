-- The test suite uses its own database (library_test), see config/packages/doctrine.yaml.
GRANT ALL PRIVILEGES ON `library_test%`.* TO 'app'@'%';
