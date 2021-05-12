export class Grid {

    // limit;
    //     // offset;
    //     // sortBy;
    //     // search;

    constructor(limit = 10, offset = 0, sortBy = [], search = ""){
        this.limit = limit;
        this.offset = offset;
        this.sortBy = sortBy;
        this.search = search;
    }

    toHttpQueryString(){
        let queryString = '?limit=' + this.limit + '&offset=' + this.offset + '&search=' + this.search;
        this.sortBy.forEach( (sortBy) => {
            queryString += '&sort_by[]=' + sortBy;
        });
        return queryString;
    }
}
