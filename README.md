# SyPer Pay Payment Api Integration Package

[![Issues](https://img.shields.io/github/issues/kataki-kh/Syber_pay-package)](https://kataki-kh/Syber_pay-package/issues)
[![Stars](https://img.shields.io/github/stars/kataki-kh/Syber_pay-package)](https://github.com/kataki-kh/Syber_pay-package/stargazers)
[![Forks](https://img.shields.io/github/forks/kataki-kh/Syber_pay-package)](https://github.com/kataki-kh/Syber_pay-package/network/members)
[![license](https://img.shields.io/github/license/kataki-kh/Syber_pay-package)](https://github.com/kataki-kh/Syber_pay-package/license)
[![releases](https://img.shields.io/github/license/kataki-kh/Syber_pay-package)](https://github.com/kataki-kh/Syber_pay-package/releases)


## This Package Will Help You Integrate Syber Pay Payment Gateway In Your Laravel Api To Support Payment On Mobile Applications

### Installation :
```sh
 	$  composer require kataki/syber-pay
 	$  php artisan Syber_pay:install
```
### open the .env file and add these lines :
```sh
syberpayURL="https://syberpay.test.sybertechnology.com/syberpay/"
applicationId="0000000132"
serviceId="009001000106"
salt="l3emxga9b"
key="y5lgm6rxq"
middleware="null"
order_model="Ticket"      //the model of your appication order be sure that the table is the same order name followed with 's'.
order_price_column="price_value"      //the column name on your appication order table which has the price that the customer should pay
order_payment_status_column="ticket_status"   //the column name on your appication order table indecate if the order is payed or not 2= payed
customer_name_column="name"    //the column name on your appication customers table that save the customer name (not required)
success_message="payment done"
error_message="payment error"
cancel_message="payment canceled"
success_view="syber-pay::success"
error_view="syber-pay::cancel"
cancel_view="syber-pay::cancel"

```
##Test card
```sh
pan=7888450004340823
pin=0000
```
##That will automaticlly generate the needed api that syber company would require :

	(post)example.com/api/transactions/syber/payment //trigerd by the mobile div and require parameter (order_id)
	(post)example.com/api/transactions/syber/notify //trigerd by syber company and will check if the payment proccess done and update you
	(post)example.com/api/transactions/syber/return //trigerd by syber company and will retrun animation redirect page show message and the payment status

	(post)example.com/api/transactions/syber/cancel  #trigerd by syber company will retrun animation redirect page show message and the payment is canceled

##Example Request :
```sh
post::example.com/api/transactions/syber/payment?order_id=1
```

##Example response :
```sh
https://syberpay.test.sybertechnology.com:443/syberpay/payment/1917b617-8659-414e-a751-a5cc42078b47
```
###it also generate payment model and table :
		
	payment table(
		bigInteger('id')->unique(),
         string('transaction_id')->unique(),
         bigInteger('order_id'),
         integer('customer_id'),
         string('hash'),
         string('note')->nullable,
         string('payment_method'),/// syber  integer('status'),///0=notvalid or pendding, 1=done
		string('type')->default('1'),///1=order payment
	)

License :
----

MIT		
