var Attachment = {
	counter: 1, 
	
	add:function(label) {
		Attachment.counter++;
		$('#attach-fields').append(
			'<li id="attachment-' + Attachment.counter + '">' + 
			'<label>' + label + ' <span class="quiet">[50MB limit]</span>: </label>' + 
			'<input type="file" tabindex="9" size="30" name="attachment[]" id="message_attachment_' + Attachment.counter + '"/>' +   
			'<a href="#" onclick="return Attachment.add(\'' + label + '\');" class="add">(+)</a>' + 
			'<a class="rem" onclick="return Attachment.remove(' + Attachment.counter + ');" href="#" >(-)</a> ' + 
			'</li>'); 	
		
		return false;
	},

	remove: function(line) {
		$('#attachment-' + line).remove(); 
		return false;
	}, 
	
	delsetup: function() {
		var options = { 
		        target:        '#dummy',   
		        beforeSubmit:  Attachment.refreshlist 
		    }; 
		 
		    $('#delattform').submit(function() { 
		        $(this).ajaxSubmit(options);
		        $.modal.close();
		        return false; 
		    }); 
	},
	
	refreshlist:function(formData, jqForm, options) { 
		var t = $('#attachid').val();
		$('#att_' + t).remove();
		var t = $('#attachid').val();
		
		var box = '#attbox_' + $('#msgid').val() + ' > ul > li'; 
		if ($(box).size() == 0) {
			$('#attbox_' + $('#msgid').val()).remove();
		}
		return true; 
	} ,
	
	delatt: function(e) {
		e.preventDefault();

		var ids = e.target.rel.split(' ');
		$('#attachid').val(ids[0]);
		if (ids[2] == 'm') 
			$('#msgid').val(ids[1]);
		else if (ids[2] == 't') { 
			$('#ticketid').val(ids[1]);
			$('#tickethid').val('');
		}
		else if (ids[2] == 'h') { 
			$('#tickethid').val(ids[1]);
			$('#ticketid').val('');
		}
		
		$('#delattdlg').modal({
			overlayId: 'osx-overlay',
			containerId: 'osx-container',
			closeHTML: '<div class="close"><a href="#" class="simplemodal-close">x</a></div>',
			minHeight:100,
			opacity:65, 
			overlayClose:true,
			onClose:OSX.close

			});

	}
};

var OSX = {
		container: null,
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('slow', function () {
				$("#deletedlg", self.container).show();
				var title = $("#osx-modal-title", self.container);
				title.show();
				d.container.slideDown('slow', function () {
					setTimeout(function () {
						var h = $("#osx-modal-data", self.container).height()
							+ title.height()
							+ 20; // padding
						d.container.animate(
							{height: h}, 
							200,
							function () {
								$("div.close", self.container).show();
								$("#osx-modal-data", self.container).show();
							}
						);
					}, 300);
				});
			})
		},
		close: function (d) {
			var self = this;
			d.container.animate(
				{top:"-" + (d.container.height() + 20)},
				500,
				function () {
					self.close(); // or $.modal.close();
				}
			);
		}
};

var floatbox = {
	dsettings: {
		targetid: '',
		anchorele: '',
		orientation: 2,
		position: [10, 30],
		hideafter: 0,
		fadeduration: [500, 500]
  },

	positiontarget:function($target, settings){
		var fixedsupport=!document.all || document.all && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
		var posoptions={position:fixedsupport? 'fixed':'absolute', visibility:'visible'}
		if (settings.fadeduration[0]>0) {
			posoptions.opacity=0;
		}

		if (settings.anchorele != '') {
			var p = $('#' + settings.anchorele).position();
			posoptions['left']= p.left + ($('#' + settings.anchorele).width());
			posoptions['top']= p.top;
		}
		else {
			posoptions[(/^[13]$/.test(settings.orientation))? 'left' : 'right']=settings.position[0];
			posoptions[(/^[12]$/.test(settings.orientation))? 'top' : 'bottom']=settings.position[1];
		}

		if (document.all && !window.XMLHttpRequest) //loose check for IE6 and below
			posoptions.width=$target.width() //IE6- seems to require an explicit width on a DIV
		$target.css(posoptions)
		/*if (!fixedsupport){
			this.keepfixed($target, settings)
			var evtstr='scroll.' + settings.targetid + ' resize.'+settings.targetid
			jQuery(window).bind(evtstr, function(){floatbox.keepfixed($target, settings)})
		}*/
		this._show($target, settings, fixedsupport)
		if (settings.hideafter>0){ //if hide timer enabled
			setTimeout(function(){
				floatbox.hide(settings.targetid)
			}, settings.hideafter+settings.fadeduration[0])
		}
	},

	keepfixed:function($target, settings){
		var $window=jQuery(window)
		var is1or3=/^[13]$/.test(settings.orientation)
		var is1or2=/^[12]$/.test(settings.orientation)
		var x=$window.scrollLeft() + (is1or3? settings.position[0] : $window.width()-$target.outerWidth()-settings.position[0])
		var y=$window.scrollTop() + (is1or2? settings.position[1] : $window.height()-$target.outerHeight()-settings.position[1])
		$target.css({left:x+'px', top:y+'px'})
	},

	_show:function($target, settings){
		if (settings.fadeduration[0]>0) 
			$target.show().animate({opacity:1}, settings.fadeduration[0]);
		else
			$target.show();
	},

	show: function(targetid) {
		var $target=jQuery('#'+targetid);
		$target.show();
	},
	hide:function(targetid){ 
		var $target=jQuery('#'+targetid);
		if ($target.css('display')=='none') 
			return; 
		var settings=this.settings; 
		if (settings.fadeduration[1]>0) 
			$target.animate({opacity:0}, settings.fadeduration[1], function(){$target.hide()});
		else
			$target.hide();
		var evtstr='scroll.' + settings.targetid + ' resize.'+settings.targetid;
		jQuery(window).unbind(evtstr);
	},
	

	init:function(options){
		var settings=jQuery.extend(settings, this.dsettings, options); 
		this.settings=settings;
		var $target=$('#'+settings.targetid); 
		floatbox.positiontarget($target, settings); 
	}
}