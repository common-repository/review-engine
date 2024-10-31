(function ($) {
    $(document).ready(function () {
        var ajax_flag = null;
        var global_product_data = null;
        var global_product_locale = null;
        var container = $('#display-button');
        var form = $('#display-button-form');

        var preview_init = function () {
            var button = $('.form-button a.reviewengine-btn');
            var buttonWrap = $('.form-button .reviewengine-btn-wrap');

            function cssPixel( value ) {
                return value + 'px';
            }

            function renderButtonStyle( state ) {
                var style = $('select[name="btn-style"]').val(),
                    size = $('input[name="size"]:checked').val(),
                    width = $('input[name="width"]').val(),
                    fullwidth = $('input[name="fullwidth"]:checked').val(),
                    bgColor = $('input[name="bg-color"]').val(),
                    bgHoverColor = $('input[name="bg-hover-color"]').val(),
                    secondaryBgColor = $('input[name="secondary-bg-color"]').val(),
                    secondaryBgHoverColor = $('input[name="secondary-bg-hover-color"]').val(),
                    textColor = $('input[name="text-color"]').val(),
                    textHoverColor = $('input[name="text-hover-color"]').val(),
                    borderTop = $('input[name="border-top"]').val(),
                    borderRight = $('input[name="border-right"]').val(),
                    borderBottom = $('input[name="border-bottom"]').val(),
                    borderLeft = $('input[name="border-left"]').val(),
                    borderStyle = $('select[name="border-style"]').val(),
                    borderColor = $('input[name="border-color"]').val(),
                    radiusTopLeft = $('input[name="radius-tleft"]').val(),
                    radiusTopRight = $('input[name="radius-tright"]').val(),
                    radiusBottomLeft = $('input[name="radius-bleft"]').val(),
                    radiusBottomRight = $('input[name="radius-bright"]').val(),
                    paddingTop = $('input[name="padding-top"]').val(),
                    paddingRight = $('input[name="padding-right"]').val(),
                    paddingBottom = $('input[name="padding-bottom"]').val(),
                    paddingLeft = $('input[name="padding-left"]').val(),
                    alignment = $('select[name="alignment"]').val(),
                    fontSize = $('input[name="font-size"]').val(),
                    lineHeight = $('input[name="line-height"]').val(),
                    letterSpacing = $('input[name="letter-spacing"]').val(),
                    fontBold = $('input[name="font-bold"]:checked').val(),
                    fontItalic = $('input[name="font-italic"]:checked').val();

                var css_style = '';

                // width
                if( fullwidth ) {
                    css_style += ' width: 100%;';
                } else if( width ) {
                    css_style += ' width: ' + cssPixel(width) + ';';
                } else {
                    css_style += ' width: auto;';
                }
                // end width

                // background color
                if( state == 'hover' ) {
                    bgColor = bgHoverColor;
                    secondaryBgColor = secondaryBgHoverColor;
                }

                if( style == 'gradient' ) {
                    if( bgColor && secondaryBgColor ) {
                        css_style += ' background: ' + bgColor + ';';
                        css_style += ' background: -moz-linear-gradient(top,' + secondaryBgColor + ' 0%, ' + bgColor + ' 100%);';
                        css_style += ' background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' + secondaryBgColor + '), color-stop(100%,' + bgColor + '));';
                        css_style += ' background: -webkit-linear-gradient(top,' + secondaryBgColor + ' 0%,' + bgColor + ' 100%);';
                        css_style += ' background: -o-linear-gradient(top,' + secondaryBgColor + ' 0%,' + bgColor + ' 100%);';
                        css_style += ' background: -ms-linear-gradient(top,' + secondaryBgColor + ' 0%,' + bgColor + ' 100%);';
                        css_style += ' background: linear-gradient(to bottom,' + secondaryBgColor + ' 0%,' + bgColor + ' 100%);';
                        css_style += ' filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="' + secondaryBgColor + '", endColorstr="' + bgColor + '",GradientType=0 );';
                    }
                } else if( style == 'transparent' && state != 'hover' ) {
                    css_style += ' background-color: transparent;';
                } else if( bgColor ) {
                    css_style += ' background-color: ' + bgColor + ';';
                }
                // end background color

                // text color
                if( state == 'hover' ) {
                    textColor = textHoverColor;
                }

                if( textColor ) {
                    css_style += ' color: ' + textColor + ';';
                }
                // end text color

                // border
                if( borderTop ) {
                    css_style += ' border-top-width: ' + cssPixel( borderTop ) + ';';
                }

                if( borderRight ) {
                    css_style += ' border-right-width: ' + cssPixel( borderRight ) + ';';
                }

                if( borderBottom ) {
                    css_style += ' border-bottom-width: ' + cssPixel( borderBottom ) + ';';
                }

                if( borderLeft ) {
                    css_style += ' border-left-width: ' + cssPixel( borderLeft ) + ';';
                }

                if( borderStyle ) {
                    css_style += ' border-style: ' + borderStyle + ';';
                }

                if( borderColor ) {
                    css_style += ' border-color: ' + borderColor + ';';
                }
                // end border

                // radius
                if( radiusTopLeft ) {
                    css_style += ' border-top-left-radius: ' + cssPixel( radiusTopLeft ) + ';';
                }

                if( radiusTopRight ) {
                    css_style += ' border-top-right-radius: ' + cssPixel( radiusTopRight ) + ';';
                }

                if( radiusBottomLeft ) {
                    css_style += ' border-bottom-left-radius: ' + cssPixel( radiusBottomLeft ) + ';';
                }

                if( radiusBottomRight ) {
                    css_style += ' border-bottom-right-radius: ' + cssPixel( radiusBottomRight ) + ';';
                }
                // end radius

                // padding
                if( paddingTop ) {
                    css_style += ' padding-top: ' + cssPixel( paddingTop ) + ';';
                }

                if( paddingRight ) {
                    css_style += ' padding-right: ' + cssPixel( paddingRight ) + ';';
                }

                if( paddingBottom ) {
                    css_style += ' padding-bottom: ' + cssPixel( paddingBottom ) + ';';
                }

                if( paddingLeft ) {
                    css_style += ' padding-left: ' + cssPixel( paddingLeft ) + ';';
                }
                // end padding

                // font
                if( fontSize ) {
                    css_style += ' font-size: ' + cssPixel( fontSize ) + ';';
                }

                if( lineHeight ) {
                    css_style += ' line-height: ' + cssPixel( lineHeight ) + ';';
                }

                if( letterSpacing ) {
                    css_style += ' letter-spacing: ' + cssPixel( letterSpacing ) + ';';
                }

                if( fontBold )  {
                    css_style += ' font-weight: 700;';
                }

                if( fontItalic )  {
                    css_style += ' font-style: italic;';
                }
                // end font

                if( css_style ) {
                    button.removeAttr('style').attr('style', css_style);
                }
            }

            function buttonToggleStyle( style ) {
                button.removeClass('reviewengine-btn-flat');
                button.removeClass('reviewengine-btn-gradient');
                button.removeClass('reviewengine-btn-transparent');
                button.addClass('reviewengine-btn-' + style);

                if( style == 'transparent' ) {
                    $('.form-group.form-group-bg-color').hide();
                    $('input[name="border-top"], input[name="border-right"], input[name="border-bottom"], input[name="border-left"]').attr('placeholder', 2);
                } else {
                    $('.form-group.form-group-bg-color').show();
                }

                if( style == 'gradient' ) {
                    $('.form-group.form-group-secondary-bg-color').show();
                    $('.form-group.form-group-secondary-bg-hover-color').show();
                } else {
                    $('.form-group.form-group-secondary-bg-color').hide();
                    $('.form-group.form-group-secondary-bg-hover-color').hide();
                }
            }

            function buttonToggleSize( size ) {
                button.removeClass('reviewengine-btn-s');
                button.removeClass('reviewengine-btn-m');
                button.removeClass('reviewengine-btn-l');
                button.removeClass('reviewengine-btn-xl');
                button.removeClass('reviewengine-btn-xxl');
                button.addClass('reviewengine-btn-' + size);

                var fontsize = '',
                    lineheight = '',
                    padingtop = '',
                    padingright = '';

                switch( size ) {
                    case 's':
                        fontsize = '18';
                        lineheight = '21';
                        padingtop = '12';
                        padingright = '15';
                        break;

                    case 'm':
                        fontsize = '24';
                        lineheight = '28';
                        padingtop = '14';
                        padingright = '22';
                        break;

                    case 'l':
                        fontsize = '32';
                        lineheight = '38';
                        padingtop = '18';
                        padingright = '30';
                        break;

                    case 'xl':
                        fontsize = '38';
                        lineheight = '41';
                        padingtop = '22';
                        padingright = '40';
                        break;

                    case 'xxl':
                        fontsize = '52';
                        lineheight = '57';
                        padingtop = '32';
                        padingright = '50';
                        break;
                }

                if( fontsize ) {
                    $('input[name="font-size"]').attr('placeholder', fontsize);
                }

                if( lineheight ) {
                    $('input[name="line-height"]').attr('placeholder', lineheight);
                }

                if( padingtop ) {
                    $('input[name="padding-top"], input[name="padding-bottom"]').attr('placeholder', padingtop);
                }

                if( padingright ) {
                    $('input[name="padding-right"], input[name="padding-left"]').attr('placeholder', padingright);
                }
            }

            function buttonToggleIcon(icon) {
                if( !icon ) {
                    icon = $('input[name="btn-icon"]').val();
                }

                button.find('.fa').remove();
                buttonWrap.removeClass('reviewengine-btn-has-icon');
                buttonWrap.removeClass('reviewengine-btn-icon-before');
                buttonWrap.removeClass('reviewengine-btn-icon-after');

                if( icon ) {
                    var position = $('select[name="icon-position"]').val();
                    var html = '<i class="fa ' +icon+ '"></i>';

                    if( position == 'before' || position == 'after' ) {
                        buttonWrap.addClass('reviewengine-btn-has-icon');
                    }

                    if( position == 'before' ) {
                        buttonWrap.addClass('reviewengine-btn-icon-before');
                        $(html).prependTo(button);
                    } else if( position == 'after' ) {
                        buttonWrap.addClass('reviewengine-btn-icon-after');
                        $(html).appendTo(button);
                    }
                }
            }

            $('.reviewengine-colorpicker').wpColorPicker({
                palettes: true,
                change: function(event, ui) {
                    $(event.target).val( ui.color.toString() );
                    renderButtonStyle();
                }
            });

            $('input#btn-icon').iconpicker({
                placement: 'inline',
            });

            $('input#btn-icon').on('iconpickerSelected', function(event){
                buttonToggleIcon( event.iconpickerValue );
            });

            $('input#btn-text').keydown( function() {
                var text = $(this).val();
                button.find('span.reviewengine-btn-text').html( text );
            }).keyup( function() {
                var text = $(this).val();
                button.find('span.reviewengine-btn-text').html( text );
            });

            $('#general input, #general select').on( 'change', function(e) {
                buttonToggleIcon();
            });

            $('#style input, #style select').on( 'change', function(e) {
                var style = $('select[name="btn-style"]').val(),
                    size = $('input[name="size"]:checked').val(),
                    alignment = $('select[name="alignment"]').val();

                if( style ) {
                    buttonToggleStyle( style );
                }

                if( size ) {
                    buttonToggleSize( size );
                }
                
                renderButtonStyle();

                if( alignment ) {
                    buttonWrap.css('text-align', alignment);
                }
            });

            button.hover( function(e) {
                renderButtonStyle('hover');
            }, function() {
                renderButtonStyle();
            });
        }

        preview_init();

        $('#reviewengine-modal').on('click', 'button.button-display-button', function (e) {
            var asin = $(this).parents('tr').attr('data-asin');
            var post_id = $(this).parents('tr').attr('data-post_id');

            if( asin ) {
                global_product_data = ReviewEngine_Modal.get_product_from_asin( asin );
            } else if( post_id ) {
                global_product_data = ReviewEngine_Modal.get_product_from_post_id( post_id );
            }

            if( post_id && global_product_data ) {
                global_product_locale = global_product_data.locale;
            } else {
                global_product_locale = ReviewEngine_Modal.get_locale_from_storage();
            }

            if( global_product_data ) {
                $('#network, #search').hide();
                container.show();
                container.find('a.name').attr('href', global_product_data.url).html( global_product_data.title );

                if( global_product_locale ) {
                    var options = '<option value="">None</option>';
                    var my_tracking_id = '';

                    $.each(reviewengine.tracking_ids, function (x, tracking_id) {
                        if( x == global_product_locale ) {
                            my_tracking_id = tracking_id;
                            options += '<option value="' + tracking_id + '" selected>' + tracking_id + '</option>';
                        }
                    });
                    container.find('select[name="tracking-id"]').empty().html(options);

                    if( my_tracking_id ) {
                        var tracking_id = container.find('select[name="tracking-id"] option:selected').val();
                        container.find('a.reviewengine-btn').attr( 'href', global_product_data.url + '?tag=' + tracking_id );
                    }
                }

                if( reviewengine.selection ) {
                    form.find('input#btn-text').val( reviewengine.selection );
                }
            }

            e.preventDefault();
        });

        form.on('submit', function (e) {
            var button_text = form.find('input#btn-text').val(),
                button_icon = form.find('input[name="btn-icon"]').val(),
                button_icon_position = form.find('select[name="icon-position"]').val(),
                button_type = form.find('select[name="btn-style"]').val(),
                button_size = form.find('input[name="size"]:checked').val();
                
            if( !button_text ) { button_text = reviewengine.selection; }
            if( !button_type ) { button_type = 'flat'; }
            if( !button_size ) { button_size = 's'; }
            if( !button_icon ) { button_icon = ''; }
            if( !button_icon_position ) { button_icon_position = ''; }

            if( ajax_flag ) {
                ajax_flag.abort();
            }

            ajax_flag = $.ajax({
                type: "POST",
                url: reviewengine.ajaxurl,
                data: {
                    action: 'ree_insert_button',
                    _locale: global_product_locale,
                    _product_data: global_product_data,
                    _post_id: reviewengine.post_id,
                    _form: form.serialize()
                },
                beforeSend: function() {
                    ReviewEngine_Modal.loading('on');
                },
                success: function( response ) {
                    ReviewEngine_Modal.loading('off');

                    if( response.success ) {
                        if( response.data.url && response.data.button_id ) {
                            var shortcode = '[reviewengine_button id="' + response.data.button_id + '" type="' + button_type + '" size="' + button_size + '" icon="' +button_icon+ '" icon_position="' + button_icon_position + '" url="' + response.data.url + '"]' + button_text + '[/reviewengine_button]';
                            ReviewEngine_Modal.insert_shortcode( shortcode );
                        }
                    }
                }
            });

            e.preventDefault();
        });

        container.on('click', 'button.button-display-cancel', function (e) {
            container.hide();
            $('#network, #search').show();

            e.preventDefault();
        });
    });
})(jQuery);