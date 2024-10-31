/*
* jQuery plugin: fieldSelection - v0.1.1 - last change: 2006-12-16
* (c) 2006 Alex Brem <alex@0xab.cd> - http://blog.0xab.cd
*/
(function(){var fieldSelection={getSelection:function(){var e=(this.jquery)?this[0]:this;return(('selectionStart'in e&&function(){var l=e.selectionEnd-e.selectionStart;return{start:e.selectionStart,end:e.selectionEnd,length:l,text:e.value.substr(e.selectionStart,l)}})||(document.selection&&function(){e.focus();var r=document.selection.createRange();if(r===null){return{start:0,end:e.value.length,length:0}}var re=e.createTextRange();var rc=re.duplicate();re.moveToBookmark(r.getBookmark());rc.setEndPoint('EndToStart',re);return{start:rc.text.length,end:rc.text.length+r.text.length,length:r.text.length,text:r.text}})||function(){return null})()},replaceSelection:function(){var e=(typeof this.id=='function')?this.get(0):this;var text=arguments[0]||'';return(('selectionStart'in e&&function(){e.value=e.value.substr(0,e.selectionStart)+text+e.value.substr(e.selectionEnd,e.value.length);return this})||(document.selection&&function(){e.focus();document.selection.createRange().text=text;return this})||function(){e.value+=text;return jQuery(e)})()}};jQuery.each(fieldSelection,function(i){jQuery.fn[i]=this})})();

var wpActiveEditor;

(function($) {

var has_modal_create = false;
var has_modal_insert = false;
var old_tb_remove = false;

window.ReviewEngine_Editor = {
    close_thickbox: function() {
        tb_remove();
    },
    resize_thickbox: function() {
        var marginLeft = 1000 / -2,
            marginTop  = 600 / -2;

        $( '#TB_window' ).css({ width: '1000px' , height: '600px' , marginLeft : marginLeft + 'px' , marginTop : marginTop + 'px', top: '50%' });
        $( '#TB_window iframe' ).css({ width: '100%' , height: '570px' });
    },
    open_thickbox_insert: function() {
        console.log('debug');
    },
    open_thickbox_create: function() {
        var post_id = ReviewEngineHelpers.post_id;
        var selection = ReviewEngine_Editor.getSelection();

        $( '#TB_window' ).remove();
        $( 'body' ).append( '<div id="TB_window"></div>' );

        tb_show( 'Review Engine - Quick Create Affiliate Link', ReviewEngineHelpers.ajaxurl + '?action=ree_modal_create&from=editor&post_id=' + post_id + '&selection=' + selection + '&TB_iframe=false' );
        ReviewEngine_Editor.resize_thickbox();
    },
    getSelection: function() {
        var editor = wp.media.editor.id(),
            range = null,
            selection = '';

        if( ('undefined' !== typeof tinyMCE ) && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden() ) {
            selection = tinyMCE.activeEditor.selection.getContent();
        } else if( editor ) {
            range = jQuery('#' + editor).getSelection();
            selection = range && range.text ? range.text : '';
        }

        return jQuery.trim(selection);
    },
    send_to_editor: function(shortcode) {
        if( !old_tb_remove ) {
            old_tb_remove = window.tb_remove;
        }

        window.tb_remove = function() {
            $('body').removeClass('modal-open');
            $('#TB_overlay').hide().attr('id', 'REE_TB_overlay')
            $('#TB_window').hide().attr('id', 'REE_TB_window');
            $('#TB_closeWindowButton').attr('id', 'REE_TB_closeWindowButton');
        }

        window.send_to_editor( shortcode );

        if( old_tb_remove ) {
            window.tb_remove = old_tb_remove;
        }
    },
}

$(document).ready( function() { 
	$(document).on('click', '.reviewengine-insert-button, .reviewengine-create-button', function(e) {
        var post_id = ReviewEngineHelpers.post_id;
        var selection = ReviewEngine_Editor.getSelection();

        if( !old_tb_remove ) {
            old_tb_remove = tb_remove;
        }

        tb_remove = function() {
            $('body').removeClass('modal-open');
            $('#TB_overlay').hide().attr('id', 'REE_TB_overlay')
            $('#TB_window').hide().attr('id', 'REE_TB_window');
            $('#TB_closeWindowButton').attr('id', 'REE_TB_closeWindowButton');
        }

        if( $(this).hasClass('reviewengine-insert-button') ) {

            if( has_modal_insert ) {
                $('body').addClass('modal-open');
                $('#REE_TB_overlay').attr('id', 'TB_overlay').show();
                $('#REE_TB_window').attr('id', 'TB_window').show();
                $('#REE_TB_closeWindowButton').attr('id', 'TB_closeWindowButton');

            } else {
                if( has_modal_create ) {
                    has_modal_create = false;
                    $('body').removeClass('modal-open');
                    $('#TB_overlay, #TB_window').remove();
                    $('#REE_TB_overlay, #REE_TB_window').remove();
                }

                tb_show( 'Review Engine - Insert Affiliate Link', ReviewEngineHelpers.ajaxurl + '?action=ree_modal_insert&post_id=' + post_id + '&selection=' + selection + '&TB_iframe=false' );
                has_modal_insert = true;
            }

        } else if( $(this).hasClass('reviewengine-create-button') ) {

            if( has_modal_create ) {
                $('body').addClass('modal-open');
                $('#REE_TB_overlay').attr('id', 'TB_overlay').show();
                $('#REE_TB_window').attr('id', 'TB_window').show();
                $('#REE_TB_closeWindowButton').attr('id', 'TB_closeWindowButton');

            } else {
                if( has_modal_insert ) {
                    has_modal_insert = false;
                    $('body').removeClass('modal-open');
                    $('#TB_overlay, #TB_window').remove();
                    $('#REE_TB_overlay, #REE_TB_window').remove();
                }

                tb_show( 'Review Engine - Quick Create Affiliate Link', ReviewEngineHelpers.ajaxurl + '?action=ree_modal_create&post_id=' + post_id + '&selection=' + selection + '&TB_iframe=false' );
                has_modal_create = true;
            }

        }

        ReviewEngine_Editor.resize_thickbox();

        if( old_tb_remove ) {
            tb_remove = old_tb_remove;
        }
    });
});
})(jQuery);