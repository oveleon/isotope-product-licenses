# isotope-product-licenses

This extension complements a license key management and its assignment to products which can then be picked up (via insert tags) and sent via the Notification Center.

> If a member is logged in, this member will automatically be assigned the license key.
>
>When using "license_collection", it is also checked whether the member can be determined from the order record, so that licenses can be sent later and the member can be assigned to it. Furthermore, the order ID is stored in the license.

The following insert tags are available:
- `{{license_collection::*}}`
- `{{license_product::*}}`

> \* = Id of collection / product

### Module:
With the module Ordered-Licenses the booked licenses can be displayed in the order confirmation.

> To determine the current order and its licenses, the GET parameter "uid" must be in the URL. This is the case on the order confirmation page, for example. It would look something like this: "complete.html?uid=550af3fe73763"

### Example content of the e-mail:

> Here are your ordered licenses 🎉\
> {{license_collection::##order_id##}}

##### Result:

> Here are your ordered licenses 🎉\
> Produkt 1: ABCD-EFGH-IJKL-QRST-MNOP\
> Produkt 2: EFGH-ABCD-MNOP-QRST-IJKL\
> Produkt 3: IJKL-ABCD-EFGH-MNOP-QRST
