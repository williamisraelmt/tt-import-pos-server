export class User {

    constructor(
        id = null,
        name = null,
        fullName = null,
        identification = null,
        email = null,
        phone = null,
        status = true
    ) {
        this.id = id;
        this.name = name;
        this.fullName = fullName;
        this.identification = identification;
        this.email = email;
        this.phone = phone;
        this.status = status;
    }
}
