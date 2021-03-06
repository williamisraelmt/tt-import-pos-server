import {has} from 'lodash';
import {CONSTS} from "../consts";

export default (to, from, next) => {
    const query = to.query;
    if (!has(query, 'product_id')) {
        next('/');
    }
    axios
        .get(`${CONSTS.HOST}catalog/exists?product_id=${query['product_id']}`)
        .then(() => next())
        .catch(() => next('/catalog'))
}
