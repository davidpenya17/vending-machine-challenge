cs-fix:
	./scripts/cs-fix.sh 1
.PHONY: cs-fix-dry-run
cs-fix-dry-run:
	./scripts/cs-fix.sh 0
.PHONY: php-stan
php-stan:
	./scripts/php-stan.sh
.PHONY: phpspec
phpspec:
	./scripts/phpspec.sh
.PHONY: phpunit
phpunit:
	./scripts/phpunit.sh