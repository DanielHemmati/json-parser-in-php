# Json parser in mighty php

![](./art/json-parser-banner.png)

## Overview

A simple JSON parser implementation in PHP that parses JSON strings into PHP data structures. This project demonstrates how to build a JSON parser from scratch without relying on PHP's built-in `json_decode()` function.

If you like to build your own JSON parser and you need to understand how it works, this project is for you.

## Features

- ✅ Parse JSON strings into PHP arrays and objects
- ✅ Support for all JSON data types:
  - Strings
  - Numbers (integers and floats)
  - Booleans (`true`/`false`)
  - `null` values
  - Arrays
  - Objects
  - Unicode characters
  - Binary data
- ✅ Proper error handling with descriptive error messages

## Installation

### Prerequisites

- PHP 8.0 or higher
- Composer

### Local Setup

1. Clone the repository:

```bash
git clone https://github.com/DanielHemmati/json-parser-in-php
cd json-parser-in-php
```

2. Install dependencies:

```bash
composer install
```

3. Run the tests:

```bash
./vendor/bin/pest
```

## Contributing

Contributions are welcome! Please feel free to submit a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.