async function fetchRequest(url, options = {}, format = null, silent = false) {
    try {
        const response = await fetch(url, options);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        switch (format) {
            case 'text':
                return await response.text();
            case 'json':
                return await response.json();
            case 'formData':
                return await response.formData();
            case 'blob':
                return await response.blob();
            case 'arrayBuffer':
                return await response.arrayBuffer();
            default:
                return await response;
        }
    } catch (err) {
        const errMsg = `[HttpClient] ~ ${options.method || 'GET'} ~ ${url} : ${err}`;
        if (!silent)
            console.error(errMsg);
        else
            console.info(errMsg);

        return null;
    }
}

function head(url, silent = false) {
    return fetchRequest(url, { method: 'HEAD' }, null, silent);
}

function get(url, resultFormat = 'json') {
    return fetchRequest(url, { method: 'GET' }, resultFormat);
}

function remove(url, resultFormat = 'json') {
    return fetchRequest(url, { method: 'DELETE' }, resultFormat);
}

function post(url, data, resultFormat = 'json') {
    return fetchRequest(url, { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    },resultFormat)
}

function put(url, data, resultFormat = 'json') {
    return fetchRequest(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }, resultFormat);
}

function patch(url, data, resultFormat = 'json') {
    return fetchRequest(url, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }, resultFormat);
}

const HttpClient = { head, get, post, put, patch, remove };
export default HttpClient;