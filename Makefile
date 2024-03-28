install:
		composer install

validate:
		composer validate

dump:
		composer dump-autoload

lint:
		composer exec --verbose phpcs -- --standard=PSR12 src tests

test:
		composer exec --verbose phpunit tests

test-coverage:
		XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

test-coverage-text:
		XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text