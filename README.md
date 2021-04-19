## About Product-recommendations

Product recommendations is a small Laravel api service which returns product recommendations in JSON format depending on the weather forecast.
External LHMT weather condition api service (https://api.meteo.lt/) is used to build this project.

```json
    "source": "LHMT",
    "city": "vilnius",
    "recommendations": [
        {
            "weather_conditions": "clear",
            "date": "2021-04-20",
            "products": [
                {
                    "name": "Voluptatem aut dolore.",
                    "sku": "VO-3",
                    "price": 10.77
                },
```

## How to run

To run this project you need Docker(https://www.docker.com/products/docker-desktop) installed.

First go to the project directory and run

```shell
composer install
```

Copy environment variables to .env file

```shell
cp .env.example .env
```

To get all required services run

```shell
./vendor/bin/sail up
```

Finally migrate and seed the database

```shell
./vendor/bin/sail artisan migrate --seed
```

## How to use

Simply make a GET request to 

```shell
/api/products/recommended/:city
```
Parameter :city should be lowercase

Aditional request parameters for product count limit and  weather condition days can be added
```shell
?limit=1&days=5
```

