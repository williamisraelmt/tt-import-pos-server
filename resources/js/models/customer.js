export class Customer {

    // id;
    // name;
    // address;
    // phone;

    constructor(id, name, address = null, phone = null, invoices = []) {
        this.id = id;
        this.name = name;
        this.address = address;
        this.phone = phone;
        this.invoices = invoices;
    }
}
