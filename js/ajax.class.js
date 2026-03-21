class AJAX {
    constructor(data = {}, callback = null) {
        this.url = AJAX.resolveUrl();
        this.data = data;
        this.callback = callback;
        this.send();
    }
    static resolveUrl() {
        const configuredUrl = document.querySelector('meta[name="app-ajax-url"]')?.content?.trim();
        if (configuredUrl) {
            return configuredUrl;
        }
        return new URL('ajax.php', window.location.href).toString();
    }
    send() {
        // https://api.jquery.com/jQuery.ajax/#jQuery-ajax-url-settings
        $.ajax({
            accepts: {
                json: 'application/json; charset=utf-8',
                html: 'text/html; charset=utf-8',
            },
            async: true,
            cache: false,
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(this.data),
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            method: 'POST',
            url: this.url,
            success: (response, textStatus, xhr) => {
                console.info('AJAX request successful', response, textStatus, xhr);
                if(this.callback != null){
                    this.callback(response);
                }
            },
            error: (xhr, status, error) => {
                console.error(`Error: ${error}`, xhr, status);
            }
        });
    }
}
