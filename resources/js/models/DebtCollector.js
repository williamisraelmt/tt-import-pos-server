export class DebtCollector {
    constructor(id, name, commission, status = true) {
        this.id = id;
        this.name = name;
        this.commission = commission;
        this.status = status;
    }
}
