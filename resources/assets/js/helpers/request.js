/**
 * @param route API route. If starts without "/", then /api/ will be prepended.
 * @param options GET requires array like ['foo', 'bar'] (optional)
 *                POST requires objects like {'foo': 'value', 'bar': 'value'}
 * @param type get|post
 * @returns {Promise<unknown>}
 */
$.request = function(route, options = [], type = 'post') {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: `${!route.startsWith('/') ? '/api/' : ''}${route + (type === 'get' ? arrayToRouteParams(options) : '')}`,
            type: type.toUpperCase(),
            data: type === 'get' ? [] : options,
            dataType: 'json',
            success: function(json) {
                if(json.message != null && json.errors != null) {
                    reject(0);
                    return;
                }

                if(json.error != null) {
                    console.warn('Rejected request:');
                    console.warn(json.error[0] + ' > ' + json.error[1]);
                    reject(json.error[0]); // Reject with error code as parameter
                    return;
                }

                console.log('Successful request:');
                console.log(json);
                resolve(json.response);
            },
            error: function(data) {
                if(data.status === 500) {
                    console.error('Failed request (500)');
                    $.error($.lang('error.code', { 'code': 500 }));
                    $.blockPlayButton(false);
                } else if(data.status === 422) {
                    console.log('Failed validation (422):');
                    let json = JSON.parse(data.responseText);
                    console.log(json.message);
                    console.log(json.errors);
                    reject(json.errors);
                } else {
                    console.error(`Failed request (${data.status}):`);
                    console.error(`Route ${route + arrayToRouteParams(options)} is unreachable`);
                    reject(-1);
                }
            }
        });
    })
};

$.formDataRequest = function(route, options) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: `${!route.startsWith('/') ? '/api/' : ''}${route}`,
            type: 'POST',
            data: options,
            contentType: false,
            processData: false,
            success: function() {
                resolve();
            },
            error: function(data) {
                reject(data);
            }
        });
    })
};

$.parseValidation = function(json, keyTranslations) {
    let result = '';
    for(let i = 0; i < Object.keys(json).length; i++) {
        result += `${i === 0 ? '' : '<br>'} * ${$.lang(keyTranslations[Object.keys(json)[i]])}`;
        for(let j = 0; j < Object.values(json)[i].length; j++) result += '<br>' + $.lang('error.'+Object.values(json)[i][j]);
    }
    return result;
};

$.setBearer = function(token) {
    whispers.bearerToken = token;
}

const whispers = {
    data: {},
    bearerToken: null
}

function arrayToRouteParams(array) {
    let result = '';
    for(let i = 0; i < array.length; i++) result += `/${array[i]}`;
    return result;
}