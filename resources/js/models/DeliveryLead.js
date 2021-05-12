export class DeliveryLead {

    // id;
    // name;
    // address;
    // phone;

    constructor(id, customerName, invoices = null, packageQuantity = null, createdAt = null) {
        this.id = id;
        this.customerName = customerName;
        this.invoices = invoices;
        this.packageQuantity = packageQuantity;
        this.createdAt = createdAt;
    }
}
