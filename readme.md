# API Calls using Laravel 5.3

The app is able to do the following,

1. Internal API Calls (GET/POST x-www-form-urlencoded)
2. External API Call (POST usin JSON(application/json))

## API Endpoints implemented

The API Client used is Postman

###Internal

1. http://115.248.209.92/api/public/api/products<br>
   Method: GET<br>
   Response Status: 200 OK<br>

2. http://115.248.209.92/api/public/api/products/{id}/variants<br>
   Method: GET<br>
   Response Status: 200 OK<br>

3. http://115.248.209.92/api/public/api/variants/{id}/attributes<br>
   Method: POST<br>
   Response Status: 200 OK<br>
   Input Params (x-www-form-urlencoded): attribute[name],attribute[value]<br>

3. http://115.248.209.92/api/public/api/variants/{id}/attributes/{name}<br>
   Method: POST<br>
   Response Status: 200 OK<br>
   Input Params (x-www-form-urlencoded): attribute[value]<br>

###External

1. http://115.248.209.92/api/public/api/order/make<br>
   Method: GET<br>
   Response Status: 200 OK<br>
   JSON Payload (application/json):<br>
   {
    "Order": {
        "customer": "Gabriel Jaramillo",
        "address": "test address",
        "total": 100,
        "source": "ShopClues",
        "group_dispatch": "yes",
        "Status": "new",
        "payment": "credit",
        "items": [{
          "sku": "OTTMANBLAC",
          "quantity": 2
        }, {
          "sku": "ARMCHBLUE",
          "quantity": 1
        }]
      }
    }

## Installation 

1. Checkout HEAD rivision of this repo to the webserver's root folder 
2. Create MySql Database, brosa_test
3. Import SQL (http://115.248.209.92/api/29-09-2016testdb-api-test.sql)
4. Modify DB_ parameters in the env file to reflect the connection to the database

