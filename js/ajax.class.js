class AJAX {
    constructor(data = {}, callback = null) {
        this.url = AJAX.resolveUrl();
        this.data = data;
        this.callback = callback;
        this.send();
    }
    static resolveUrl() {
        const currentUrl = new URL(window.location.href);
        if (currentUrl.hostname.endsWith('.test')) {
            return new URL('ajax.php', currentUrl).toString();
        }

        const herdBaseUrl = document.querySelector('meta[name="app-herd-url"]')?.content?.trim();
        if (herdBaseUrl) {
            return new URL('ajax.php', `${herdBaseUrl.replace(/\/+$/, '')}/`).toString();
        }

        return new URL('ajax.php', currentUrl).toString();
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
            data: this.data,
            dataType: 'json',
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
