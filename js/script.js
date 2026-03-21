// erst wenn das document vollständig geladen wurde ...
document.addEventListener('DOMContentLoaded', () => {
    // event, das auf den klich auf das document reagiert
    document.addEventListener('click', (event) => {
        new AJAX('ajax.php', {mouse:{
            x: event.clientX,
            y: event.clientY,
            target: event.target.tagName
        }}, callbackWriteDOM);
    });

});

const callbackWriteDOM = (response) => {
    document.querySelector('body').innerHTML += response.html;
};

class AJAX {
    constructor(url, data, callback) {
        this.url = url;
        this.data = data;
        this.callback = callback;
        this.send();
    }
    send() {
        // erzeugt den XMLHttpRequest -> ajax-call
        fetch(this.url + '?get=attrib', {
            method: 'POST',
            headers: {
                'Accept': 'application/json; charset=utf-8',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(this.data)
        // then wird im nächsten schritt nach der antwort des ajax-calls automatisch ausgeführt
        }).then(
            // wandle die json-zeichenkette in ein javascript-objekt (JSON) um
            response => response.json()
        // wird nach dem vorangegangenem schritt ausgeführt
        ).then((response) => {
                // rufe die callback auf und übergeben den response
                this.callback(response);
            }
        // falls ein Fehler auftritt wird es hier verarbeitet
        ).catch((error) => {
            console.error('Error:', error);
        });
    }
}