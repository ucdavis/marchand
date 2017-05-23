// This is a manifest file that'll be compiled into application.js, which will include all the files
// listed below.
//
// Any JavaScript/Coffee file within this directory, lib/assets/javascripts, vendor/assets/javascripts,
// or any plugin's vendor/assets/javascripts directory can be referenced here using a relative path.
//
// It's not advisable to add code directly here, but if you do, it'll appear at the bottom of the
// compiled file. JavaScript code in this file should be added after the last require_* statement.
//
// Read Sprockets README (https://github.com/rails/sprockets#sprockets-directives) for details
// about supported directives.
//
//= require jquery
//= require jquery_ujs
//= require turbolinks
//= require bootstrap.min.js
//= require bootstrap2-toggle.js
//= require bootstrap-slider.js
//= require_tree .

$(document).ready(function(){

    var StickyElement = function(node){
        var doc = $(document),
        fixed = false,
        anchor = node.find('.sticky-anchor'),
        content = node.find('.sticky-content');

        var onScroll = function(e){
            var docTop = doc.scrollTop(),
            anchorTop = anchor.offset().top;
            testyes = node.find('.btn-group-up');

            console.log('scroll', docTop, anchorTop);
            if(docTop > anchorTop+300){
                if(!fixed){
                    testyes.addClass('dropup');
                    content.addClass('fixed');
                    fixed = true;
                }
            }  else   {
                if(fixed){
                    anchor.height(0);
                    content.removeClass('fixed');
                    testyes.removeClass('dropup');
                    fixed = false;
                }
            }
        };

        $(window).on('scroll', onScroll);
    };

    var stickydemo = new StickyElement($('#sticky'));


    $("#slider").slider({
        min: 0,
        max: 100,
        step: 1,
        values: [10, 90],
        slide: function(event, ui) {
            for (var i = 0; i < ui.values.length; ++i) {
                $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
            }
        }
    });

    $("input.sliderValue").change(function() {
        var $this = $(this);
        $("#slider").slider("values", $this.data("index"), $this.val());
    });
});


