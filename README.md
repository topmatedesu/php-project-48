### Hexlet tests and linter status:
[![Actions Status](https://github.com/topmatedesu/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/topmatedesu/php-project-48/actions)
[![Github Actions](https://github.com/topmatedesu/php-project-48/actions/workflows/github-check.yml/badge.svg)](https://github.com/topmatedesu/php-project-48/actions/workflows/github-check.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/3a9035d62769af702e9f/maintainability)](https://codeclimate.com/github/topmatedesu/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3a9035d62769af702e9f/test_coverage)](https://codeclimate.com/github/topmatedesu/php-project-48/test_coverage)

# Difference Calculator
This is a library that allows you to find differences in files

## Requirements
PHP 8.2.10

## How to install
```
git clone https://github.com/topmatedesu/php-project-48.git
```
```
make install
```
## How to use
### Show help
```
./bin/gendiff -h
```
### Show version
```
./bin/gendiff -v
```
### Find the differences
Format options: stylish (default), plain, json
```
./bin/gendiff --format <option> <path to first file> <path to second file>
```

## Asciinema recordings:
* ### [Json diff](https://asciinema.org/a/3Sc6b0WzmzV0kCM4QnkvgTLlF)
* ### [Yml diff](https://asciinema.org/a/wNMSbjcCNNrnkNUlwMIWxgMdT)
* ### [Nested diff](https://asciinema.org/a/l9rGicehobib2y0nh7wKodjcZ)
* ### [Plain diff](https://asciinema.org/a/DLEdQ7nsUmo9cwsdQmBo4m2hf)
* ### [Json format diff](https://asciinema.org/a/n9ocs3WXO6vixRn2TNFvlgMh2)