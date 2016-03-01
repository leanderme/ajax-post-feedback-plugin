/*! ============================================================
 * bootstrapSwitch v1.8 by Larentis Mattia @SpiritualGuru
 * http://www.larentis.eu/
 *
 * Enhanced for radiobuttons by Stein, Peter @BdMdesigN
 * http://www.bdmdesign.org/
 *
 * Project site:
 * http://www.larentis.eu/switch/
 * ============================================================
 * Licensed under the Apache License, Version 2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 * ============================================================ */

!function ($) {
  "use strict";

  $.fn['bootstrapSwitch'] = function (method) {
    var inputSelector = 'input[type!="hidden"]';
    var methods = {
      init: function () {
        return this.each(function () {
            var $element = $(this)
              , $div
              , $switchLeft
              , $switchRight
              , $label
              , $form = $element.closest('form')
              , myClasses = ""
              , classes = $element.attr('class')
              , color
              , moving
              , onLabel = "ON"
              , offLabel = "OFF"
              , icon = false
              , textLabel = false;

            $.each(['switch-mini', 'switch-small', 'switch-large'], function (i, el) {
              if (classes.indexOf(el) >= 0)
                myClasses = el;
            });

            $element.addClass('has-switch');

            if ($element.data('on') !== undefined)
              color = "switch-" + $element.data('on');

            if ($element.data('on-label') !== undefined)
              onLabel = $element.data('on-label');

            if ($element.data('off-label') !== undefined)
              offLabel = $element.data('off-label');

            if ($element.data('label-icon') !== undefined)
              icon = $element.data('label-icon');

            if ($element.data('text-label') !== undefined)
              textLabel = $element.data('text-label');

            $switchLeft = $('<span>')
              .addClass("switch-left")
              .addClass(myClasses)
              .addClass(color)
              .html(onLabel);

            color = '';
            if ($element.data('off') !== undefined)
              color = "switch-" + $element.data('off');

            $switchRight = $('<span>')
              .addClass("switch-right")
              .addClass(myClasses)
              .addClass(color)
              .html(offLabel);

            $label = $('<label>')
              .html("&nbsp;")
              .addClass(myClasses)
              .attr('for', $element.find(inputSelector).attr('id'));

            if (icon) {
              $label.html('<i class="icon ' + icon + '"></i>');
            }

            if (textLabel) {
              $label.html('' + textLabel + '');
            }

            $div = $element.find(inputSelector).wrap($('<div>')).parent().data('animated', false);

            if ($element.data('animated') !== false)
              $div.addClass('switch-animate').data('animated', true);

            $div
              .append($switchLeft)
              .append($label)
              .append($switchRight);

            $element.find('>div').addClass(
              $element.find(inputSelector).is(':checked') ? 'switch-on' : 'switch-off'
            );

            if ($element.find(inputSelector).is(':disabled'))
              $(this).addClass('deactivate');

            var changeStatus = function ($this) {
              if ($element.parent('label').is('.label-change-switch')) {

              } else {
                $this.siblings('label').trigger('mousedown').trigger('mouseup').trigger('click');
              }
            };

            $element.on('keydown', function (e) {
              if (e.keyCode === 32) {
                e.stopImmediatePropagation();
                e.preventDefault();
                changeStatus($(e.target).find('span:first'));
              }
            });

            $switchLeft.on('click', function (e) {
              changeStatus($(this));
            });

            $switchRight.on('click', function (e) {
              changeStatus($(this));
            });

            $element.find(inputSelector).on('change', function (e, skipOnChange) {
              var $this = $(this)
                , $element = $this.parent()
                , thisState = $this.is(':checked')
                , state = $element.is('.switch-off');

              e.preventDefault();

              $element.css('left', '');

              if (state === thisState) {

                if (thisState)
                  $element.removeClass('switch-off').addClass('switch-on');
                else $element.removeClass('switch-on').addClass('switch-off');

                if ($element.data('animated') !== false)
                  $element.addClass("switch-animate");

                if (typeof skipOnChange === 'boolean' && skipOnChange)
                  return;

                $element.parent().trigger('switch-change', {'el': $this, 'value': thisState})
              }
            });

            $element.find('label').on('mousedown touchstart', function (e) {
              var $this = $(this);
              moving = false;

              e.preventDefault();
              e.stopImmediatePropagation();

              $this.closest('div').removeClass('switch-animate');

              if ($this.closest('.has-switch').is('.deactivate')) {
                $this.unbind('click');
              } else if ($this.closest('.switch-on').parent().is('.radio-no-uncheck')) {
                $this.unbind('click');
              } else {
                $this.on('mousemove touchmove', function (e) {
                  var $element = $(this).closest('.make-switch')
                    , relativeX = (e.pageX || e.originalEvent.targetTouches[0].pageX) - $element.offset().left
                    , percent = (relativeX / $element.width()) * 100
                    , left = 25
                    , right = 75;

                  moving = true;

                  if (percent < left)
                    percent = left;
                  else if (percent > right)
                    percent = right;

                  $element.find('>div').css('left', (percent - right) + "%")
                });

                $this.on('click touchend', function (e) {
                  var $this = $(this)
                    , $myInputBox = $this.siblings('input');

                  e.stopImmediatePropagation();
                  e.preventDefault();

                  $this.unbind('mouseleave');

                  if (moving)
                    $myInputBox.prop('checked', !(parseInt($this.parent().css('left')) < -25));
                  else
                    $myInputBox.prop("checked", !$myInputBox.is(":checked"));

                  moving = false;
                  $myInputBox.trigger('change');
                });

                $this.on('mouseleave', function (e) {
                  var $this = $(this)
                    , $myInputBox = $this.siblings('input');

                  e.preventDefault();
                  e.stopImmediatePropagation();

                  $this.unbind('mouseleave mousemove');
                  $this.trigger('mouseup');

                  $myInputBox.prop('checked', !(parseInt($this.parent().css('left')) < -25)).trigger('change');
                });

                $this.on('mouseup', function (e) {
                  e.stopImmediatePropagation();
                  e.preventDefault();

                  $(this).trigger('mouseleave');
                });
              }
            });

            if ($form.data('bootstrapSwitch') !== 'injected') {
              $form.bind('reset', function () {
                setTimeout(function () {
                  $form.find('.make-switch').each(function () {
                    var $input = $(this).find(inputSelector);

                    $input.prop('checked', $input.is(':checked')).trigger('change');
                  });
                }, 1);
              });
              $form.data('bootstrapSwitch', 'injected');
            }
          }
        );
      },
      toggleActivation: function () {
        var $this = $(this);

        $this.toggleClass('deactivate');
        $this.find(inputSelector).prop('disabled', $this.is('.deactivate'));
      },
      isActive: function () {
        return !$(this).hasClass('deactivate');
      },
      setActive: function (active) {
        var $this = $(this);

        if (active) {
          $this.removeClass('deactivate');
          $this.find(inputSelector).removeAttr('disabled');
        }
        else {
          $this.addClass('deactivate');
          $this.find(inputSelector).attr('disabled', 'disabled');
        }
      },
      toggleState: function (skipOnChange) {
        var $input = $(this).find(':checkbox');
        $input.prop('checked', !$input.is(':checked')).trigger('change', skipOnChange);
      },
      toggleRadioState: function (skipOnChange) {
        var $radioinput = $(this).find(':radio');
        $radioinput.not(':checked').prop('checked', !$radioinput.is(':checked')).trigger('change', skipOnChange);
      },
      toggleRadioStateAllowUncheck: function (uncheck, skipOnChange) {
        var $radioinput = $(this).find(':radio');
        if (uncheck) {
          $radioinput.not(':checked').trigger('change', skipOnChange);
        }
        else {
          $radioinput.not(':checked').prop('checked', !$radioinput.is(':checked')).trigger('change', skipOnChange);
        }
      },
      setState: function (value, skipOnChange) {
        $(this).find(inputSelector).prop('checked', value).trigger('change', skipOnChange);
      },
      setOnLabel: function (value) {
        var $switchLeft = $(this).find(".switch-left");
        $switchLeft.html(value);
      },
      setOffLabel: function (value) {
        var $switchRight = $(this).find(".switch-right");
        $switchRight.html(value);
      },
      setOnClass: function (value) {
        var $switchLeft = $(this).find(".switch-left");
        var color = '';
        if (value !== undefined) {
          if ($(this).attr('data-on') !== undefined) {
            color = "switch-" + $(this).attr('data-on')
          }
          $switchLeft.removeClass(color);
          color = "switch-" + value;
          $switchLeft.addClass(color);
        }
      },
      setOffClass: function (value) {
        var $switchRight = $(this).find(".switch-right");
        var color = '';
        if (value !== undefined) {
          if ($(this).attr('data-off') !== undefined) {
            color = "switch-" + $(this).attr('data-off')
          }
          $switchRight.removeClass(color);
          color = "switch-" + value;
          $switchRight.addClass(color);
        }
      },
      setAnimated: function (value) {
        var $element = $(this).find(inputSelector).parent();
        if (value === undefined) value = false;
        $element.data('animated', value);
        $element.attr('data-animated', value);

        if ($element.data('animated') !== false) {
          $element.addClass("switch-animate");
        } else {
          $element.removeClass("switch-animate");
        }
      },
      setSizeClass: function (value) {
        var $element = $(this);
        var $switchLeft = $element.find(".switch-left");
        var $switchRight = $element.find(".switch-right");
        var $label = $element.find("label");
        $.each(['switch-mini', 'switch-small', 'switch-large'], function (i, el) {
          if (el !== value) {
            $switchLeft.removeClass(el);
            $switchRight.removeClass(el);
            $label.removeClass(el);
          } else {
            $switchLeft.addClass(el);
            $switchRight.addClass(el);
            $label.addClass(el);
          }
        });
      },
      status: function () {
        return $(this).find(inputSelector).is(':checked');
      },
      destroy: function () {
        var $element = $(this)
          , $div = $element.find('div')
          , $form = $element.closest('form')
          , $inputbox;

        $div.find(':not(input)').remove();

        $inputbox = $div.children();
        $inputbox.unwrap().unwrap();

        $inputbox.unbind('change');

        if ($form) {
          $form.unbind('reset');
          $form.removeData('bootstrapSwitch');
        }

        return $inputbox;
      }
    };

    if (methods[method])
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    else if (typeof method === 'object' || !method)
      return methods.init.apply(this, arguments);
    else
      $.error('Method ' + method + ' does not exist!');
  };
}(jQuery);

(function ($) {
  $(function () {
    $('.make-switch')['bootstrapSwitch']();
  });
})(jQuery);


/* =============================================================
 * flatui-checkbox.js v0.0.2
 * ============================================================ */
 
!function ($) {

 /* CHECKBOX PUBLIC CLASS DEFINITION
  * ============================== */

  var Checkbox = function (element, options) {
    this.init(element, options);
  }

  Checkbox.prototype = {
    
    constructor: Checkbox
    
  , init: function (element, options) {      
      var $el = this.$element = $(element)
      
      this.options = $.extend({}, $.fn.checkbox.defaults, options);      
      $el.before(this.options.template);    
      this.setState(); 
    }  
   
  , setState: function () {    
      var $el = this.$element
        , $parent = $el.closest('.checkbox');
        
        $el.prop('disabled') && $parent.addClass('disabled');   
        $el.prop('checked') && $parent.addClass('checked');
    }  
    
  , toggle: function () {    
      var ch = 'checked'
        , $el = this.$element
        , $parent = $el.closest('.checkbox')
        , checked = $el.prop(ch)
        , e = $.Event('toggle')
      
      if ($el.prop('disabled') == false) {
        $parent.toggleClass(ch) && checked ? $el.removeAttr(ch) : $el.attr(ch, true);
        $el.trigger(e).trigger('change'); 
      }
    }  
    
  , setCheck: function (option) {    
      var d = 'disabled'
        , ch = 'checked'
        , $el = this.$element
        , $parent = $el.closest('.checkbox')
        , checkAction = option == 'check' ? true : false
        , e = $.Event(option)
      
      $parent[checkAction ? 'addClass' : 'removeClass' ](ch) && checkAction ? $el.attr(ch, true) : $el.removeAttr(ch);
      $el.trigger(e).trigger('change');       
    }  
      
  }


 /* CHECKBOX PLUGIN DEFINITION
  * ======================== */

  var old = $.fn.checkbox

  $.fn.checkbox = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('checkbox')
        , options = $.extend({}, $.fn.checkbox.defaults, $this.data(), typeof option == 'object' && option);
      if (!data) $this.data('checkbox', (data = new Checkbox(this, options)));
      if (option == 'toggle') data.toggle()
      if (option == 'check' || option == 'uncheck') data.setCheck(option)
      else if (option) data.setState(); 
    });
  }
  
  $.fn.checkbox.defaults = {
    template: '<span class="icons"><span class="first-icon fui-checkbox-unchecked"></span><span class="second-icon fui-checkbox-checked"></span></span>'
  }


 /* CHECKBOX NO CONFLICT
  * ================== */

  $.fn.checkbox.noConflict = function () {
    $.fn.checkbox = old;
    return this;
  }


 /* CHECKBOX DATA-API
  * =============== */

  $(document).on('click.checkbox.data-api', '[data-toggle^=checkbox], .checkbox', function (e) {
    var $checkbox = $(e.target);
    if (e.target.tagName != "A") {      
      e && e.preventDefault() && e.stopPropagation();
      if (!$checkbox.hasClass('checkbox')) $checkbox = $checkbox.closest('.checkbox');
      $checkbox.find(':checkbox').checkbox('toggle');
    }
  });
  
  $(window).on('load', function () {
    $('[data-toggle="checkbox"]').each(function () {
      var $checkbox = $(this);
      $checkbox.checkbox();
    });
  });

}(window.jQuery);

/* =============================================================
 * flatui-radio.js v0.0.2
 * ============================================================ */

!function ($) {

 /* RADIO PUBLIC CLASS DEFINITION
  * ============================== */

  var Radio = function (element, options) {
    this.init(element, options);
  }

  Radio.prototype = {
  
    constructor: Radio
    
  , init: function (element, options) {      
      var $el = this.$element = $(element)
      
      this.options = $.extend({}, $.fn.radio.defaults, options);      
      $el.before(this.options.template);    
      this.setState();
    }   
    
  , setState: function () {    
      var $el = this.$element
        , $parent = $el.closest('.radio');
        
        $el.prop('disabled') && $parent.addClass('disabled');   
        $el.prop('checked') && $parent.addClass('checked');
    } 
    
  , toggle: function () {    
      var d = 'disabled'
        , ch = 'checked'
        , $el = this.$element
        , checked = $el.prop(ch)
        , $parent = $el.closest('.radio')      
        , $parentWrap = $el.closest('form').length ? $el.closest('form') : $el.closest('body')
        , $elemGroup = $parentWrap.find(':radio[name="' + $el.attr('name') + '"]')
        , e = $.Event('toggle')
        
        $elemGroup.not($el).each(function () {
          var $el = $(this)
            , $parent = $(this).closest('.radio');
            
            if ($el.prop(d) == false) {
              $parent.removeClass(ch) && $el.attr(ch, false).trigger('change');
            } 
        });
      
        if ($el.prop(d) == false) {
          if (checked == false) $parent.addClass(ch) && $el.attr(ch, true);
          $el.trigger(e);
          
          if (checked !== $el.prop(ch)) {
            $el.trigger('change'); 
          }
        }               
    } 
     
  , setCheck: function (option) {    
      var ch = 'checked'
        , $el = this.$element
        , $parent = $el.closest('.radio')
        , checkAction = option == 'check' ? true : false
        , checked = $el.prop(ch)
        , $parentWrap = $el.closest('form').length ? $el.closest('form') : $el.closest('body')
        , $elemGroup = $parentWrap.find(':radio[name="' + $el['attr']('name') + '"]')
        , e = $.Event(option)
        
      $elemGroup.not($el).each(function () {
        var $el = $(this)
          , $parent = $(this).closest('.radio');
          
          $parent.removeClass(ch) && $el.removeAttr(ch);
      });
            
      $parent[checkAction ? 'addClass' : 'removeClass'](ch) && checkAction ? $el.attr(ch, true) : $el.removeAttr(ch);
      $el.trigger(e);  
          
      if (checked !== $el.prop(ch)) {
        $el.trigger('change'); 
      }
    }  
     
  }


 /* RADIO PLUGIN DEFINITION
  * ======================== */

  var old = $.fn.radio

  $.fn.radio = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('radio')
        , options = $.extend({}, $.fn.radio.defaults, $this.data(), typeof option == 'object' && option);
      if (!data) $this.data('radio', (data = new Radio(this, options)));
      if (option == 'toggle') data.toggle()
      if (option == 'check' || option == 'uncheck') data.setCheck(option)
      else if (option) data.setState(); 
    });
  }
  
  $.fn.radio.defaults = {
    template: '<span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span>'
  }


 /* RADIO NO CONFLICT
  * ================== */

  $.fn.radio.noConflict = function () {
    $.fn.radio = old;
    return this;
  }


 /* RADIO DATA-API
  * =============== */

  $(document).on('click.radio.data-api', '[data-toggle^=radio], .radio', function (e) {
    var $radio = $(e.target);
    if (e.target.tagName != "A") {    
      e && e.preventDefault() && e.stopPropagation();
      if (!$radio.hasClass('radio')) $radio = $radio.closest('.radio');
      $radio.find(':radio').radio('toggle');
    }
  });
  
  $(window).on('load', function () {
    $('[data-toggle="radio"]').each(function () {
      var $radio = $(this);
      $radio.radio();
    });
  });

}(window.jQuery);

/*

  jQuery Tags Input Plugin 1.3.3
  
  Copyright (c) 2011 XOXCO, Inc
  
  Documentation for this plugin lives here:
  http://xoxco.com/clickable/jquery-tags-input
  
  Licensed under the MIT license:
  http://www.opensource.org/licenses/mit-license.php

  ben@xoxco.com

*/

(function($) {

  var delimiter = new Array();
  var tags_callbacks = new Array();
  $.fn.doAutosize = function(o){
      var minWidth = $(this).data('minwidth'),
          maxWidth = $(this).data('maxwidth'),
          val = '',
          input = $(this),
          testSubject = $('#'+$(this).data('tester_id'));
  
      if (val === (val = input.val())) {return;}
  
      // Enter new content into testSubject
      var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      testSubject.html(escaped);
      // Calculate new width + whether to change
      var testerWidth = testSubject.width(),
          newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
          currentWidth = input.width(),
          isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
                               || (newWidth > minWidth && newWidth < maxWidth);
  
      // Animate width
      if (isValidWidthChange) {
          input.width(newWidth);
      }


  };
  $.fn.resetAutosize = function(options){
    // alert(JSON.stringify(options));
    var minWidth =  $(this).data('minwidth') || options.minInputWidth || $(this).width(),
        maxWidth = $(this).data('maxwidth') || options.maxInputWidth || ($(this).closest('.tagsinput').width() - options.inputPadding),
        val = '',
        input = $(this),
        testSubject = $('<tester/>').css({
            position: 'absolute',
            top: -9999,
            left: -9999,
            width: 'auto',
            fontSize: input.css('fontSize'),
            fontFamily: input.css('fontFamily'),
            fontWeight: input.css('fontWeight'),
            letterSpacing: input.css('letterSpacing'),
            whiteSpace: 'nowrap'
        }),
        testerId = $(this).attr('id')+'_autosize_tester';
    if(! $('#'+testerId).length > 0){
      testSubject.attr('id', testerId);
      testSubject.appendTo('body');
    }

    input.data('minwidth', minWidth);
    input.data('maxwidth', maxWidth);
    input.data('tester_id', testerId);
    input.css('width', minWidth);
  };
  
  $.fn.addTag = function(value,options) {
      options = jQuery.extend({focus:false,callback:true},options);
      this.each(function() { 
        var id = $(this).attr('id');

        var tagslist = $(this).val().split(delimiter[id]);
        if (tagslist[0] == '') { 
          tagslist = new Array();
        }

        value = jQuery.trim(value);
    
        if (options.unique) {
          var skipTag = $(this).tagExist(value);
          if(skipTag == true) {
              //Marks fake input as not_valid to let styling it
                $('#'+id+'_tag').addClass('not_valid');
            }
        } else {
          var skipTag = false; 
        }
        
        if (value !='' && skipTag != true) { 
                    $('<span>').addClass('tag').append(
                        $('<span>').text(value).append('&nbsp;&nbsp;'),
                        $('<a class="tagsinput-remove-link">', {
                            href  : '#',
                            title : 'Remove tag',
                            text  : ''
                        }).click(function () {
                            return $('#' + id).removeTag(escape(value));
                        })
                    ).insertBefore('#' + id + '_addTag');

          tagslist.push(value);
        
          $('#'+id+'_tag').val('');
          if (options.focus) {
            $('#'+id+'_tag').focus();
          } else {    
            $('#'+id+'_tag').blur();
          }
          
          $.fn.tagsInput.updateTagsField(this,tagslist);
          
          if (options.callback && tags_callbacks[id] && tags_callbacks[id]['onAddTag']) {
            var f = tags_callbacks[id]['onAddTag'];
            f.call(this, value);
          }
          if(tags_callbacks[id] && tags_callbacks[id]['onChange'])
          {
            var i = tagslist.length;
            var f = tags_callbacks[id]['onChange'];
            f.call(this, $(this), tagslist[i-1]);
          }         
        }
    
      });   
      
      return false;
    };
    
  $.fn.removeTag = function(value) { 
      value = unescape(value);
      this.each(function() { 
        var id = $(this).attr('id');
  
        var old = $(this).val().split(delimiter[id]);
          
        $('#'+id+'_tagsinput .tag').remove();
        str = '';
        for (i=0; i< old.length; i++) { 
          if (old[i]!=value) { 
            str = str + delimiter[id] +old[i];
          }
        }
        
        $.fn.tagsInput.importTags(this,str);

        if (tags_callbacks[id] && tags_callbacks[id]['onRemoveTag']) {
          var f = tags_callbacks[id]['onRemoveTag'];
          f.call(this, value);
        }
      });
          
      return false;
    };
  
  $.fn.tagExist = function(val) {
    var id = $(this).attr('id');
    var tagslist = $(this).val().split(delimiter[id]);
    return (jQuery.inArray(val, tagslist) >= 0); //true when tag exists, false when not
  };
  
  // clear all existing tags and import new ones from a string
  $.fn.importTags = function(str) {
                id = $(this).attr('id');
    $('#'+id+'_tagsinput .tag').remove();
    $.fn.tagsInput.importTags(this,str);
  }
    
  $.fn.tagsInput = function(options) { 
    var settings = jQuery.extend({
      interactive:true,
      defaultText:'',
      minChars:0,
      width:'',
      height:'',
      autocomplete: {selectFirst: false },
      'hide':true,
      'delimiter':',',
      'unique':true,
      removeWithBackspace:true,
      placeholderColor:'#666666',
      autosize: true,
      comfortZone: 20,
      inputPadding: 6*2
    },options);

    this.each(function() { 
      if (settings.hide) { 
        $(this).hide();       
      }
      var id = $(this).attr('id');
      if (!id || delimiter[$(this).attr('id')]) {
        id = $(this).attr('id', 'tags' + new Date().getTime()).attr('id');
      }
      
      var data = jQuery.extend({
        pid:id,
        real_input: '#'+id,
        holder: '#'+id+'_tagsinput',
        input_wrapper: '#'+id+'_addTag',
        fake_input: '#'+id+'_tag'
      },settings);
  
      delimiter[id] = data.delimiter;
      
      if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
        tags_callbacks[id] = new Array();
        tags_callbacks[id]['onAddTag'] = settings.onAddTag;
        tags_callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
        tags_callbacks[id]['onChange'] = settings.onChange;
      }
  
            var containerClass = $('#'+id).attr('class').replace('tagsinput', '');
      var markup = '<div id="'+id+'_tagsinput" class="tagsinput '+containerClass+'"><div class="tagsinput-add-container" id="'+id+'_addTag"><div class="tagsinput-add"></div>';
      
      if (settings.interactive) {
        markup = markup + '<input id="'+id+'_tag" value="" data-default="'+settings.defaultText+'" />';
      }
      
      markup = markup + '</div></div>';
      
      $(markup).insertAfter(this);

      $(data.holder).css('width',settings.width);
      $(data.holder).css('min-height',settings.height);
      $(data.holder).css('height','100%');
  
      if ($(data.real_input).val()!='') { 
        $.fn.tagsInput.importTags($(data.real_input),$(data.real_input).val());
      }   
      if (settings.interactive) { 
        $(data.fake_input).val($(data.fake_input).attr('data-default'));
        $(data.fake_input).css('color',settings.placeholderColor);
            $(data.fake_input).resetAutosize(settings);
    
        $(data.holder).bind('click',data,function(event) {
          $(event.data.fake_input).focus();
        });
      
        $(data.fake_input).bind('focus',data,function(event) {
          if ($(event.data.fake_input).val()==$(event.data.fake_input).attr('data-default')) { 
            $(event.data.fake_input).val('');
          }
          $(event.data.fake_input).css('color','#000000');    
        });
            
        if (settings.autocomplete_url != undefined) {
          autocomplete_options = {source: settings.autocomplete_url};
          for (attrname in settings.autocomplete) { 
            autocomplete_options[attrname] = settings.autocomplete[attrname]; 
          }
        
          if (jQuery.Autocompleter !== undefined) {
            $(data.fake_input).autocomplete(settings.autocomplete_url, settings.autocomplete);
            $(data.fake_input).bind('result',data,function(event,data,formatted) {
              if (data) {
                $('#'+id).addTag(data[0] + "",{focus:true,unique:(settings.unique)});
              }
              });
          } else if (jQuery.ui.autocomplete !== undefined) {
            $(data.fake_input).autocomplete(autocomplete_options);
            $(data.fake_input).bind('autocompleteselect',data,function(event,ui) {
              $(event.data.real_input).addTag(ui.item.value,{focus:true,unique:(settings.unique)});
              return false;
            });
          }
        
          
        } else {
            // if a user tabs out of the field, create a new tag
            // this is only available if autocomplete is not used.
            $(data.fake_input).bind('blur',data,function(event) { 
              var d = $(this).attr('data-default');
              if ($(event.data.fake_input).val()!='' && $(event.data.fake_input).val()!=d) { 
                if( (event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)) )
                  $(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:(settings.unique)});
              } else {
                $(event.data.fake_input).val($(event.data.fake_input).attr('data-default'));
                $(event.data.fake_input).css('color',settings.placeholderColor);
              }
              return false;
            });
        
        }
        // if user types a comma, create a new tag
        $(data.fake_input).bind('keypress',data,function(event) {
          if (event.which==event.data.delimiter.charCodeAt(0) || event.which==13 ) {
              event.preventDefault();
            if( (event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)) )
              $(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:(settings.unique)});
              $(event.data.fake_input).resetAutosize(settings);
            return false;
          } else if (event.data.autosize) {
                  $(event.data.fake_input).doAutosize(settings);
            
                }
        });
        //Delete last tag on backspace
        data.removeWithBackspace && $(data.fake_input).bind('keydown', function(event)
        {
          if(event.keyCode == 8 && $(this).val() == '')
          {
             event.preventDefault();
             var last_tag = $(this).closest('.tagsinput').find('.tag:last').text();
             var id = $(this).attr('id').replace(/_tag$/, '');
             last_tag = last_tag.replace(/[\s\u00a0]+x$/, '');
             $('#' + id).removeTag(escape(last_tag));
             $(this).trigger('focus');
          }
        });
        $(data.fake_input).blur();
        
        //Removes the not_valid class when user changes the value of the fake input
        if(data.unique) {
            $(data.fake_input).keydown(function(event){
                if(event.keyCode == 8 || String.fromCharCode(event.which).match(/\w+|[Ã¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“ÃšÃ±Ã‘,/]+/)) {
                    $(this).removeClass('not_valid');
                }
            });
        }
      } // if settings.interactive
    });
      
    return this;
  
  };
  
  $.fn.tagsInput.updateTagsField = function(obj,tagslist) { 
    var id = $(obj).attr('id');
    $(obj).val(tagslist.join(delimiter[id]));
  };
  
  $.fn.tagsInput.importTags = function(obj,val) {     
    $(obj).val('');
    var id = $(obj).attr('id');
    var tags = val.split(delimiter[id]);
    for (i=0; i<tags.length; i++) { 
      $(obj).addTag(tags[i],{focus:false,callback:false});
    }
    if(tags_callbacks[id] && tags_callbacks[id]['onChange'])
    {
      var f = tags_callbacks[id]['onChange'];
      f.call(obj, obj, tags[i]);
    }
  };

})(jQuery);

jQuery(function($){
// Generated by CoffeeScript 1.6.3
(function() {
  $.fn.fancySelect = function(opts) {
    var isiOS, settings;
    settings = $.extend({
      forceiOS: false
    }, opts);
    isiOS = !!navigator.userAgent.match(/iP(hone|od|ad)/i);
    return this.each(function() {
      var copyOptionsToList, disabled, options, sel, trigger, updateTriggerText, wrapper;
      sel = $(this);
      if (sel.hasClass('fancified') || sel[0].tagName !== 'SELECT') {
        return;
      }
      sel.addClass('fancified');
      sel.css({
        width: 1,
        height: 1,
        display: 'block',
        position: 'absolute',
        top: 0,
        left: 0,
        opacity: 0
      });
      sel.wrap('<div class="fancy-select">');
      wrapper = sel.parent();
      wrapper.addClass(sel.data('class'));
      wrapper.append('<div class="trigger">');
      if (!(isiOS && !settings.forceiOS)) {
        wrapper.append('<ul class="options">');
      }
      trigger = wrapper.find('.trigger');
      options = wrapper.find('.options');
      disabled = sel.prop('disabled');
      if (disabled) {
        wrapper.addClass('disabled');
      }
      updateTriggerText = function() {
        return trigger.text(sel.find(':selected').text());
      };
      sel.on('blur', function() {
        if (trigger.hasClass('open')) {
          return setTimeout(function() {
            return trigger.trigger('close');
          }, 120);
        }
      });
      trigger.on('close', function() {
        trigger.removeClass('open');
        return options.removeClass('open');
      });
      trigger.on('click', function() {
        var offParent, parent;
        if (!disabled) {
          trigger.toggleClass('open');
          if (isiOS && !settings.forceiOS) {
            if (trigger.hasClass('open')) {
              return sel.focus();
            }
          } else {
            if (trigger.hasClass('open')) {
              parent = trigger.parent();
              offParent = parent.offsetParent();
              if ((parent.offset().top + parent.outerHeight() + options.outerHeight() + 20) > $(window).height()) {
                options.addClass('overflowing');
              } else {
                options.removeClass('overflowing');
              }
            }
            options.toggleClass('open');
            if (!isiOS) {
              return sel.focus();
            }
          }
        }
      });
      sel.on('enable', function() {
        sel.prop('disabled', false);
        wrapper.removeClass('disabled');
        disabled = false;
        return copyOptionsToList();
      });
      sel.on('disable', function() {
        sel.prop('disabled', true);
        wrapper.addClass('disabled');
        return disabled = true;
      });
      sel.on('change', function(e) {
        if (e.originalEvent && e.originalEvent.isTrusted) {
          return e.stopPropagation();
        } else {
          return updateTriggerText();
        }
      });
      sel.on('keydown', function(e) {
        var hovered, newHovered, w;
        w = e.which;
        hovered = options.find('.hover');
        hovered.removeClass('hover');
        if (!options.hasClass('open')) {
          if (w === 13 || w === 32 || w === 38 || w === 40) {
            e.preventDefault();
            return trigger.trigger('click');
          }
        } else {
          if (w === 38) {
            e.preventDefault();
            if (hovered.length && hovered.index() > 0) {
              hovered.prev().addClass('hover');
            } else {
              options.find('li:last-child').addClass('hover');
            }
          } else if (w === 40) {
            e.preventDefault();
            if (hovered.length && hovered.index() < options.find('li').length - 1) {
              hovered.next().addClass('hover');
            } else {
              options.find('li:first-child').addClass('hover');
            }
          } else if (w === 27) {
            e.preventDefault();
            trigger.trigger('click');
          } else if (w === 13 || w === 32) {
            e.preventDefault();
            hovered.trigger('click');
          } else if (w === 9) {
            if (trigger.hasClass('open')) {
              trigger.trigger('close');
            }
          }
          newHovered = options.find('.hover');
          if (newHovered.length) {
            options.scrollTop(0);
            return options.scrollTop(newHovered.position().top - 12);
          }
        }
      });
      options.on('click', 'li', function() {
        sel.val($(this).data('value')).trigger('change');
        if (!isiOS) {
          return sel.trigger('blur').trigger('focus');
        }
      });
      options.on('mouseenter', 'li', function() {
        var hovered, nowHovered;
        nowHovered = $(this);
        hovered = options.find('.hover');
        hovered.removeClass('hover');
        return nowHovered.addClass('hover');
      });
      options.on('mouseleave', 'li', function() {
        return options.find('.hover').removeClass('hover');
      });
      copyOptionsToList = function() {
        var selOpts;
        updateTriggerText();
        if (isiOS && !settings.forceiOS) {
          return;
        }
        selOpts = sel.find('option');
        return sel.find('option').each(function(i, opt) {
          opt = $(opt);
          if (opt.val() && !opt.prop('disabled')) {
            return options.append("<li data-value=\"" + (opt.val()) + "\">" + (opt.text()) + "</li>");
          }
        });
      };
      sel.on('update', function() {
        wrapper.find('.options').empty();
        return copyOptionsToList();
      });
      return copyOptionsToList();
    });
  };

}).call(this);
});

jQuery(function($){
 var el, newPoint, newPlace, offset;
 
 // Select all range inputs, watch for change
 $("input[type='range']").change(function() {
 

   el = $(this);
   width = el.width();
   
   // Figure out placement percentage between left and right of input
   newPoint = (el.val() - el.attr("min")) / (el.attr("max") - el.attr("min"));
   
   // Janky value to get pointer to line up better
   offset = -1.3;
   
   // Prevent bubble from going beyond left or right (unsupported browsers)
   if (newPoint < 0) { newPlace = 0; }
   else if (newPoint > 1) { newPlace = width; }
   else { newPlace = width * newPoint + offset; offset -= newPoint; }
   
   // Move bubble
   el
     .next("output")
     .css({
       display:'block',
       left: newPlace+10,
       marginLeft: offset + "%"
     })
     .text(el.val());
 })
});

jQuery(function($){
	$(document).ready(function(){
		$(".pf-options .options-wrapr h3").click(function(){
			$(this).toggleClass("active").next().slideToggle("fast");
			return false;
		});

  /** AJAX Save Options */
    $('#of_save').live('click',function() {
        
      var nonce = $('#security').val();
            
      $('.ajax-loading-img').fadeIn();
      
      //get serialized data from all our option fields      
      var serializedReturn = $('#of_form :input[name][name!="security"][name!="of_reset"]').serialize();
  
      $('#of_form :input[type=checkbox]').each(function() {     
          if (!this.checked) {
              serializedReturn += '&'+this.name+'=0';
          }
      });
              
      var data = {
        type: 'save',
        action: 'pf_ajax_post_action',
        security: nonce,
        data: serializedReturn
      };
            
      $.post(ajaxurl, data, function(response) {
        var success = $('#of-popup-save');
        var fail = $('#of-popup-fail');
        var loading = $('.save-btn');  
              
        if (response==1) {
          success.fadeIn();
          loading.css({
            padding:   '10px',
            background:'#1ABC9C'
          }).val('Save');      
        } else { 
          fail.fadeIn();
        }
              
        window.setTimeout(function(){
          success.fadeOut(); 
          fail.fadeOut(); 
        }, 2000);
      });
        
    return false; 
            
    });   


	});
   // Switch
    $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();

    $('.basic-sel').fancySelect();

    $('.save-btn').on("click",function(e){
      $(this).css({
        padding:  '10px',
        background:     '#34495E'
      }).val('loading...');
    });

    jQuery("#prev-btn-triggr").click(function() {                 
        tb_show("", "http://localhost/plug/play-track/?TB_iframe=true");
        return false;
    });

});
