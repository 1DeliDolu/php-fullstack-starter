// wenn DOM komplett geladen ist
$(document).ready((e)=>{
    try {
        console.log('DOM geladen');

        // load content
        // header
        $('body').addClass('home');
        new AJAX({mvc: 'header.display', template: 'special'}, showHeader);
        new AJAX({mvc: 'menu.display'}, showMenu);
        new AJAX({mvc: 'menu.display', template:'submenu'}, showFooter);
        new AJAX({mvc: 'footer.display'}, showFooter);

    }catch(error) {
        console.error(error);
    }
});


function setMenuEvents() {
    // change theme button
    $('#change-theme').on('click', (e)=>{
        if($(e.target).hasClass('bi-sun-fill')){
            $(e.target).removeClass('bi-sun-fill').addClass('bi-moon-fill');
            $('body').attr('data-bs-theme', 'light');
        }else{
            $(e.target).addClass('bi-sun-fill').removeClass('bi-moon-fill');
            $('body').attr('data-bs-theme', 'dark');
        }
    });
    // events for hyperlinks
    $('a[data-href], button[data-href]').unbind('click').on('click', (e)=>{
        // remove anchor-default-hyperlink-functionality
        e.preventDefault();
        // remove active-class from all hyperlinks and add active-class to the clicked one
        $('a[data-href]').removeClass('active');
        $(e.target).addClass('active');
        // call data-href change body-class and AJAX request
        const href = $(e.target).attr('data-href');
        if(href === 'login'){
            new AJAX({mvc: 'login.display', template: 'modal'}, showModal);
        }else if(href === 'register'){
            new AJAX({mvc: 'login.display', template: 'modal.register'}, showModal);
        }else if(href === 'getpassword'){
            new AJAX({class: 'password.getPassword', password:''}, (response) => {
                $('#pass').val(response.html).attr('type', 'text').trigger('input');
                $('#pass_repeat').prop('readonly', false).val(response.html).prop('readonly', true).trigger('input');

            });
        }else if(href === 'home'){
            $('body').addClass('home');
            new AJAX({mvc: 'header.display', template: 'special'}, showHeader);
        }else{
            $('body').removeClass('home');
            new AJAX({mvc: 'header.display', template: 'default'}, showHeader);
        }
    });
}

// if form loaded
function loadedForm(id) {
    $(`#${id}`).on('submit', (e) => {
        // clear default behavior -> no send to action
        e.preventDefault();
        // read all inputs, textareas, selects, etc. -> save key and values in data
        $data = {
            mvc: $(`#${id}`).attr('data-action'),
        };
        $(`#${id} input, #${id} textarea, #${id} select`).each((idx, elem)=>{
            $data[$(elem).attr('id')] = $(elem).val();
        });        
        // send data to server
        new AJAX($data, (response) => {
            if(response.html){
                $('.modal').modal('hide');
            }else{
                // ??
                $('.modal .modal-dialog').shake();
            }            
        });
    });
    // special events for forms
    // deactivate submit on start
    $('#form-register [type="submit"]').prop('disabled', true);
    // check username
    $('#form-register #username').on('input', (e) => {
        if($(e.target).val().length == 0){
            $(e.target).removeClass('is-invalid is-valid');
        }else{
            new AJAX(
                {
                    class: 'mail.getVerify',
                    mail: $(e.target).val().trim()
                },
                (response) => {
                    $(e.target).addClass(response.html?'is-valid':'is-invalid').removeClass(response.html?'is-invalid':'is-valid').trigger('change');
                }
            );
        }
        $(e.target).trigger('change');
    });
    // check password
    $('#form-register #pass').on('keydown',(e)=>{
        $('#pass_repeat').prop('readonly', false).val('').trigger('input');
        $(e.target).attr('type', 'password').trigger('change');
    }).on('input',(e)=>{
        if($(e.target).val().length == 0){
            $(e.target).removeClass('is-invalid is-valid');
            // add without Y: (20240826)
            $('.password + .progress').attr('aria-valuenow', 0);
            $('.password + .progress .progress-bar').css({
                width: 0
            }).removeClass('bg-success bg-warning bg-danger bg-info bg-light bg-dark');
            // end add
        }else{
            new AJAX(
                {
                    class: 'password.getMistakes',
                    password: $(e.target).val().trim()
                },
                (response) => {
                    $(e.target).addClass(response.html.code?'is-invalid':'is-valid').removeClass(response.html.code?'is-valid':'is-invalid').trigger('change');
                    // change progress bar
                    let bgClass = ((percent)=>{
                        if(percent >= 1){
                            return 'bg-success';
                        }else if(percent <= .5){
                            return 'bg-danger';
                        }else if(percent > 0){  // change without Y: (20240826)
                            return 'bg-warning';
                        }else{                  // add without Y: (20240826)
                            return '';          // add without Y: (20240826)
                        }
                    })(response.html.percentage);
                    $('.password + .progress').attr('aria-valuenow', response.html.percentage);
                    $('.password + .progress .progress-bar').css({
                        width: response.html.percentage * 100 + '%'
                    }).removeClass('bg-success bg-warning bg-danger bg-info bg-light bg-dark').addClass(bgClass);
                }
            );
        }
        $(e.target).trigger('change');
    });
    // check password repeat
    $('#form-register #pass_repeat').on('input',(e)=>{
        if($(e.target).val().length == 0){
            $(e.target).removeClass('is-invalid is-valid');
        }else if($(e.target).val()!= $('#form-register #pass').val()){
            $(e.target).addClass('is-invalid').removeClass('is-valid');
        }else{
            $(e.target).addClass('is-valid').removeClass('is-invalid');
        }
        $(e.target).trigger('change');
    });
    // enable submit on valid form
    $('#form-register input').on('change',(e)=>{
        $('#form-register [type="submit"]').prop('disabled', $('#form-register input').length != $('#form-register input.is-valid').length);
    });
}

// callbacks for AJAX requests
const showHeader = (response) => {
    $('header').html(response.html);
};
const showMenu = (response) => {
    $('nav').html(response.html);
    setMenuEvents();
};
const showFooter = (response) => {
    $('footer').append(response.html);
    setMenuEvents();
};
const showModal = (response) => {
    $('body').append(response.html.modal);
    setMenuEvents();
    $(`#${response.html.id}`).modal('show').on('hidden.bs.modal', (e) => {
        $(e.target).remove();
    });
}


// jquery prototype - Erweiterung von jQuery durch eigene Methoden
jQuery.fn.shake = function(options = {}) {
    this.each(function(i) {
        for (var x = 1; x <= 3; x++) {
            $(this).animate({left: -5}, 50).animate({left: 5}, 50);
        }
        $(this).animate({left: 0}, 50);
    });
    return this;
}