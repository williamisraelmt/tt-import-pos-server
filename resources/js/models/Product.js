export class Product {

    constructor(
        id = null,
        name= null,
        defaultCode= null,
        status= null,
        category= null,
        availableQuantity= null,
        defaultPhotoUrl = null,
        brand = null,
        listPrice = null,
        standardPrice = null) {
        this.id = id;
        this.name = name;
        this.defaultCode = defaultCode;
        this.status = status;
        this.category = category;
        this.availableQuantity = availableQuantity;
        this.defaultPhotoUrl = defaultPhotoUrl;
        this.brand = brand;
        this.listPrice = listPrice;
        this.standardPrice = standardPrice;
    }
}
