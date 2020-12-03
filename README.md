# isotope-product-licenses

This extension complements a license key management and its assignment to products which can then be picked up (via insert tags) and sent via the Notification Center.

> If a member is logged in, this member will automatically be assigned the license key.

The following insert tags are available:
- `{{license_collection::*}}`
- `{{license_product::*}}`

> \* = Id of collection / product

### Example content of the e-mail:

> Here are your ordered licenses 🎉\
> {{license_collection::##order_id##}}

##### Result:

> Here are your ordered licenses 🎉\
> Produkt 1: ABCD-EFGH-IJKL-QRST-MNOP\
> Produkt 2: EFGH-ABCD-MNOP-QRST-IJKL\
> Produkt 3: IJKL-ABCD-EFGH-MNOP-QRST
