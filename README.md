# In Stock
In Stock is an application built with Laravel + Vue that tracks the availability and price of certain products across multiple retailers.
Each product might be avaliable from certain retailers, but we have to check if it's in stock.

## Checking availability and prices
If you want to check the availability and prices for a given product, you should add products and retailers in database.
Then, you can run the following command:

```
php artisan instock:track
```

The command above will returns all the products in database and updates the availability and prices for each retailer.

**This application is under development**