export class Customer {

    // id;
    // name;
    // address;
    // phone;

    constructor(id, name, address = null, phone = null, invoices = [], debtCollectorId = null, debtCollectorName = null) {
        this.id = id;
        this.name = name;
        this.address = address;
        this.phone = phone;
        this.invoices = invoices;
        this.debtCollectorId = debtCollectorId;
        this.debtCollectorName = debtCollectorName;
    }
}
