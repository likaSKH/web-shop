## Local Setup
#### Clone Repository
    git clone git@github.com:likaSKH/web-shop.git
    cd web-shop
#### Install Dependencies
    composer install
#### Setup env
    cp .env.example .env
Make sure to set database variables values correctly 
#### Set up Sail
    php artisan sail:install
#### Run Sail
    ./vendor/bin/sail up -d
#### Generate app key
    ./vendor/bin/sail artisan key:generate
#### Run migrations and seeders
    ./vendor/bin/sail artisan migrate --seed
#### Compile Frontend Assets
    ./vendor/bin/sail npm install && ./vendor/bin/sail npm run build

## Filament Admin Access
- URL : {your-host}/admin
- Credentials:
  - **Email**: admin@domain.com
  - **Password**: password // All Users will have this as a password 

## Features
### Admin Panel - Admin user
- Categories
  - Listing of Categories
  - Creating new Category with option to choose parent category
  - Edit Category
 - Orders
   - Listing of orders
     - With ability to filter Canceled Orders
     - Cancel Order
   - Create new Order
   - Edit existing Order
 - Products
   - List of Products
   - Create new Product
   - Edit existing Product
 - Customers
   - List of Customers
   - Edit Balance 
   - View customers orders
   - Create new customer
### Admin Panel - Customer
- Orders
- Listing of orders
    - With ability to filter Canceled Orders
    - Cancel Order
### WebShop Front
- Listing of Products
- Ability to filter products by categories
- Ability to search products by name
- Ability to sort products by:
  - Name Ascending
  - Name Descending
  - Price Ascending
  - Price Descending
- Pagination for products
- If product quantity is available Order button appears
  - On order modal 
    - You choose quantity of product to buy
    - You can see total price and available balance on account
    - Cancel - cancels action
    - Confirm - Will place an order
- Notifications are available for successfully and unsuccessfully actions, with description texts
- Users can navigate to Profile where they can see their balance and additional information
- Orders link is available in navigation which will link to Filament dashboard Orders list

