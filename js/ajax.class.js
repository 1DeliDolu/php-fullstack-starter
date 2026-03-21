class AJAX {
    constructor(data = {}, callback = null) {
        this.url = 'ajax.php';
        this.data = data;
        this.callback = callback;
        this.send();
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