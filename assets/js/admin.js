jQuery(document).ready(function($){"use strict";var file_frame;jQuery('#muv-kk-email-vorlage-logo-waehlen-button').on('click',function(event){event.preventDefault();if(file_frame){file_frame.open();return}
file_frame=wp.media.frames.file_frame=wp.media({title:'Bild für Kopfzeile',button:{text:'Dieses Bild in Kopfzeile verwenden'},multiple:!1});file_frame.on('select',function(){var attachment=file_frame.state().get('selection').first().toJSON();$('#muv-kk-email-vorlage-logo').val(attachment.url);$('#muv-kk-email-vorlage-logo-preview').attr('src',attachment.url).show()});file_frame.open()});$('#muv-kk-email-vorlage-logo-loeschen-button').click(function(){$('#muv-kk-email-vorlage-logo').val('');$('#muv-kk-email-vorlage-logo-preview').attr('src','').hide();return!1})})