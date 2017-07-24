//= require jquery
//= require jquery_ujs
//= require bootstrap-sprockets
//= require bootstrap2-toggle.js
//= require bootstrap-slider.js
//= require sticky.js
//= require_tree .

$(document).ready(function(){

  // Provides 'affix' support for search sidebar
  var StickyElement = function(node) {
    var doc = $(document),
    fixed = false,
    anchor = node.find('.sticky-anchor'),
    content = node.find('.sticky-content');

    if(anchor.length > 0) {
      var onScroll = function(e) {
        var docTop = doc.scrollTop(),
        anchorTop = anchor.offset().top;
        testyes = node.find('.btn-group-up');

        console.log('scroll', docTop, anchorTop);
        if(docTop > anchorTop+300){
          if(!fixed) {
            testyes.addClass('dropup');
            content.addClass('fixed');
            fixed = true;
          }
        }  else {
          if(fixed) {
            anchor.height(0);
            content.removeClass('fixed');
            testyes.removeClass('dropup');
            fixed = false;
          }
        }
      };

      $(window).on('scroll', onScroll);
    }
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
