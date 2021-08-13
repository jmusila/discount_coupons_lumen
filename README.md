## Discount Coupons Lumen API

### Getting Started
- Clone this repository
    `$ git clone https://github.com/jmusila/discount_coupons_lumen.git`
- Navigate to the folder `discount_coupons_lumen`

### Installation
- After cloning this repo:
- Create .env on the root directory and copy .env_exmaple content to it
- Create database and add database configurations on the .env file
- Run $ composer install to install dependancies


### Testing on Postman
- Run `$ php -S localhost:8000 -t public` to start the server
- Test the following endpoint:

| EndPoint                       | Functionality                           |
| -------------------------------|:---------------------------------------:|
| POST /api/payment_calculation  | Returns Discounted Total Cost           |
|                                                                          |

- Request Body Example
```
{
	"cart_total" : 5000,
	"gift_card_ids" : [6, 7, 8]
}
```

- [Postman Collection](https://www.getpostman.com/collections/e68c244fb867c20e22bf)

