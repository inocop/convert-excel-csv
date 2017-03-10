## Environment
Docker for mac ver1.13.1
- php 5.6.30
- phpexcel 1.8+
- composer

## Start

### docker run
```
$ cd convert-excel-csv/
$ docker-compose up -d
```

### composer install
```
$ docker exec -it convertexcelcsv_web_1 bash
$ cd /var/www/html/src/
$ composer install
```

http://localhost:8080/controller.php

## Author
inocop

## License
MIT
