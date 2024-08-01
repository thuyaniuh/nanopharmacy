//** jQuery Scroll to Top Control script- (c) Dynamic Drive DHTML code library: http://www.dynamicdrive.com.
//** Available/ usage terms at http://www.dynamicdrive.com (March 30th, 09')
//** v1.1 (April 7th, 09'):
//** 1) Adds ability to scroll to an absolute position (from top of page) or specific element on the page instead.
//** 2) Fixes scroll animation not working in Opera. 


var scrolltotop={
	//startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
	//scrollto: Keyword (Integer, or "Scroll_to_Element_ID"). How far to scroll document up when control is clicked on (0=top).
	setting: {startline:100, scrollto: 0, scrollduration:1000, fadeduration:[500, 100]},
	controlHTML: '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA1ZJREFUeNrMmb9rU1EUx0+u3QQDdTDQjHWLlCbp8AYXB8nwcHOQDIWINNBJBBd/VdBFkC6izSJ0KBlcHAzkP0j90UAlmx0UUkiXB7Xu8dzwvfXk8ZK8l3fz4oEvTZN3cz/vvHvv+ZFUpXKXprA0y2EVWDnWMivLWsTnHqvLOmJ1WAesFus06kQLEa/XUC6rxMqPuS4DFcV7bVaT9QmwVgG1p8qsO5iY4I0WvNOBt7r4LAuv5jDWwQ1pVVh11h7GxgJMsaqsDdYK3mvBC014Jch6rG/i/zy87gL2PusGq8baYfWnAVxiPcCXEe52D3ffi7g02tB7PIUyPPuWdZX1mnUcNPDC6mrgUtKP5zm818ddPmV9ZP2h6U2P3Wd9xvcW4dHLWCZeGMAlwK3jrrZYT1gnZM/0dzVYv7FOr+Nk+Mo6G/eIU3isBu4Ra5dmZ9vw2kvM6WH+8zWpfAOqWHN9rItZwhnbxVx9zF2VHyrfUbKB1zu4u6RsG3MSGApBgGUcJQfY/klbDXOvgGUI0MH2Jxwlh3MAPMTcBBZHArqIEC2cc/OyOhgyYBoApnHKEyJEL8YE32MC9sBAYEorESdPEb6mtTesa/gbx5pg0UyOEjumNSa2hoHbxOvNmJBtke0UFE5yCpNZhIDbsgRpWHIKcZcQC+PAuQiRrgVIw7KskLsR8rk4cA28bliANCxZJdL0rgU4sgRpWBaVhcfaGHGdDU8OzkFPpOm24OJCGhZPCXcuhxgYBW4UZBgzLN0FLMiiOG4m1SjTWCPiWMNypMSWLtD/Y4alo8Sh6EyodZOyvMlkNJsSIU4mDfO0ElgGIU/5kgRXFObzsIzYUIOkQfnSLJm4zsNMonqedimRydR9qX/SJlN9k7gO1SQm1ZfFU5JmiiWZ+g8BymKpKloeSZgsN2sy9fPHYlNuygJ+1raOuVK+8nNk66ODXoluR6yhoN6foeceo92iC/gXaIeMBTwDZBqQN1lXELNPLG6IZ2itXBJwv8J2tzw0clLY9mvQRdbPGB0ufc7dg9duiccaCDepP3iMtfFD7DCt2zS5gRkUvmQD0xTqsRqYhIHvWF/oXwvYgR5StBZwWtS+1lrA8gjS+kDDTfRShPg90yY6iYij9YoS+hnirwADAGY77yaWzqSxAAAAAElFTkSuQmCC" style="width:40px; height:40px" />', //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
	controlattrs: {offsetx:10, offsety:10}, //offset of control relative to right/ bottom of window corner
	anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) //if control is positioned using JavaScript
			this.$control.css({opacity:0}) //hide control immediately after clicking it
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto)
		if (typeof dest=="string" && jQuery('#'+dest).length==1) //check element set by string exists
			dest=jQuery('#'+dest).offset().top
		else
			dest=0
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		var $window=jQuery(window)
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety
		this.$control.css({left:controlx+'px', top:controly+'px'})
	},

	togglecontrol:function(){
		var scrolltop=jQuery(window).scrollTop()
		if (!this.cssfixedsupport)
			this.keepfixed()
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0])
			this.state.isvisible=true
		}
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1])
			this.state.isvisible=false
		}
	},
	
	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop
			var iebrws=document.all
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body')
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.attr({title:'Scroll Back to Top'})
				.click(function(){mainobj.scrollup(); return false})
				.appendTo('body')
			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') //loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}) //IE6- seems to require an explicit width on a DIV containing text
			mainobj.togglecontrol()
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup()
				return false
			})
			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol()
			})
		})
	}
}

scrolltotop.init()