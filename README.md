# API Calls using Laravel 5.3

The app is able to do the following,

1. Internal API Calls (GET/POST x-www-form-urlencoded)
2. External API Call (POST usin JSON(application/json))

## API Endpoints implemented

###Internal

1. http://115.248.209.92/api/public/api/products<br>

   Lists all products, the respective variants and the associated attributes

   Method: GET<br>
   Response Status: 200 OK<br>
   {
     "products": [
       {
         "id": 1,
         "name": "2 Seater",
         "Collection_name": "Ake",
         "variants": []
       },
       {
         "id": 2,
         "name": "3 Seater",
         "Collection_name": "Ake",
         "variants": []
       },
       {
         "id": 3,
         "name": "Ottoman",
         "Collection_name": "Ake",
         "variants": [
           {
             "id": 6658,
             "sku": "OTTMANBLAC",
             "cost_price": 148,
             "is_active": "yes",
             "attributes": [
               {
                 "id": 40,
                 "attribute_name": "Colour",
                 "value": "Black & White"
               }
             ]
           }
         ]
       },
       {
         "id": 4,
         "name": "Armchair",
         "Collection_name": "Ake",
         "variants": [
           {
             "id": 6649,
             "sku": "ARMCHBLUE",
             "cost_price": 100,
             "is_active": "yes",
             "attributes": [
               {
                 "id": 39,
                 "attribute_name": "Colour",
                 "value": "Pink"
               }
             ]
           }
         ]
       },
       {
         "id": 5,
         "name": "Shelf",
         "Collection_name": "Alf",
         "variants": []
       }
     ],
     "count": 5,
     "pages": 1,
     "current_page": 1
   }

2. http://115.248.209.92/api/public/api/products/{id}/variants<br>
   
   List all variants of a product specified by product_id
   
   Method: GET<br>
   Response Status: 200 OK<br>
   {
     "product_variants": [
       {
         "id": 6658,
         "product_id": 3,
         "variant_id": 6658,
         "sku": "OTTMANBLAC",
         "cost_price": 148,
         "is_active": "yes"
       }
     ],
     "count": 1,
     "pages": 1,
     "current_page": 1
   }

3. http://115.248.209.92/api/public/api/variants/{id}/attributes<br>

   Update attribute value associated with a product variant. If attribute does not exist, create attribute and associate with the product variant
   
   Method: POST<br>
   Input Params (x-www-form-urlencoded): attribute[name],attribute[value]<br>
   Response Status: 200 OK<br>
   {
   "id": 1,
   "variant_id": 1,
   "attribute_id": X,
   "value": "Shape",
   "attribute_name": "Round"
   }

3. http://115.248.209.92/api/public/api/variants/{id}/attributes/{name}<br>

   Update attribute value associated with a product variant. If attribute does not exist, display an error

   Method: POST<br>
   Input Params (x-www-form-urlencoded): attribute[value]<br>
   Response Status: 200 OK<br>
   {
      "id": 1,
      "variant_id": 1,
      "attribute_id": 1,
      "value": "Yellow",
      "attribute_name": "colour"
   }

###External

1. http://115.248.209.92/api/public/api/order/make<br>
   Method: GET<br>
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
    }<br><br>
    
    Response Status: 200 OK<br>
    

## Installation 

1. Checkout HEAD rivision of this repo to the webserver's root folder 
2. Create MySql Database, brosa_test
3. Import SQL (http://115.248.209.92/api/api.sql)
4. Modify DB_ parameters in the env file to reflect the connection to the database
5. Use Postman or similar API Client to consume webservices

