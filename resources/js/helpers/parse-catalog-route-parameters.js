import {has, isNull, toNumber} from 'lodash';

export function parseCatalogRouteParameters(query) {
    return {
        search: has(query, 'search') && !isNull(query['search']) ? query['search'] : '',
        currentPage: has(query, 'currentPage') && !isNull(query['currentPage']) ? toNumber(query['currentPage']) : 1
    }
}
