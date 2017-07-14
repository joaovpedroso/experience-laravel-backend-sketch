!(function () {
    this.Featured = function ()
    {
        var defaults = {
            url: window.location.pathname,
            faChecked: '<i class="fa fa-star"></i>',
            faUnchecked: '<i class="fa fa-star-o"></i>',
            onSuccess: function () {},
            onError: function () {}
        }

        this.el = document.querySelectorAll(arguments[0]);
        if (this.el.length == 0) return false;

        this.label = [];

        this.options = extendDefaults(defaults, arguments[1]);

        this.init();
    }

    Featured.prototype.init = function ()
    {
        for (var i = 0; i < this.el.length; ++i) {
            this.insertLabel(i);
            this.createEvents(i);
        }
    }

    Featured.prototype.insertLabel = function (i)
    {
        this.el[i].style.display = 'none';

        this.el[i].setAttribute('id', 'featured' + i);

        this.label[i] = document.createElement('label');
        this.label[i].setAttribute('for', 'featured' + i);

        if (this.el[i].checked) {
            this.label[i].innerHTML = this.options.faChecked;
        } else {
            this.label[i].innerHTML = this.options.faUnchecked;
        }

        this.el[i].parentNode.insertBefore(this.label[i], this.el[i].nextSibling);
    }

    Featured.prototype.createEvents = function (i)
    {
        var self = this;

        this.el[i].onchange = function (event)
        {
            self.executeAjax(event.srcElement.checked, i);

            if (event.srcElement.checked) {
                self.label[i].innerHTML = self.options.faChecked;
            } else {
                self.label[i].innerHTML = self.options.faUnchecked;
            }
        }
    }

    Featured.prototype.executeAjax = function (checked, i)
    {
        var self = this;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = xmlhttp.responseText;

                if (response == 200) {
                    self.options.onSuccess();
                } else {
                    self.options.onError();
                }
            }
        }

        var url = this.options.url + '?checked=' + checked;
        url += '&id=' + this.el[i].getAttribute('data-id');

        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    function extendDefaults(source, properties)
    {
        var property;

        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }

        return source;
    }
}());

!(function () {
    this.Video = function ()
    {
        this.el = document.querySelector(arguments[0]);
        if ( ! this.el) return false;

        this.youtubeToken = arguments[1].youtubeToken;

        this.init();
    }

    Video.prototype.init = function ()
    {
        this.addEvents();
        this.checkIfHasVideo();
    }

    Video.prototype.addEvents = function ()
    {
        var self = this;

        this.el.onchange = function (event)
        {
            document.querySelector('.js-video-output').innerHTML = '';

            var link = event.srcElement.value;

            self.checkVideoType(link);
        }
    }

    Video.prototype.checkIfHasVideo = function ()
    {
        this.checkVideoType(this.el.value);
    }

    Video.prototype.checkVideoType = function (link)
    {
        if (link.indexOf('youtu') > -1) {
            videoYoutube(link, this.youtubeToken);
        } else if (link.indexOf('facebook') > -1) {
            videoFacebook(link);
        } else if (link.indexOf('vimeo') > -1) {
            videoVimeo(link);
        }
    }

    function videoYoutube (link, token)
    {
        if (link.indexOf('youtu.be') > -1) { //youtu.br/ID
            var n = link.lastIndexOf('/');
            var video_id = link.substring(n + 1);
        } else { //youtube.com/watch?v=ID
            var n = link.indexOf('v=');
            var video_id = link.substring(n + 2);

            if (video_id.indexOf('&') > -1) {
                video_id = video_id.substring(0, video_id.indexOf('&'));
            }
        }

        var url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='
            + video_id +
            '&key=' + token;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = JSON.parse(xmlhttp.responseText);

                var publishedAt = response.items[0].snippet.publishedAt;
                publishedAt = publishedAt.substring(0, publishedAt.indexOf('T'));
                publishedAt = publishedAt.split('-').reverse().join('/');

                videoOutput(
                    'http://img.youtube.com/vi/' + video_id + '/hqdefault.jpg',
                    response.items[0].snippet.title,
                    response.items[0].snippet.description,
                    publishedAt
                );
            }
        }
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    function videoFacebook (link)
    {
        var video_id = link.match(/\d+/)[0];

        var url = "https://graph.facebook.com/" + video_id;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = JSON.parse(xmlhttp.responseText);

                var publishedAt = response.created_time;
                publishedAt = publishedAt.substring(0, publishedAt.indexOf('T'));
                publishedAt = publishedAt.split('-').reverse().join('/');

                videoOutput(
                    response.picture,
                    response.from.name,
                    response.description,
                    publishedAt
                );
            }
        }
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    function videoVimeo (link)
    {
        var video_id = link.match(/\d+/)[0];

        var url = "https://vimeo.com/api/v2/video/" + video_id + ".json";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = JSON.parse(xmlhttp.responseText);

                var publishedAt = response[0].upload_date;
                publishedAt = publishedAt.substring(0, publishedAt.indexOf(' '));
                publishedAt = publishedAt.split('-').reverse().join('/');

                videoOutput(
                    response[0].thumbnail_medium,
                    response[0].title,
                    response[0].description,
                    publishedAt
                );
            }
        }
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }

    function videoOutput (the_image, the_title, the_description, the_date)
    {
        //the row
        var row = document.createElement('div');
        row.setAttribute('class', 'row');

        //the first col (image)
        var first_col = document.createElement('div');
        first_col.setAttribute('class', 'col-md-3');
        row.appendChild(first_col);

        var image = document.createElement('img');
        image.setAttribute('class', 'img-responsive');
        image.setAttribute('src', the_image);
        first_col.appendChild(image);

        //the second col (descriptions)
        var second_col = document.createElement('div');
        second_col.setAttribute('class', 'col-md-9');
        row.appendChild(second_col);

        var title = document.createElement('h4');
        title.innerHTML = the_title;
        second_col.appendChild(title);

        var description = document.createElement('p');
        description.innerHTML = the_description;
        second_col.appendChild(description);

        var date = document.createElement('p');
        date.innerHTML = 'Publicado em: ' + the_date;
        second_col.appendChild(date);

        //output
        document.querySelector('.js-video-output').appendChild(row);
    }
}());

!(function () {
    this.MapLocation = function ()
    {
        var defaults = {
            lat: 0,
            lng: 0,
            scrollwheel: false,
            zoom: 15,
            title: 'Select the company location.',
            latInput: '#lat',
            lngInput: '#lng',
            activator: false
        }

        this.el = document.querySelector(arguments[0]);
        if ( ! this.el ) return false;

        this.options = extendDefaults(defaults, arguments[1]);

        this.init();
    }

    MapLocation.prototype.init = function ()
    {
        if ( ! this.options.activator ) {
            this.initMap();
        } else {
            var self = this;
            document.querySelector(this.options.activator).onclick = function () {
                if (self.el.firstChild) return;
                self.initMap();
            }
        }
    }

    MapLocation.prototype.initMap = function ()
    {
        var latInputVal = document.querySelector(this.options.latInput).value,
            lngInputVal = document.querySelector(this.options.lngInput).value,
            lat = +this.options.lat,
            lng = +this.options.lng;

        if (latInputVal) lat = +latInputVal;
        if (lngInputVal) lng = +lngInputVal;

        var myLatLng = {lat:lat, lng:lng};

        this.map = new google.maps.Map(this.el, {
            scrollwheel: this.options.scrollwheel,
            zoom: this.options.zoom,
            center: myLatLng
        });

        this.marker = new google.maps.Marker({
            position: myLatLng,
            map: this.map,
            draggable: true,
            title: this.options.title
        });

        this.addDragEvent();
        this.addManualChangeEvent();
    }

    MapLocation.prototype.addDragEvent = function ()
    {
        var self = this;
        google.maps.event.addListener(this.marker, 'dragend', function (event) {
            document.querySelector(self.options.latInput).value = this.getPosition().lat();
            document.querySelector(self.options.lngInput).value = this.getPosition().lng();
        });
    }

    MapLocation.prototype.addManualChangeEvent = function ()
    {
        var self = this;

        document.querySelector(this.options.latInput).onchange = refreshLatLng;
        document.querySelector(this.options.lngInput).onchange = refreshLatLng;

        function refreshLatLng() {
            var lat = document.querySelector(self.options.latInput).value,
                lng = document.querySelector(self.options.lngInput).value,
                newLatLng = new google.maps.LatLng(lat, lng);

            self.marker.setPosition(newLatLng);
            self.map.panTo(newLatLng)
        }
    }

    function extendDefaults(source, properties)
    {
        var property;
        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }
        return source;
    }
}());

//Token to Ajax Requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


//Input mask - Input helper
$(function($){
    var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
    $('.phone-mask').mask(SPMaskBehavior, spOptions);

    $('.date-mask').mask('00/00/0000');
    $('.datetime-mask').mask('00/00/0000 00:00');

    $('.cpf-mask').mask('000.000.000-00', {reverse: true});

    $('.cnpj-mask').mask('00.000.000/0000-00', {reverse: true});

    $('.cep-mask').mask('00000-000');

    $('.hour-mask').mask('00:00');

    $('.money-mask').mask("#.##0,00", {reverse: true});
});


//Date Pickers
$('.input-append.date').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy',
});


//Time pickers
$('.clockpicker').clockpicker({
    autoclose: true
});


//Multiselect - Select2 plug-in
$(".multi").select2();


//Featured button
var options = {
    url: '/content/noticias/featured',
    onError: function () {
        noty({text: 'Ocorreu um problema! Tente novamente mais tarde.', type: 'error'});
    }
}
var featured = new Featured('.js-featured', options);


//Video output
var video = new Video('.js-video', {
    youtubeToken: 'AIzaSyBP5tt8ohF2kawLcb58qcnqumF_Fuayv44'
});


//Dropzone (refresh the page after uploaded)
Dropzone.autoDiscover = false;
if (document.querySelector('.dropzone')) {
    var myDropzone = new Dropzone(".dropzone");

    myDropzone.on("complete", function (file) {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            location.reload();
        }
    });
}





//knob
$(function() {
    $(".dial").knob();
});


//Switchery
var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
elems.forEach(function(html) {
    var switchery = new Switchery(html, { color: '#9ec54d' });
});


//
// toggle charts
//
$('.js-toggle-chart').on('click', function() {
    $('.js-charts').slideToggle(200);
});


//
// back button
//
function goBack() {
    window.history.back();
}

//
// form:get = hide empty inputs from url
//
$(document).on('submit', 'form[method=get]', function() {
    var inputs = $(this).find('input, select, textarea');

    $.each(inputs, function() {
        if ($(this).val() == '') {
            $(this).prop('disabled', true);
        }
    });
});

// auto-fill "http://" when clicking on a input type=url
$('input[type=url]').on('focus', function () {
    if ($(this).val()) return;

    $(this).val('http://');
});

$('input[type=url]').on('blur', function () {
    if ( $(this).val() != 'http://' ) return;

    $(this).val(null);
});

//
// the checkbox to select everything
//
$('.table th .checkall').on('click', function () {
    if ($(this).is(':checked')) {
        $('.table').find('tbody td .check-select input').prop('checked', true).change();
    }
    else {
        $('.table').find('tbody td .check-select input').prop('checked', false).change();
    }
});

$('.table tbody td .check-select input').on('change', function() {
    if ($(this).is(':checked')) {
        $(this).parent().parent().parent().addClass('row_selected');
        $(this).attr('checked', true);
    }
    else {
        $(this).parent().parent().parent().removeClass('row_selected');
        $(this).attr('checked', false);

        $('.table').find('.checkall').attr('checked', false);
    }

    if ( $('.table').find('.check-select input:checked').length > 0 )
        $('.selected-options').css('visibility', 'visible');
    else
        $('.selected-options').css('visibility', 'hidden');

    if ( $('.table').find('tbody .check-select input').length == $('.table').find('tbody .check-select input:checked').length )
        $('.table').find('.checkall').attr('checked', true);
});

//
// delete selected items
//
$('.selected-options .delete').on('click', function() {
    var token = $(this).find('input').val();
    var checked = $('.table tbody').find('td .check-select input:checked');
    var params = new Array();

    $.each(checked, function(index, value) {
        var id = $(value).attr('id');
        params.push(id);
    });

    post_request(window.location.pathname+'/0', {selected: params}, 'post', token);
});

//
// restore selected items
//
$('.selected-options .restore').on('click', function() {
    var checked = $('.table tbody').find('td .check-select input:checked');
    var params = new Array();

    $.each(checked, function(index, value) {
        var id = $(value).attr('id');
        params.push(id);
    });

    var path = window.location.pathname;
    path = path.replace('trash', '');
    path = path+'0/restore';

    post_request(path, {entries: params}, 'get');
});

//
// change status of selected items
//
$('.selected-options .status').on('click', function() {
    var checked = $('.table tbody').find('td .check-select input:checked');
    var params = new Array();

    $.each(checked, function(index, value) {
        var id = $(value).attr('id');
        params.push(id);
    });

    var status = $(this).data('status');
    var path = window.location.pathname + '/status/' + status;

    post_request(path, {entries: params}, 'get');
});

//
// change user of selected items
//
$('.selected-options .users').on('click', function() {
    var checked = $('.table tbody').find('td .check-select input:checked');
    var params = new Array();

    $.each(checked, function(index, value) {
        var id = $(value).attr('id');
        params.push(id);
    });

    var user = $(this).data('id');
    var path = window.location.pathname + '/transfer/' + user;
    path = path.replace('done/', ''); //gohorse fix

    post_request(path, {entries: params}, 'get');
});


//
// function to send a post request
//
function post_request(path, params, method, token) {
    method = method || "post";

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    //if there is a TOKEN, it is a DELETE REQUEST!
    if (typeof token != 'undefined') {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "_token");
        hiddenField.setAttribute("value", token);
        form.appendChild(hiddenField);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "_method");
        hiddenField.setAttribute("value", "DELETE");
        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form.submit();
}

!(function() {
    this.Cep = function() {
        //Default options
        var defaults = {
            cepInput: '.js-cep',
            streetInput: ".js-street",
            complementInput: ".js-complement",
            neighborhoodInput: ".js-neighborhood",
            stateInput: ".js-state",
            cityInput: ".js-city",
            onError: null,
            onSuccess: null,
        }

        //Initialize plugin
        this.options = defaults;
        this.arguments = arguments;
        this.init();
    }


    //Initialize Plugin
    Cep.prototype.init = function() {
        //Form argument (default: <form>)
        if (typeof this.arguments[0] === "undefined") {
            this.arguments[0] = "form";
        }

        //Get the user personal options
        if (this.arguments[1] && typeof this.arguments[1] === "object") {
            this.options = extendDefaults(this.options, this.arguments[1]);
        }

        //Get the selected form
        this.el = document.querySelectorAll(this.arguments[0])[0];

        //Initialize input event
        var self = this;
        var cepInput = this.el.querySelectorAll(this.options.cepInput)[0];
        cepInput.addEventListener("input", function(event) {
            self.get(event);
        });
    }


    //Get data from VIACEP.com.br
    Cep.prototype.get = function(event) {
        var cep = event.srcElement.value.replace(/\D/g, "");
        if (cep.length != 8) {
            return false;
        }

        var url = "https://viacep.com.br/ws/" + cep + "/json/";
        var self = this;

        //Json Http Request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = JSON.parse(xmlhttp.responseText);

                if (response.erro === true) {
                    self.onError();
                    return false;
                }

                self.onSuccess();
                self.response = response;
                self.fillInputs();
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }


    //Fill inputs with returned data
    Cep.prototype.fillInputs = function() {
        //Inputs
        var streetInput = this.el.querySelectorAll(this.options.streetInput)[0];
        var complementInput = this.el.querySelectorAll(this.options.complementInput)[0];
        var neighborhoodInput = this.el.querySelectorAll(this.options.neighborhoodInput)[0];
        var stateInput = this.el.querySelectorAll(this.options.stateInput)[0];
        var cityInput = this.el.querySelectorAll(this.options.cityInput)[0];

        //Fill inputs
        streetInput.value = this.response.logradouro;
        complementInput.value = this.response.complemento;
        neighborhoodInput.value = this.response.bairro;
        stateInput.value = this.response.uf;

        if (cityInput.tagName == "SELECT") {
            this.fillCitiesForSelect();
        }
        else { // "INPUT"
            cityInput.value = this.response.localidade;
        }
    }


    //Fill cities for <SELECT> (with Ajax)
    Cep.prototype.fillCitiesForSelect = function() {
        var stateInput = this.el.querySelectorAll(this.options.stateInput)[0];
        var cityInput = this.el.querySelectorAll(this.options.cityInput)[0];

        //Trigger "change" event on State Input
        stateInput.dispatchEvent(new CustomEvent("change"));

        //Check if the Cities are filled
        var condition = function() {
            options = cityInput.childNodes.length;
            check(options > 1);
        }

        var self = this;
        var check = function(filled) {
            if (filled) {
                for (var i = 0; i < cityInput.childNodes.length; i++) {
                    if (cityInput.childNodes[i].text == self.response.localidade) {
                        cityInput.selectedIndex = i;
                        break;
                    }
                }
            }
            else {
                setTimeout(function() {
                    condition();
                }, 100);
            }
        }

        condition();
    }


    //When the Cep is not found
    Cep.prototype.onError = function() {
        if (typeof this.options.onError === "function") {
            this.options.onError();
        }
    }

    //When the Cep is found
    Cep.prototype.onSuccess = function() {
        if (typeof this.options.onSuccess === "function") {
            this.options.onSuccess();
        }
    }


    //Extend Defaults with User Options
    function extendDefaults(source, properties) {
        var property;

        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }
        return source;
    }
}());

+function ($) { "use strict";

    var isIE = window.navigator.appName == 'Microsoft Internet Explorer'

    // FILEUPLOAD PUBLIC CLASS DEFINITION
    // =================================

    var Fileinput = function (element, options) {
        this.$element = $(element)

        this.$input = this.$element.find(':file')
        if (this.$input.length === 0) return

        this.name = this.$input.attr('name') || options.name

        this.$hidden = this.$element.find('input[type=hidden][name="' + this.name + '"]')
        if (this.$hidden.length === 0) {
            this.$hidden = $('<input type="hidden">').insertBefore(this.$input)
        }

        this.$preview = this.$element.find('.fileinput-preview')
        var height = this.$preview.css('height')
        if (this.$preview.css('display') !== 'inline' && height !== '0px' && height !== 'none') {
            this.$preview.css('line-height', height)
        }

        this.original = {
            exists: this.$element.hasClass('fileinput-exists'),
            preview: this.$preview.html(),
            hiddenVal: this.$hidden.val()
        }

        this.listen()
    }

    Fileinput.prototype.listen = function() {
        this.$input.on('change.bs.fileinput', $.proxy(this.change, this))
        $(this.$input[0].form).on('reset.bs.fileinput', $.proxy(this.reset, this))

        this.$element.find('[data-trigger="fileinput"]').on('click.bs.fileinput', $.proxy(this.trigger, this))
        this.$element.find('[data-dismiss="fileinput"]').on('click.bs.fileinput', $.proxy(this.clear, this))
    },

        Fileinput.prototype.change = function(e) {
            var files = e.target.files === undefined ? (e.target && e.target.value ? [{ name: e.target.value.replace(/^.+\\/, '')}] : []) : e.target.files

            e.stopPropagation()

            if (files.length === 0) {
                this.clear()
                return
            }

            this.$hidden.val('')
            this.$hidden.attr('name', '')
            this.$input.attr('name', this.name)

            var file = files[0]

            if (this.$preview.length > 0 && (typeof file.type !== "undefined" ? file.type.match(/^image\/(gif|png|jpeg)$/) : file.name.match(/\.(gif|png|jpe?g)$/i)) && typeof FileReader !== "undefined") {
                var reader = new FileReader()
                var preview = this.$preview
                var element = this.$element

                reader.onload = function(re) {
                    var $img = $('<img>')
                    $img[0].src = re.target.result
                    files[0].result = re.target.result

                    element.find('.fileinput-filename').text(file.name)

                    // if parent has max-height, using `(max-)height: 100%` on child doesn't take padding and border into account
                    if (preview.css('max-height') != 'none') $img.css('max-height', parseInt(preview.css('max-height'), 10) - parseInt(preview.css('padding-top'), 10) - parseInt(preview.css('padding-bottom'), 10)  - parseInt(preview.css('border-top'), 10) - parseInt(preview.css('border-bottom'), 10))

                    preview.html($img)
                    element.addClass('fileinput-exists').removeClass('fileinput-new')

                    element.trigger('change.bs.fileinput', files)
                }

                reader.readAsDataURL(file)
            } else {
                if (files.length == 1)
                    this.$element.find('.fileinput-filename').text(file.name)
                else
                    this.$element.find('.fileinput-filename').text(files.length + ' arquivos selecionados')
                this.$preview.text(file.name)

                this.$element.addClass('fileinput-exists').removeClass('fileinput-new')

                this.$element.trigger('change.bs.fileinput')
            }
        },

        Fileinput.prototype.clear = function(e) {
            if (e) e.preventDefault()

            this.$hidden.val('')
            this.$hidden.attr('name', this.name)
            this.$input.attr('name', '')

            //ie8+ doesn't support changing the value of input with type=file so clone instead
            if (isIE) {
                var inputClone = this.$input.clone(true);
                this.$input.after(inputClone);
                this.$input.remove();
                this.$input = inputClone;
            } else {
                this.$input.val('')
            }

            this.$preview.html('')
            this.$element.find('.fileinput-filename').text('')
            this.$element.addClass('fileinput-new').removeClass('fileinput-exists')

            if (e !== undefined) {
                this.$input.trigger('change')
                this.$element.trigger('clear.bs.fileinput')
            }
        },

        Fileinput.prototype.reset = function() {
            this.clear()

            this.$hidden.val(this.original.hiddenVal)
            this.$preview.html(this.original.preview)
            this.$element.find('.fileinput-filename').text('')

            if (this.original.exists) this.$element.addClass('fileinput-exists').removeClass('fileinput-new')
            else this.$element.addClass('fileinput-new').removeClass('fileinput-exists')

            this.$element.trigger('reset.bs.fileinput')
        },

        Fileinput.prototype.trigger = function(e) {
            this.$input.trigger('click')
            e.preventDefault()
        }


    // FILEUPLOAD PLUGIN DEFINITION
    // ===========================

    var old = $.fn.fileinput

    $.fn.fileinput = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('bs.fileinput')
            if (!data) $this.data('bs.fileinput', (data = new Fileinput(this, options)))
            if (typeof options == 'string') data[options]()
        })
    }

    $.fn.fileinput.Constructor = Fileinput


    // FILEINPUT NO CONFLICT
    // ====================

    $.fn.fileinput.noConflict = function () {
        $.fn.fileinput = old
        return this
    }


    // FILEUPLOAD DATA-API
    // ==================

    $(document).on('click.fileinput.data-api', '[data-provides="fileinput"]', function (e) {
        var $this = $(this)
        if ($this.data('bs.fileinput')) return
        $this.fileinput($this.data())

        var $target = $(e.target).closest('[data-dismiss="fileinput"],[data-trigger="fileinput"]');
        if ($target.length > 0) {
            e.preventDefault()
            $target.trigger('click.bs.fileinput')
        }
    })

}(window.jQuery);

(function ( $ ) {

    $.fn.states = function(options) {

        var select = $(this);

        var base = $('meta[name="base-path"]').attr('content');

        var settings = $.extend({
            'url': base + '/states',
            'default': select.attr('default'),
            'onChange': function(state){}
        }, options );

        $.get(settings.url, null, function (json) {

            select.append('<option value="">Selecione...</option>');

            $.each(json, function (key, value) {
                select.append('<option value="' + value.state + '" '+(settings.default==value.state?'selected':'')+'>' + value.state + '</option>');
            })

            settings.onChange(select.val());

        }, 'json');

        select.change(function(){
            settings.onChange(select.val());
        });
    };


    $.fn.cities = function(options) {

        var select = $(this);

        var base = $('meta[name="base-path"]').attr('content');

        var settings = $.extend({
            'url': base + '/cities',
            'default': select.attr('default'),
            'state': null
        }, options );

        if ((settings.state == null) || (settings.state == 0))
            return false;
        else {

            select.html('<option>Carregando...</option>');

            $.get(settings.url + '/' + settings.state, null, function (json) {
                select.html('<option value="">Selecione...</option>');

                $.each(json, function (key, value) {
                    select.append('<option value="' + value.id + '" '+((settings.default==value.id || settings.default==value.name)?'selected':'')+'>' + value.name + '</option>');
                })

            }, 'json');

        }
    };

}( jQuery ));

/**
 * New / Close
 */
$('#btn-new-ticket').click( function() {
    $('#new-ticket-wrapper').slideToggle("fast","linear")
})

$('#btn-close-ticket').click( function() {
    $('#new-ticket-wrapper').slideToggle("fast","linear")
});


/**
 * Open reply form
 */
$('.js-ticket-open-reply').on('click', function() {
    $('.js-ticket-question').slideUp(100);
    $('.js-ticket-reply').slideDown(200);
    $("html, body").animate({ scrollTop: $(document).height() }, 1200);
    $('textarea').focus();
});


/**
 * Select Portal ~ Names
 */
$(function() {
    var portals = $('.js-portals-select').select2('val');
    selectUsers(portals);
});

$('.js-portals-select').on('change', function() {
    var portals = $(this).val();
    selectUsers(portals);
});

function selectUsers(portals) {
    var fill = '<div class="col-md-4 m-t-10"><input type="checkbox" class="js-select-all-users"> <h5 class="portal-name inline-block"></h5><div class="checkboxes"></div></div>';
    fill = $(fill);

    //if the select is empty, clear everything
    if (portals === null) $('.js-portals-fill').html(null);

    $.each(portals, function(index, value) {
        var base = $('meta[name="base-path"]').attr('content');
        var url = base + '/portals/' + value + '/users';

        //when exclude a portal from select
        var added = $('*[class^="portal-"]');
        $.each(added, function() {
            var classe = $(this).parent().attr('class').split(' ').slice(-1).pop();
            var id = classe.split('-').slice(-1).pop();

            var not_exists = portals.indexOf(id) === -1;
            if (not_exists) $('.'+classe).remove();
        });

        $.get(url, function(res) {
            //if the portal has already been added
            if ( $('.portal-'+res.portal.id).length > 0 ) return false;

            //fill the title
            fill.find('.portal-name').text(res.portal.name)
                .parent().addClass('portal-'+res.portal.id);

            //fill the checkboxes
            var checkbox = '';
            $.each(res.users, function(index, user) {
                //don't show admin and employee
                if (user.role == 1 || user.role == 2) return;

                //role
                switch (user.role) {
                    case 1: //Super Admin
                        var role = "<span class='text-error' data-toggle='tooltip' title='Super Admin'><i class='fa fa-user'></i></span>";
                        break;
                    case 2: //Colaborador
                        var role = "<span class='text-success' data-toggle='tooltip' title='Franqueadora'><i class='fa fa-user'></i></span>";
                        break;
                    case 0: //Franqueado
                        var role = "<span data-toggle='tooltip' title='Franqueado'><i class='fa fa-user'></i></span>";
                        break;
                    case 3: //Funcionário da Unidade
                        var role = "<span data-toggle='tooltip' title='Funcionário da Unidade'><i class='fa fa-user'></i></span>";
                        break;
                }

                //responsability
                var resp = '';
                switch (user.responsability) {
                    case 2: //Comercial
                        resp = "<span class='text-success' data-toggle='tooltip' title='Comercial'><i class='fa fa-fw fa-usd'></i></span>";
                        break;
                    case 3: //Assistente de Conteúdo
                        resp = "<span class='text-info' data-toggle='tooltip' title='Assistente de Conteúdo'><i class='fa fa-fw fa-database'></i></span>";
                        break;
                    case 4: //Jornalista
                        resp = "<span class='text-black' data-toggle='tooltip' title='Jornalista'><i class='fa fa-fw fa-newspaper-o'></i></span>";
                        break;
                    case 5: //Fotógrafo
                        resp = "<span class='text-warning' data-toggle='tooltip' title='Fotógrafo'><i class='fa fa-fw fa-camera'></i></span>";
                        break;
                }

                checkbox += '<div class="checkbox check-success"><input id="user-'+user.id+'" type="checkbox" name="users[]" value="'+user.id+'"><label for="user-'+user.id+'">'+role+'&nbsp;'+resp+'&nbsp;&nbsp;'+user.name+'</label></div>';
            });
            fill.find('.checkboxes').html(checkbox);

            //print on the screen
            $('.js-portals-fill').append(fill);
        });
    });
}

$(document).on('change', '.js-select-all-users', function() {
    var checked = $(this).prop('checked');

    if (checked)
        $(this).parent().find('.checkboxes input').prop('checked', true);
    else
        $(this).parent().find('.checkboxes input').prop('checked', false);
});


/**
 * Print Ticket
 */
function printTicket(id) {
    $('.grid').removeClass('no-print');
    $('.grid').not('#'+id).addClass('no-print');
    window.print();
}

/**
 * Open ticket in name of
 */
$('.js-name-of').on('change', function() {
    var checked = $(this).prop('checked');

    if (checked) {
        $('.js-department').hide();
        $('.js-department-name-of').show();

        $('.js-users').show();

        $('.js-portals').hide();
    }
    else {
        $('.js-department').show();
        $('.js-department-name-of').hide();

        $('.js-users').hide();

        $('.js-portals').show();
    }
});

//
// checkboxes for modules ~ submodules
//
$('.js-all-modules .submodules .checkbox input').on('change', function() {
    var checked = $('.js-all-modules .submodules .checkbox input:checked').length;

    if (checked > 0)
        $(this).parent().parent().parent().find('.main-module input').prop('checked', true);
    else
        $(this).parent().parent().parent().find('.main-module input').prop('checked', false);
});


//
// show departments and superadmin when select employee user
// hide permissions tab when the user is a super admin
//
function checkRole(admin, employee) {
    if (admin) {
        $('.nav-tabs').find('.js-hide-admin').slideUp(200);
    }
    else {
        $('.nav-tabs').find('.js-hide-admin').slideDown(200);
    }

    if (employee) {
        $('.js-employee').slideDown(200);
        $('.js-responsability').slideUp(200);
    } else {
        $('.js-employee').slideUp(200);
        $('.js-responsability').slideDown(200);
    }
}

$('.js-role select').on('change', function() {
    var employee = ( $(this).val() == 2 || $(this).val() == 1 );
    var admin = $(this).val() == 1;
    checkRole(admin, employee);

    var user = ( $(this).val() == 3 || $(this).val() == 0 );
    hideNotShowModulesToUsers(user);
});

$(function() {
    var employee = ( $('.js-role select').val() == 2 || $('.js-role select').val() == 1 );
    var admin = $('.js-role select').val() == 1;
    checkRole(admin, employee);
});


//
// select all portals
//
$('.js-select-all-portals').on('click', function() {
    var portals = $('.js-all-portals').find('input');
    var checked = $(this).prop('checked');

    $.each(portals, function() {
        if (checked) {
            $(this).prop('checked', true);
        }
        else {
            $(this).prop('checked', false);
        }
    });
});

var portalBoxes = $('*[id^="portal-"]');
portalBoxes.on('click', function() {
    var selectall = $('.js-select-all-portals');

    if ((selectall.prop('checked') == true) && ($(this).prop('checked') == false))
        selectall.prop('checked', false);

    if(portalBoxes.length == portalBoxes.filter(':checked').length)
        selectall.prop('checked', true);
});


//
// select all modules
//
$('.js-select-all-modules').on('click', function() {
    var modules = $('.js-all-modules').find('input');
    var checked = $(this).prop('checked');

    $.each(modules, function() {
        if (checked) {
            $(this).prop('checked', true);
        }
        else {
            $(this).prop('checked', false);
        }
    });
});

var moduleBoxes = $('*[id^="module-"]');
moduleBoxes.on('click', function() {
    var selectall = $('.js-select-all-modules');

    if ((selectall.prop('checked') == true) && ($(this).prop('checked') == false))
        selectall.prop('checked', false);

    if(moduleBoxes.length == moduleBoxes.filter(':checked').length)
        selectall.prop('checked', true);
});


//
// hide the modules where show = 0 if the user is a common user
//
function hideNotShowModulesToUsers(user) {
    var modules = $('.js-all-modules');

    //if a common user, hide the modules where show = 0
    if (modules.data('user') && user)
        modules.find('*[data-show=0]').hide();
    else
        modules.find('*[data-show=0]').show();
}

$(function() {
    hideNotShowModulesToUsers(true);
});

var wait = '>_ Aguarde! Carregando...';

/**
 * Quick shell commands
 */
$('.js-command').on('click', function() {
    $('#terminal').html(wait);

    var command = $(this).data('command');
    terminal(command);
});

/**
 * Manual shell commands
 */
$('.js-manual-terminal').on('submit', function(e) {
    e.preventDefault();

    $('#terminal').html(wait);

    var command = $(this).find('input[name=command]').val();
    terminal(command);
});


/**
 * Execute command
 */
var terminal = function(command) {
    var url = '/config/terminal/command';

    $.post(url, {command:command}, function(res) {

        res = colorize(res);

        $('#terminal').slideUp(200, function() {
            $(this).html(res).slideDown(200);
        });

    });
}

/*
 * Git Log
 */
function gitlog(res) {
    if (res.substring(0,6) == "commit") {
        res = "<span class='text-warning'>" + res + "</span>";
    }

    return res;
}

/*
 * Git Pull
 */
function gitpull(res) {
    if (res.indexOf('Bin') > -1) {
        res = res.split('Bin ');

        if (res.length == 2) {
            res[1] = res[1].split(' -> ');

            if (res[1].length == 2) {
                res[1][1] = res[1][1].split(' ');

                if (parseInt(res[1][0]) > parseInt(res[1][1][0])) {
                    res[1][0] = "<span class='text-danger'>" + res[1][0] + "</span>";
                    res[1][1][0] = "<span class='text-success'>" + res[1][1][0] + "</span>";
                }
                else {
                    res[1][0] = "<span class='text-success'>" + res[1][0] + "</span>";
                    res[1][1][0] = "<span class='text-danger'>" + res[1][1][0] + "</span>";
                }

                res[1][1] = res[1][1].join(' ');
            }

            res[1] = res[1].join(' -> ');
        }

        res = res.join('Bin ');
    }
    else {
        res = res.split('|');

        if (res.length == 2) {
            res[1] = res[1].replace(/-/g, "<span class='text-danger'>-</span>");
            res[1] = res[1].replace(/\+/g, "<span class='text-success'>+</span>");
        }

        res = res.join('|');
    }

    return res;
}

/*
 * Composer Update
 */
function composerupdate(res) {
    if (res.indexOf('Removing') > -1 || res.indexOf('Installing') > -1) {
        res = res.split(' ');

        res[2] = "<span class='text-success'>" + res[2] + "</span>";

        res[3] = res[3].slice(0,1) + "<span class='text-warning'>" + res[3].slice(1,-1) + "</span>" + res[3].slice(-1);

        res = res.join(' ');
    }

    if (res.indexOf("Downloading") > -1) {
        res = res.slice(0,13) + "<span class='text-warning'>" + res.slice(13) + "</span>";
    }

    return res;
}

/*
 * Colorize!
 */
function colorize(res) {
    res = res.split('\n');

    var pull = false,
        update = false;

    for (r in res) {
        res[r] = res[r].trim();

        //colorize!
        //log
        res[r] = gitlog(res[r]);

        //pull
        if (res[r].substring(0,12) == 'Fast-forward') {
            pull = true;
        }

        if (pull) {
            res[r] = gitpull(res[r]);
        }

        //update
        if (res[r].substring(0,21) == 'Updating dependencies') {
            update = true;
        }

        if (update) {
            res[r] = composerupdate(res[r]);
        }
        //end colorize!
    }

    res = res.join('<br />');

    return res;
}

//duplicate phones
$(document).on('click', '.js-add-phone', function() {
    var dup = $(this).parent().find('.js-dup');
    var html = dup.html();

    dup.parent().append(html);
});


//show partner tab
$('.js-marital').on('change', function() {
    var value = $(this).val();
    marital(value);
});

$(function() {
    var value = $('.js-marital').val();
    marital(value);
});

var marital = function(value) {
    if (value == 2)
        $('.js-partner-tab').show();
    else
        $('.js-partner-tab').hide();
}


//fill territory when choose the city
$('.js-city').on('change', function() {
    var territory = $('.js-territory').val();
    if (territory != '') return false;

    var value = $(this).find('option:selected').text() + ' / ' + $('.js-state').val();
    $('.js-territory').val(value);
});

$(document).on('click', '.js-edit-task', function() {
    var td = $(this).parent().parent(),
        old_td = td.html(),
        subject = td.find('.subject'),
        message = td.find('.message'),
        subject_text = subject.text(),
        message_text = message.text(),
        new_subject = '<input type="text" name="subject" class="form-control" value="'+subject_text+'">',
        new_message = '<textarea name="message" class="form-control" rows="3"></textarea>',
        submit = '<button type="submit" class="btn btn-primary btn-small submit-task">Salvar alterações</button>';

    //hide the edit button
    $(this).hide();

    //replace the text to a form
    td.wrapInner('<form method="post"></form>');
    subject.html(new_subject);
    message.html(new_message);
    message.append('<span class="esc text-muted">Pressione <strong>ESC</strong> para cancelar</span>');
    message.append(submit);
    td.find('form').prepend('<input type="hidden" name="_method" value="PATCH">');

    //workaround to focus on message and put the cursor at the end of text
    $('textarea[name=message]').focus().val(message_text);

    //when press esc, cancel!
    $('input[name=subject], textarea[name=message]').on('keyup', function(e) {
        if (e.keyCode != 27) return;

        td.html(old_td);

        //fix tooltip bug
        $('.tooltip').hide();
        $(document).tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    });
});

//if is selected the "source by me", disable the input "source"
$(function() {
    var checked = $('.js-own').prop('checked');
    disableOwnSource(checked);
});

$('.js-own').on('change', function() {
    var checked = $(this).prop('checked');
    disableOwnSource(checked);
});

function disableOwnSource(checked)
{
    if (checked) {
        $('.js-source').attr('disabled', true);
    } else {
        $('.js-source').attr('disabled', false);
    }
}


//set active photo
$('.js-photo-active').on('click', function () {
    var id = $(this).data('id');

    var base = $('meta[name="base-path"]').attr('content');
    var url = base + "/content/noticias/cover";

    var self = this;

    $.post(url, {id:id}, function (response) {
        if (response == 418) {
            noty({text:'Antes você deve inserir legenda e créditos.', type:'error'});
            return false;
        }

        if (response != 200) {
            noty({text:'Ocorreu um problema, tente novamente mais tarde.', type:'error'});
            return false;
        }

        $('.js-photo-active').removeClass('active');
        $(self).addClass('active');
    });
});


//add comments
$('.js-add-quote').on('click', function () {
    $(this).slideUp();
    $('.js-quote').slideDown(200);
});

//open the tab from url
$(function() {
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
});

//duplicate phones
$(document).on('keyup', '.js-phone', function() {
    if ( ! $(this).val().length ) return;

    var row = $(this).parent().parent().parent();
    if (row.next().hasClass('row') ) return;

    var new_input = $('.js-duplicate-phone').next().clone();
    $(new_input).find('input').val(null);

    row.after(new_input);
});

//duplicate service
$(document).on('keyup', '.js-service', function() {
    if ( ! $(this).val().length ) return;

    var col = $(this).parent().parent();
    if (col.next().hasClass('col-md-6') ) return;

    var new_input = $('.js-duplicate-service').next().clone();
    $(new_input).find('input').val(null);

    col.after(new_input);
});

//24x7: work_all_day
$('#work_all_day').on('change', function () {
    if ($(this).prop('checked')) {
        $('#working').find(':not(#work_all_day)').attr('disabled', true);
    } else {
        $('#working').find(':not(#work_all_day)').attr('disabled', false);
    }
});

//auto-fill date-end for featured date
$('input[name=begin_featured_date]').on('change', function () {
    var val = $(this).val();
    val = val.split('/');

    var year = val[2],
        month = (val[1] - 1),
        day = val[0];

    var end_date = new Date(year, month, day);

    var year = end_date.getFullYear() + 1
    month = ('0' + (end_date.getMonth() + 1)).slice(-2),
        day = ('0' + end_date.getDate()).slice(-2);

    var end_date = day + '/' + month + '/' + year;

    $('input[name=end_featured_date]').val(end_date);
});


//add new rule for Working Hours
$(document).on('click', '.new-rule', function () {
    //clone the div
    var div = $('.js-dup-working-hours').next().clone();
    $(div).find('input[type=text]').val(null);
    $(div).find('input[type=checkbox]').attr('checked', false);
    $(div).addClass('m-t-15');

    var delete_button = '<button type="button" class="delete-rule"><i class="fa fa-times"></i></button>';
    $(div).find('div:last-child').append(delete_button);

    var number = $('.working-hours').length;

    //for each input checkbox
    $(div).find('input[type=checkbox]').each(function () {
        //change the id of the checkbox
        var checkId = $(this).attr('id');
        checkId = checkId.substring(0, checkId.length - 1);
        checkId += number;
        $(this).attr('id', checkId);

        //change the for of label
        $(this).next().attr('for', checkId);

        //change the name of the checkbox
        var name = $(this).attr('name');
        name = name.replace(/[0-9]/g, number);
        $(this).attr('name', name);
    });

    //for each input from-to
    $(div).find('input[type=text]').each(function () {
        //change the name
        var name = $(this).attr('name');
        name = name.replace(/[0-9]/g, number);
        $(this).attr('name', name);
    });

    //append it!
    $('.js-dup-working-hours').parent().append(div);
});

//delete the new rule
$(document).on('click', '.delete-rule', function () {
    $(this).parent().parent().remove();
});


//show the edit group input
$('.js-group').on('dblclick', function () {
    $(this).hide();
    $(this).parent().find('.js-edit-group').show();
    $(this).parent().find('.js-delete').show();
});

$('.js-edit-group').on('keyup', function (e) {
    if (e.keyCode == 27) {
        $(this).parent().find('.js-group').show();
        $(this).parent().find('.js-delete').hide();
        $(this).hide();
    }
});

//show inputs
$('.js-editable').dblclick(function () {
    $(this).find('.can-edit span').hide();
    $(this).find('.can-edit input').show();
});

//when pressed a key (esc or enter)
$('.js-editable .can-edit').find('input, select, textarea').keyup(function (e) {
    if (e.keyCode === 27) {
        $('.js-editable').find('.can-edit span').show();
        $('.js-editable').find('.can-edit').find('input, select, textarea').hide();
    }

    if (e.keyCode === 13) {
        var url = $(this).parent().parent().data('url');
        var id = $(this).parent().parent().data('id');

        var params = $(this).parent().parent().find('input').serialize();

        params = params.replace('&_method=DELETE', '');
        params += '&id=' + id;

        var self = this;

        $.post(url, params, function (res) {
            if (res != 200) {
                noty({text: 'Houve um problema. Tente novamente.', type: 'error'});
            } else {
                var fields = $(self).parent().parent().find('.can-edit');

                for (i=0; i < fields.length; ++i) {
                    var newValue = $(fields[i]).find('input, select, textarea').val();

                    $(fields[i]).find('span.js-refresh-value').text(newValue);
                }

                $('.js-editable').find('.can-edit span').show();
                $('.js-editable').find('.can-edit').find('input, select, textarea').hide();
            }
        });
    }
});
