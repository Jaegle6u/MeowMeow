require('../scss/front.scss');
import '@popperjs/core';
require('../../node_modules/tinymce/tinymce');
require('../../node_modules/tinymce/themes/silver/index');
require('../../node_modules/tinymce/icons/default/index');
import 'bootstrap';

// Init wysiwyg
tinymce.init({
    selector: 'textarea.wysiwyg',
});
//Pour installer Jquery : npm install jquery
//puis ajouter ligne ci-dessous
var $ = require( "jquery" );

document.getElementById("btn_tout").onclick = function() {
    console.log("Click tout");
    var elements = document.getElementsByClassName("list_admin");
    for(var i= 0; i < elements.length; i++)
    {
        $(elements[i]).show();
    }
};
document.getElementById("btn_publie").onclick = function() {
    console.log("Click publie");
    var elements = document.getElementsByClassName("list_admin");
    // $(elements).show();
    for(var i= 0; i < elements.length; i++)
    {
        if(elements[i].dataset.publish == 1)
        {
            $(elements[i]).show();
        }
        else
        {
            $(elements[i]).hide();
        }
        
    }

};
document.getElementById("btn_non_publie").onclick = function() {
    console.log("Click non publie");
    var elements = document.getElementsByClassName("list_admin");
    // $(elements).show();
    for(var i= 0; i < elements.length; i++)
    {
        if(elements[i].dataset.publish == 1)
        {
            $(elements[i]).hide();
        }
        else
        {
            $(elements[i]).show();
        }
        
    }
};