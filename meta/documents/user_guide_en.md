# What is eCourier? For what?

The eCourier is the all-round system for CEP service providers (courier express package) from [bamboo software](https://bamboo-software.de/). This plugin enables you to ship as an all-in-one solution with more than 60 courier services, whether city/direct courier or national and international express shipping.

A courier service is ideal for time-sensitive or high-value goods shipments. A classic application is, for example, shipping food that needs to be frozen overnight so that the cold chain is not interrupted.

Use this plugin to integrate one of the supported courier services into your plentymarkets system. It is then possible to carry out the familiar step of registering the shipping order in the shipping center and in plentyBase.

## Quickstart

To use this plugin, you must be registered as a sender with the courier service of your choice. You will then receive a username and password to configure the plugin.

You can find a list of the supported courier services [here](https://bamboo-software.de/ecourier/).

## Practical example: DER KURIER

**Use for your registration at DER KURIER one of the following ways:**

- Phone: +49 (0) 6677 95-0
- [Email](mailto:info@derkurier.de)
- [Contact form](https://derkurier.de/kontakt/)

When contacting them, please mention that you found the plentymarkets plugin for eCourier here in the marketplace.

### Plugin Configuration

As soon as you have received the user data from DER KURIER, you can store them in the plugin and generate your first shipping label.

#### Deposit access data

To get started, you must first enable API access.

1. Open the menu **Plugins » Plugin set overview**.
2. Select the desired plugin set.
3. Click **eCourier (bamboo software)**.<br>→ A new view will open.
4. Select the **Global** section from the list.
5. Enter your username and password.
6. **Save** the settings.

Make sure that the mode is set to **DEMO** for all test scenarios. After adjusting the sender settings, you can register shipments in the shipping center and receive the appropriate transaction number incl. label back.

As soon as you are from DER KURIER have received the release for productive operation, you must set the switch to **LIVE** here.

#### Shipper Settings

Enter your address data according to registration in the **Sender** area. You can also configure your pick-up/delivery time and an optional delivery notes under **Shipping**.

### DER KURIER as a shipping option

If the plugin has been successfully installed and the tests have been successful, it is time to make the shipping service provider selectable as an option in the checkout of your shop.

1. Activate your **[delivery countries](https://knowledge.plentymarkets.com/en-gb/manual/main/fulfilment/preparing-the-shipment.html#200)**
2. Create your (shipping)**[regions](https://knowledge.plentymarkets.com/en-gb/manual/main/fulfilment/preparing-the-shipment.html#400)**
3. Create your **[Shipping Service Provider](https://knowledge.plentymarkets.com/en-gb/manual/main/fulfilment/preparing-the-shipment.html#800)** _**DER KURIER**_
  * Choose _**Sonstiges**_ in the _Shipping Service Provider_ column
  * Store `https://leotrace.derkurier.de/paketstatusNeu.aspx?Lang=DE&parcel=$PaketNr&ZIP=$PLZ` as tracking URL
4. Create your **[shipping profiles](https://knowledge.plentymarkets.com/en-gb/manual/main/fulfilment/preparing-the-shipment.html#1000)** and **[table of shipping charges](https://knowledge.plentymarkets.com/en-gb/manual/main/fulfilment/preparing-the-shipment.html#1500)** for _**DER KURIER**_

#### GDPR: Information on data transmission (email and telephone)

You can configure this in your shipping profile using the option **[Transfer email and phone](https://knowledge.plentymarkets.com/en-gb/manual/main/business-decisions/gdpr.html#700)**. The customer's e-mail address and telephone number are not mandatory fields in the interface. So you don't necessarily have to transfer them.

## Credits

This plugin was kindly funded by [DER KURIER](https://derkurier.de/) and [beefgourmet.de](https://www.beefgourmet.de/).

<sub><sup>Every single purchase helps with constant further development and the implementation of user requests. Thanks very much!</sup></sub>