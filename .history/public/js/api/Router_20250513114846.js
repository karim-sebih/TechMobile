import { ROUTER_PATH } from "../config.js";

// Note : HttpClient n'est pas défini dans votre projet, ajoutons une implémentation simple
const HttpClient = {
    async get(url) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur dans HttpClient.get:', error);
            throw error;
        }
    },
    async post(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur dans HttpClient.post:', error);
            throw error;
        }
    },
    async put(url, data) {
        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur dans HttpClient.put:', error);
            throw error;
        }
    },
    async patch(url, data) {
        try {
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur dans HttpClient.patch:', error);
            throw error;
        }
    },
    async remove(url) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur dans HttpClient.remove:', error);
            throw error;
        }
    },
};

function Router(resource) {
    const getURL = `${ROUTER_PATH}?resource=${encodeURIComponent(resource)}`;
    console.log('Router URL:', getURL);

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

    async function getByUserId(user_id) {
        return await HttpClient.get(`${getURL}&cart_by_user=${user_id}`);
    }

    async function getByUserMail(user_mail) {
        return await HttpClient.get(`${getURL}&mail=${user_mail}`);
    }

    return { getAll, getOne, put, post, patch, remove, getByUserId, getByUserMail };
}

export default Router;