import HttpClient from '';

async function fileExists(filepath) {
    const file = await HttpClient.head(filepath, true);
    return file?.ok;
}

async function getTextFileContent(filepath) {
    return HttpClient.get(filepath, 'text');
}

async function getJSONFileContent(filepath) {
    return HttpClient.get(filepath, 'json');
}

export { fileExists, getJSONFileContent, getTextFileContent };