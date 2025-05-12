
import { ROUTER_PATH } from "../config.js";

function Router(resource) {
    const getURL = `${ROUTER_PATH}?resource=${encodeURIComponent(resource)}`;
    console.log(getURL);
    async function getAll(filters = null) {
        let fullURL = getURL;
        if (filters) {
            fullURL += Object.entries(filters)
                .map(([k, v]) => {
                    if (Array.isArray(v)) {
                        return v.map(val => `&${k}[]=${encodeURIComponent(val)}`).join('');
                    }
                    return `&${k}=${encodeURIComponent(v)}`;
                }).join('');
        }
        return await HttpClient.get(fullURL);
    }

    async function getOne(id) {
        return await HttpClient.get(`${getURL}&id=${id}`);
    }

    async function post(data) {
        data["resource"] = resource;
        return await HttpClient.post(ROUTER_PATH, data);
    }

    async function put(id, data) {
        data["resource"] = resource;
        data["id"] = id;
        return await HttpClient.put(ROUTER_PATH, data);
    }

    async function patch(id, data) {
        data["resource"] = resource;
        data["id"] = id;
        return await HttpClient.patch(ROUTER_PATH, data);
    }

    async function remove(id) {
        return await HttpClient.remove(`${getURL}&id=${id}`);
    }

    async function getByUserId(user_id){
        return await HttpClient.get(`${getURL}&cart_by_user=${user_id}`);
    }

    async function getByUserMail(user_mail){
        return await HttpClient.get(`${getURL}&mail=${user_mail}`);
    }

    return { getAll, getOne, put, post, patch, remove, getByUserId, getByUserMail };
}

export default Router;