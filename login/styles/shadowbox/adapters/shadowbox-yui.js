if(typeof YAHOO=="undefined"){throw"Unable to load Shadowbox adapter, YAHOO not found"}if(typeof Shadowbox=="undefined"){throw"Unable to load Shadowbox adapter, Shadowbox not found"}(function(a){var b=YAHOO.util.Event,c=YAHOO.util.Dom;a.lib={getStyle:function(e,d){return c.getStyle(e,d)},remove:function(d){d.parentNode.removeChild(d)},getTarget:function(d){return b.getTarget(d)},getPageXY:function(d){return[b.getPageX(d),b.getPageY(d)]},preventDefault:function(d){b.preventDefault(d)},keyCode:function(d){return d.keyCode},addEvent:function(f,d,e){b.addListener(f,d,e)},removeEvent:function(f,d,e){b.removeListener(f,d,e)},append:function(f,e){if(f.insertAdjacentHTML){f.insertAdjacentHTML("BeforeEnd",e)}else{if(f.lastChild){var d=f.ownerDocument.createRange();d.setStartAfter(f.lastChild);var g=d.createContextualFragment(e);f.appendChild(g)}else{f.innerHTML=e}}}}})(Shadowbox);