window.addEvent('domready', function(){
	var body = $$('body')[0];
	body.addEvent('mouseenter:relay(a)', function(event){
		var el = event.target;
		if(el.get('tag') != 'a'){
			el = el.getParent('a');
		}
		//We only want to change external links
		if(el.href && (window.location.host != el.hostname) &&(el.href.toLowerCase().substr(0, 7) != 'mailto:') && (el.href.toLowerCase().substr(0, 11) != 'javascript:')){
			if(!el.get('origHref')){
				el.set('origHref', el.href);
			}
			(function(){
				el.href = 'index.php?option=com_linktracker&task=track&link='+encodeURIComponent(el.href);
			}).delay(100);
		}
	});
	body.addEvent('mousemove:relay(a)', function(event){
		var el = event.target;
		if(el.get('tag') != 'a'){
			el = el.getParent('a');
		}
		if(el.get('origHref')){
			el.href = el.get('origHref');
			(function(){
				el.href = 'index.php?option=com_linktracker&task=track&link='+encodeURIComponent(el.get('origHref'));
			}).delay(100);
		}
	});
	body.addEvent('mouseleave:relay(a)', function(event){
		var el = event.target;
		if(el.get('tag') != 'a'){
			el = el.getParent('a');
		}
		if(el.get('origHref')){
			(function(){
				el.href = el.get('origHref');
			}).delay(100);
		}
	});
});