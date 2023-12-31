# Vodokachka

 ### CLIENT
```
VUE 3 - OPTION \ JAVASCRIPT
node.js v16.17.1
npm 8.19.3
PRIMEVUE 3.45.0
```
FOR RUN APP:

### Compiles and hot-reloads for development
```
npm run dev
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### SERVER
Laravel 10x - PHP 8.1 
API - 127.0.0.1/api/..

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vodokachka
DB_USERNAME=root
DB_PASSWORD=
```
### FOR RUN 
1. Make migration 
```
php artisan migrate or export DB
```
2. Start APP - php artisan serve
### Run server
```
php artisan serve
```

ROUTES LIST FOR API
```

GET|HEAD        / .................................................................. 
POST            _ignition/execute-solution ignition.executeSolution › Spatie\Larave…
GET|HEAD        _ignition/health-check ignition.healthCheck › Spatie\LaravelIgnitio…
POST            _ignition/update-config ignition.updateConfig › Spatie\LaravelIgnit…
GET|HEAD        api/bill ......................... bill.index › BillController@index
POST            api/bill ......................... bill.store › BillController@store
GET|HEAD        api/bill/{bill} .................... bill.show › BillController@show
PUT|PATCH       api/bill/{bill} ................ bill.update › BillController@update
DELETE          api/bill/{bill} .............. bill.destroy › BillController@destroy
GET|HEAD        api/period ................... period.index › PeriodController@index
POST            api/period ................... period.store › PeriodController@store
GET|HEAD        api/period/{period} ............ period.show › PeriodController@show
PUT|PATCH       api/period/{period} ........ period.update › PeriodController@update
DELETE          api/period/{period} ...... period.destroy › PeriodController@destroy  
GET|HEAD        api/pump ............. pump.index › PumpMeterRecordsController@index  
POST            api/pump ............. pump.store › PumpMeterRecordsController@store  
GET|HEAD        api/pump/{pump} ........ pump.show › PumpMeterRecordsController@show  
PUT|PATCH       api/pump/{pump} .... pump.update › PumpMeterRecordsController@update  
DELETE          api/pump/{pump} .. pump.destroy › PumpMeterRecordsController@destroy  
GET|HEAD        api/rate ......................... rate.index › RateController@index  
POST            api/rate ......................... rate.store › RateController@store  
GET|HEAD        api/rate/{rate} .................... rate.show › RateController@show  
PUT|PATCH       api/rate/{rate} ................ rate.update › RateController@update  
DELETE          api/rate/{rate} .............. rate.destroy › RateController@destroy  
GET|HEAD        api/resident ............. resident.index › ResidentController@index  
POST            api/resident ............. resident.store › ResidentController@store  
GET|HEAD        api/resident/{resident} .... resident.show › ResidentController@show  
PUT|PATCH       api/resident/{resident} resident.update › ResidentController@update   
DELETE          api/resident/{resident} resident.destroy › ResidentController@destr…  
GET|HEAD        api/user ...........................................................

```



