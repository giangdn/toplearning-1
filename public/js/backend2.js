"use strict"; var tid = setInterval(function () { if ("complete" === document.readyState) { clearInterval(tid); var a = document.querySelector.bind(document), b = document.querySelector(".vertical_nav"), c = document.querySelector(".wrapper"), d = document.getElementById("js-menu"), e = d.querySelectorAll(".menu--item__has_sub_menu"); a(".toggle_menu").onclick = function () { b.classList.toggle("vertical_nav__opened"), c.classList.toggle("toggle-content") }, a(".collapse_menu").onclick = function () { b.classList.toggle("vertical_nav__minify"), c.classList.toggle("wrapper__minify"); for (var a = 0; a < e.length; a++)e[a].classList.remove("menu--subitens__opened") }; for (var f = 0; f < e.length; f++)e[f].classList.contains("menu--item__has_sub_menu") && e[f].querySelector(".menu--link").addEventListener("click", function (a) { for (var b = 0; b < e.length; b++)a.target.offsetParent != e[b] && e[b].classList.remove("menu--subitens__opened"); a.target.offsetParent.classList.toggle("menu--subitens__opened") }, !1) } }, 100);
/*!
  * Bootstrap v4.3.1 (https://getbootstrap.com/)
  * Copyright 2011-2019 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
  */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports,require("jquery")):"function"==typeof define&&define.amd?define(["exports","jquery"],e):e((t=t||self).bootstrap={},t.jQuery)}(this,function(t,p){"use strict";function i(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function s(t,e,n){return e&&i(t.prototype,e),n&&i(t,n),t}function l(o){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{},e=Object.keys(r);"function"==typeof Object.getOwnPropertySymbols&&(e=e.concat(Object.getOwnPropertySymbols(r).filter(function(t){return Object.getOwnPropertyDescriptor(r,t).enumerable}))),e.forEach(function(t){var e,n,i;e=o,i=r[n=t],n in e?Object.defineProperty(e,n,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[n]=i})}return o}p=p&&p.hasOwnProperty("default")?p.default:p;var e="transitionend";function n(t){var e=this,n=!1;return p(this).one(m.TRANSITION_END,function(){n=!0}),setTimeout(function(){n||m.triggerTransitionEnd(e)},t),this}var m={TRANSITION_END:"bsTransitionEnd",getUID:function(t){for(;t+=~~(1e6*Math.random()),document.getElementById(t););return t},getSelectorFromElement:function(t){var e=t.getAttribute("data-target");if(!e||"#"===e){var n=t.getAttribute("href");e=n&&"#"!==n?n.trim():""}try{return document.querySelector(e)?e:null}catch(t){return null}},getTransitionDurationFromElement:function(t){if(!t)return 0;var e=p(t).css("transition-duration"),n=p(t).css("transition-delay"),i=parseFloat(e),o=parseFloat(n);return i||o?(e=e.split(",")[0],n=n.split(",")[0],1e3*(parseFloat(e)+parseFloat(n))):0},reflow:function(t){return t.offsetHeight},triggerTransitionEnd:function(t){p(t).trigger(e)},supportsTransitionEnd:function(){return Boolean(e)},isElement:function(t){return(t[0]||t).nodeType},typeCheckConfig:function(t,e,n){for(var i in n)if(Object.prototype.hasOwnProperty.call(n,i)){var o=n[i],r=e[i],s=r&&m.isElement(r)?"element":(a=r,{}.toString.call(a).match(/\s([a-z]+)/i)[1].toLowerCase());if(!new RegExp(o).test(s))throw new Error(t.toUpperCase()+': Option "'+i+'" provided type "'+s+'" but expected type "'+o+'".')}var a},findShadowRoot:function(t){if(!document.documentElement.attachShadow)return null;if("function"!=typeof t.getRootNode)return t instanceof ShadowRoot?t:t.parentNode?m.findShadowRoot(t.parentNode):null;var e=t.getRootNode();return e instanceof ShadowRoot?e:null}};p.fn.emulateTransitionEnd=n,p.event.special[m.TRANSITION_END]={bindType:e,delegateType:e,handle:function(t){if(p(t.target).is(this))return t.handleObj.handler.apply(this,arguments)}};var o="alert",r="bs.alert",a="."+r,c=p.fn[o],h={CLOSE:"close"+a,CLOSED:"closed"+a,CLICK_DATA_API:"click"+a+".data-api"},u="alert",f="fade",d="show",g=function(){function i(t){this._element=t}var t=i.prototype;return t.close=function(t){var e=this._element;t&&(e=this._getRootElement(t)),this._triggerCloseEvent(e).isDefaultPrevented()||this._removeElement(e)},t.dispose=function(){p.removeData(this._element,r),this._element=null},t._getRootElement=function(t){var e=m.getSelectorFromElement(t),n=!1;return e&&(n=document.querySelector(e)),n||(n=p(t).closest("."+u)[0]),n},t._triggerCloseEvent=function(t){var e=p.Event(h.CLOSE);return p(t).trigger(e),e},t._removeElement=function(e){var n=this;if(p(e).removeClass(d),p(e).hasClass(f)){var t=m.getTransitionDurationFromElement(e);p(e).one(m.TRANSITION_END,function(t){return n._destroyElement(e,t)}).emulateTransitionEnd(t)}else this._destroyElement(e)},t._destroyElement=function(t){p(t).detach().trigger(h.CLOSED).remove()},i._jQueryInterface=function(n){return this.each(function(){var t=p(this),e=t.data(r);e||(e=new i(this),t.data(r,e)),"close"===n&&e[n](this)})},i._handleDismiss=function(e){return function(t){t&&t.preventDefault(),e.close(this)}},s(i,null,[{key:"VERSION",get:function(){return"4.3.1"}}]),i}();p(document).on(h.CLICK_DATA_API,'[data-dismiss="alert"]',g._handleDismiss(new g)),p.fn[o]=g._jQueryInterface,p.fn[o].Constructor=g,p.fn[o].noConflict=function(){return p.fn[o]=c,g._jQueryInterface};var _="button",v="bs.button",y="."+v,E=".data-api",b=p.fn[_],w="active",C="btn",T="focus",S='[data-toggle^="button"]',D='[data-toggle="buttons"]',I='input:not([type="hidden"])',A=".active",O=".btn",N={CLICK_DATA_API:"click"+y+E,FOCUS_BLUR_DATA_API:"focus"+y+E+" blur"+y+E},k=function(){function n(t){this._element=t}var t=n.prototype;return t.toggle=function(){var t=!0,e=!0,n=p(this._element).closest(D)[0];if(n){var i=this._element.querySelector(I);if(i){if("radio"===i.type)if(i.checked&&this._element.classList.contains(w))t=!1;else{var o=n.querySelector(A);o&&p(o).removeClass(w)}if(t){if(i.hasAttribute("disabled")||n.hasAttribute("disabled")||i.classList.contains("disabled")||n.classList.contains("disabled"))return;i.checked=!this._element.classList.contains(w),p(i).trigger("change")}i.focus(),e=!1}}e&&this._element.setAttribute("aria-pressed",!this._element.classList.contains(w)),t&&p(this._element).toggleClass(w)},t.dispose=function(){p.removeData(this._element,v),this._element=null},n._jQueryInterface=function(e){return this.each(function(){var t=p(this).data(v);t||(t=new n(this),p(this).data(v,t)),"toggle"===e&&t[e]()})},s(n,null,[{key:"VERSION",get:function(){return"4.3.1"}}]),n}();p(document).on(N.CLICK_DATA_API,S,function(t){t.preventDefault();var e=t.target;p(e).hasClass(C)||(e=p(e).closest(O)),k._jQueryInterface.call(p(e),"toggle")}).on(N.FOCUS_BLUR_DATA_API,S,function(t){var e=p(t.target).closest(O)[0];p(e).toggleClass(T,/^focus(in)?$/.test(t.type))}),p.fn[_]=k._jQueryInterface,p.fn[_].Constructor=k,p.fn[_].noConflict=function(){return p.fn[_]=b,k._jQueryInterface};var L="carousel",x="bs.carousel",P="."+x,H=".data-api",j=p.fn[L],R={interval:5e3,keyboard:!0,slide:!1,pause:"hover",wrap:!0,touch:!0},F={interval:"(number|boolean)",keyboard:"boolean",slide:"(boolean|string)",pause:"(string|boolean)",wrap:"boolean",touch:"boolean"},M="next",W="prev",U="left",B="right",q={SLIDE:"slide"+P,SLID:"slid"+P,KEYDOWN:"keydown"+P,MOUSEENTER:"mouseenter"+P,MOUSELEAVE:"mouseleave"+P,TOUCHSTART:"touchstart"+P,TOUCHMOVE:"touchmove"+P,TOUCHEND:"touchend"+P,POINTERDOWN:"pointerdown"+P,POINTERUP:"pointerup"+P,DRAG_START:"dragstart"+P,LOAD_DATA_API:"load"+P+H,CLICK_DATA_API:"click"+P+H},K="carousel",Q="active",V="slide",Y="carousel-item-right",z="carousel-item-left",X="carousel-item-next",G="carousel-item-prev",$="pointer-event",J=".active",Z=".active.carousel-item",tt=".carousel-item",et=".carousel-item img",nt=".carousel-item-next, .carousel-item-prev",it=".carousel-indicators",ot="[data-slide], [data-slide-to]",rt='[data-ride="carousel"]',st={TOUCH:"touch",PEN:"pen"},at=function(){function r(t,e){this._items=null,this._interval=null,this._activeElement=null,this._isPaused=!1,this._isSliding=!1,this.touchTimeout=null,this.touchStartX=0,this.touchDeltaX=0,this._config=this._getConfig(e),this._element=t,this._indicatorsElement=this._element.querySelector(it),this._touchSupported="ontouchstart"in document.documentElement||0<navigator.maxTouchPoints,this._pointerEvent=Boolean(window.PointerEvent||window.MSPointerEvent),this._addEventListeners()}var t=r.prototype;return t.next=function(){this._isSliding||this._slide(M)},t.nextWhenVisible=function(){!document.hidden&&p(this._element).is(":visible")&&"hidden"!==p(this._element).css("visibility")&&this.next()},t.prev=function(){this._isSliding||this._slide(W)},t.pause=function(t){t||(this._isPaused=!0),this._element.querySelector(nt)&&(m.triggerTransitionEnd(this._element),this.cycle(!0)),clearInterval(this._interval),this._interval=null},t.cycle=function(t){t||(this._isPaused=!1),this._interval&&(clearInterval(this._interval),this._interval=null),this._config.interval&&!this._isPaused&&(this._interval=setInterval((document.visibilityState?this.nextWhenVisible:this.next).bind(this),this._config.interval))},t.to=function(t){var e=this;this._activeElement=this._element.querySelector(Z);var n=this._getItemIndex(this._activeElement);if(!(t>this._items.length-1||t<0))if(this._isSliding)p(this._element).one(q.SLID,function(){return e.to(t)});else{if(n===t)return this.pause(),void this.cycle();var i=n<t?M:W;this._slide(i,this._items[t])}},t.dispose=function(){p(this._element).off(P),p.removeData(this._element,x),this._items=null,this._config=null,this._element=null,this._interval=null,this._isPaused=null,this._isSliding=null,this._activeElement=null,this._indicatorsElement=null},t._getConfig=function(t){return t=l({},R,t),m.typeCheckConfig(L,t,F),t},t._handleSwipe=function(){var t=Math.abs(this.touchDeltaX);if(!(t<=40)){var e=t/this.touchDeltaX;0<e&&this.prev(),e<0&&this.next()}},t._addEventListeners=function(){var e=this;this._config.keyboard&&p(this._element).on(q.KEYDOWN,function(t){return e._keydown(t)}),"hover"===this._config.pause&&p(this._element).on(q.MOUSEENTER,function(t){return e.pause(t)}).on(q.MOUSELEAVE,function(t){return e.cycle(t)}),this._config.touch&&this._addTouchEventListeners()},t._addTouchEventListeners=function(){var n=this;if(this._touchSupported){var e=function(t){n._pointerEvent&&st[t.originalEvent.pointerType.toUpperCase()]?n.touchStartX=t.originalEvent.clientX:n._pointerEvent||(n.touchStartX=t.originalEvent.touches[0].clientX)},i=function(t){n._pointerEvent&&st[t.originalEvent.pointerType.toUpperCase()]&&(n.touchDeltaX=t.originalEvent.clientX-n.touchStartX),n._handleSwipe(),"hover"===n._config.pause&&(n.pause(),n.touchTimeout&&clearTimeout(n.touchTimeout),n.touchTimeout=setTimeout(function(t){return n.cycle(t)},500+n._config.interval))};p(this._element.querySelectorAll(et)).on(q.DRAG_START,function(t){return t.preventDefault()}),this._pointerEvent?(p(this._element).on(q.POINTERDOWN,function(t){return e(t)}),p(this._element).on(q.POINTERUP,function(t){return i(t)}),this._element.classList.add($)):(p(this._element).on(q.TOUCHSTART,function(t){return e(t)}),p(this._element).on(q.TOUCHMOVE,function(t){var e;(e=t).originalEvent.touches&&1<e.originalEvent.touches.length?n.touchDeltaX=0:n.touchDeltaX=e.originalEvent.touches[0].clientX-n.touchStartX}),p(this._element).on(q.TOUCHEND,function(t){return i(t)}))}},t._keydown=function(t){if(!/input|textarea/i.test(t.target.tagName))switch(t.which){case 37:t.preventDefault(),this.prev();break;case 39:t.preventDefault(),this.next()}},t._getItemIndex=function(t){return this._items=t&&t.parentNode?[].slice.call(t.parentNode.querySelectorAll(tt)):[],this._items.indexOf(t)},t._getItemByDirection=function(t,e){var n=t===M,i=t===W,o=this._getItemIndex(e),r=this._items.length-1;if((i&&0===o||n&&o===r)&&!this._config.wrap)return e;var s=(o+(t===W?-1:1))%this._items.length;return-1===s?this._items[this._items.length-1]:this._items[s]},t._triggerSlideEvent=function(t,e){var n=this._getItemIndex(t),i=this._getItemIndex(this._element.querySelector(Z)),o=p.Event(q.SLIDE,{relatedTarget:t,direction:e,from:i,to:n});return p(this._element).trigger(o),o},t._setActiveIndicatorElement=function(t){if(this._indicatorsElement){var e=[].slice.call(this._indicatorsElement.querySelectorAll(J));p(e).removeClass(Q);var n=this._indicatorsElement.children[this._getItemIndex(t)];n&&p(n).addClass(Q)}},t._slide=function(t,e){var n,i,o,r=this,s=this._element.querySelector(Z),a=this._getItemIndex(s),l=e||s&&this._getItemByDirection(t,s),c=this._getItemIndex(l),h=Boolean(this._interval);if(o=t===M?(n=z,i=X,U):(n=Y,i=G,B),l&&p(l).hasClass(Q))this._isSliding=!1;else if(!this._triggerSlideEvent(l,o).isDefaultPrevented()&&s&&l){this._isSliding=!0,h&&this.pause(),this._setActiveIndicatorElement(l);var u=p.Event(q.SLID,{relatedTarget:l,direction:o,from:a,to:c});if(p(this._element).hasClass(V)){p(l).addClass(i),m.reflow(l),p(s).addClass(n),p(l).addClass(n);var f=parseInt(l.getAttribute("data-interval"),10);this._config.interval=f?(this._config.defaultInterval=this._config.defaultInterval||this._config.interval,f):this._config.defaultInterval||this._config.interval;var d=m.getTransitionDurationFromElement(s);p(s).one(m.TRANSITION_END,function(){p(l).removeClass(n+" "+i).addClass(Q),p(s).removeClass(Q+" "+i+" "+n),r._isSliding=!1,setTimeout(function(){return p(r._element).trigger(u)},0)}).emulateTransitionEnd(d)}else p(s).removeClass(Q),p(l).addClass(Q),this._isSliding=!1,p(this._element).trigger(u);h&&this.cycle()}},r._jQueryInterface=function(i){return this.each(function(){var t=p(this).data(x),e=l({},R,p(this).data());"object"==typeof i&&(e=l({},e,i));var n="string"==typeof i?i:e.slide;if(t||(t=new r(this,e),p(this).data(x,t)),"number"==typeof i)t.to(i);else if("string"==typeof n){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}else e.interval&&e.ride&&(t.pause(),t.cycle())})},r._dataApiClickHandler=function(t){var e=m.getSelectorFromElement(this);if(e){var n=p(e)[0];if(n&&p(n).hasClass(K)){var i=l({},p(n).data(),p(this).data()),o=this.getAttribute("data-slide-to");o&&(i.interval=!1),r._jQueryInterface.call(p(n),i),o&&p(n).data(x).to(o),t.preventDefault()}}},s(r,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return R}}]),r}();p(document).on(q.CLICK_DATA_API,ot,at._dataApiClickHandler),p(window).on(q.LOAD_DATA_API,function(){for(var t=[].slice.call(document.querySelectorAll(rt)),e=0,n=t.length;e<n;e++){var i=p(t[e]);at._jQueryInterface.call(i,i.data())}}),p.fn[L]=at._jQueryInterface,p.fn[L].Constructor=at,p.fn[L].noConflict=function(){return p.fn[L]=j,at._jQueryInterface};var lt="collapse",ct="bs.collapse",ht="."+ct,ut=p.fn[lt],ft={toggle:!0,parent:""},dt={toggle:"boolean",parent:"(string|element)"},pt={SHOW:"show"+ht,SHOWN:"shown"+ht,HIDE:"hide"+ht,HIDDEN:"hidden"+ht,CLICK_DATA_API:"click"+ht+".data-api"},mt="show",gt="collapse",_t="collapsing",vt="collapsed",yt="width",Et="height",bt=".show, .collapsing",wt='[data-toggle="collapse"]',Ct=function(){function a(e,t){this._isTransitioning=!1,this._element=e,this._config=this._getConfig(t),this._triggerArray=[].slice.call(document.querySelectorAll('[data-toggle="collapse"][href="#'+e.id+'"],[data-toggle="collapse"][data-target="#'+e.id+'"]'));for(var n=[].slice.call(document.querySelectorAll(wt)),i=0,o=n.length;i<o;i++){var r=n[i],s=m.getSelectorFromElement(r),a=[].slice.call(document.querySelectorAll(s)).filter(function(t){return t===e});null!==s&&0<a.length&&(this._selector=s,this._triggerArray.push(r))}this._parent=this._config.parent?this._getParent():null,this._config.parent||this._addAriaAndCollapsedClass(this._element,this._triggerArray),this._config.toggle&&this.toggle()}var t=a.prototype;return t.toggle=function(){p(this._element).hasClass(mt)?this.hide():this.show()},t.show=function(){var t,e,n=this;if(!this._isTransitioning&&!p(this._element).hasClass(mt)&&(this._parent&&0===(t=[].slice.call(this._parent.querySelectorAll(bt)).filter(function(t){return"string"==typeof n._config.parent?t.getAttribute("data-parent")===n._config.parent:t.classList.contains(gt)})).length&&(t=null),!(t&&(e=p(t).not(this._selector).data(ct))&&e._isTransitioning))){var i=p.Event(pt.SHOW);if(p(this._element).trigger(i),!i.isDefaultPrevented()){t&&(a._jQueryInterface.call(p(t).not(this._selector),"hide"),e||p(t).data(ct,null));var o=this._getDimension();p(this._element).removeClass(gt).addClass(_t),this._element.style[o]=0,this._triggerArray.length&&p(this._triggerArray).removeClass(vt).attr("aria-expanded",!0),this.setTransitioning(!0);var r="scroll"+(o[0].toUpperCase()+o.slice(1)),s=m.getTransitionDurationFromElement(this._element);p(this._element).one(m.TRANSITION_END,function(){p(n._element).removeClass(_t).addClass(gt).addClass(mt),n._element.style[o]="",n.setTransitioning(!1),p(n._element).trigger(pt.SHOWN)}).emulateTransitionEnd(s),this._element.style[o]=this._element[r]+"px"}}},t.hide=function(){var t=this;if(!this._isTransitioning&&p(this._element).hasClass(mt)){var e=p.Event(pt.HIDE);if(p(this._element).trigger(e),!e.isDefaultPrevented()){var n=this._getDimension();this._element.style[n]=this._element.getBoundingClientRect()[n]+"px",m.reflow(this._element),p(this._element).addClass(_t).removeClass(gt).removeClass(mt);var i=this._triggerArray.length;if(0<i)for(var o=0;o<i;o++){var r=this._triggerArray[o],s=m.getSelectorFromElement(r);if(null!==s)p([].slice.call(document.querySelectorAll(s))).hasClass(mt)||p(r).addClass(vt).attr("aria-expanded",!1)}this.setTransitioning(!0);this._element.style[n]="";var a=m.getTransitionDurationFromElement(this._element);p(this._element).one(m.TRANSITION_END,function(){t.setTransitioning(!1),p(t._element).removeClass(_t).addClass(gt).trigger(pt.HIDDEN)}).emulateTransitionEnd(a)}}},t.setTransitioning=function(t){this._isTransitioning=t},t.dispose=function(){p.removeData(this._element,ct),this._config=null,this._parent=null,this._element=null,this._triggerArray=null,this._isTransitioning=null},t._getConfig=function(t){return(t=l({},ft,t)).toggle=Boolean(t.toggle),m.typeCheckConfig(lt,t,dt),t},t._getDimension=function(){return p(this._element).hasClass(yt)?yt:Et},t._getParent=function(){var t,n=this;m.isElement(this._config.parent)?(t=this._config.parent,"undefined"!=typeof this._config.parent.jquery&&(t=this._config.parent[0])):t=document.querySelector(this._config.parent);var e='[data-toggle="collapse"][data-parent="'+this._config.parent+'"]',i=[].slice.call(t.querySelectorAll(e));return p(i).each(function(t,e){n._addAriaAndCollapsedClass(a._getTargetFromElement(e),[e])}),t},t._addAriaAndCollapsedClass=function(t,e){var n=p(t).hasClass(mt);e.length&&p(e).toggleClass(vt,!n).attr("aria-expanded",n)},a._getTargetFromElement=function(t){var e=m.getSelectorFromElement(t);return e?document.querySelector(e):null},a._jQueryInterface=function(i){return this.each(function(){var t=p(this),e=t.data(ct),n=l({},ft,t.data(),"object"==typeof i&&i?i:{});if(!e&&n.toggle&&/show|hide/.test(i)&&(n.toggle=!1),e||(e=new a(this,n),t.data(ct,e)),"string"==typeof i){if("undefined"==typeof e[i])throw new TypeError('No method named "'+i+'"');e[i]()}})},s(a,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return ft}}]),a}();p(document).on(pt.CLICK_DATA_API,wt,function(t){"A"===t.currentTarget.tagName&&t.preventDefault();var n=p(this),e=m.getSelectorFromElement(this),i=[].slice.call(document.querySelectorAll(e));p(i).each(function(){var t=p(this),e=t.data(ct)?"toggle":n.data();Ct._jQueryInterface.call(t,e)})}),p.fn[lt]=Ct._jQueryInterface,p.fn[lt].Constructor=Ct,p.fn[lt].noConflict=function(){return p.fn[lt]=ut,Ct._jQueryInterface};for(var Tt="undefined"!=typeof window&&"undefined"!=typeof document,St=["Edge","Trident","Firefox"],Dt=0,It=0;It<St.length;It+=1)if(Tt&&0<=navigator.userAgent.indexOf(St[It])){Dt=1;break}var At=Tt&&window.Promise?function(t){var e=!1;return function(){e||(e=!0,window.Promise.resolve().then(function(){e=!1,t()}))}}:function(t){var e=!1;return function(){e||(e=!0,setTimeout(function(){e=!1,t()},Dt))}};function Ot(t){return t&&"[object Function]"==={}.toString.call(t)}function Nt(t,e){if(1!==t.nodeType)return[];var n=t.ownerDocument.defaultView.getComputedStyle(t,null);return e?n[e]:n}function kt(t){return"HTML"===t.nodeName?t:t.parentNode||t.host}function Lt(t){if(!t)return document.body;switch(t.nodeName){case"HTML":case"BODY":return t.ownerDocument.body;case"#document":return t.body}var e=Nt(t),n=e.overflow,i=e.overflowX,o=e.overflowY;return/(auto|scroll|overlay)/.test(n+o+i)?t:Lt(kt(t))}var xt=Tt&&!(!window.MSInputMethodContext||!document.documentMode),Pt=Tt&&/MSIE 10/.test(navigator.userAgent);function Ht(t){return 11===t?xt:10===t?Pt:xt||Pt}function jt(t){if(!t)return document.documentElement;for(var e=Ht(10)?document.body:null,n=t.offsetParent||null;n===e&&t.nextElementSibling;)n=(t=t.nextElementSibling).offsetParent;var i=n&&n.nodeName;return i&&"BODY"!==i&&"HTML"!==i?-1!==["TH","TD","TABLE"].indexOf(n.nodeName)&&"static"===Nt(n,"position")?jt(n):n:t?t.ownerDocument.documentElement:document.documentElement}function Rt(t){return null!==t.parentNode?Rt(t.parentNode):t}function Ft(t,e){if(!(t&&t.nodeType&&e&&e.nodeType))return document.documentElement;var n=t.compareDocumentPosition(e)&Node.DOCUMENT_POSITION_FOLLOWING,i=n?t:e,o=n?e:t,r=document.createRange();r.setStart(i,0),r.setEnd(o,0);var s,a,l=r.commonAncestorContainer;if(t!==l&&e!==l||i.contains(o))return"BODY"===(a=(s=l).nodeName)||"HTML"!==a&&jt(s.firstElementChild)!==s?jt(l):l;var c=Rt(t);return c.host?Ft(c.host,e):Ft(t,Rt(e).host)}function Mt(t){var e="top"===(1<arguments.length&&void 0!==arguments[1]?arguments[1]:"top")?"scrollTop":"scrollLeft",n=t.nodeName;if("BODY"!==n&&"HTML"!==n)return t[e];var i=t.ownerDocument.documentElement;return(t.ownerDocument.scrollingElement||i)[e]}function Wt(t,e){var n="x"===e?"Left":"Top",i="Left"===n?"Right":"Bottom";return parseFloat(t["border"+n+"Width"],10)+parseFloat(t["border"+i+"Width"],10)}function Ut(t,e,n,i){return Math.max(e["offset"+t],e["scroll"+t],n["client"+t],n["offset"+t],n["scroll"+t],Ht(10)?parseInt(n["offset"+t])+parseInt(i["margin"+("Height"===t?"Top":"Left")])+parseInt(i["margin"+("Height"===t?"Bottom":"Right")]):0)}function Bt(t){var e=t.body,n=t.documentElement,i=Ht(10)&&getComputedStyle(n);return{height:Ut("Height",e,n,i),width:Ut("Width",e,n,i)}}var qt=function(){function i(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(t,e,n){return e&&i(t.prototype,e),n&&i(t,n),t}}(),Kt=function(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t},Qt=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var i in n)Object.prototype.hasOwnProperty.call(n,i)&&(t[i]=n[i])}return t};function Vt(t){return Qt({},t,{right:t.left+t.width,bottom:t.top+t.height})}function Yt(t){var e={};try{if(Ht(10)){e=t.getBoundingClientRect();var n=Mt(t,"top"),i=Mt(t,"left");e.top+=n,e.left+=i,e.bottom+=n,e.right+=i}else e=t.getBoundingClientRect()}catch(t){}var o={left:e.left,top:e.top,width:e.right-e.left,height:e.bottom-e.top},r="HTML"===t.nodeName?Bt(t.ownerDocument):{},s=r.width||t.clientWidth||o.right-o.left,a=r.height||t.clientHeight||o.bottom-o.top,l=t.offsetWidth-s,c=t.offsetHeight-a;if(l||c){var h=Nt(t);l-=Wt(h,"x"),c-=Wt(h,"y"),o.width-=l,o.height-=c}return Vt(o)}function zt(t,e){var n=2<arguments.length&&void 0!==arguments[2]&&arguments[2],i=Ht(10),o="HTML"===e.nodeName,r=Yt(t),s=Yt(e),a=Lt(t),l=Nt(e),c=parseFloat(l.borderTopWidth,10),h=parseFloat(l.borderLeftWidth,10);n&&o&&(s.top=Math.max(s.top,0),s.left=Math.max(s.left,0));var u=Vt({top:r.top-s.top-c,left:r.left-s.left-h,width:r.width,height:r.height});if(u.marginTop=0,u.marginLeft=0,!i&&o){var f=parseFloat(l.marginTop,10),d=parseFloat(l.marginLeft,10);u.top-=c-f,u.bottom-=c-f,u.left-=h-d,u.right-=h-d,u.marginTop=f,u.marginLeft=d}return(i&&!n?e.contains(a):e===a&&"BODY"!==a.nodeName)&&(u=function(t,e){var n=2<arguments.length&&void 0!==arguments[2]&&arguments[2],i=Mt(e,"top"),o=Mt(e,"left"),r=n?-1:1;return t.top+=i*r,t.bottom+=i*r,t.left+=o*r,t.right+=o*r,t}(u,e)),u}function Xt(t){if(!t||!t.parentElement||Ht())return document.documentElement;for(var e=t.parentElement;e&&"none"===Nt(e,"transform");)e=e.parentElement;return e||document.documentElement}function Gt(t,e,n,i){var o=4<arguments.length&&void 0!==arguments[4]&&arguments[4],r={top:0,left:0},s=o?Xt(t):Ft(t,e);if("viewport"===i)r=function(t){var e=1<arguments.length&&void 0!==arguments[1]&&arguments[1],n=t.ownerDocument.documentElement,i=zt(t,n),o=Math.max(n.clientWidth,window.innerWidth||0),r=Math.max(n.clientHeight,window.innerHeight||0),s=e?0:Mt(n),a=e?0:Mt(n,"left");return Vt({top:s-i.top+i.marginTop,left:a-i.left+i.marginLeft,width:o,height:r})}(s,o);else{var a=void 0;"scrollParent"===i?"BODY"===(a=Lt(kt(e))).nodeName&&(a=t.ownerDocument.documentElement):a="window"===i?t.ownerDocument.documentElement:i;var l=zt(a,s,o);if("HTML"!==a.nodeName||function t(e){var n=e.nodeName;if("BODY"===n||"HTML"===n)return!1;if("fixed"===Nt(e,"position"))return!0;var i=kt(e);return!!i&&t(i)}(s))r=l;else{var c=Bt(t.ownerDocument),h=c.height,u=c.width;r.top+=l.top-l.marginTop,r.bottom=h+l.top,r.left+=l.left-l.marginLeft,r.right=u+l.left}}var f="number"==typeof(n=n||0);return r.left+=f?n:n.left||0,r.top+=f?n:n.top||0,r.right-=f?n:n.right||0,r.bottom-=f?n:n.bottom||0,r}function $t(t,e,i,n,o){var r=5<arguments.length&&void 0!==arguments[5]?arguments[5]:0;if(-1===t.indexOf("auto"))return t;var s=Gt(i,n,r,o),a={top:{width:s.width,height:e.top-s.top},right:{width:s.right-e.right,height:s.height},bottom:{width:s.width,height:s.bottom-e.bottom},left:{width:e.left-s.left,height:s.height}},l=Object.keys(a).map(function(t){return Qt({key:t},a[t],{area:(e=a[t],e.width*e.height)});var e}).sort(function(t,e){return e.area-t.area}),c=l.filter(function(t){var e=t.width,n=t.height;return e>=i.clientWidth&&n>=i.clientHeight}),h=0<c.length?c[0].key:l[0].key,u=t.split("-")[1];return h+(u?"-"+u:"")}function Jt(t,e,n){var i=3<arguments.length&&void 0!==arguments[3]?arguments[3]:null;return zt(n,i?Xt(e):Ft(e,n),i)}function Zt(t){var e=t.ownerDocument.defaultView.getComputedStyle(t),n=parseFloat(e.marginTop||0)+parseFloat(e.marginBottom||0),i=parseFloat(e.marginLeft||0)+parseFloat(e.marginRight||0);return{width:t.offsetWidth+i,height:t.offsetHeight+n}}function te(t){var e={left:"right",right:"left",bottom:"top",top:"bottom"};return t.replace(/left|right|bottom|top/g,function(t){return e[t]})}function ee(t,e,n){n=n.split("-")[0];var i=Zt(t),o={width:i.width,height:i.height},r=-1!==["right","left"].indexOf(n),s=r?"top":"left",a=r?"left":"top",l=r?"height":"width",c=r?"width":"height";return o[s]=e[s]+e[l]/2-i[l]/2,o[a]=n===a?e[a]-i[c]:e[te(a)],o}function ne(t,e){return Array.prototype.find?t.find(e):t.filter(e)[0]}function ie(t,n,e){return(void 0===e?t:t.slice(0,function(t,e,n){if(Array.prototype.findIndex)return t.findIndex(function(t){return t[e]===n});var i=ne(t,function(t){return t[e]===n});return t.indexOf(i)}(t,"name",e))).forEach(function(t){t.function&&console.warn("`modifier.function` is deprecated, use `modifier.fn`!");var e=t.function||t.fn;t.enabled&&Ot(e)&&(n.offsets.popper=Vt(n.offsets.popper),n.offsets.reference=Vt(n.offsets.reference),n=e(n,t))}),n}function oe(t,n){return t.some(function(t){var e=t.name;return t.enabled&&e===n})}function re(t){for(var e=[!1,"ms","Webkit","Moz","O"],n=t.charAt(0).toUpperCase()+t.slice(1),i=0;i<e.length;i++){var o=e[i],r=o?""+o+n:t;if("undefined"!=typeof document.body.style[r])return r}return null}function se(t){var e=t.ownerDocument;return e?e.defaultView:window}function ae(t,e,n,i){n.updateBound=i,se(t).addEventListener("resize",n.updateBound,{passive:!0});var o=Lt(t);return function t(e,n,i,o){var r="BODY"===e.nodeName,s=r?e.ownerDocument.defaultView:e;s.addEventListener(n,i,{passive:!0}),r||t(Lt(s.parentNode),n,i,o),o.push(s)}(o,"scroll",n.updateBound,n.scrollParents),n.scrollElement=o,n.eventsEnabled=!0,n}function le(){var t,e;this.state.eventsEnabled&&(cancelAnimationFrame(this.scheduleUpdate),this.state=(t=this.reference,e=this.state,se(t).removeEventListener("resize",e.updateBound),e.scrollParents.forEach(function(t){t.removeEventListener("scroll",e.updateBound)}),e.updateBound=null,e.scrollParents=[],e.scrollElement=null,e.eventsEnabled=!1,e))}function ce(t){return""!==t&&!isNaN(parseFloat(t))&&isFinite(t)}function he(n,i){Object.keys(i).forEach(function(t){var e="";-1!==["width","height","top","right","bottom","left"].indexOf(t)&&ce(i[t])&&(e="px"),n.style[t]=i[t]+e})}var ue=Tt&&/Firefox/i.test(navigator.userAgent);function fe(t,e,n){var i=ne(t,function(t){return t.name===e}),o=!!i&&t.some(function(t){return t.name===n&&t.enabled&&t.order<i.order});if(!o){var r="`"+e+"`",s="`"+n+"`";console.warn(s+" modifier is required by "+r+" modifier in order to work, be sure to include it before "+r+"!")}return o}var de=["auto-start","auto","auto-end","top-start","top","top-end","right-start","right","right-end","bottom-end","bottom","bottom-start","left-end","left","left-start"],pe=de.slice(3);function me(t){var e=1<arguments.length&&void 0!==arguments[1]&&arguments[1],n=pe.indexOf(t),i=pe.slice(n+1).concat(pe.slice(0,n));return e?i.reverse():i}var ge="flip",_e="clockwise",ve="counterclockwise";function ye(t,o,r,e){var s=[0,0],a=-1!==["right","left"].indexOf(e),n=t.split(/(\+|\-)/).map(function(t){return t.trim()}),i=n.indexOf(ne(n,function(t){return-1!==t.search(/,|\s/)}));n[i]&&-1===n[i].indexOf(",")&&console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");var l=/\s*,\s*|\s+/,c=-1!==i?[n.slice(0,i).concat([n[i].split(l)[0]]),[n[i].split(l)[1]].concat(n.slice(i+1))]:[n];return(c=c.map(function(t,e){var n=(1===e?!a:a)?"height":"width",i=!1;return t.reduce(function(t,e){return""===t[t.length-1]&&-1!==["+","-"].indexOf(e)?(t[t.length-1]=e,i=!0,t):i?(t[t.length-1]+=e,i=!1,t):t.concat(e)},[]).map(function(t){return function(t,e,n,i){var o=t.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),r=+o[1],s=o[2];if(!r)return t;if(0!==s.indexOf("%"))return"vh"!==s&&"vw"!==s?r:("vh"===s?Math.max(document.documentElement.clientHeight,window.innerHeight||0):Math.max(document.documentElement.clientWidth,window.innerWidth||0))/100*r;var a=void 0;switch(s){case"%p":a=n;break;case"%":case"%r":default:a=i}return Vt(a)[e]/100*r}(t,n,o,r)})})).forEach(function(n,i){n.forEach(function(t,e){ce(t)&&(s[i]+=t*("-"===n[e-1]?-1:1))})}),s}var Ee={placement:"bottom",positionFixed:!1,eventsEnabled:!0,removeOnDestroy:!1,onCreate:function(){},onUpdate:function(){},modifiers:{shift:{order:100,enabled:!0,fn:function(t){var e=t.placement,n=e.split("-")[0],i=e.split("-")[1];if(i){var o=t.offsets,r=o.reference,s=o.popper,a=-1!==["bottom","top"].indexOf(n),l=a?"left":"top",c=a?"width":"height",h={start:Kt({},l,r[l]),end:Kt({},l,r[l]+r[c]-s[c])};t.offsets.popper=Qt({},s,h[i])}return t}},offset:{order:200,enabled:!0,fn:function(t,e){var n=e.offset,i=t.placement,o=t.offsets,r=o.popper,s=o.reference,a=i.split("-")[0],l=void 0;return l=ce(+n)?[+n,0]:ye(n,r,s,a),"left"===a?(r.top+=l[0],r.left-=l[1]):"right"===a?(r.top+=l[0],r.left+=l[1]):"top"===a?(r.left+=l[0],r.top-=l[1]):"bottom"===a&&(r.left+=l[0],r.top+=l[1]),t.popper=r,t},offset:0},preventOverflow:{order:300,enabled:!0,fn:function(t,i){var e=i.boundariesElement||jt(t.instance.popper);t.instance.reference===e&&(e=jt(e));var n=re("transform"),o=t.instance.popper.style,r=o.top,s=o.left,a=o[n];o.top="",o.left="",o[n]="";var l=Gt(t.instance.popper,t.instance.reference,i.padding,e,t.positionFixed);o.top=r,o.left=s,o[n]=a,i.boundaries=l;var c=i.priority,h=t.offsets.popper,u={primary:function(t){var e=h[t];return h[t]<l[t]&&!i.escapeWithReference&&(e=Math.max(h[t],l[t])),Kt({},t,e)},secondary:function(t){var e="right"===t?"left":"top",n=h[e];return h[t]>l[t]&&!i.escapeWithReference&&(n=Math.min(h[e],l[t]-("right"===t?h.width:h.height))),Kt({},e,n)}};return c.forEach(function(t){var e=-1!==["left","top"].indexOf(t)?"primary":"secondary";h=Qt({},h,u[e](t))}),t.offsets.popper=h,t},priority:["left","right","top","bottom"],padding:5,boundariesElement:"scrollParent"},keepTogether:{order:400,enabled:!0,fn:function(t){var e=t.offsets,n=e.popper,i=e.reference,o=t.placement.split("-")[0],r=Math.floor,s=-1!==["top","bottom"].indexOf(o),a=s?"right":"bottom",l=s?"left":"top",c=s?"width":"height";return n[a]<r(i[l])&&(t.offsets.popper[l]=r(i[l])-n[c]),n[l]>r(i[a])&&(t.offsets.popper[l]=r(i[a])),t}},arrow:{order:500,enabled:!0,fn:function(t,e){var n;if(!fe(t.instance.modifiers,"arrow","keepTogether"))return t;var i=e.element;if("string"==typeof i){if(!(i=t.instance.popper.querySelector(i)))return t}else if(!t.instance.popper.contains(i))return console.warn("WARNING: `arrow.element` must be child of its popper element!"),t;var o=t.placement.split("-")[0],r=t.offsets,s=r.popper,a=r.reference,l=-1!==["left","right"].indexOf(o),c=l?"height":"width",h=l?"Top":"Left",u=h.toLowerCase(),f=l?"left":"top",d=l?"bottom":"right",p=Zt(i)[c];a[d]-p<s[u]&&(t.offsets.popper[u]-=s[u]-(a[d]-p)),a[u]+p>s[d]&&(t.offsets.popper[u]+=a[u]+p-s[d]),t.offsets.popper=Vt(t.offsets.popper);var m=a[u]+a[c]/2-p/2,g=Nt(t.instance.popper),_=parseFloat(g["margin"+h],10),v=parseFloat(g["border"+h+"Width"],10),y=m-t.offsets.popper[u]-_-v;return y=Math.max(Math.min(s[c]-p,y),0),t.arrowElement=i,t.offsets.arrow=(Kt(n={},u,Math.round(y)),Kt(n,f,""),n),t},element:"[x-arrow]"},flip:{order:600,enabled:!0,fn:function(p,m){if(oe(p.instance.modifiers,"inner"))return p;if(p.flipped&&p.placement===p.originalPlacement)return p;var g=Gt(p.instance.popper,p.instance.reference,m.padding,m.boundariesElement,p.positionFixed),_=p.placement.split("-")[0],v=te(_),y=p.placement.split("-")[1]||"",E=[];switch(m.behavior){case ge:E=[_,v];break;case _e:E=me(_);break;case ve:E=me(_,!0);break;default:E=m.behavior}return E.forEach(function(t,e){if(_!==t||E.length===e+1)return p;_=p.placement.split("-")[0],v=te(_);var n,i=p.offsets.popper,o=p.offsets.reference,r=Math.floor,s="left"===_&&r(i.right)>r(o.left)||"right"===_&&r(i.left)<r(o.right)||"top"===_&&r(i.bottom)>r(o.top)||"bottom"===_&&r(i.top)<r(o.bottom),a=r(i.left)<r(g.left),l=r(i.right)>r(g.right),c=r(i.top)<r(g.top),h=r(i.bottom)>r(g.bottom),u="left"===_&&a||"right"===_&&l||"top"===_&&c||"bottom"===_&&h,f=-1!==["top","bottom"].indexOf(_),d=!!m.flipVariations&&(f&&"start"===y&&a||f&&"end"===y&&l||!f&&"start"===y&&c||!f&&"end"===y&&h);(s||u||d)&&(p.flipped=!0,(s||u)&&(_=E[e+1]),d&&(y="end"===(n=y)?"start":"start"===n?"end":n),p.placement=_+(y?"-"+y:""),p.offsets.popper=Qt({},p.offsets.popper,ee(p.instance.popper,p.offsets.reference,p.placement)),p=ie(p.instance.modifiers,p,"flip"))}),p},behavior:"flip",padding:5,boundariesElement:"viewport"},inner:{order:700,enabled:!1,fn:function(t){var e=t.placement,n=e.split("-")[0],i=t.offsets,o=i.popper,r=i.reference,s=-1!==["left","right"].indexOf(n),a=-1===["top","left"].indexOf(n);return o[s?"left":"top"]=r[n]-(a?o[s?"width":"height"]:0),t.placement=te(e),t.offsets.popper=Vt(o),t}},hide:{order:800,enabled:!0,fn:function(t){if(!fe(t.instance.modifiers,"hide","preventOverflow"))return t;var e=t.offsets.reference,n=ne(t.instance.modifiers,function(t){return"preventOverflow"===t.name}).boundaries;if(e.bottom<n.top||e.left>n.right||e.top>n.bottom||e.right<n.left){if(!0===t.hide)return t;t.hide=!0,t.attributes["x-out-of-boundaries"]=""}else{if(!1===t.hide)return t;t.hide=!1,t.attributes["x-out-of-boundaries"]=!1}return t}},computeStyle:{order:850,enabled:!0,fn:function(t,e){var n=e.x,i=e.y,o=t.offsets.popper,r=ne(t.instance.modifiers,function(t){return"applyStyle"===t.name}).gpuAcceleration;void 0!==r&&console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");var s,a,l,c,h,u,f,d,p,m,g,_,v,y,E=void 0!==r?r:e.gpuAcceleration,b=jt(t.instance.popper),w=Yt(b),C={position:o.position},T=(s=t,a=window.devicePixelRatio<2||!ue,l=s.offsets,c=l.popper,h=l.reference,u=Math.round,f=Math.floor,d=function(t){return t},p=u(h.width),m=u(c.width),g=-1!==["left","right"].indexOf(s.placement),_=-1!==s.placement.indexOf("-"),y=a?u:d,{left:(v=a?g||_||p%2==m%2?u:f:d)(p%2==1&&m%2==1&&!_&&a?c.left-1:c.left),top:y(c.top),bottom:y(c.bottom),right:v(c.right)}),S="bottom"===n?"top":"bottom",D="right"===i?"left":"right",I=re("transform"),A=void 0,O=void 0;if(O="bottom"===S?"HTML"===b.nodeName?-b.clientHeight+T.bottom:-w.height+T.bottom:T.top,A="right"===D?"HTML"===b.nodeName?-b.clientWidth+T.right:-w.width+T.right:T.left,E&&I)C[I]="translate3d("+A+"px, "+O+"px, 0)",C[S]=0,C[D]=0,C.willChange="transform";else{var N="bottom"===S?-1:1,k="right"===D?-1:1;C[S]=O*N,C[D]=A*k,C.willChange=S+", "+D}var L={"x-placement":t.placement};return t.attributes=Qt({},L,t.attributes),t.styles=Qt({},C,t.styles),t.arrowStyles=Qt({},t.offsets.arrow,t.arrowStyles),t},gpuAcceleration:!0,x:"bottom",y:"right"},applyStyle:{order:900,enabled:!0,fn:function(t){var e,n;return he(t.instance.popper,t.styles),e=t.instance.popper,n=t.attributes,Object.keys(n).forEach(function(t){!1!==n[t]?e.setAttribute(t,n[t]):e.removeAttribute(t)}),t.arrowElement&&Object.keys(t.arrowStyles).length&&he(t.arrowElement,t.arrowStyles),t},onLoad:function(t,e,n,i,o){var r=Jt(o,e,t,n.positionFixed),s=$t(n.placement,r,e,t,n.modifiers.flip.boundariesElement,n.modifiers.flip.padding);return e.setAttribute("x-placement",s),he(e,{position:n.positionFixed?"fixed":"absolute"}),n},gpuAcceleration:void 0}}},be=function(){function r(t,e){var n=this,i=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{};!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,r),this.scheduleUpdate=function(){return requestAnimationFrame(n.update)},this.update=At(this.update.bind(this)),this.options=Qt({},r.Defaults,i),this.state={isDestroyed:!1,isCreated:!1,scrollParents:[]},this.reference=t&&t.jquery?t[0]:t,this.popper=e&&e.jquery?e[0]:e,this.options.modifiers={},Object.keys(Qt({},r.Defaults.modifiers,i.modifiers)).forEach(function(t){n.options.modifiers[t]=Qt({},r.Defaults.modifiers[t]||{},i.modifiers?i.modifiers[t]:{})}),this.modifiers=Object.keys(this.options.modifiers).map(function(t){return Qt({name:t},n.options.modifiers[t])}).sort(function(t,e){return t.order-e.order}),this.modifiers.forEach(function(t){t.enabled&&Ot(t.onLoad)&&t.onLoad(n.reference,n.popper,n.options,t,n.state)}),this.update();var o=this.options.eventsEnabled;o&&this.enableEventListeners(),this.state.eventsEnabled=o}return qt(r,[{key:"update",value:function(){return function(){if(!this.state.isDestroyed){var t={instance:this,styles:{},arrowStyles:{},attributes:{},flipped:!1,offsets:{}};t.offsets.reference=Jt(this.state,this.popper,this.reference,this.options.positionFixed),t.placement=$t(this.options.placement,t.offsets.reference,this.popper,this.reference,this.options.modifiers.flip.boundariesElement,this.options.modifiers.flip.padding),t.originalPlacement=t.placement,t.positionFixed=this.options.positionFixed,t.offsets.popper=ee(this.popper,t.offsets.reference,t.placement),t.offsets.popper.position=this.options.positionFixed?"fixed":"absolute",t=ie(this.modifiers,t),this.state.isCreated?this.options.onUpdate(t):(this.state.isCreated=!0,this.options.onCreate(t))}}.call(this)}},{key:"destroy",value:function(){return function(){return this.state.isDestroyed=!0,oe(this.modifiers,"applyStyle")&&(this.popper.removeAttribute("x-placement"),this.popper.style.position="",this.popper.style.top="",this.popper.style.left="",this.popper.style.right="",this.popper.style.bottom="",this.popper.style.willChange="",this.popper.style[re("transform")]=""),this.disableEventListeners(),this.options.removeOnDestroy&&this.popper.parentNode.removeChild(this.popper),this}.call(this)}},{key:"enableEventListeners",value:function(){return function(){this.state.eventsEnabled||(this.state=ae(this.reference,this.options,this.state,this.scheduleUpdate))}.call(this)}},{key:"disableEventListeners",value:function(){return le.call(this)}}]),r}();be.Utils=("undefined"!=typeof window?window:global).PopperUtils,be.placements=de,be.Defaults=Ee;var we="dropdown",Ce="bs.dropdown",Te="."+Ce,Se=".data-api",De=p.fn[we],Ie=new RegExp("38|40|27"),Ae={HIDE:"hide"+Te,HIDDEN:"hidden"+Te,SHOW:"show"+Te,SHOWN:"shown"+Te,CLICK:"click"+Te,CLICK_DATA_API:"click"+Te+Se,KEYDOWN_DATA_API:"keydown"+Te+Se,KEYUP_DATA_API:"keyup"+Te+Se},Oe="disabled",Ne="show",ke="dropup",Le="dropright",xe="dropleft",Pe="dropdown-menu-right",He="position-static",je='[data-toggle="dropdown"]',Re=".dropdown form",Fe=".dropdown-menu",Me=".navbar-nav",We=".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)",Ue="top-start",Be="top-end",qe="bottom-start",Ke="bottom-end",Qe="right-start",Ve="left-start",Ye={offset:0,flip:!0,boundary:"scrollParent",reference:"toggle",display:"dynamic"},ze={offset:"(number|string|function)",flip:"boolean",boundary:"(string|element)",reference:"(string|element)",display:"string"},Xe=function(){function c(t,e){this._element=t,this._popper=null,this._config=this._getConfig(e),this._menu=this._getMenuElement(),this._inNavbar=this._detectNavbar(),this._addEventListeners()}var t=c.prototype;return t.toggle=function(){if(!this._element.disabled&&!p(this._element).hasClass(Oe)){var t=c._getParentFromElement(this._element),e=p(this._menu).hasClass(Ne);if(c._clearMenus(),!e){var n={relatedTarget:this._element},i=p.Event(Ae.SHOW,n);if(p(t).trigger(i),!i.isDefaultPrevented()){if(!this._inNavbar){if("undefined"==typeof be)throw new TypeError("Bootstrap's dropdowns require Popper.js (https://popper.js.org/)");var o=this._element;"parent"===this._config.reference?o=t:m.isElement(this._config.reference)&&(o=this._config.reference,"undefined"!=typeof this._config.reference.jquery&&(o=this._config.reference[0])),"scrollParent"!==this._config.boundary&&p(t).addClass(He),this._popper=new be(o,this._menu,this._getPopperConfig())}"ontouchstart"in document.documentElement&&0===p(t).closest(Me).length&&p(document.body).children().on("mouseover",null,p.noop),this._element.focus(),this._element.setAttribute("aria-expanded",!0),p(this._menu).toggleClass(Ne),p(t).toggleClass(Ne).trigger(p.Event(Ae.SHOWN,n))}}}},t.show=function(){if(!(this._element.disabled||p(this._element).hasClass(Oe)||p(this._menu).hasClass(Ne))){var t={relatedTarget:this._element},e=p.Event(Ae.SHOW,t),n=c._getParentFromElement(this._element);p(n).trigger(e),e.isDefaultPrevented()||(p(this._menu).toggleClass(Ne),p(n).toggleClass(Ne).trigger(p.Event(Ae.SHOWN,t)))}},t.hide=function(){if(!this._element.disabled&&!p(this._element).hasClass(Oe)&&p(this._menu).hasClass(Ne)){var t={relatedTarget:this._element},e=p.Event(Ae.HIDE,t),n=c._getParentFromElement(this._element);p(n).trigger(e),e.isDefaultPrevented()||(p(this._menu).toggleClass(Ne),p(n).toggleClass(Ne).trigger(p.Event(Ae.HIDDEN,t)))}},t.dispose=function(){p.removeData(this._element,Ce),p(this._element).off(Te),this._element=null,(this._menu=null)!==this._popper&&(this._popper.destroy(),this._popper=null)},t.update=function(){this._inNavbar=this._detectNavbar(),null!==this._popper&&this._popper.scheduleUpdate()},t._addEventListeners=function(){var e=this;p(this._element).on(Ae.CLICK,function(t){t.preventDefault(),t.stopPropagation(),e.toggle()})},t._getConfig=function(t){return t=l({},this.constructor.Default,p(this._element).data(),t),m.typeCheckConfig(we,t,this.constructor.DefaultType),t},t._getMenuElement=function(){if(!this._menu){var t=c._getParentFromElement(this._element);t&&(this._menu=t.querySelector(Fe))}return this._menu},t._getPlacement=function(){var t=p(this._element.parentNode),e=qe;return t.hasClass(ke)?(e=Ue,p(this._menu).hasClass(Pe)&&(e=Be)):t.hasClass(Le)?e=Qe:t.hasClass(xe)?e=Ve:p(this._menu).hasClass(Pe)&&(e=Ke),e},t._detectNavbar=function(){return 0<p(this._element).closest(".navbar").length},t._getOffset=function(){var e=this,t={};return"function"==typeof this._config.offset?t.fn=function(t){return t.offsets=l({},t.offsets,e._config.offset(t.offsets,e._element)||{}),t}:t.offset=this._config.offset,t},t._getPopperConfig=function(){var t={placement:this._getPlacement(),modifiers:{offset:this._getOffset(),flip:{enabled:this._config.flip},preventOverflow:{boundariesElement:this._config.boundary}}};return"static"===this._config.display&&(t.modifiers.applyStyle={enabled:!1}),t},c._jQueryInterface=function(e){return this.each(function(){var t=p(this).data(Ce);if(t||(t=new c(this,"object"==typeof e?e:null),p(this).data(Ce,t)),"string"==typeof e){if("undefined"==typeof t[e])throw new TypeError('No method named "'+e+'"');t[e]()}})},c._clearMenus=function(t){if(!t||3!==t.which&&("keyup"!==t.type||9===t.which))for(var e=[].slice.call(document.querySelectorAll(je)),n=0,i=e.length;n<i;n++){var o=c._getParentFromElement(e[n]),r=p(e[n]).data(Ce),s={relatedTarget:e[n]};if(t&&"click"===t.type&&(s.clickEvent=t),r){var a=r._menu;if(p(o).hasClass(Ne)&&!(t&&("click"===t.type&&/input|textarea/i.test(t.target.tagName)||"keyup"===t.type&&9===t.which)&&p.contains(o,t.target))){var l=p.Event(Ae.HIDE,s);p(o).trigger(l),l.isDefaultPrevented()||("ontouchstart"in document.documentElement&&p(document.body).children().off("mouseover",null,p.noop),e[n].setAttribute("aria-expanded","false"),p(a).removeClass(Ne),p(o).removeClass(Ne).trigger(p.Event(Ae.HIDDEN,s)))}}}},c._getParentFromElement=function(t){var e,n=m.getSelectorFromElement(t);return n&&(e=document.querySelector(n)),e||t.parentNode},c._dataApiKeydownHandler=function(t){if((/input|textarea/i.test(t.target.tagName)?!(32===t.which||27!==t.which&&(40!==t.which&&38!==t.which||p(t.target).closest(Fe).length)):Ie.test(t.which))&&(t.preventDefault(),t.stopPropagation(),!this.disabled&&!p(this).hasClass(Oe))){var e=c._getParentFromElement(this),n=p(e).hasClass(Ne);if(n&&(!n||27!==t.which&&32!==t.which)){var i=[].slice.call(e.querySelectorAll(We));if(0!==i.length){var o=i.indexOf(t.target);38===t.which&&0<o&&o--,40===t.which&&o<i.length-1&&o++,o<0&&(o=0),i[o].focus()}}else{if(27===t.which){var r=e.querySelector(je);p(r).trigger("focus")}p(this).trigger("click")}}},s(c,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return Ye}},{key:"DefaultType",get:function(){return ze}}]),c}();p(document).on(Ae.KEYDOWN_DATA_API,je,Xe._dataApiKeydownHandler).on(Ae.KEYDOWN_DATA_API,Fe,Xe._dataApiKeydownHandler).on(Ae.CLICK_DATA_API+" "+Ae.KEYUP_DATA_API,Xe._clearMenus).on(Ae.CLICK_DATA_API,je,function(t){t.preventDefault(),t.stopPropagation(),Xe._jQueryInterface.call(p(this),"toggle")}).on(Ae.CLICK_DATA_API,Re,function(t){t.stopPropagation()}),p.fn[we]=Xe._jQueryInterface,p.fn[we].Constructor=Xe,p.fn[we].noConflict=function(){return p.fn[we]=De,Xe._jQueryInterface};var Ge="modal",$e="bs.modal",Je="."+$e,Ze=p.fn[Ge],tn={backdrop:!0,keyboard:!0,focus:!0,show:!0},en={backdrop:"(boolean|string)",keyboard:"boolean",focus:"boolean",show:"boolean"},nn={HIDE:"hide"+Je,HIDDEN:"hidden"+Je,SHOW:"show"+Je,SHOWN:"shown"+Je,FOCUSIN:"focusin"+Je,RESIZE:"resize"+Je,CLICK_DISMISS:"click.dismiss"+Je,KEYDOWN_DISMISS:"keydown.dismiss"+Je,MOUSEUP_DISMISS:"mouseup.dismiss"+Je,MOUSEDOWN_DISMISS:"mousedown.dismiss"+Je,CLICK_DATA_API:"click"+Je+".data-api"},on="modal-dialog-scrollable",rn="modal-scrollbar-measure",sn="modal-backdrop",an="modal-open",ln="fade",cn="show",hn=".modal-dialog",un=".modal-body",fn='[data-toggle="modal"]',dn='[data-dismiss="modal"]',pn=".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",mn=".sticky-top",gn=function(){function o(t,e){this._config=this._getConfig(e),this._element=t,this._dialog=t.querySelector(hn),this._backdrop=null,this._isShown=!1,this._isBodyOverflowing=!1,this._ignoreBackdropClick=!1,this._isTransitioning=!1,this._scrollbarWidth=0}var t=o.prototype;return t.toggle=function(t){return this._isShown?this.hide():this.show(t)},t.show=function(t){var e=this;if(!this._isShown&&!this._isTransitioning){p(this._element).hasClass(ln)&&(this._isTransitioning=!0);var n=p.Event(nn.SHOW,{relatedTarget:t});p(this._element).trigger(n),this._isShown||n.isDefaultPrevented()||(this._isShown=!0,this._checkScrollbar(),this._setScrollbar(),this._adjustDialog(),this._setEscapeEvent(),this._setResizeEvent(),p(this._element).on(nn.CLICK_DISMISS,dn,function(t){return e.hide(t)}),p(this._dialog).on(nn.MOUSEDOWN_DISMISS,function(){p(e._element).one(nn.MOUSEUP_DISMISS,function(t){p(t.target).is(e._element)&&(e._ignoreBackdropClick=!0)})}),this._showBackdrop(function(){return e._showElement(t)}))}},t.hide=function(t){var e=this;if(t&&t.preventDefault(),this._isShown&&!this._isTransitioning){var n=p.Event(nn.HIDE);if(p(this._element).trigger(n),this._isShown&&!n.isDefaultPrevented()){this._isShown=!1;var i=p(this._element).hasClass(ln);if(i&&(this._isTransitioning=!0),this._setEscapeEvent(),this._setResizeEvent(),p(document).off(nn.FOCUSIN),p(this._element).removeClass(cn),p(this._element).off(nn.CLICK_DISMISS),p(this._dialog).off(nn.MOUSEDOWN_DISMISS),i){var o=m.getTransitionDurationFromElement(this._element);p(this._element).one(m.TRANSITION_END,function(t){return e._hideModal(t)}).emulateTransitionEnd(o)}else this._hideModal()}}},t.dispose=function(){[window,this._element,this._dialog].forEach(function(t){return p(t).off(Je)}),p(document).off(nn.FOCUSIN),p.removeData(this._element,$e),this._config=null,this._element=null,this._dialog=null,this._backdrop=null,this._isShown=null,this._isBodyOverflowing=null,this._ignoreBackdropClick=null,this._isTransitioning=null,this._scrollbarWidth=null},t.handleUpdate=function(){this._adjustDialog()},t._getConfig=function(t){return t=l({},tn,t),m.typeCheckConfig(Ge,t,en),t},t._showElement=function(t){var e=this,n=p(this._element).hasClass(ln);this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE||document.body.appendChild(this._element),this._element.style.display="block",this._element.removeAttribute("aria-hidden"),this._element.setAttribute("aria-modal",!0),p(this._dialog).hasClass(on)?this._dialog.querySelector(un).scrollTop=0:this._element.scrollTop=0,n&&m.reflow(this._element),p(this._element).addClass(cn),this._config.focus&&this._enforceFocus();var i=p.Event(nn.SHOWN,{relatedTarget:t}),o=function(){e._config.focus&&e._element.focus(),e._isTransitioning=!1,p(e._element).trigger(i)};if(n){var r=m.getTransitionDurationFromElement(this._dialog);p(this._dialog).one(m.TRANSITION_END,o).emulateTransitionEnd(r)}else o()},t._enforceFocus=function(){var e=this;p(document).off(nn.FOCUSIN).on(nn.FOCUSIN,function(t){document!==t.target&&e._element!==t.target&&0===p(e._element).has(t.target).length&&e._element.focus()})},t._setEscapeEvent=function(){var e=this;this._isShown&&this._config.keyboard?p(this._element).on(nn.KEYDOWN_DISMISS,function(t){27===t.which&&(t.preventDefault(),e.hide())}):this._isShown||p(this._element).off(nn.KEYDOWN_DISMISS)},t._setResizeEvent=function(){var e=this;this._isShown?p(window).on(nn.RESIZE,function(t){return e.handleUpdate(t)}):p(window).off(nn.RESIZE)},t._hideModal=function(){var t=this;this._element.style.display="none",this._element.setAttribute("aria-hidden",!0),this._element.removeAttribute("aria-modal"),this._isTransitioning=!1,this._showBackdrop(function(){p(document.body).removeClass(an),t._resetAdjustments(),t._resetScrollbar(),p(t._element).trigger(nn.HIDDEN)})},t._removeBackdrop=function(){this._backdrop&&(p(this._backdrop).remove(),this._backdrop=null)},t._showBackdrop=function(t){var e=this,n=p(this._element).hasClass(ln)?ln:"";if(this._isShown&&this._config.backdrop){if(this._backdrop=document.createElement("div"),this._backdrop.className=sn,n&&this._backdrop.classList.add(n),p(this._backdrop).appendTo(document.body),p(this._element).on(nn.CLICK_DISMISS,function(t){e._ignoreBackdropClick?e._ignoreBackdropClick=!1:t.target===t.currentTarget&&("static"===e._config.backdrop?e._element.focus():e.hide())}),n&&m.reflow(this._backdrop),p(this._backdrop).addClass(cn),!t)return;if(!n)return void t();var i=m.getTransitionDurationFromElement(this._backdrop);p(this._backdrop).one(m.TRANSITION_END,t).emulateTransitionEnd(i)}else if(!this._isShown&&this._backdrop){p(this._backdrop).removeClass(cn);var o=function(){e._removeBackdrop(),t&&t()};if(p(this._element).hasClass(ln)){var r=m.getTransitionDurationFromElement(this._backdrop);p(this._backdrop).one(m.TRANSITION_END,o).emulateTransitionEnd(r)}else o()}else t&&t()},t._adjustDialog=function(){var t=this._element.scrollHeight>document.documentElement.clientHeight;!this._isBodyOverflowing&&t&&(this._element.style.paddingLeft=this._scrollbarWidth+"px"),this._isBodyOverflowing&&!t&&(this._element.style.paddingRight=this._scrollbarWidth+"px")},t._resetAdjustments=function(){this._element.style.paddingLeft="",this._element.style.paddingRight=""},t._checkScrollbar=function(){var t=document.body.getBoundingClientRect();this._isBodyOverflowing=t.left+t.right<window.innerWidth,this._scrollbarWidth=this._getScrollbarWidth()},t._setScrollbar=function(){var o=this;if(this._isBodyOverflowing){var t=[].slice.call(document.querySelectorAll(pn)),e=[].slice.call(document.querySelectorAll(mn));p(t).each(function(t,e){var n=e.style.paddingRight,i=p(e).css("padding-right");p(e).data("padding-right",n).css("padding-right",parseFloat(i)+o._scrollbarWidth+"px")}),p(e).each(function(t,e){var n=e.style.marginRight,i=p(e).css("margin-right");p(e).data("margin-right",n).css("margin-right",parseFloat(i)-o._scrollbarWidth+"px")});var n=document.body.style.paddingRight,i=p(document.body).css("padding-right");p(document.body).data("padding-right",n).css("padding-right",parseFloat(i)+this._scrollbarWidth+"px")}p(document.body).addClass(an)},t._resetScrollbar=function(){var t=[].slice.call(document.querySelectorAll(pn));p(t).each(function(t,e){var n=p(e).data("padding-right");p(e).removeData("padding-right"),e.style.paddingRight=n||""});var e=[].slice.call(document.querySelectorAll(""+mn));p(e).each(function(t,e){var n=p(e).data("margin-right");"undefined"!=typeof n&&p(e).css("margin-right",n).removeData("margin-right")});var n=p(document.body).data("padding-right");p(document.body).removeData("padding-right"),document.body.style.paddingRight=n||""},t._getScrollbarWidth=function(){var t=document.createElement("div");t.className=rn,document.body.appendChild(t);var e=t.getBoundingClientRect().width-t.clientWidth;return document.body.removeChild(t),e},o._jQueryInterface=function(n,i){return this.each(function(){var t=p(this).data($e),e=l({},tn,p(this).data(),"object"==typeof n&&n?n:{});if(t||(t=new o(this,e),p(this).data($e,t)),"string"==typeof n){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n](i)}else e.show&&t.show(i)})},s(o,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return tn}}]),o}();p(document).on(nn.CLICK_DATA_API,fn,function(t){var e,n=this,i=m.getSelectorFromElement(this);i&&(e=document.querySelector(i));var o=p(e).data($e)?"toggle":l({},p(e).data(),p(this).data());"A"!==this.tagName&&"AREA"!==this.tagName||t.preventDefault();var r=p(e).one(nn.SHOW,function(t){t.isDefaultPrevented()||r.one(nn.HIDDEN,function(){p(n).is(":visible")&&n.focus()})});gn._jQueryInterface.call(p(e),o,this)}),p.fn[Ge]=gn._jQueryInterface,p.fn[Ge].Constructor=gn,p.fn[Ge].noConflict=function(){return p.fn[Ge]=Ze,gn._jQueryInterface};var _n=["background","cite","href","itemtype","longdesc","poster","src","xlink:href"],vn={"*":["class","dir","id","lang","role",/^aria-[\w-]*$/i],a:["target","href","title","rel"],area:[],b:[],br:[],col:[],code:[],div:[],em:[],hr:[],h1:[],h2:[],h3:[],h4:[],h5:[],h6:[],i:[],img:["src","alt","title","width","height"],li:[],ol:[],p:[],pre:[],s:[],small:[],span:[],sub:[],sup:[],strong:[],u:[],ul:[]},yn=/^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi,En=/^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i;function bn(t,s,e){if(0===t.length)return t;if(e&&"function"==typeof e)return e(t);for(var n=(new window.DOMParser).parseFromString(t,"text/html"),a=Object.keys(s),l=[].slice.call(n.body.querySelectorAll("*")),i=function(t,e){var n=l[t],i=n.nodeName.toLowerCase();if(-1===a.indexOf(n.nodeName.toLowerCase()))return n.parentNode.removeChild(n),"continue";var o=[].slice.call(n.attributes),r=[].concat(s["*"]||[],s[i]||[]);o.forEach(function(t){(function(t,e){var n=t.nodeName.toLowerCase();if(-1!==e.indexOf(n))return-1===_n.indexOf(n)||Boolean(t.nodeValue.match(yn)||t.nodeValue.match(En));for(var i=e.filter(function(t){return t instanceof RegExp}),o=0,r=i.length;o<r;o++)if(n.match(i[o]))return!0;return!1})(t,r)||n.removeAttribute(t.nodeName)})},o=0,r=l.length;o<r;o++)i(o);return n.body.innerHTML}var wn="tooltip",Cn="bs.tooltip",Tn="."+Cn,Sn=p.fn[wn],Dn="bs-tooltip",In=new RegExp("(^|\\s)"+Dn+"\\S+","g"),An=["sanitize","whiteList","sanitizeFn"],On={animation:"boolean",template:"string",title:"(string|element|function)",trigger:"string",delay:"(number|object)",html:"boolean",selector:"(string|boolean)",placement:"(string|function)",offset:"(number|string|function)",container:"(string|element|boolean)",fallbackPlacement:"(string|array)",boundary:"(string|element)",sanitize:"boolean",sanitizeFn:"(null|function)",whiteList:"object"},Nn={AUTO:"auto",TOP:"top",RIGHT:"right",BOTTOM:"bottom",LEFT:"left"},kn={animation:!0,template:'<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,selector:!1,placement:"top",offset:0,container:!1,fallbackPlacement:"flip",boundary:"scrollParent",sanitize:!0,sanitizeFn:null,whiteList:vn},Ln="show",xn="out",Pn={HIDE:"hide"+Tn,HIDDEN:"hidden"+Tn,SHOW:"show"+Tn,SHOWN:"shown"+Tn,INSERTED:"inserted"+Tn,CLICK:"click"+Tn,FOCUSIN:"focusin"+Tn,FOCUSOUT:"focusout"+Tn,MOUSEENTER:"mouseenter"+Tn,MOUSELEAVE:"mouseleave"+Tn},Hn="fade",jn="show",Rn=".tooltip-inner",Fn=".arrow",Mn="hover",Wn="focus",Un="click",Bn="manual",qn=function(){function i(t,e){if("undefined"==typeof be)throw new TypeError("Bootstrap's tooltips require Popper.js (https://popper.js.org/)");this._isEnabled=!0,this._timeout=0,this._hoverState="",this._activeTrigger={},this._popper=null,this.element=t,this.config=this._getConfig(e),this.tip=null,this._setListeners()}var t=i.prototype;return t.enable=function(){this._isEnabled=!0},t.disable=function(){this._isEnabled=!1},t.toggleEnabled=function(){this._isEnabled=!this._isEnabled},t.toggle=function(t){if(this._isEnabled)if(t){var e=this.constructor.DATA_KEY,n=p(t.currentTarget).data(e);n||(n=new this.constructor(t.currentTarget,this._getDelegateConfig()),p(t.currentTarget).data(e,n)),n._activeTrigger.click=!n._activeTrigger.click,n._isWithActiveTrigger()?n._enter(null,n):n._leave(null,n)}else{if(p(this.getTipElement()).hasClass(jn))return void this._leave(null,this);this._enter(null,this)}},t.dispose=function(){clearTimeout(this._timeout),p.removeData(this.element,this.constructor.DATA_KEY),p(this.element).off(this.constructor.EVENT_KEY),p(this.element).closest(".modal").off("hide.bs.modal"),this.tip&&p(this.tip).remove(),this._isEnabled=null,this._timeout=null,this._hoverState=null,(this._activeTrigger=null)!==this._popper&&this._popper.destroy(),this._popper=null,this.element=null,this.config=null,this.tip=null},t.show=function(){var e=this;if("none"===p(this.element).css("display"))throw new Error("Please use show on visible elements");var t=p.Event(this.constructor.Event.SHOW);if(this.isWithContent()&&this._isEnabled){p(this.element).trigger(t);var n=m.findShadowRoot(this.element),i=p.contains(null!==n?n:this.element.ownerDocument.documentElement,this.element);if(t.isDefaultPrevented()||!i)return;var o=this.getTipElement(),r=m.getUID(this.constructor.NAME);o.setAttribute("id",r),this.element.setAttribute("aria-describedby",r),this.setContent(),this.config.animation&&p(o).addClass(Hn);var s="function"==typeof this.config.placement?this.config.placement.call(this,o,this.element):this.config.placement,a=this._getAttachment(s);this.addAttachmentClass(a);var l=this._getContainer();p(o).data(this.constructor.DATA_KEY,this),p.contains(this.element.ownerDocument.documentElement,this.tip)||p(o).appendTo(l),p(this.element).trigger(this.constructor.Event.INSERTED),this._popper=new be(this.element,o,{placement:a,modifiers:{offset:this._getOffset(),flip:{behavior:this.config.fallbackPlacement},arrow:{element:Fn},preventOverflow:{boundariesElement:this.config.boundary}},onCreate:function(t){t.originalPlacement!==t.placement&&e._handlePopperPlacementChange(t)},onUpdate:function(t){return e._handlePopperPlacementChange(t)}}),p(o).addClass(jn),"ontouchstart"in document.documentElement&&p(document.body).children().on("mouseover",null,p.noop);var c=function(){e.config.animation&&e._fixTransition();var t=e._hoverState;e._hoverState=null,p(e.element).trigger(e.constructor.Event.SHOWN),t===xn&&e._leave(null,e)};if(p(this.tip).hasClass(Hn)){var h=m.getTransitionDurationFromElement(this.tip);p(this.tip).one(m.TRANSITION_END,c).emulateTransitionEnd(h)}else c()}},t.hide=function(t){var e=this,n=this.getTipElement(),i=p.Event(this.constructor.Event.HIDE),o=function(){e._hoverState!==Ln&&n.parentNode&&n.parentNode.removeChild(n),e._cleanTipClass(),e.element.removeAttribute("aria-describedby"),p(e.element).trigger(e.constructor.Event.HIDDEN),null!==e._popper&&e._popper.destroy(),t&&t()};if(p(this.element).trigger(i),!i.isDefaultPrevented()){if(p(n).removeClass(jn),"ontouchstart"in document.documentElement&&p(document.body).children().off("mouseover",null,p.noop),this._activeTrigger[Un]=!1,this._activeTrigger[Wn]=!1,this._activeTrigger[Mn]=!1,p(this.tip).hasClass(Hn)){var r=m.getTransitionDurationFromElement(n);p(n).one(m.TRANSITION_END,o).emulateTransitionEnd(r)}else o();this._hoverState=""}},t.update=function(){null!==this._popper&&this._popper.scheduleUpdate()},t.isWithContent=function(){return Boolean(this.getTitle())},t.addAttachmentClass=function(t){p(this.getTipElement()).addClass(Dn+"-"+t)},t.getTipElement=function(){return this.tip=this.tip||p(this.config.template)[0],this.tip},t.setContent=function(){var t=this.getTipElement();this.setElementContent(p(t.querySelectorAll(Rn)),this.getTitle()),p(t).removeClass(Hn+" "+jn)},t.setElementContent=function(t,e){"object"!=typeof e||!e.nodeType&&!e.jquery?this.config.html?(this.config.sanitize&&(e=bn(e,this.config.whiteList,this.config.sanitizeFn)),t.html(e)):t.text(e):this.config.html?p(e).parent().is(t)||t.empty().append(e):t.text(p(e).text())},t.getTitle=function(){var t=this.element.getAttribute("data-original-title");return t||(t="function"==typeof this.config.title?this.config.title.call(this.element):this.config.title),t},t._getOffset=function(){var e=this,t={};return"function"==typeof this.config.offset?t.fn=function(t){return t.offsets=l({},t.offsets,e.config.offset(t.offsets,e.element)||{}),t}:t.offset=this.config.offset,t},t._getContainer=function(){return!1===this.config.container?document.body:m.isElement(this.config.container)?p(this.config.container):p(document).find(this.config.container)},t._getAttachment=function(t){return Nn[t.toUpperCase()]},t._setListeners=function(){var i=this;this.config.trigger.split(" ").forEach(function(t){if("click"===t)p(i.element).on(i.constructor.Event.CLICK,i.config.selector,function(t){return i.toggle(t)});else if(t!==Bn){var e=t===Mn?i.constructor.Event.MOUSEENTER:i.constructor.Event.FOCUSIN,n=t===Mn?i.constructor.Event.MOUSELEAVE:i.constructor.Event.FOCUSOUT;p(i.element).on(e,i.config.selector,function(t){return i._enter(t)}).on(n,i.config.selector,function(t){return i._leave(t)})}}),p(this.element).closest(".modal").on("hide.bs.modal",function(){i.element&&i.hide()}),this.config.selector?this.config=l({},this.config,{trigger:"manual",selector:""}):this._fixTitle()},t._fixTitle=function(){var t=typeof this.element.getAttribute("data-original-title");(this.element.getAttribute("title")||"string"!==t)&&(this.element.setAttribute("data-original-title",this.element.getAttribute("title")||""),this.element.setAttribute("title",""))},t._enter=function(t,e){var n=this.constructor.DATA_KEY;(e=e||p(t.currentTarget).data(n))||(e=new this.constructor(t.currentTarget,this._getDelegateConfig()),p(t.currentTarget).data(n,e)),t&&(e._activeTrigger["focusin"===t.type?Wn:Mn]=!0),p(e.getTipElement()).hasClass(jn)||e._hoverState===Ln?e._hoverState=Ln:(clearTimeout(e._timeout),e._hoverState=Ln,e.config.delay&&e.config.delay.show?e._timeout=setTimeout(function(){e._hoverState===Ln&&e.show()},e.config.delay.show):e.show())},t._leave=function(t,e){var n=this.constructor.DATA_KEY;(e=e||p(t.currentTarget).data(n))||(e=new this.constructor(t.currentTarget,this._getDelegateConfig()),p(t.currentTarget).data(n,e)),t&&(e._activeTrigger["focusout"===t.type?Wn:Mn]=!1),e._isWithActiveTrigger()||(clearTimeout(e._timeout),e._hoverState=xn,e.config.delay&&e.config.delay.hide?e._timeout=setTimeout(function(){e._hoverState===xn&&e.hide()},e.config.delay.hide):e.hide())},t._isWithActiveTrigger=function(){for(var t in this._activeTrigger)if(this._activeTrigger[t])return!0;return!1},t._getConfig=function(t){var e=p(this.element).data();return Object.keys(e).forEach(function(t){-1!==An.indexOf(t)&&delete e[t]}),"number"==typeof(t=l({},this.constructor.Default,e,"object"==typeof t&&t?t:{})).delay&&(t.delay={show:t.delay,hide:t.delay}),"number"==typeof t.title&&(t.title=t.title.toString()),"number"==typeof t.content&&(t.content=t.content.toString()),m.typeCheckConfig(wn,t,this.constructor.DefaultType),t.sanitize&&(t.template=bn(t.template,t.whiteList,t.sanitizeFn)),t},t._getDelegateConfig=function(){var t={};if(this.config)for(var e in this.config)this.constructor.Default[e]!==this.config[e]&&(t[e]=this.config[e]);return t},t._cleanTipClass=function(){var t=p(this.getTipElement()),e=t.attr("class").match(In);null!==e&&e.length&&t.removeClass(e.join(""))},t._handlePopperPlacementChange=function(t){var e=t.instance;this.tip=e.popper,this._cleanTipClass(),this.addAttachmentClass(this._getAttachment(t.placement))},t._fixTransition=function(){var t=this.getTipElement(),e=this.config.animation;null===t.getAttribute("x-placement")&&(p(t).removeClass(Hn),this.config.animation=!1,this.hide(),this.show(),this.config.animation=e)},i._jQueryInterface=function(n){return this.each(function(){var t=p(this).data(Cn),e="object"==typeof n&&n;if((t||!/dispose|hide/.test(n))&&(t||(t=new i(this,e),p(this).data(Cn,t)),"string"==typeof n)){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}})},s(i,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return kn}},{key:"NAME",get:function(){return wn}},{key:"DATA_KEY",get:function(){return Cn}},{key:"Event",get:function(){return Pn}},{key:"EVENT_KEY",get:function(){return Tn}},{key:"DefaultType",get:function(){return On}}]),i}();p.fn[wn]=qn._jQueryInterface,p.fn[wn].Constructor=qn,p.fn[wn].noConflict=function(){return p.fn[wn]=Sn,qn._jQueryInterface};var Kn="popover",Qn="bs.popover",Vn="."+Qn,Yn=p.fn[Kn],zn="bs-popover",Xn=new RegExp("(^|\\s)"+zn+"\\S+","g"),Gn=l({},qn.Default,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'}),$n=l({},qn.DefaultType,{content:"(string|element|function)"}),Jn="fade",Zn="show",ti=".popover-header",ei=".popover-body",ni={HIDE:"hide"+Vn,HIDDEN:"hidden"+Vn,SHOW:"show"+Vn,SHOWN:"shown"+Vn,INSERTED:"inserted"+Vn,CLICK:"click"+Vn,FOCUSIN:"focusin"+Vn,FOCUSOUT:"focusout"+Vn,MOUSEENTER:"mouseenter"+Vn,MOUSELEAVE:"mouseleave"+Vn},ii=function(t){var e,n;function i(){return t.apply(this,arguments)||this}n=t,(e=i).prototype=Object.create(n.prototype),(e.prototype.constructor=e).__proto__=n;var o=i.prototype;return o.isWithContent=function(){return this.getTitle()||this._getContent()},o.addAttachmentClass=function(t){p(this.getTipElement()).addClass(zn+"-"+t)},o.getTipElement=function(){return this.tip=this.tip||p(this.config.template)[0],this.tip},o.setContent=function(){var t=p(this.getTipElement());this.setElementContent(t.find(ti),this.getTitle());var e=this._getContent();"function"==typeof e&&(e=e.call(this.element)),this.setElementContent(t.find(ei),e),t.removeClass(Jn+" "+Zn)},o._getContent=function(){return this.element.getAttribute("data-content")||this.config.content},o._cleanTipClass=function(){var t=p(this.getTipElement()),e=t.attr("class").match(Xn);null!==e&&0<e.length&&t.removeClass(e.join(""))},i._jQueryInterface=function(n){return this.each(function(){var t=p(this).data(Qn),e="object"==typeof n?n:null;if((t||!/dispose|hide/.test(n))&&(t||(t=new i(this,e),p(this).data(Qn,t)),"string"==typeof n)){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}})},s(i,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return Gn}},{key:"NAME",get:function(){return Kn}},{key:"DATA_KEY",get:function(){return Qn}},{key:"Event",get:function(){return ni}},{key:"EVENT_KEY",get:function(){return Vn}},{key:"DefaultType",get:function(){return $n}}]),i}(qn);p.fn[Kn]=ii._jQueryInterface,p.fn[Kn].Constructor=ii,p.fn[Kn].noConflict=function(){return p.fn[Kn]=Yn,ii._jQueryInterface};var oi="scrollspy",ri="bs.scrollspy",si="."+ri,ai=p.fn[oi],li={offset:10,method:"auto",target:""},ci={offset:"number",method:"string",target:"(string|element)"},hi={ACTIVATE:"activate"+si,SCROLL:"scroll"+si,LOAD_DATA_API:"load"+si+".data-api"},ui="dropdown-item",fi="active",di='[data-spy="scroll"]',pi=".nav, .list-group",mi=".nav-link",gi=".nav-item",_i=".list-group-item",vi=".dropdown",yi=".dropdown-item",Ei=".dropdown-toggle",bi="offset",wi="position",Ci=function(){function n(t,e){var n=this;this._element=t,this._scrollElement="BODY"===t.tagName?window:t,this._config=this._getConfig(e),this._selector=this._config.target+" "+mi+","+this._config.target+" "+_i+","+this._config.target+" "+yi,this._offsets=[],this._targets=[],this._activeTarget=null,this._scrollHeight=0,p(this._scrollElement).on(hi.SCROLL,function(t){return n._process(t)}),this.refresh(),this._process()}var t=n.prototype;return t.refresh=function(){var e=this,t=this._scrollElement===this._scrollElement.window?bi:wi,o="auto"===this._config.method?t:this._config.method,r=o===wi?this._getScrollTop():0;this._offsets=[],this._targets=[],this._scrollHeight=this._getScrollHeight(),[].slice.call(document.querySelectorAll(this._selector)).map(function(t){var e,n=m.getSelectorFromElement(t);if(n&&(e=document.querySelector(n)),e){var i=e.getBoundingClientRect();if(i.width||i.height)return[p(e)[o]().top+r,n]}return null}).filter(function(t){return t}).sort(function(t,e){return t[0]-e[0]}).forEach(function(t){e._offsets.push(t[0]),e._targets.push(t[1])})},t.dispose=function(){p.removeData(this._element,ri),p(this._scrollElement).off(si),this._element=null,this._scrollElement=null,this._config=null,this._selector=null,this._offsets=null,this._targets=null,this._activeTarget=null,this._scrollHeight=null},t._getConfig=function(t){if("string"!=typeof(t=l({},li,"object"==typeof t&&t?t:{})).target){var e=p(t.target).attr("id");e||(e=m.getUID(oi),p(t.target).attr("id",e)),t.target="#"+e}return m.typeCheckConfig(oi,t,ci),t},t._getScrollTop=function(){return this._scrollElement===window?this._scrollElement.pageYOffset:this._scrollElement.scrollTop},t._getScrollHeight=function(){return this._scrollElement.scrollHeight||Math.max(document.body.scrollHeight,document.documentElement.scrollHeight)},t._getOffsetHeight=function(){return this._scrollElement===window?window.innerHeight:this._scrollElement.getBoundingClientRect().height},t._process=function(){var t=this._getScrollTop()+this._config.offset,e=this._getScrollHeight(),n=this._config.offset+e-this._getOffsetHeight();if(this._scrollHeight!==e&&this.refresh(),n<=t){var i=this._targets[this._targets.length-1];this._activeTarget!==i&&this._activate(i)}else{if(this._activeTarget&&t<this._offsets[0]&&0<this._offsets[0])return this._activeTarget=null,void this._clear();for(var o=this._offsets.length;o--;){this._activeTarget!==this._targets[o]&&t>=this._offsets[o]&&("undefined"==typeof this._offsets[o+1]||t<this._offsets[o+1])&&this._activate(this._targets[o])}}},t._activate=function(e){this._activeTarget=e,this._clear();var t=this._selector.split(",").map(function(t){return t+'[data-target="'+e+'"],'+t+'[href="'+e+'"]'}),n=p([].slice.call(document.querySelectorAll(t.join(","))));n.hasClass(ui)?(n.closest(vi).find(Ei).addClass(fi),n.addClass(fi)):(n.addClass(fi),n.parents(pi).prev(mi+", "+_i).addClass(fi),n.parents(pi).prev(gi).children(mi).addClass(fi)),p(this._scrollElement).trigger(hi.ACTIVATE,{relatedTarget:e})},t._clear=function(){[].slice.call(document.querySelectorAll(this._selector)).filter(function(t){return t.classList.contains(fi)}).forEach(function(t){return t.classList.remove(fi)})},n._jQueryInterface=function(e){return this.each(function(){var t=p(this).data(ri);if(t||(t=new n(this,"object"==typeof e&&e),p(this).data(ri,t)),"string"==typeof e){if("undefined"==typeof t[e])throw new TypeError('No method named "'+e+'"');t[e]()}})},s(n,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"Default",get:function(){return li}}]),n}();p(window).on(hi.LOAD_DATA_API,function(){for(var t=[].slice.call(document.querySelectorAll(di)),e=t.length;e--;){var n=p(t[e]);Ci._jQueryInterface.call(n,n.data())}}),p.fn[oi]=Ci._jQueryInterface,p.fn[oi].Constructor=Ci,p.fn[oi].noConflict=function(){return p.fn[oi]=ai,Ci._jQueryInterface};var Ti="bs.tab",Si="."+Ti,Di=p.fn.tab,Ii={HIDE:"hide"+Si,HIDDEN:"hidden"+Si,SHOW:"show"+Si,SHOWN:"shown"+Si,CLICK_DATA_API:"click"+Si+".data-api"},Ai="dropdown-menu",Oi="active",Ni="disabled",ki="fade",Li="show",xi=".dropdown",Pi=".nav, .list-group",Hi=".active",ji="> li > .active",Ri='[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',Fi=".dropdown-toggle",Mi="> .dropdown-menu .active",Wi=function(){function i(t){this._element=t}var t=i.prototype;return t.show=function(){var n=this;if(!(this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE&&p(this._element).hasClass(Oi)||p(this._element).hasClass(Ni))){var t,i,e=p(this._element).closest(Pi)[0],o=m.getSelectorFromElement(this._element);if(e){var r="UL"===e.nodeName||"OL"===e.nodeName?ji:Hi;i=(i=p.makeArray(p(e).find(r)))[i.length-1]}var s=p.Event(Ii.HIDE,{relatedTarget:this._element}),a=p.Event(Ii.SHOW,{relatedTarget:i});if(i&&p(i).trigger(s),p(this._element).trigger(a),!a.isDefaultPrevented()&&!s.isDefaultPrevented()){o&&(t=document.querySelector(o)),this._activate(this._element,e);var l=function(){var t=p.Event(Ii.HIDDEN,{relatedTarget:n._element}),e=p.Event(Ii.SHOWN,{relatedTarget:i});p(i).trigger(t),p(n._element).trigger(e)};t?this._activate(t,t.parentNode,l):l()}}},t.dispose=function(){p.removeData(this._element,Ti),this._element=null},t._activate=function(t,e,n){var i=this,o=(!e||"UL"!==e.nodeName&&"OL"!==e.nodeName?p(e).children(Hi):p(e).find(ji))[0],r=n&&o&&p(o).hasClass(ki),s=function(){return i._transitionComplete(t,o,n)};if(o&&r){var a=m.getTransitionDurationFromElement(o);p(o).removeClass(Li).one(m.TRANSITION_END,s).emulateTransitionEnd(a)}else s()},t._transitionComplete=function(t,e,n){if(e){p(e).removeClass(Oi);var i=p(e.parentNode).find(Mi)[0];i&&p(i).removeClass(Oi),"tab"===e.getAttribute("role")&&e.setAttribute("aria-selected",!1)}if(p(t).addClass(Oi),"tab"===t.getAttribute("role")&&t.setAttribute("aria-selected",!0),m.reflow(t),t.classList.contains(ki)&&t.classList.add(Li),t.parentNode&&p(t.parentNode).hasClass(Ai)){var o=p(t).closest(xi)[0];if(o){var r=[].slice.call(o.querySelectorAll(Fi));p(r).addClass(Oi)}t.setAttribute("aria-expanded",!0)}n&&n()},i._jQueryInterface=function(n){return this.each(function(){var t=p(this),e=t.data(Ti);if(e||(e=new i(this),t.data(Ti,e)),"string"==typeof n){if("undefined"==typeof e[n])throw new TypeError('No method named "'+n+'"');e[n]()}})},s(i,null,[{key:"VERSION",get:function(){return"4.3.1"}}]),i}();p(document).on(Ii.CLICK_DATA_API,Ri,function(t){t.preventDefault(),Wi._jQueryInterface.call(p(this),"show")}),p.fn.tab=Wi._jQueryInterface,p.fn.tab.Constructor=Wi,p.fn.tab.noConflict=function(){return p.fn.tab=Di,Wi._jQueryInterface};var Ui="toast",Bi="bs.toast",qi="."+Bi,Ki=p.fn[Ui],Qi={CLICK_DISMISS:"click.dismiss"+qi,HIDE:"hide"+qi,HIDDEN:"hidden"+qi,SHOW:"show"+qi,SHOWN:"shown"+qi},Vi="fade",Yi="hide",zi="show",Xi="showing",Gi={animation:"boolean",autohide:"boolean",delay:"number"},$i={animation:!0,autohide:!0,delay:500},Ji='[data-dismiss="toast"]',Zi=function(){function i(t,e){this._element=t,this._config=this._getConfig(e),this._timeout=null,this._setListeners()}var t=i.prototype;return t.show=function(){var t=this;p(this._element).trigger(Qi.SHOW),this._config.animation&&this._element.classList.add(Vi);var e=function(){t._element.classList.remove(Xi),t._element.classList.add(zi),p(t._element).trigger(Qi.SHOWN),t._config.autohide&&t.hide()};if(this._element.classList.remove(Yi),this._element.classList.add(Xi),this._config.animation){var n=m.getTransitionDurationFromElement(this._element);p(this._element).one(m.TRANSITION_END,e).emulateTransitionEnd(n)}else e()},t.hide=function(t){var e=this;this._element.classList.contains(zi)&&(p(this._element).trigger(Qi.HIDE),t?this._close():this._timeout=setTimeout(function(){e._close()},this._config.delay))},t.dispose=function(){clearTimeout(this._timeout),this._timeout=null,this._element.classList.contains(zi)&&this._element.classList.remove(zi),p(this._element).off(Qi.CLICK_DISMISS),p.removeData(this._element,Bi),this._element=null,this._config=null},t._getConfig=function(t){return t=l({},$i,p(this._element).data(),"object"==typeof t&&t?t:{}),m.typeCheckConfig(Ui,t,this.constructor.DefaultType),t},t._setListeners=function(){var t=this;p(this._element).on(Qi.CLICK_DISMISS,Ji,function(){return t.hide(!0)})},t._close=function(){var t=this,e=function(){t._element.classList.add(Yi),p(t._element).trigger(Qi.HIDDEN)};if(this._element.classList.remove(zi),this._config.animation){var n=m.getTransitionDurationFromElement(this._element);p(this._element).one(m.TRANSITION_END,e).emulateTransitionEnd(n)}else e()},i._jQueryInterface=function(n){return this.each(function(){var t=p(this),e=t.data(Bi);if(e||(e=new i(this,"object"==typeof n&&n),t.data(Bi,e)),"string"==typeof n){if("undefined"==typeof e[n])throw new TypeError('No method named "'+n+'"');e[n](this)}})},s(i,null,[{key:"VERSION",get:function(){return"4.3.1"}},{key:"DefaultType",get:function(){return Gi}},{key:"Default",get:function(){return $i}}]),i}();p.fn[Ui]=Zi._jQueryInterface,p.fn[Ui].Constructor=Zi,p.fn[Ui].noConflict=function(){return p.fn[Ui]=Ki,Zi._jQueryInterface},function(){if("undefined"==typeof p)throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");var t=p.fn.jquery.split(" ")[0].split(".");if(t[0]<2&&t[1]<9||1===t[0]&&9===t[1]&&t[2]<1||4<=t[0])throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")}(),t.Util=m,t.Alert=g,t.Button=k,t.Carousel=at,t.Collapse=Ct,t.Dropdown=Xe,t.Modal=gn,t.Popover=ii,t.Scrollspy=Ci,t.Tab=Wi,t.Toast=Zi,t.Tooltip=qn,Object.defineProperty(t,"__esModule",{value:!0})});
//# sourceMappingURL=bootstrap.bundle.min.js.map
/**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN
 */
!function(a,b,c,d){function e(b,c){this.settings=null,this.options=a.extend({},e.Defaults,c),this.$element=a(b),this._handlers={},this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._widths=[],this._invalidated={},this._pipe=[],this._drag={time:null,target:null,pointer:null,stage:{start:null,current:null},direction:null},this._states={current:{},tags:{initializing:["busy"],animating:["busy"],dragging:["interacting"]}},a.each(["onResize","onThrottledResize"],a.proxy(function(b,c){this._handlers[c]=a.proxy(this[c],this)},this)),a.each(e.Plugins,a.proxy(function(a,b){this._plugins[a.charAt(0).toLowerCase()+a.slice(1)]=new b(this)},this)),a.each(e.Workers,a.proxy(function(b,c){this._pipe.push({filter:c.filter,run:a.proxy(c.run,this)})},this)),this.setup(),this.initialize()}e.Defaults={items:3,loop:!1,center:!1,rewind:!1,checkVisibility:!0,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:b,fallbackEasing:"swing",slideTransition:"",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",refreshClass:"owl-refresh",loadedClass:"owl-loaded",loadingClass:"owl-loading",rtlClass:"owl-rtl",responsiveClass:"owl-responsive",dragClass:"owl-drag",itemClass:"owl-item",stageClass:"owl-stage",stageOuterClass:"owl-stage-outer",grabClass:"owl-grab"},e.Width={Default:"default",Inner:"inner",Outer:"outer"},e.Type={Event:"event",State:"state"},e.Plugins={},e.Workers=[{filter:["width","settings"],run:function(){this._width=this.$element.width()}},{filter:["width","items","settings"],run:function(a){a.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){this.$stage.children(".cloned").remove()}},{filter:["width","items","settings"],run:function(a){var b=this.settings.margin||"",c=!this.settings.autoWidth,d=this.settings.rtl,e={width:"auto","margin-left":d?b:"","margin-right":d?"":b};!c&&this.$stage.children().css(e),a.css=e}},{filter:["width","items","settings"],run:function(a){var b=(this.width()/this.settings.items).toFixed(3)-this.settings.margin,c=null,d=this._items.length,e=!this.settings.autoWidth,f=[];for(a.items={merge:!1,width:b};d--;)c=this._mergers[d],c=this.settings.mergeFit&&Math.min(c,this.settings.items)||c,a.items.merge=c>1||a.items.merge,f[d]=e?b*c:this._items[d].width();this._widths=f}},{filter:["items","settings"],run:function(){var b=[],c=this._items,d=this.settings,e=Math.max(2*d.items,4),f=2*Math.ceil(c.length/2),g=d.loop&&c.length?d.rewind?e:Math.max(e,f):0,h="",i="";for(g/=2;g>0;)b.push(this.normalize(b.length/2,!0)),h+=c[b[b.length-1]][0].outerHTML,b.push(this.normalize(c.length-1-(b.length-1)/2,!0)),i=c[b[b.length-1]][0].outerHTML+i,g-=1;this._clones=b,a(h).addClass("cloned").appendTo(this.$stage),a(i).addClass("cloned").prependTo(this.$stage)}},{filter:["width","items","settings"],run:function(){for(var a=this.settings.rtl?1:-1,b=this._clones.length+this._items.length,c=-1,d=0,e=0,f=[];++c<b;)d=f[c-1]||0,e=this._widths[this.relative(c)]+this.settings.margin,f.push(d+e*a);this._coordinates=f}},{filter:["width","items","settings"],run:function(){var a=this.settings.stagePadding,b=this._coordinates,c={width:Math.ceil(Math.abs(b[b.length-1]))+2*a,"padding-left":a||"","padding-right":a||""};this.$stage.css(c)}},{filter:["width","items","settings"],run:function(a){var b=this._coordinates.length,c=!this.settings.autoWidth,d=this.$stage.children();if(c&&a.items.merge)for(;b--;)a.css.width=this._widths[this.relative(b)],d.eq(b).css(a.css);else c&&(a.css.width=a.items.width,d.css(a.css))}},{filter:["items"],run:function(){this._coordinates.length<1&&this.$stage.removeAttr("style")}},{filter:["width","items","settings"],run:function(a){a.current=a.current?this.$stage.children().index(a.current):0,a.current=Math.max(this.minimum(),Math.min(this.maximum(),a.current)),this.reset(a.current)}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var a,b,c,d,e=this.settings.rtl?1:-1,f=2*this.settings.stagePadding,g=this.coordinates(this.current())+f,h=g+this.width()*e,i=[];for(c=0,d=this._coordinates.length;c<d;c++)a=this._coordinates[c-1]||0,b=Math.abs(this._coordinates[c])+f*e,(this.op(a,"<=",g)&&this.op(a,">",h)||this.op(b,"<",g)&&this.op(b,">",h))&&i.push(c);this.$stage.children(".active").removeClass("active"),this.$stage.children(":eq("+i.join("), :eq(")+")").addClass("active"),this.$stage.children(".center").removeClass("center"),this.settings.center&&this.$stage.children().eq(this.current()).addClass("center")}}],e.prototype.initializeStage=function(){this.$stage=this.$element.find("."+this.settings.stageClass),this.$stage.length||(this.$element.addClass(this.options.loadingClass),this.$stage=a("<"+this.settings.stageElement+">",{class:this.settings.stageClass}).wrap(a("<div/>",{class:this.settings.stageOuterClass})),this.$element.append(this.$stage.parent()))},e.prototype.initializeItems=function(){var b=this.$element.find(".owl-item");if(b.length)return this._items=b.get().map(function(b){return a(b)}),this._mergers=this._items.map(function(){return 1}),void this.refresh();this.replace(this.$element.children().not(this.$stage.parent())),this.isVisible()?this.refresh():this.invalidate("width"),this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass)},e.prototype.initialize=function(){if(this.enter("initializing"),this.trigger("initialize"),this.$element.toggleClass(this.settings.rtlClass,this.settings.rtl),this.settings.autoWidth&&!this.is("pre-loading")){var a,b,c;a=this.$element.find("img"),b=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:d,c=this.$element.children(b).width(),a.length&&c<=0&&this.preloadAutoWidthImages(a)}this.initializeStage(),this.initializeItems(),this.registerEventHandlers(),this.leave("initializing"),this.trigger("initialized")},e.prototype.isVisible=function(){return!this.settings.checkVisibility||this.$element.is(":visible")},e.prototype.setup=function(){var b=this.viewport(),c=this.options.responsive,d=-1,e=null;c?(a.each(c,function(a){a<=b&&a>d&&(d=Number(a))}),e=a.extend({},this.options,c[d]),"function"==typeof e.stagePadding&&(e.stagePadding=e.stagePadding()),delete e.responsive,e.responsiveClass&&this.$element.attr("class",this.$element.attr("class").replace(new RegExp("("+this.options.responsiveClass+"-)\\S+\\s","g"),"$1"+d))):e=a.extend({},this.options),this.trigger("change",{property:{name:"settings",value:e}}),this._breakpoint=d,this.settings=e,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}})},e.prototype.optionsLogic=function(){this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},e.prototype.prepare=function(b){var c=this.trigger("prepare",{content:b});return c.data||(c.data=a("<"+this.settings.itemElement+"/>").addClass(this.options.itemClass).append(b)),this.trigger("prepared",{content:c.data}),c.data},e.prototype.update=function(){for(var b=0,c=this._pipe.length,d=a.proxy(function(a){return this[a]},this._invalidated),e={};b<c;)(this._invalidated.all||a.grep(this._pipe[b].filter,d).length>0)&&this._pipe[b].run(e),b++;this._invalidated={},!this.is("valid")&&this.enter("valid")},e.prototype.width=function(a){switch(a=a||e.Width.Default){case e.Width.Inner:case e.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},e.prototype.refresh=function(){this.enter("refreshing"),this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$element.addClass(this.options.refreshClass),this.update(),this.$element.removeClass(this.options.refreshClass),this.leave("refreshing"),this.trigger("refreshed")},e.prototype.onThrottledResize=function(){b.clearTimeout(this.resizeTimer),this.resizeTimer=b.setTimeout(this._handlers.onResize,this.settings.responsiveRefreshRate)},e.prototype.onResize=function(){return!!this._items.length&&(this._width!==this.$element.width()&&(!!this.isVisible()&&(this.enter("resizing"),this.trigger("resize").isDefaultPrevented()?(this.leave("resizing"),!1):(this.invalidate("width"),this.refresh(),this.leave("resizing"),void this.trigger("resized")))))},e.prototype.registerEventHandlers=function(){a.support.transition&&this.$stage.on(a.support.transition.end+".owl.core",a.proxy(this.onTransitionEnd,this)),!1!==this.settings.responsive&&this.on(b,"resize",this._handlers.onThrottledResize),this.settings.mouseDrag&&(this.$element.addClass(this.options.dragClass),this.$stage.on("mousedown.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("dragstart.owl.core selectstart.owl.core",function(){return!1})),this.settings.touchDrag&&(this.$stage.on("touchstart.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("touchcancel.owl.core",a.proxy(this.onDragEnd,this)))},e.prototype.onDragStart=function(b){var d=null;3!==b.which&&(a.support.transform?(d=this.$stage.css("transform").replace(/.*\(|\)| /g,"").split(","),d={x:d[16===d.length?12:4],y:d[16===d.length?13:5]}):(d=this.$stage.position(),d={x:this.settings.rtl?d.left+this.$stage.width()-this.width()+this.settings.margin:d.left,y:d.top}),this.is("animating")&&(a.support.transform?this.animate(d.x):this.$stage.stop(),this.invalidate("position")),this.$element.toggleClass(this.options.grabClass,"mousedown"===b.type),this.speed(0),this._drag.time=(new Date).getTime(),this._drag.target=a(b.target),this._drag.stage.start=d,this._drag.stage.current=d,this._drag.pointer=this.pointer(b),a(c).on("mouseup.owl.core touchend.owl.core",a.proxy(this.onDragEnd,this)),a(c).one("mousemove.owl.core touchmove.owl.core",a.proxy(function(b){var d=this.difference(this._drag.pointer,this.pointer(b));a(c).on("mousemove.owl.core touchmove.owl.core",a.proxy(this.onDragMove,this)),Math.abs(d.x)<Math.abs(d.y)&&this.is("valid")||(b.preventDefault(),this.enter("dragging"),this.trigger("drag"))},this)))},e.prototype.onDragMove=function(a){var b=null,c=null,d=null,e=this.difference(this._drag.pointer,this.pointer(a)),f=this.difference(this._drag.stage.start,e);this.is("dragging")&&(a.preventDefault(),this.settings.loop?(b=this.coordinates(this.minimum()),c=this.coordinates(this.maximum()+1)-b,f.x=((f.x-b)%c+c)%c+b):(b=this.settings.rtl?this.coordinates(this.maximum()):this.coordinates(this.minimum()),c=this.settings.rtl?this.coordinates(this.minimum()):this.coordinates(this.maximum()),d=this.settings.pullDrag?-1*e.x/5:0,f.x=Math.max(Math.min(f.x,b+d),c+d)),this._drag.stage.current=f,this.animate(f.x))},e.prototype.onDragEnd=function(b){var d=this.difference(this._drag.pointer,this.pointer(b)),e=this._drag.stage.current,f=d.x>0^this.settings.rtl?"left":"right";a(c).off(".owl.core"),this.$element.removeClass(this.options.grabClass),(0!==d.x&&this.is("dragging")||!this.is("valid"))&&(this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(this.closest(e.x,0!==d.x?f:this._drag.direction)),this.invalidate("position"),this.update(),this._drag.direction=f,(Math.abs(d.x)>3||(new Date).getTime()-this._drag.time>300)&&this._drag.target.one("click.owl.core",function(){return!1})),this.is("dragging")&&(this.leave("dragging"),this.trigger("dragged"))},e.prototype.closest=function(b,c){var e=-1,f=30,g=this.width(),h=this.coordinates();return this.settings.freeDrag||a.each(h,a.proxy(function(a,i){return"left"===c&&b>i-f&&b<i+f?e=a:"right"===c&&b>i-g-f&&b<i-g+f?e=a+1:this.op(b,"<",i)&&this.op(b,">",h[a+1]!==d?h[a+1]:i-g)&&(e="left"===c?a+1:a),-1===e},this)),this.settings.loop||(this.op(b,">",h[this.minimum()])?e=b=this.minimum():this.op(b,"<",h[this.maximum()])&&(e=b=this.maximum())),e},e.prototype.animate=function(b){var c=this.speed()>0;this.is("animating")&&this.onTransitionEnd(),c&&(this.enter("animating"),this.trigger("translate")),a.support.transform3d&&a.support.transition?this.$stage.css({transform:"translate3d("+b+"px,0px,0px)",transition:this.speed()/1e3+"s"+(this.settings.slideTransition?" "+this.settings.slideTransition:"")}):c?this.$stage.animate({left:b+"px"},this.speed(),this.settings.fallbackEasing,a.proxy(this.onTransitionEnd,this)):this.$stage.css({left:b+"px"})},e.prototype.is=function(a){return this._states.current[a]&&this._states.current[a]>0},e.prototype.current=function(a){if(a===d)return this._current;if(0===this._items.length)return d;if(a=this.normalize(a),this._current!==a){var b=this.trigger("change",{property:{name:"position",value:a}});b.data!==d&&(a=this.normalize(b.data)),this._current=a,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},e.prototype.invalidate=function(b){return"string"===a.type(b)&&(this._invalidated[b]=!0,this.is("valid")&&this.leave("valid")),a.map(this._invalidated,function(a,b){return b})},e.prototype.reset=function(a){(a=this.normalize(a))!==d&&(this._speed=0,this._current=a,this.suppress(["translate","translated"]),this.animate(this.coordinates(a)),this.release(["translate","translated"]))},e.prototype.normalize=function(a,b){var c=this._items.length,e=b?0:this._clones.length;return!this.isNumeric(a)||c<1?a=d:(a<0||a>=c+e)&&(a=((a-e/2)%c+c)%c+e/2),a},e.prototype.relative=function(a){return a-=this._clones.length/2,this.normalize(a,!0)},e.prototype.maximum=function(a){var b,c,d,e=this.settings,f=this._coordinates.length;if(e.loop)f=this._clones.length/2+this._items.length-1;else if(e.autoWidth||e.merge){if(b=this._items.length)for(c=this._items[--b].width(),d=this.$element.width();b--&&!((c+=this._items[b].width()+this.settings.margin)>d););f=b+1}else f=e.center?this._items.length-1:this._items.length-e.items;return a&&(f-=this._clones.length/2),Math.max(f,0)},e.prototype.minimum=function(a){return a?0:this._clones.length/2},e.prototype.items=function(a){return a===d?this._items.slice():(a=this.normalize(a,!0),this._items[a])},e.prototype.mergers=function(a){return a===d?this._mergers.slice():(a=this.normalize(a,!0),this._mergers[a])},e.prototype.clones=function(b){var c=this._clones.length/2,e=c+this._items.length,f=function(a){return a%2==0?e+a/2:c-(a+1)/2};return b===d?a.map(this._clones,function(a,b){return f(b)}):a.map(this._clones,function(a,c){return a===b?f(c):null})},e.prototype.speed=function(a){return a!==d&&(this._speed=a),this._speed},e.prototype.coordinates=function(b){var c,e=1,f=b-1;return b===d?a.map(this._coordinates,a.proxy(function(a,b){return this.coordinates(b)},this)):(this.settings.center?(this.settings.rtl&&(e=-1,f=b+1),c=this._coordinates[b],c+=(this.width()-c+(this._coordinates[f]||0))/2*e):c=this._coordinates[f]||0,c=Math.ceil(c))},e.prototype.duration=function(a,b,c){return 0===c?0:Math.min(Math.max(Math.abs(b-a),1),6)*Math.abs(c||this.settings.smartSpeed)},e.prototype.to=function(a,b){var c=this.current(),d=null,e=a-this.relative(c),f=(e>0)-(e<0),g=this._items.length,h=this.minimum(),i=this.maximum();this.settings.loop?(!this.settings.rewind&&Math.abs(e)>g/2&&(e+=-1*f*g),a=c+e,(d=((a-h)%g+g)%g+h)!==a&&d-e<=i&&d-e>0&&(c=d-e,a=d,this.reset(c))):this.settings.rewind?(i+=1,a=(a%i+i)%i):a=Math.max(h,Math.min(i,a)),this.speed(this.duration(c,a,b)),this.current(a),this.isVisible()&&this.update()},e.prototype.next=function(a){a=a||!1,this.to(this.relative(this.current())+1,a)},e.prototype.prev=function(a){a=a||!1,this.to(this.relative(this.current())-1,a)},e.prototype.onTransitionEnd=function(a){if(a!==d&&(a.stopPropagation(),(a.target||a.srcElement||a.originalTarget)!==this.$stage.get(0)))return!1;this.leave("animating"),this.trigger("translated")},e.prototype.viewport=function(){var d;return this.options.responsiveBaseElement!==b?d=a(this.options.responsiveBaseElement).width():b.innerWidth?d=b.innerWidth:c.documentElement&&c.documentElement.clientWidth?d=c.documentElement.clientWidth:console.warn("Can not detect viewport width."),d},e.prototype.replace=function(b){this.$stage.empty(),this._items=[],b&&(b=b instanceof jQuery?b:a(b)),this.settings.nestedItemSelector&&(b=b.find("."+this.settings.nestedItemSelector)),b.filter(function(){return 1===this.nodeType}).each(a.proxy(function(a,b){b=this.prepare(b),this.$stage.append(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)},this)),this.reset(this.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},e.prototype.add=function(b,c){var e=this.relative(this._current);c=c===d?this._items.length:this.normalize(c,!0),b=b instanceof jQuery?b:a(b),this.trigger("add",{content:b,position:c}),b=this.prepare(b),0===this._items.length||c===this._items.length?(0===this._items.length&&this.$stage.append(b),0!==this._items.length&&this._items[c-1].after(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)):(this._items[c].before(b),this._items.splice(c,0,b),this._mergers.splice(c,0,1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)),this._items[e]&&this.reset(this._items[e].index()),this.invalidate("items"),this.trigger("added",{content:b,position:c})},e.prototype.remove=function(a){(a=this.normalize(a,!0))!==d&&(this.trigger("remove",{content:this._items[a],position:a}),this._items[a].remove(),this._items.splice(a,1),this._mergers.splice(a,1),this.invalidate("items"),this.trigger("removed",{content:null,position:a}))},e.prototype.preloadAutoWidthImages=function(b){b.each(a.proxy(function(b,c){this.enter("pre-loading"),c=a(c),a(new Image).one("load",a.proxy(function(a){c.attr("src",a.target.src),c.css("opacity",1),this.leave("pre-loading"),!this.is("pre-loading")&&!this.is("initializing")&&this.refresh()},this)).attr("src",c.attr("src")||c.attr("data-src")||c.attr("data-src-retina"))},this))},e.prototype.destroy=function(){this.$element.off(".owl.core"),this.$stage.off(".owl.core"),a(c).off(".owl.core"),!1!==this.settings.responsive&&(b.clearTimeout(this.resizeTimer),this.off(b,"resize",this._handlers.onThrottledResize));for(var d in this._plugins)this._plugins[d].destroy();this.$stage.children(".cloned").remove(),this.$stage.unwrap(),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.remove(),this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class",this.$element.attr("class").replace(new RegExp(this.options.responsiveClass+"-\\S+\\s","g"),"")).removeData("owl.carousel")},e.prototype.op=function(a,b,c){var d=this.settings.rtl;switch(b){case"<":return d?a>c:a<c;case">":return d?a<c:a>c;case">=":return d?a<=c:a>=c;case"<=":return d?a>=c:a<=c}},e.prototype.on=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d):a.attachEvent&&a.attachEvent("on"+b,c)},e.prototype.off=function(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d):a.detachEvent&&a.detachEvent("on"+b,c)},e.prototype.trigger=function(b,c,d,f,g){var h={item:{count:this._items.length,index:this.current()}},i=a.camelCase(a.grep(["on",b,d],function(a){return a}).join("-").toLowerCase()),j=a.Event([b,"owl",d||"carousel"].join(".").toLowerCase(),a.extend({relatedTarget:this},h,c));return this._supress[b]||(a.each(this._plugins,function(a,b){b.onTrigger&&b.onTrigger(j)}),this.register({type:e.Type.Event,name:b}),this.$element.trigger(j),this.settings&&"function"==typeof this.settings[i]&&this.settings[i].call(this,j)),j},e.prototype.enter=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]===d&&(this._states.current[b]=0),this._states.current[b]++},this))},e.prototype.leave=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]--},this))},e.prototype.register=function(b){if(b.type===e.Type.Event){if(a.event.special[b.name]||(a.event.special[b.name]={}),!a.event.special[b.name].owl){var c=a.event.special[b.name]._default;a.event.special[b.name]._default=function(a){return!c||!c.apply||a.namespace&&-1!==a.namespace.indexOf("owl")?a.namespace&&a.namespace.indexOf("owl")>-1:c.apply(this,arguments)},a.event.special[b.name].owl=!0}}else b.type===e.Type.State&&(this._states.tags[b.name]?this._states.tags[b.name]=this._states.tags[b.name].concat(b.tags):this._states.tags[b.name]=b.tags,this._states.tags[b.name]=a.grep(this._states.tags[b.name],a.proxy(function(c,d){return a.inArray(c,this._states.tags[b.name])===d},this)))},e.prototype.suppress=function(b){a.each(b,a.proxy(function(a,b){this._supress[b]=!0},this))},e.prototype.release=function(b){a.each(b,a.proxy(function(a,b){delete this._supress[b]},this))},e.prototype.pointer=function(a){var c={x:null,y:null};return a=a.originalEvent||a||b.event,a=a.touches&&a.touches.length?a.touches[0]:a.changedTouches&&a.changedTouches.length?a.changedTouches[0]:a,a.pageX?(c.x=a.pageX,c.y=a.pageY):(c.x=a.clientX,c.y=a.clientY),c},e.prototype.isNumeric=function(a){return!isNaN(parseFloat(a))},e.prototype.difference=function(a,b){return{x:a.x-b.x,y:a.y-b.y}},a.fn.owlCarousel=function(b){var c=Array.prototype.slice.call(arguments,1);return this.each(function(){var d=a(this),f=d.data("owl.carousel");f||(f=new e(this,"object"==typeof b&&b),d.data("owl.carousel",f),a.each(["next","prev","to","destroy","refresh","replace","add","remove"],function(b,c){f.register({type:e.Type.Event,name:c}),f.$element.on(c+".owl.carousel.core",a.proxy(function(a){a.namespace&&a.relatedTarget!==this&&(this.suppress([c]),f[c].apply(this,[].slice.call(arguments,1)),this.release([c]))},f))})),"string"==typeof b&&"_"!==b.charAt(0)&&f[b].apply(f,c)})},a.fn.owlCarousel.Constructor=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._interval=null,this._visible=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoRefresh&&this.watch()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={autoRefresh:!0,autoRefreshInterval:500},e.prototype.watch=function(){this._interval||(this._visible=this._core.isVisible(),this._interval=b.setInterval(a.proxy(this.refresh,this),this._core.settings.autoRefreshInterval))},e.prototype.refresh=function(){this._core.isVisible()!==this._visible&&(this._visible=!this._visible,this._core.$element.toggleClass("owl-hidden",!this._visible),this._visible&&this._core.invalidate("width")&&this._core.refresh())},e.prototype.destroy=function(){var a,c;b.clearInterval(this._interval);for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoRefresh=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel resized.owl.carousel":a.proxy(function(b){if(b.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(b.property&&"position"==b.property.name||"initialized"==b.type)){var c=this._core.settings,e=c.center&&Math.ceil(c.items/2)||c.items,f=c.center&&-1*e||0,g=(b.property&&b.property.value!==d?b.property.value:this._core.current())+f,h=this._core.clones().length,i=a.proxy(function(a,b){this.load(b)},this);for(c.lazyLoadEager>0&&(e+=c.lazyLoadEager,c.loop&&(g-=c.lazyLoadEager,e++));f++<e;)this.load(h/2+this._core.relative(g)),h&&a.each(this._core.clones(this._core.relative(g)),i),g++}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={lazyLoad:!1,lazyLoadEager:0},e.prototype.load=function(c){var d=this._core.$stage.children().eq(c),e=d&&d.find(".owl-lazy");!e||a.inArray(d.get(0),this._loaded)>-1||(e.each(a.proxy(function(c,d){var e,f=a(d),g=b.devicePixelRatio>1&&f.attr("data-src-retina")||f.attr("data-src")||f.attr("data-srcset");this._core.trigger("load",{element:f,url:g},"lazy"),f.is("img")?f.one("load.owl.lazy",a.proxy(function(){f.css("opacity",1),this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("src",g):f.is("source")?f.one("load.owl.lazy",a.proxy(function(){this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("srcset",g):(e=new Image,e.onload=a.proxy(function(){f.css({"background-image":'url("'+g+'")',opacity:"1"}),this._core.trigger("loaded",{element:f,url:g},"lazy")},this),e.src=g)},this)),this._loaded.push(d.get(0)))},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this._core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(c){this._core=c,this._previousHeight=null,this._handlers={"initialized.owl.carousel refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&"position"===a.property.name&&this.update()},this),"loaded.owl.lazy":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&a.element.closest("."+this._core.settings.itemClass).index()===this._core.current()&&this.update()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._intervalId=null;var d=this;a(b).on("load",function(){d._core.settings.autoHeight&&d.update()}),a(b).resize(function(){d._core.settings.autoHeight&&(null!=d._intervalId&&clearTimeout(d._intervalId),d._intervalId=setTimeout(function(){d.update()},250))})};e.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},e.prototype.update=function(){var b=this._core._current,c=b+this._core.settings.items,d=this._core.settings.lazyLoad,e=this._core.$stage.children().toArray().slice(b,c),f=[],g=0;a.each(e,function(b,c){f.push(a(c).height())}),g=Math.max.apply(null,f),g<=1&&d&&this._previousHeight&&(g=this._previousHeight),this._previousHeight=g,this._core.$stage.parent().height(g).addClass(this._core.settings.autoHeightClass)},e.prototype.destroy=function(){var a,b;for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoHeight=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._videos={},this._playing=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.register({type:"state",name:"playing",tags:["interacting"]})},this),"resize.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.video&&this.isInFullScreen()&&a.preventDefault()},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.is("resizing")&&this._core.$stage.find(".cloned .owl-video-frame").remove()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"===a.property.name&&this._playing&&this.stop()},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find(".owl-video");c.length&&(c.css("display","none"),this.fetch(c,a(b.content)))}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",a.proxy(function(a){this.play(a)},this))};e.Defaults={video:!1,videoHeight:!1,videoWidth:!1},e.prototype.fetch=function(a,b){var c=function(){return a.attr("data-vimeo-id")?"vimeo":a.attr("data-vzaar-id")?"vzaar":"youtube"}(),d=a.attr("data-vimeo-id")||a.attr("data-youtube-id")||a.attr("data-vzaar-id"),e=a.attr("data-width")||this._core.settings.videoWidth,f=a.attr("data-height")||this._core.settings.videoHeight,g=a.attr("href");if(!g)throw new Error("Missing video URL.");if(d=g.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com|be\-nocookie\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),d[3].indexOf("youtu")>-1)c="youtube";else if(d[3].indexOf("vimeo")>-1)c="vimeo";else{if(!(d[3].indexOf("vzaar")>-1))throw new Error("Video URL not supported.");c="vzaar"}d=d[6],this._videos[g]={type:c,id:d,width:e,height:f},b.attr("data-video",g),this.thumbnail(a,this._videos[g])},e.prototype.thumbnail=function(b,c){var d,e,f,g=c.width&&c.height?"width:"+c.width+"px;height:"+c.height+"px;":"",h=b.find("img"),i="src",j="",k=this._core.settings,l=function(c){e='<div class="owl-video-play-icon"></div>',d=k.lazyLoad?a("<div/>",{class:"owl-video-tn "+j,srcType:c}):a("<div/>",{class:"owl-video-tn",style:"opacity:1;background-image:url("+c+")"}),b.after(d),b.after(e)};if(b.wrap(a("<div/>",{class:"owl-video-wrapper",style:g})),this._core.settings.lazyLoad&&(i="data-src",j="owl-lazy"),h.length)return l(h.attr(i)),h.remove(),!1;"youtube"===c.type?(f="//img.youtube.com/vi/"+c.id+"/hqdefault.jpg",l(f)):"vimeo"===c.type?a.ajax({type:"GET",url:"//vimeo.com/api/v2/video/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a[0].thumbnail_large,l(f)}}):"vzaar"===c.type&&a.ajax({type:"GET",url:"//vzaar.com/api/videos/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a.framegrab_url,l(f)}})},e.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null,this._core.leave("playing"),this._core.trigger("stopped",null,"video")},e.prototype.play=function(b){var c,d=a(b.target),e=d.closest("."+this._core.settings.itemClass),f=this._videos[e.attr("data-video")],g=f.width||"100%",h=f.height||this._core.$stage.height();this._playing||(this._core.enter("playing"),this._core.trigger("play",null,"video"),e=this._core.items(this._core.relative(e.index())),this._core.reset(e.index()),c=a('<iframe frameborder="0" allowfullscreen mozallowfullscreen webkitAllowFullScreen ></iframe>'),c.attr("height",h),c.attr("width",g),"youtube"===f.type?c.attr("src","//www.youtube.com/embed/"+f.id+"?autoplay=1&rel=0&v="+f.id):"vimeo"===f.type?c.attr("src","//player.vimeo.com/video/"+f.id+"?autoplay=1"):"vzaar"===f.type&&c.attr("src","//view.vzaar.com/"+f.id+"/player?autoplay=true"),a(c).wrap('<div class="owl-video-frame" />').insertAfter(e.find(".owl-video")),this._playing=e.addClass("owl-video-playing"))},e.prototype.isInFullScreen=function(){var b=c.fullscreenElement||c.mozFullScreenElement||c.webkitFullscreenElement;return b&&a(b).parent().hasClass("owl-video-frame")},e.prototype.destroy=function(){var a,b;this._core.$element.off("click.owl.video");for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Video=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this.core=b,this.core.options=a.extend({},e.Defaults,this.core.options),this.swapping=!0,this.previous=d,this.next=d,this.handlers={"change.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&(this.previous=this.core.current(),this.next=a.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":a.proxy(function(a){a.namespace&&(this.swapping="translated"==a.type)},this),"translate.owl.carousel":a.proxy(function(a){a.namespace&&this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};e.Defaults={animateOut:!1,
animateIn:!1},e.prototype.swap=function(){if(1===this.core.settings.items&&a.support.animation&&a.support.transition){this.core.speed(0);var b,c=a.proxy(this.clear,this),d=this.core.$stage.children().eq(this.previous),e=this.core.$stage.children().eq(this.next),f=this.core.settings.animateIn,g=this.core.settings.animateOut;this.core.current()!==this.previous&&(g&&(b=this.core.coordinates(this.previous)-this.core.coordinates(this.next),d.one(a.support.animation.end,c).css({left:b+"px"}).addClass("animated owl-animated-out").addClass(g)),f&&e.one(a.support.animation.end,c).addClass("animated owl-animated-in").addClass(f))}},e.prototype.clear=function(b){a(b.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.onTransitionEnd()},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Animate=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._call=null,this._time=0,this._timeout=0,this._paused=!0,this._handlers={"changed.owl.carousel":a.proxy(function(a){a.namespace&&"settings"===a.property.name?this._core.settings.autoplay?this.play():this.stop():a.namespace&&"position"===a.property.name&&this._paused&&(this._time=0)},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoplay&&this.play()},this),"play.owl.autoplay":a.proxy(function(a,b,c){a.namespace&&this.play(b,c)},this),"stop.owl.autoplay":a.proxy(function(a){a.namespace&&this.stop()},this),"mouseover.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"mouseleave.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.play()},this),"touchstart.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"touchend.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this.play()},this)},this._core.$element.on(this._handlers),this._core.options=a.extend({},e.Defaults,this._core.options)};e.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},e.prototype._next=function(d){this._call=b.setTimeout(a.proxy(this._next,this,d),this._timeout*(Math.round(this.read()/this._timeout)+1)-this.read()),this._core.is("interacting")||c.hidden||this._core.next(d||this._core.settings.autoplaySpeed)},e.prototype.read=function(){return(new Date).getTime()-this._time},e.prototype.play=function(c,d){var e;this._core.is("rotating")||this._core.enter("rotating"),c=c||this._core.settings.autoplayTimeout,e=Math.min(this._time%(this._timeout||c),c),this._paused?(this._time=this.read(),this._paused=!1):b.clearTimeout(this._call),this._time+=this.read()%c-e,this._timeout=c,this._call=b.setTimeout(a.proxy(this._next,this,d),c-e)},e.prototype.stop=function(){this._core.is("rotating")&&(this._time=0,this._paused=!0,b.clearTimeout(this._call),this._core.leave("rotating"))},e.prototype.pause=function(){this._core.is("rotating")&&!this._paused&&(this._time=this.read(),this._paused=!0,b.clearTimeout(this._call))},e.prototype.destroy=function(){var a,b;this.stop();for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.autoplay=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(b){this._core=b,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":a.proxy(function(b){b.namespace&&this._core.settings.dotsData&&this._templates.push('<div class="'+this._core.settings.dotClass+'">'+a(b.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot")+"</div>")},this),"added.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,0,this._templates.pop())},this),"remove.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,1)},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&this.draw()},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&!this._initialized&&(this._core.trigger("initialize",null,"navigation"),this.initialize(),this.update(),this.draw(),this._initialized=!0,this._core.trigger("initialized",null,"navigation"))},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._initialized&&(this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation"))},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers)};e.Defaults={nav:!1,navText:['<span aria-label="Previous">&#x2039;</span>','<span aria-label="Next">&#x203a;</span>'],navSpeed:!1,navElement:'button type="button" role="presentation"',navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotsData:!1,dotsSpeed:!1,dotsContainer:!1},e.prototype.initialize=function(){var b,c=this._core.settings;this._controls.$relative=(c.navContainer?a(c.navContainer):a("<div>").addClass(c.navContainerClass).appendTo(this.$element)).addClass("disabled"),this._controls.$previous=a("<"+c.navElement+">").addClass(c.navClass[0]).html(c.navText[0]).prependTo(this._controls.$relative).on("click",a.proxy(function(a){this.prev(c.navSpeed)},this)),this._controls.$next=a("<"+c.navElement+">").addClass(c.navClass[1]).html(c.navText[1]).appendTo(this._controls.$relative).on("click",a.proxy(function(a){this.next(c.navSpeed)},this)),c.dotsData||(this._templates=[a('<button role="button">').addClass(c.dotClass).append(a("<span>")).prop("outerHTML")]),this._controls.$absolute=(c.dotsContainer?a(c.dotsContainer):a("<div>").addClass(c.dotsClass).appendTo(this.$element)).addClass("disabled"),this._controls.$absolute.on("click","button",a.proxy(function(b){var d=a(b.target).parent().is(this._controls.$absolute)?a(b.target).index():a(b.target).parent().index();b.preventDefault(),this.to(d,c.dotsSpeed)},this));for(b in this._overrides)this._core[b]=a.proxy(this[b],this)},e.prototype.destroy=function(){var a,b,c,d,e;e=this._core.settings;for(a in this._handlers)this.$element.off(a,this._handlers[a]);for(b in this._controls)"$relative"===b&&e.navContainer?this._controls[b].html(""):this._controls[b].remove();for(d in this.overides)this._core[d]=this._overrides[d];for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},e.prototype.update=function(){var a,b,c,d=this._core.clones().length/2,e=d+this._core.items().length,f=this._core.maximum(!0),g=this._core.settings,h=g.center||g.autoWidth||g.dotsData?1:g.dotsEach||g.items;if("page"!==g.slideBy&&(g.slideBy=Math.min(g.slideBy,g.items)),g.dots||"page"==g.slideBy)for(this._pages=[],a=d,b=0,c=0;a<e;a++){if(b>=h||0===b){if(this._pages.push({start:Math.min(f,a-d),end:a-d+h-1}),Math.min(f,a-d)===f)break;b=0,++c}b+=this._core.mergers(this._core.relative(a))}},e.prototype.draw=function(){var b,c=this._core.settings,d=this._core.items().length<=c.items,e=this._core.relative(this._core.current()),f=c.loop||c.rewind;this._controls.$relative.toggleClass("disabled",!c.nav||d),c.nav&&(this._controls.$previous.toggleClass("disabled",!f&&e<=this._core.minimum(!0)),this._controls.$next.toggleClass("disabled",!f&&e>=this._core.maximum(!0))),this._controls.$absolute.toggleClass("disabled",!c.dots||d),c.dots&&(b=this._pages.length-this._controls.$absolute.children().length,c.dotsData&&0!==b?this._controls.$absolute.html(this._templates.join("")):b>0?this._controls.$absolute.append(new Array(b+1).join(this._templates[0])):b<0&&this._controls.$absolute.children().slice(b).remove(),this._controls.$absolute.find(".active").removeClass("active"),this._controls.$absolute.children().eq(a.inArray(this.current(),this._pages)).addClass("active"))},e.prototype.onTrigger=function(b){var c=this._core.settings;b.page={index:a.inArray(this.current(),this._pages),count:this._pages.length,size:c&&(c.center||c.autoWidth||c.dotsData?1:c.dotsEach||c.items)}},e.prototype.current=function(){var b=this._core.relative(this._core.current());return a.grep(this._pages,a.proxy(function(a,c){return a.start<=b&&a.end>=b},this)).pop()},e.prototype.getPosition=function(b){var c,d,e=this._core.settings;return"page"==e.slideBy?(c=a.inArray(this.current(),this._pages),d=this._pages.length,b?++c:--c,c=this._pages[(c%d+d)%d].start):(c=this._core.relative(this._core.current()),d=this._core.items().length,b?c+=e.slideBy:c-=e.slideBy),c},e.prototype.next=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!0),b)},e.prototype.prev=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!1),b)},e.prototype.to=function(b,c,d){var e;!d&&this._pages.length?(e=this._pages.length,a.proxy(this._overrides.to,this._core)(this._pages[(b%e+e)%e].start,c)):a.proxy(this._overrides.to,this._core)(b,c)},a.fn.owlCarousel.Constructor.Plugins.Navigation=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(c){this._core=c,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":a.proxy(function(c){c.namespace&&"URLHash"===this._core.settings.startPosition&&a(b).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");if(!c)return;this._hashes[c]=b.content}},this),"changed.owl.carousel":a.proxy(function(c){if(c.namespace&&"position"===c.property.name){var d=this._core.items(this._core.relative(this._core.current())),e=a.map(this._hashes,function(a,b){return a===d?b:null}).join();if(!e||b.location.hash.slice(1)===e)return;b.location.hash=e}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers),a(b).on("hashchange.owl.navigation",a.proxy(function(a){var c=b.location.hash.substring(1),e=this._core.$stage.children(),f=this._hashes[c]&&e.index(this._hashes[c]);f!==d&&f!==this._core.current()&&this._core.to(this._core.relative(f),!1,!0)},this))};e.Defaults={URLhashListener:!1},e.prototype.destroy=function(){var c,d;a(b).off("hashchange.owl.navigation");for(c in this._handlers)this._core.$element.off(c,this._handlers[c]);for(d in Object.getOwnPropertyNames(this))"function"!=typeof this[d]&&(this[d]=null)},a.fn.owlCarousel.Constructor.Plugins.Hash=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){function e(b,c){var e=!1,f=b.charAt(0).toUpperCase()+b.slice(1);return a.each((b+" "+h.join(f+" ")+f).split(" "),function(a,b){if(g[b]!==d)return e=!c||b,!1}),e}function f(a){return e(a,!0)}var g=a("<support>").get(0).style,h="Webkit Moz O ms".split(" "),i={transition:{end:{WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",transition:"transitionend"}},animation:{end:{WebkitAnimation:"webkitAnimationEnd",MozAnimation:"animationend",OAnimation:"oAnimationEnd",animation:"animationend"}}},j={csstransforms:function(){return!!e("transform")},csstransforms3d:function(){return!!e("perspective")},csstransitions:function(){return!!e("transition")},cssanimations:function(){return!!e("animation")}};j.csstransitions()&&(a.support.transition=new String(f("transition")),a.support.transition.end=i.transition.end[a.support.transition]),j.cssanimations()&&(a.support.animation=new String(f("animation")),a.support.animation.end=i.animation.end[a.support.animation]),j.csstransforms()&&(a.support.transform=new String(f("transform")),a.support.transform3d=j.csstransforms3d())}(window.Zepto||window.jQuery,window,document);

 /*
 * # Semantic UI - 2.4.1
 * https://github.com/Semantic-Org/Semantic-UI
 *
 *
 * Copyright 2014 Contributors
 * Released under the MIT license

 *
 */
!function(p,h,v,b){p.site=p.fn.site=function(e){var s,l,i=(new Date).getTime(),o=[],t=e,n="string"==typeof t,c=[].slice.call(arguments,1),u=p.isPlainObject(e)?p.extend(!0,{},p.site.settings,e):p.extend({},p.site.settings),a=u.namespace,d=u.error,r="module-"+a,f=p(v),m=this,g=f.data(r);return s={initialize:function(){s.instantiate()},instantiate:function(){s.verbose("Storing instance of site",s),g=s,f.data(r,s)},normalize:function(){s.fix.console(),s.fix.requestAnimationFrame()},fix:{console:function(){s.debug("Normalizing window.console"),console!==b&&console.log!==b||(s.verbose("Console not available, normalizing events"),s.disable.console()),void 0!==console.group&&void 0!==console.groupEnd&&void 0!==console.groupCollapsed||(s.verbose("Console group not available, normalizing events"),h.console.group=function(){},h.console.groupEnd=function(){},h.console.groupCollapsed=function(){}),void 0===console.markTimeline&&(s.verbose("Mark timeline not available, normalizing events"),h.console.markTimeline=function(){})},consoleClear:function(){s.debug("Disabling programmatic console clearing"),h.console.clear=function(){}},requestAnimationFrame:function(){s.debug("Normalizing requestAnimationFrame"),h.requestAnimationFrame===b&&(s.debug("RequestAnimationFrame not available, normalizing event"),h.requestAnimationFrame=h.requestAnimationFrame||h.mozRequestAnimationFrame||h.webkitRequestAnimationFrame||h.msRequestAnimationFrame||function(e){setTimeout(e,0)})}},moduleExists:function(e){return p.fn[e]!==b&&p.fn[e].settings!==b},enabled:{modules:function(e){var n=[];return e=e||u.modules,p.each(e,function(e,t){s.moduleExists(t)&&n.push(t)}),n}},disabled:{modules:function(e){var n=[];return e=e||u.modules,p.each(e,function(e,t){s.moduleExists(t)||n.push(t)}),n}},change:{setting:function(o,a,e,r){e="string"==typeof e?"all"===e?u.modules:[e]:e||u.modules,r=r===b||r,p.each(e,function(e,t){var n,i=!s.moduleExists(t)||(p.fn[t].settings.namespace||!1);s.moduleExists(t)&&(s.verbose("Changing default setting",o,a,t),p.fn[t].settings[o]=a,r&&i&&0<(n=p(":data(module-"+i+")")).length&&(s.verbose("Modifying existing settings",n),n[t]("setting",o,a)))})},settings:function(i,e,o){e="string"==typeof e?[e]:e||u.modules,o=o===b||o,p.each(e,function(e,t){var n;s.moduleExists(t)&&(s.verbose("Changing default setting",i,t),p.extend(!0,p.fn[t].settings,i),o&&a&&0<(n=p(":data(module-"+a+")")).length&&(s.verbose("Modifying existing settings",n),n[t]("setting",i)))})}},enable:{console:function(){s.console(!0)},debug:function(e,t){e=e||u.modules,s.debug("Enabling debug for modules",e),s.change.setting("debug",!0,e,t)},verbose:function(e,t){e=e||u.modules,s.debug("Enabling verbose debug for modules",e),s.change.setting("verbose",!0,e,t)}},disable:{console:function(){s.console(!1)},debug:function(e,t){e=e||u.modules,s.debug("Disabling debug for modules",e),s.change.setting("debug",!1,e,t)},verbose:function(e,t){e=e||u.modules,s.debug("Disabling verbose debug for modules",e),s.change.setting("verbose",!1,e,t)}},console:function(e){if(e){if(g.cache.console===b)return void s.error(d.console);s.debug("Restoring console function"),h.console=g.cache.console}else s.debug("Disabling console function"),g.cache.console=h.console,h.console={clear:function(){},error:function(){},group:function(){},groupCollapsed:function(){},groupEnd:function(){},info:function(){},log:function(){},markTimeline:function(){},warn:function(){}}},destroy:function(){s.verbose("Destroying previous site for",f),f.removeData(r)},cache:{},setting:function(e,t){if(p.isPlainObject(e))p.extend(!0,u,e);else{if(t===b)return u[e];u[e]=t}},internal:function(e,t){if(p.isPlainObject(e))p.extend(!0,s,e);else{if(t===b)return s[e];s[e]=t}},debug:function(){u.debug&&(u.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,u.name+":"),s.debug.apply(console,arguments)))},verbose:function(){u.verbose&&u.debug&&(u.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,u.name+":"),s.verbose.apply(console,arguments)))},error:function(){s.error=Function.prototype.bind.call(console.error,console,u.name+":"),s.error.apply(console,arguments)},performance:{log:function(e){var t,n;u.performance&&(n=(t=(new Date).getTime())-(i||t),i=t,o.push({Element:m,Name:e[0],Arguments:[].slice.call(e,1)||"","Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=u.name+":",n=0;i=!1,clearTimeout(s.performance.timer),p.each(o,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",(console.group!==b||console.table!==b)&&0<o.length&&(console.groupCollapsed(e),console.table?console.table(o):p.each(o,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),o=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||c,t=m||t,"string"==typeof i&&r!==b&&(i=i.split(/[\. ]/),o=i.length-1,p.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(p.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==b)return a=r[n],!1;if(!p.isPlainObject(r[t])||e==o)return r[t]!==b?a=r[t]:s.error(d.method,i),!1;r=r[t]}})),p.isFunction(a)?n=a.apply(t,e):a!==b&&(n=a),p.isArray(l)?l.push(n):l!==b?l=[l,n]:n!==b&&(l=n),a}},n?(g===b&&s.initialize(),s.invoke(t)):(g!==b&&s.destroy(),s.initialize()),l!==b?l:this},p.site.settings={name:"Site",namespace:"site",error:{console:"Console cannot be restored, most likely it was overwritten outside of module",method:"The method you called is not defined."},debug:!1,verbose:!1,performance:!0,modules:["accordion","api","checkbox","dimmer","dropdown","embed","form","suimodal","nag","popup","rating","shape","sidebar","state","sticky","tab","transition","visit","visibility"],siteNamespace:"site",namespaceStub:{cache:{},config:{},sections:{},section:{},utilities:{}}},p.extend(p.expr[":"],{data:p.expr.createPseudo?p.expr.createPseudo(function(t){return function(e){return!!p.data(e,t)}}):function(e,t,n){return!!p.data(e,n[3])}})}(jQuery,window,document),function(F,e,O,D){"use strict";e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),F.fn.form=function(x){var C,w=F(this),S=w.selector||"",k=(new Date).getTime(),T=[],A=x,R=arguments[1],P="string"==typeof A,E=[].slice.call(arguments,1);return w.each(function(){var n,l,t,e,d,c,u,f,m,i,s,o,a,g,p,h,r=F(this),v=this,b=[],y=!1;(h={initialize:function(){h.get.settings(),P?(p===D&&h.instantiate(),h.invoke(A)):(p!==D&&p.invoke("destroy"),h.verbose("Initializing form validation",r,d),h.bindEvents(),h.set.defaults(),h.instantiate())},instantiate:function(){h.verbose("Storing instance of module",h),p=h,r.data(a,h)},destroy:function(){h.verbose("Destroying previous module",p),h.removeEvents(),r.removeData(a)},refresh:function(){h.verbose("Refreshing selector cache"),n=r.find(f.field),l=r.find(f.group),t=r.find(f.message),r.find(f.prompt),e=r.find(f.submit),r.find(f.clear),r.find(f.reset)},submit:function(){h.verbose("Submitting form",r),r.submit()},attachEvents:function(e,t){t=t||"submit",F(e).on("click"+g,function(e){h[t](),e.preventDefault()})},bindEvents:function(){h.verbose("Attaching form events"),r.on("submit"+g,h.validate.form).on("blur"+g,f.field,h.event.field.blur).on("click"+g,f.submit,h.submit).on("click"+g,f.reset,h.reset).on("click"+g,f.clear,h.clear),d.keyboardShortcuts&&r.on("keydown"+g,f.field,h.event.field.keydown),n.each(function(){var e=F(this),t=e.prop("type"),n=h.get.changeEvent(t,e);F(this).on(n+g,h.event.field.change)})},clear:function(){n.each(function(){var e=F(this),t=e.parent(),n=e.closest(l),i=n.find(f.prompt),o=e.data(u.defaultValue)||"",a=t.is(f.uiCheckbox),r=t.is(f.uiDropdown);n.hasClass(m.error)&&(h.verbose("Resetting error on field",n),n.removeClass(m.error),i.remove()),r?(h.verbose("Resetting dropdown value",t,o),t.dropdown("clear")):a?e.prop("checked",!1):(h.verbose("Resetting field value",e,o),e.val(""))})},reset:function(){n.each(function(){var e=F(this),t=e.parent(),n=e.closest(l),i=n.find(f.prompt),o=e.data(u.defaultValue),a=t.is(f.uiCheckbox),r=t.is(f.uiDropdown),s=n.hasClass(m.error);o!==D&&(s&&(h.verbose("Resetting error on field",n),n.removeClass(m.error),i.remove()),r?(h.verbose("Resetting dropdown value",t,o),t.dropdown("restore defaults")):a?(h.verbose("Resetting checkbox value",t,o),e.prop("checked",o)):(h.verbose("Resetting field value",e,o),e.val(o)))})},determine:{isValid:function(){var n=!0;return F.each(c,function(e,t){h.validate.field(t,e,!0)||(n=!1)}),n}},is:{bracketedRule:function(e){return e.type&&e.type.match(d.regExp.bracket)},shorthandFields:function(e){var t=e[Object.keys(e)[0]];return h.is.shorthandRules(t)},shorthandRules:function(e){return"string"==typeof e||F.isArray(e)},empty:function(e){return!e||0===e.length||(e.is('input[type="checkbox"]')?!e.is(":checked"):h.is.blank(e))},blank:function(e){return""===F.trim(e.val())},valid:function(e){var n=!0;return e?(h.verbose("Checking if field is valid",e),h.validate.field(c[e],e,!1)):(h.verbose("Checking if form is valid"),F.each(c,function(e,t){h.is.valid(e)||(n=!1)}),n)}},removeEvents:function(){r.off(g),n.off(g),e.off(g),n.off(g)},event:{field:{keydown:function(e){var t=F(this),n=e.which,i=t.is(f.input),o=t.is(f.checkbox),a=0<t.closest(f.uiDropdown).length,r=13;n==27&&(h.verbose("Escape key pressed blurring field"),t.blur()),e.ctrlKey||n!=r||!i||a||o||(y||(t.one("keyup"+g,h.event.field.keyup),h.submit(),h.debug("Enter pressed on input submitting form")),y=!0)},keyup:function(){y=!1},blur:function(e){var t=F(this),n=t.closest(l),i=h.get.validation(t);n.hasClass(m.error)?(h.debug("Revalidating field",t,i),i&&h.validate.field(i)):"blur"==d.on&&i&&h.validate.field(i)},change:function(e){var t=F(this),n=t.closest(l),i=h.get.validation(t);i&&("change"==d.on||n.hasClass(m.error)&&d.revalidate)&&(clearTimeout(h.timer),h.timer=setTimeout(function(){h.debug("Revalidating field",t,h.get.validation(t)),h.validate.field(i)},d.delay))}}},get:{ancillaryValue:function(e){return!(!e.type||!e.value&&!h.is.bracketedRule(e))&&(e.value!==D?e.value:e.type.match(d.regExp.bracket)[1]+"")},ruleName:function(e){return h.is.bracketedRule(e)?e.type.replace(e.type.match(d.regExp.bracket)[0],""):e.type},changeEvent:function(e,t){return"checkbox"==e||"radio"==e||"hidden"==e||t.is("select")?"change":h.get.inputEvent()},inputEvent:function(){return O.createElement("input").oninput!==D?"input":O.createElement("input").onpropertychange!==D?"propertychange":"keyup"},fieldsFromShorthand:function(e){var i={};return F.each(e,function(n,e){"string"==typeof e&&(e=[e]),i[n]={rules:[]},F.each(e,function(e,t){i[n].rules.push({type:t})})}),i},prompt:function(e,t){var n,i,o=h.get.ruleName(e),a=h.get.ancillaryValue(e),r=h.get.field(t.identifier),s=r.val(),l=F.isFunction(e.prompt)?e.prompt(s):e.prompt||d.prompt[o]||d.text.unspecifiedRule,c=-1!==l.search("{value}"),u=-1!==l.search("{name}");return c&&(l=l.replace("{value}",r.val())),u&&(i=1==(n=r.closest(f.group).find("label").eq(0)).length?n.text():r.prop("placeholder")||d.text.unspecifiedField,l=l.replace("{name}",i)),l=(l=l.replace("{identifier}",t.identifier)).replace("{ruleValue}",a),e.prompt||h.verbose("Using default validation prompt for type",l,o),l},settings:function(){if(F.isPlainObject(x)){var e=Object.keys(x);0<e.length&&(x[e[0]].identifier!==D&&x[e[0]].rules!==D)?(d=F.extend(!0,{},F.fn.form.settings,R),c=F.extend({},F.fn.form.settings.defaults,x),h.error(d.error.oldSyntax,v),h.verbose("Extending settings from legacy parameters",c,d)):(x.fields&&h.is.shorthandFields(x.fields)&&(x.fields=h.get.fieldsFromShorthand(x.fields)),d=F.extend(!0,{},F.fn.form.settings,x),c=F.extend({},F.fn.form.settings.defaults,d.fields),h.verbose("Extending settings",c,d))}else d=F.fn.form.settings,c=F.fn.form.settings.defaults,h.verbose("Using default form validation",c,d);o=d.namespace,u=d.metadata,f=d.selector,m=d.className,i=d.regExp,s=d.error,a="module-"+o,g="."+o,p=r.data(a),h.refresh()},field:function(e){return h.verbose("Finding field with identifier",e),e=h.escape.string(e),0<n.filter("#"+e).length?n.filter("#"+e):0<n.filter('[name="'+e+'"]').length?n.filter('[name="'+e+'"]'):0<n.filter('[name="'+e+'[]"]').length?n.filter('[name="'+e+'[]"]'):0<n.filter("[data-"+u.validate+'="'+e+'"]').length?n.filter("[data-"+u.validate+'="'+e+'"]'):F("<input/>")},fields:function(e){var n=F();return F.each(e,function(e,t){n=n.add(h.get.field(t))}),n},validation:function(n){var i,o;return!!c&&(F.each(c,function(e,t){o=t.identifier||e,h.get.field(o)[0]==n[0]&&(t.identifier=o,i=t)}),i||!1)},value:function(e){var t=[];return t.push(e),h.get.values.call(v,t)[e]},values:function(e){var t=F.isArray(e)?h.get.fields(e):n,c={};return t.each(function(e,t){var n=F(t),i=(n.prop("type"),n.prop("name")),o=n.val(),a=n.is(f.checkbox),r=n.is(f.radio),s=-1!==i.indexOf("[]"),l=!!a&&n.is(":checked");i&&(s?(i=i.replace("[]",""),c[i]||(c[i]=[]),a?l?c[i].push(o||!0):c[i].push(!1):c[i].push(o)):r?c[i]!==D&&0!=c[i]||(c[i]=!!l&&(o||!0)):c[i]=a?!!l&&(o||!0):o)}),c}},has:{field:function(e){return h.verbose("Checking for existence of a field with identifier",e),"string"!=typeof(e=h.escape.string(e))&&h.error(s.identifier,e),0<n.filter("#"+e).length||(0<n.filter('[name="'+e+'"]').length||0<n.filter("[data-"+u.validate+'="'+e+'"]').length)}},escape:{string:function(e){return(e=String(e)).replace(i.escape,"\\$&")}},add:{rule:function(e,t){h.add.field(e,t)},field:function(n,e){var i={};h.is.shorthandRules(e)?(e=F.isArray(e)?e:[e],i[n]={rules:[]},F.each(e,function(e,t){i[n].rules.push({type:t})})):i[n]=e,c=F.extend({},c,i),h.debug("Adding rules",i,c)},fields:function(e){var t;t=e&&h.is.shorthandFields(e)?h.get.fieldsFromShorthand(e):e,c=F.extend({},c,t)},prompt:function(e,t){var n=h.get.field(e).closest(l),i=n.children(f.prompt),o=0!==i.length;t="string"==typeof t?[t]:t,h.verbose("Adding field error state",e),n.addClass(m.error),d.inline&&(o||(i=d.templates.prompt(t)).appendTo(n),i.html(t[0]),o?h.verbose("Inline errors are disabled, no inline error added",e):d.transition&&F.fn.transition!==D&&r.transition("is supported")?(h.verbose("Displaying error with css transition",d.transition),i.transition(d.transition+" in",d.duration)):(h.verbose("Displaying error with fallback javascript animation"),i.fadeIn(d.duration)))},errors:function(e){h.debug("Adding form error messages",e),h.set.error(),t.html(d.templates.error(e))}},remove:{rule:function(n,e){var i=F.isArray(e)?e:[e];if(e==D)return h.debug("Removed all rules"),void(c[n].rules=[]);c[n]!=D&&F.isArray(c[n].rules)&&F.each(c[n].rules,function(e,t){-1!==i.indexOf(t.type)&&(h.debug("Removed rule",t.type),c[n].rules.splice(e,1))})},field:function(e){var t=F.isArray(e)?e:[e];F.each(t,function(e,t){h.remove.rule(t)})},rules:function(e,n){F.isArray(e)?F.each(fields,function(e,t){h.remove.rule(t,n)}):h.remove.rule(e,n)},fields:function(e){h.remove.field(e)},prompt:function(e){var t=h.get.field(e).closest(l),n=t.children(f.prompt);t.removeClass(m.error),d.inline&&n.is(":visible")&&(h.verbose("Removing prompt for field",e),d.transition&&F.fn.transition!==D&&r.transition("is supported")?n.transition(d.transition+" out",d.duration,function(){n.remove()}):n.fadeOut(d.duration,function(){n.remove()}))}},set:{success:function(){r.removeClass(m.error).addClass(m.success)},defaults:function(){n.each(function(){var e=F(this),t=0<e.filter(f.checkbox).length?e.is(":checked"):e.val();e.data(u.defaultValue,t)})},error:function(){r.removeClass(m.success).addClass(m.error)},value:function(e,t){var n={};return n[e]=t,h.set.values.call(v,n)},values:function(e){F.isEmptyObject(e)||F.each(e,function(e,t){var n,i=h.get.field(e),o=i.parent(),a=F.isArray(t),r=o.is(f.uiCheckbox),s=o.is(f.uiDropdown),l=i.is(f.radio)&&r;0<i.length&&(a&&r?(h.verbose("Selecting multiple",t,i),o.checkbox("uncheck"),F.each(t,function(e,t){n=i.filter('[value="'+t+'"]'),o=n.parent(),0<n.length&&o.checkbox("check")})):l?(h.verbose("Selecting radio value",t,i),i.filter('[value="'+t+'"]').parent(f.uiCheckbox).checkbox("check")):r?(h.verbose("Setting checkbox value",t,o),!0===t?o.checkbox("check"):o.checkbox("uncheck")):s?(h.verbose("Setting dropdown value",t,o),o.dropdown("set selected",t)):(h.verbose("Setting field value",t,i),i.val(t)))})}},validate:{form:function(e,t){var n=h.get.values();if(y)return!1;if(b=[],h.determine.isValid()){if(h.debug("Form has no validation errors, submitting"),h.set.success(),!0!==t)return d.onSuccess.call(v,e,n)}else if(h.debug("Form has errors"),h.set.error(),d.inline||h.add.errors(b),r.data("moduleApi")!==D&&e.stopImmediatePropagation(),!0!==t)return d.onFailure.call(v,b,n)},field:function(n,e,t){t=t===D||t,"string"==typeof n&&(h.verbose("Validating field",n),n=c[e=n]);var i=n.identifier||e,o=h.get.field(i),a=!!n.depends&&h.get.field(n.depends),r=!0,s=[];return n.identifier||(h.debug("Using field name as identifier",i),n.identifier=i),o.prop("disabled")?(h.debug("Field is disabled. Skipping",i),r=!0):n.optional&&h.is.blank(o)?(h.debug("Field is optional and blank. Skipping",i),r=!0):n.depends&&h.is.empty(a)?(h.debug("Field depends on another value that is not present or empty. Skipping",a),r=!0):n.rules!==D&&F.each(n.rules,function(e,t){h.has.field(i)&&!h.validate.rule(n,t)&&(h.debug("Field is invalid",i,t.type),s.push(h.get.prompt(t,n)),r=!1)}),r?(t&&(h.remove.prompt(i,s),d.onValid.call(o)),!0):(t&&(b=b.concat(s),h.add.prompt(i,s),d.onInvalid.call(o,s)),!1)},rule:function(e,t){var n=h.get.field(e.identifier),i=(t.type,n.val()),o=h.get.ancillaryValue(t),a=h.get.ruleName(t),r=d.rules[a];if(F.isFunction(r))return i=i===D||""===i||null===i?"":F.trim(i+""),r.call(n,i,o);h.error(s.noRule,a)}},setting:function(e,t){if(F.isPlainObject(e))F.extend(!0,d,e);else{if(t===D)return d[e];d[e]=t}},internal:function(e,t){if(F.isPlainObject(e))F.extend(!0,h,e);else{if(t===D)return h[e];h[e]=t}},debug:function(){!d.silent&&d.debug&&(d.performance?h.performance.log(arguments):(h.debug=Function.prototype.bind.call(console.info,console,d.name+":"),h.debug.apply(console,arguments)))},verbose:function(){!d.silent&&d.verbose&&d.debug&&(d.performance?h.performance.log(arguments):(h.verbose=Function.prototype.bind.call(console.info,console,d.name+":"),h.verbose.apply(console,arguments)))},error:function(){d.silent||(h.error=Function.prototype.bind.call(console.error,console,d.name+":"),h.error.apply(console,arguments))},performance:{log:function(e){var t,n;d.performance&&(n=(t=(new Date).getTime())-(k||t),k=t,T.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:v,"Execution Time":n})),clearTimeout(h.performance.timer),h.performance.timer=setTimeout(h.performance.display,500)},display:function(){var e=d.name+":",n=0;k=!1,clearTimeout(h.performance.timer),F.each(T,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",S&&(e+=" '"+S+"'"),1<w.length&&(e+=" ("+w.length+")"),(console.group!==D||console.table!==D)&&0<T.length&&(console.groupCollapsed(e),console.table?console.table(T):F.each(T,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),T=[]}},invoke:function(i,e,t){var o,a,n,r=p;return e=e||E,t=v||t,"string"==typeof i&&r!==D&&(i=i.split(/[\. ]/),o=i.length-1,F.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(F.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==D)return a=r[n],!1;if(!F.isPlainObject(r[t])||e==o)return r[t]!==D&&(a=r[t]),!1;r=r[t]}})),F.isFunction(a)?n=a.apply(t,e):a!==D&&(n=a),F.isArray(C)?C.push(n):C!==D?C=[C,n]:n!==D&&(C=n),a}}).initialize()}),C!==D?C:this},F.fn.form.settings={name:"Form",namespace:"form",debug:!1,verbose:!1,performance:!0,fields:!1,keyboardShortcuts:!0,on:"submit",inline:!1,delay:200,revalidate:!0,transition:"scale",duration:200,onValid:function(){},onInvalid:function(){},onSuccess:function(){return!0},onFailure:function(){return!1},metadata:{defaultValue:"default",validate:"validate"},regExp:{htmlID:/^[a-zA-Z][\w:.-]*$/g,bracket:/\[(.*)\]/i,decimal:/^\d+\.?\d*$/,email:/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i,escape:/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,flags:/^\/(.*)\/(.*)?/,integer:/^\-?\d+$/,number:/^\-?\d*(\.\d+)?$/,url:/(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/i},text:{unspecifiedRule:"Please enter a valid value",unspecifiedField:"This field"},prompt:{empty:"{name} must have a value",checked:"{name} must be checked",email:"{name} must be a valid e-mail",url:"{name} must be a valid url",regExp:"{name} is not formatted correctly",integer:"{name} must be an integer",decimal:"{name} must be a decimal number",number:"{name} must be set to a number",is:'{name} must be "{ruleValue}"',isExactly:'{name} must be exactly "{ruleValue}"',not:'{name} cannot be set to "{ruleValue}"',notExactly:'{name} cannot be set to exactly "{ruleValue}"',contain:'{name} must contain "{ruleValue}"',containExactly:'{name} must contain exactly "{ruleValue}"',doesntContain:'{name} cannot contain  "{ruleValue}"',doesntContainExactly:'{name} cannot contain exactly "{ruleValue}"',minLength:"{name} must be at least {ruleValue} characters",length:"{name} must be at least {ruleValue} characters",exactLength:"{name} must be exactly {ruleValue} characters",maxLength:"{name} cannot be longer than {ruleValue} characters",match:"{name} must match {ruleValue} field",different:"{name} must have a different value than {ruleValue} field",creditCard:"{name} must be a valid credit card number",minCount:"{name} must have at least {ruleValue} choices",exactCount:"{name} must have exactly {ruleValue} choices",maxCount:"{name} must have {ruleValue} or less choices"},selector:{checkbox:'input[type="checkbox"], input[type="radio"]',clear:".clear",field:"input, textarea, select",group:".field",input:"input",message:".error.message",prompt:".prompt.label",radio:'input[type="radio"]',reset:'.reset:not([type="reset"])',submit:'.submit:not([type="submit"])',uiCheckbox:".ui.checkbox",uiDropdown:".ui.dropdown"},className:{error:"error",label:"ui prompt label",pressed:"down",success:"success"},error:{identifier:"You must specify a string identifier for each field",method:"The method you called is not defined.",noRule:"There is no rule matching the one you specified",oldSyntax:"Starting in 2.0 forms now only take a single settings object. Validation settings converted to new syntax automatically."},templates:{error:function(e){var n='<ul class="list">';return F.each(e,function(e,t){n+="<li>"+t+"</li>"}),F(n+="</ul>")},prompt:function(e){return F("<div/>").addClass("ui basic red pointing prompt label").html(e[0])}},rules:{empty:function(e){return!(e===D||""===e||F.isArray(e)&&0===e.length)},checked:function(){return 0<F(this).filter(":checked").length},email:function(e){return F.fn.form.settings.regExp.email.test(e)},url:function(e){return F.fn.form.settings.regExp.url.test(e)},regExp:function(e,t){if(t instanceof RegExp)return e.match(t);var n,i=t.match(F.fn.form.settings.regExp.flags);return i&&(t=2<=i.length?i[1]:t,n=3<=i.length?i[2]:""),e.match(new RegExp(t,n))},integer:function(e,t){var n,i,o,a=F.fn.form.settings.regExp.integer;return t&&-1===["",".."].indexOf(t)&&(-1==t.indexOf("..")?a.test(t)&&(n=i=t-0):(o=t.split("..",2),a.test(o[0])&&(n=o[0]-0),a.test(o[1])&&(i=o[1]-0))),a.test(e)&&(n===D||n<=e)&&(i===D||e<=i)},decimal:function(e){return F.fn.form.settings.regExp.decimal.test(e)},number:function(e){return F.fn.form.settings.regExp.number.test(e)},is:function(e,t){return t="string"==typeof t?t.toLowerCase():t,(e="string"==typeof e?e.toLowerCase():e)==t},isExactly:function(e,t){return e==t},not:function(e,t){return(e="string"==typeof e?e.toLowerCase():e)!=(t="string"==typeof t?t.toLowerCase():t)},notExactly:function(e,t){return e!=t},contains:function(e,t){return t=t.replace(F.fn.form.settings.regExp.escape,"\\$&"),-1!==e.search(new RegExp(t,"i"))},containsExactly:function(e,t){return t=t.replace(F.fn.form.settings.regExp.escape,"\\$&"),-1!==e.search(new RegExp(t))},doesntContain:function(e,t){return t=t.replace(F.fn.form.settings.regExp.escape,"\\$&"),-1===e.search(new RegExp(t,"i"))},doesntContainExactly:function(e,t){return t=t.replace(F.fn.form.settings.regExp.escape,"\\$&"),-1===e.search(new RegExp(t))},minLength:function(e,t){return e!==D&&e.length>=t},length:function(e,t){return e!==D&&e.length>=t},exactLength:function(e,t){return e!==D&&e.length==t},maxLength:function(e,t){return e!==D&&e.length<=t},match:function(e,t){var n;F(this);return 0<F('[data-validate="'+t+'"]').length?n=F('[data-validate="'+t+'"]').val():0<F("#"+t).length?n=F("#"+t).val():0<F('[name="'+t+'"]').length?n=F('[name="'+t+'"]').val():0<F('[name="'+t+'[]"]').length&&(n=F('[name="'+t+'[]"]')),n!==D&&e.toString()==n.toString()},different:function(e,t){var n;F(this);return 0<F('[data-validate="'+t+'"]').length?n=F('[data-validate="'+t+'"]').val():0<F("#"+t).length?n=F("#"+t).val():0<F('[name="'+t+'"]').length?n=F('[name="'+t+'"]').val():0<F('[name="'+t+'[]"]').length&&(n=F('[name="'+t+'[]"]')),n!==D&&e.toString()!==n.toString()},creditCard:function(n,e){var t,i,o={visa:{pattern:/^4/,length:[16]},amex:{pattern:/^3[47]/,length:[15]},mastercard:{pattern:/^5[1-5]/,length:[16]},discover:{pattern:/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/,length:[16]},unionPay:{pattern:/^(62|88)/,length:[16,17,18,19]},jcb:{pattern:/^35(2[89]|[3-8][0-9])/,length:[16]},maestro:{pattern:/^(5018|5020|5038|6304|6759|676[1-3])/,length:[12,13,14,15,16,17,18,19]},dinersClub:{pattern:/^(30[0-5]|^36)/,length:[14]},laser:{pattern:/^(6304|670[69]|6771)/,length:[16,17,18,19]},visaElectron:{pattern:/^(4026|417500|4508|4844|491(3|7))/,length:[16]}},a={},r=!1,s="string"==typeof e&&e.split(",");if("string"==typeof n&&0!==n.length){if(n=n.replace(/[\-]/g,""),s&&(F.each(s,function(e,t){(i=o[t])&&(a={length:-1!==F.inArray(n.length,i.length),pattern:-1!==n.search(i.pattern)}).length&&a.pattern&&(r=!0)}),!r))return!1;if((t={number:-1!==F.inArray(n.length,o.unionPay.length),pattern:-1!==n.search(o.unionPay.pattern)}).number&&t.pattern)return!0;for(var l=n.length,c=0,u=[[0,1,2,3,4,5,6,7,8,9],[0,2,4,6,8,1,3,5,7,9]],d=0;l--;)d+=u[c][parseInt(n.charAt(l),10)],c^=1;return d%10==0&&0<d}},minCount:function(e,t){return 0==t||(1==t?""!==e:e.split(",").length>=t)},exactCount:function(e,t){return 0==t?""===e:1==t?""!==e&&-1===e.search(","):e.split(",").length==t},maxCount:function(e,t){return 0!=t&&(1==t?-1===e.search(","):e.split(",").length<=t)}}}}(jQuery,window,document),function(S,k,e,T){"use strict";k=void 0!==k&&k.Math==Math?k:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),S.fn.accordion=function(a){var v,r=S(this),b=(new Date).getTime(),y=[],x=a,C="string"==typeof x,w=[].slice.call(arguments,1);k.requestAnimationFrame||k.mozRequestAnimationFrame||k.webkitRequestAnimationFrame||k.msRequestAnimationFrame;return r.each(function(){var e,c,u=S.isPlainObject(a)?S.extend(!0,{},S.fn.accordion.settings,a):S.extend({},S.fn.accordion.settings),d=u.className,t=u.namespace,f=u.selector,s=u.error,n="."+t,i="module-"+t,o=r.selector||"",m=S(this),g=m.find(f.title),p=m.find(f.content),l=this,h=m.data(i);c={initialize:function(){c.debug("Initializing",m),c.bind.events(),u.observeChanges&&c.observeChanges(),c.instantiate()},instantiate:function(){h=c,m.data(i,c)},destroy:function(){c.debug("Destroying previous instance",m),m.off(n).removeData(i)},refresh:function(){g=m.find(f.title),p=m.find(f.content)},observeChanges:function(){"MutationObserver"in k&&((e=new MutationObserver(function(e){c.debug("DOM tree modified, updating selector cache"),c.refresh()})).observe(l,{childList:!0,subtree:!0}),c.debug("Setting up mutation observer",e))},bind:{events:function(){c.debug("Binding delegated events"),m.on(u.on+n,f.trigger,c.event.click)}},event:{click:function(){c.toggle.call(this)}},toggle:function(e){var t=e!==T?"number"==typeof e?g.eq(e):S(e).closest(f.title):S(this).closest(f.title),n=t.next(p),i=n.hasClass(d.animating),o=n.hasClass(d.active),a=o&&!i,r=!o&&i;c.debug("Toggling visibility of content",t),a||r?u.collapsible?c.close.call(t):c.debug("Cannot close accordion content collapsing is disabled"):c.open.call(t)},open:function(e){var t=e!==T?"number"==typeof e?g.eq(e):S(e).closest(f.title):S(this).closest(f.title),n=t.next(p),i=n.hasClass(d.animating);n.hasClass(d.active)||i?c.debug("Accordion already open, skipping",n):(c.debug("Opening accordion content",t),u.onOpening.call(n),u.onChanging.call(n),u.exclusive&&c.closeOthers.call(t),t.addClass(d.active),n.stop(!0,!0).addClass(d.animating),u.animateChildren&&(S.fn.transition!==T&&m.transition("is supported")?n.children().transition({animation:"fade in",queue:!1,useFailSafe:!0,debug:u.debug,verbose:u.verbose,duration:u.duration}):n.children().stop(!0,!0).animate({opacity:1},u.duration,c.resetOpacity)),n.slideDown(u.duration,u.easing,function(){n.removeClass(d.animating).addClass(d.active),c.reset.display.call(this),u.onOpen.call(this),u.onChange.call(this)}))},close:function(e){var t=e!==T?"number"==typeof e?g.eq(e):S(e).closest(f.title):S(this).closest(f.title),n=t.next(p),i=n.hasClass(d.animating),o=n.hasClass(d.active);!o&&!(!o&&i)||o&&i||(c.debug("Closing accordion content",n),u.onClosing.call(n),u.onChanging.call(n),t.removeClass(d.active),n.stop(!0,!0).addClass(d.animating),u.animateChildren&&(S.fn.transition!==T&&m.transition("is supported")?n.children().transition({animation:"fade out",queue:!1,useFailSafe:!0,debug:u.debug,verbose:u.verbose,duration:u.duration}):n.children().stop(!0,!0).animate({opacity:0},u.duration,c.resetOpacity)),n.slideUp(u.duration,u.easing,function(){n.removeClass(d.animating).removeClass(d.active),c.reset.display.call(this),u.onClose.call(this),u.onChange.call(this)}))},closeOthers:function(e){var t,n,i,o=e!==T?g.eq(e):S(this).closest(f.title),a=o.parents(f.content).prev(f.title),r=o.closest(f.accordion),s=f.title+"."+d.active+":visible",l=f.content+"."+d.active+":visible";i=u.closeNested?(t=r.find(s).not(a)).next(p):(t=r.find(s).not(a),n=r.find(l).find(s).not(a),(t=t.not(n)).next(p)),0<t.length&&(c.debug("Exclusive enabled, closing other content",t),t.removeClass(d.active),i.removeClass(d.animating).stop(!0,!0),u.animateChildren&&(S.fn.transition!==T&&m.transition("is supported")?i.children().transition({animation:"fade out",useFailSafe:!0,debug:u.debug,verbose:u.verbose,duration:u.duration}):i.children().stop(!0,!0).animate({opacity:0},u.duration,c.resetOpacity)),i.slideUp(u.duration,u.easing,function(){S(this).removeClass(d.active),c.reset.display.call(this)}))},reset:{display:function(){c.verbose("Removing inline display from element",this),S(this).css("display",""),""===S(this).attr("style")&&S(this).attr("style","").removeAttr("style")},opacity:function(){c.verbose("Removing inline opacity from element",this),S(this).css("opacity",""),""===S(this).attr("style")&&S(this).attr("style","").removeAttr("style")}},setting:function(e,t){if(c.debug("Changing setting",e,t),S.isPlainObject(e))S.extend(!0,u,e);else{if(t===T)return u[e];S.isPlainObject(u[e])?S.extend(!0,u[e],t):u[e]=t}},internal:function(e,t){if(c.debug("Changing internal",e,t),t===T)return c[e];S.isPlainObject(e)?S.extend(!0,c,e):c[e]=t},debug:function(){!u.silent&&u.debug&&(u.performance?c.performance.log(arguments):(c.debug=Function.prototype.bind.call(console.info,console,u.name+":"),c.debug.apply(console,arguments)))},verbose:function(){!u.silent&&u.verbose&&u.debug&&(u.performance?c.performance.log(arguments):(c.verbose=Function.prototype.bind.call(console.info,console,u.name+":"),c.verbose.apply(console,arguments)))},error:function(){u.silent||(c.error=Function.prototype.bind.call(console.error,console,u.name+":"),c.error.apply(console,arguments))},performance:{log:function(e){var t,n;u.performance&&(n=(t=(new Date).getTime())-(b||t),b=t,y.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:l,"Execution Time":n})),clearTimeout(c.performance.timer),c.performance.timer=setTimeout(c.performance.display,500)},display:function(){var e=u.name+":",n=0;b=!1,clearTimeout(c.performance.timer),S.each(y,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",o&&(e+=" '"+o+"'"),(console.group!==T||console.table!==T)&&0<y.length&&(console.groupCollapsed(e),console.table?console.table(y):S.each(y,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),y=[]}},invoke:function(i,e,t){var o,a,n,r=h;return e=e||w,t=l||t,"string"==typeof i&&r!==T&&(i=i.split(/[\. ]/),o=i.length-1,S.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(S.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==T)return a=r[n],!1;if(!S.isPlainObject(r[t])||e==o)return r[t]!==T?a=r[t]:c.error(s.method,i),!1;r=r[t]}})),S.isFunction(a)?n=a.apply(t,e):a!==T&&(n=a),S.isArray(v)?v.push(n):v!==T?v=[v,n]:n!==T&&(v=n),a}},C?(h===T&&c.initialize(),c.invoke(x)):(h!==T&&h.invoke("destroy"),c.initialize())}),v!==T?v:this},S.fn.accordion.settings={name:"Accordion",namespace:"accordion",silent:!1,debug:!1,verbose:!1,performance:!0,on:"click",observeChanges:!0,exclusive:!0,collapsible:!0,closeNested:!1,animateChildren:!0,duration:350,easing:"easeOutQuad",onOpening:function(){},onClosing:function(){},onChanging:function(){},onOpen:function(){},onClose:function(){},onChange:function(){},error:{method:"The method you called is not defined"},className:{active:"active",animating:"animating"},selector:{accordion:".accordion",title:".title",trigger:".title",content:".content"}},S.extend(S.easing,{easeOutQuad:function(e,t,n,i,o){return-i*(t/=o)*(t-2)+n}})}(jQuery,window,document),function(T,A,R,P){"use strict";A=void 0!==A&&A.Math==Math?A:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),T.fn.checkbox=function(v){var b,e=T(this),y=e.selector||"",x=(new Date).getTime(),C=[],w=v,S="string"==typeof w,k=[].slice.call(arguments,1);return e.each(function(){var e,s,i=T.extend(!0,{},T.fn.checkbox.settings,v),t=i.className,n=i.namespace,o=i.selector,l=i.error,a="."+n,r="module-"+n,c=T(this),u=T(this).children(o.label),d=T(this).children(o.input),f=d[0],m=!1,g=!1,p=c.data(r),h=this;s={initialize:function(){s.verbose("Initializing checkbox",i),s.create.label(),s.bind.events(),s.set.tabbable(),s.hide.input(),s.observeChanges(),s.instantiate(),s.setup()},instantiate:function(){s.verbose("Storing instance of module",s),p=s,c.data(r,s)},destroy:function(){s.verbose("Destroying module"),s.unbind.events(),s.show.input(),c.removeData(r)},fix:{reference:function(){c.is(o.input)&&(s.debug("Behavior called on <input> adjusting invoked element"),c=c.closest(o.checkbox),s.refresh())}},setup:function(){s.set.initialLoad(),s.is.indeterminate()?(s.debug("Initial value is indeterminate"),s.indeterminate()):s.is.checked()?(s.debug("Initial value is checked"),s.check()):(s.debug("Initial value is unchecked"),s.uncheck()),s.remove.initialLoad()},refresh:function(){u=c.children(o.label),d=c.children(o.input),f=d[0]},hide:{input:function(){s.verbose("Modifying <input> z-index to be unselectable"),d.addClass(t.hidden)}},show:{input:function(){s.verbose("Modifying <input> z-index to be selectable"),d.removeClass(t.hidden)}},observeChanges:function(){"MutationObserver"in A&&((e=new MutationObserver(function(e){s.debug("DOM tree modified, updating selector cache"),s.refresh()})).observe(h,{childList:!0,subtree:!0}),s.debug("Setting up mutation observer",e))},attachEvents:function(e,t){var n=T(e);t=T.isFunction(s[t])?s[t]:s.toggle,0<n.length?(s.debug("Attaching checkbox events to element",e,t),n.on("click"+a,t)):s.error(l.notFound)},event:{click:function(e){var t=T(e.target);t.is(o.input)?s.verbose("Using default check action on initialized checkbox"):t.is(o.link)?s.debug("Clicking link inside checkbox, skipping toggle"):(s.toggle(),d.focus(),e.preventDefault())},keydown:function(e){var t=e.which,n=13,i=32;g=t==27?(s.verbose("Escape key pressed blurring field"),d.blur(),!0):!(e.ctrlKey||t!=i&&t!=n)&&(s.verbose("Enter/space key pressed, toggling checkbox"),s.toggle(),!0)},keyup:function(e){g&&e.preventDefault()}},check:function(){s.should.allowCheck()&&(s.debug("Checking checkbox",d),s.set.checked(),s.should.ignoreCallbacks()||(i.onChecked.call(f),i.onChange.call(f)))},uncheck:function(){s.should.allowUncheck()&&(s.debug("Unchecking checkbox"),s.set.unchecked(),s.should.ignoreCallbacks()||(i.onUnchecked.call(f),i.onChange.call(f)))},indeterminate:function(){s.should.allowIndeterminate()?s.debug("Checkbox is already indeterminate"):(s.debug("Making checkbox indeterminate"),s.set.indeterminate(),s.should.ignoreCallbacks()||(i.onIndeterminate.call(f),i.onChange.call(f)))},determinate:function(){s.should.allowDeterminate()?s.debug("Checkbox is already determinate"):(s.debug("Making checkbox determinate"),s.set.determinate(),s.should.ignoreCallbacks()||(i.onDeterminate.call(f),i.onChange.call(f)))},enable:function(){s.is.enabled()?s.debug("Checkbox is already enabled"):(s.debug("Enabling checkbox"),s.set.enabled(),i.onEnable.call(f),i.onEnabled.call(f))},disable:function(){s.is.disabled()?s.debug("Checkbox is already disabled"):(s.debug("Disabling checkbox"),s.set.disabled(),i.onDisable.call(f),i.onDisabled.call(f))},get:{radios:function(){var e=s.get.name();return T('input[name="'+e+'"]').closest(o.checkbox)},otherRadios:function(){return s.get.radios().not(c)},name:function(){return d.attr("name")}},is:{initialLoad:function(){return m},radio:function(){return d.hasClass(t.radio)||"radio"==d.attr("type")},indeterminate:function(){return d.prop("indeterminate")!==P&&d.prop("indeterminate")},checked:function(){return d.prop("checked")!==P&&d.prop("checked")},disabled:function(){return d.prop("disabled")!==P&&d.prop("disabled")},enabled:function(){return!s.is.disabled()},determinate:function(){return!s.is.indeterminate()},unchecked:function(){return!s.is.checked()}},should:{allowCheck:function(){return s.is.determinate()&&s.is.checked()&&!s.should.forceCallbacks()?(s.debug("Should not allow check, checkbox is already checked"),!1):!1!==i.beforeChecked.apply(f)||(s.debug("Should not allow check, beforeChecked cancelled"),!1)},allowUncheck:function(){return s.is.determinate()&&s.is.unchecked()&&!s.should.forceCallbacks()?(s.debug("Should not allow uncheck, checkbox is already unchecked"),!1):!1!==i.beforeUnchecked.apply(f)||(s.debug("Should not allow uncheck, beforeUnchecked cancelled"),!1)},allowIndeterminate:function(){return s.is.indeterminate()&&!s.should.forceCallbacks()?(s.debug("Should not allow indeterminate, checkbox is already indeterminate"),!1):!1!==i.beforeIndeterminate.apply(f)||(s.debug("Should not allow indeterminate, beforeIndeterminate cancelled"),!1)},allowDeterminate:function(){return s.is.determinate()&&!s.should.forceCallbacks()?(s.debug("Should not allow determinate, checkbox is already determinate"),!1):!1!==i.beforeDeterminate.apply(f)||(s.debug("Should not allow determinate, beforeDeterminate cancelled"),!1)},forceCallbacks:function(){return s.is.initialLoad()&&i.fireOnInit},ignoreCallbacks:function(){return m&&!i.fireOnInit}},can:{change:function(){return!(c.hasClass(t.disabled)||c.hasClass(t.readOnly)||d.prop("disabled")||d.prop("readonly"))},uncheck:function(){return"boolean"==typeof i.uncheckable?i.uncheckable:!s.is.radio()}},set:{initialLoad:function(){m=!0},checked:function(){s.verbose("Setting class to checked"),c.removeClass(t.indeterminate).addClass(t.checked),s.is.radio()&&s.uncheckOthers(),s.is.indeterminate()||!s.is.checked()?(s.verbose("Setting state to checked",f),d.prop("indeterminate",!1).prop("checked",!0),s.trigger.change()):s.debug("Input is already checked, skipping input property change")},unchecked:function(){s.verbose("Removing checked class"),c.removeClass(t.indeterminate).removeClass(t.checked),s.is.indeterminate()||!s.is.unchecked()?(s.debug("Setting state to unchecked"),d.prop("indeterminate",!1).prop("checked",!1),s.trigger.change()):s.debug("Input is already unchecked")},indeterminate:function(){s.verbose("Setting class to indeterminate"),c.addClass(t.indeterminate),s.is.indeterminate()?s.debug("Input is already indeterminate, skipping input property change"):(s.debug("Setting state to indeterminate"),d.prop("indeterminate",!0),s.trigger.change())},determinate:function(){s.verbose("Removing indeterminate class"),c.removeClass(t.indeterminate),s.is.determinate()?s.debug("Input is already determinate, skipping input property change"):(s.debug("Setting state to determinate"),d.prop("indeterminate",!1))},disabled:function(){s.verbose("Setting class to disabled"),c.addClass(t.disabled),s.is.disabled()?s.debug("Input is already disabled, skipping input property change"):(s.debug("Setting state to disabled"),d.prop("disabled","disabled"),s.trigger.change())},enabled:function(){s.verbose("Removing disabled class"),c.removeClass(t.disabled),s.is.enabled()?s.debug("Input is already enabled, skipping input property change"):(s.debug("Setting state to enabled"),d.prop("disabled",!1),s.trigger.change())},tabbable:function(){s.verbose("Adding tabindex to checkbox"),d.attr("tabindex")===P&&d.attr("tabindex",0)}},remove:{initialLoad:function(){m=!1}},trigger:{change:function(){var e=R.createEvent("HTMLEvents"),t=d[0];t&&(s.verbose("Triggering native change event"),e.initEvent("change",!0,!1),t.dispatchEvent(e))}},create:{label:function(){0<d.prevAll(o.label).length?(d.prev(o.label).detach().insertAfter(d),s.debug("Moving existing label",u)):s.has.label()||(u=T("<label>").insertAfter(d),s.debug("Creating label",u))}},has:{label:function(){return 0<u.length}},bind:{events:function(){s.verbose("Attaching checkbox events"),c.on("click"+a,s.event.click).on("keydown"+a,o.input,s.event.keydown).on("keyup"+a,o.input,s.event.keyup)}},unbind:{events:function(){s.debug("Removing events"),c.off(a)}},uncheckOthers:function(){var e=s.get.otherRadios();s.debug("Unchecking other radios",e),e.removeClass(t.checked)},toggle:function(){s.can.change()?s.is.indeterminate()||s.is.unchecked()?(s.debug("Currently unchecked"),s.check()):s.is.checked()&&s.can.uncheck()&&(s.debug("Currently checked"),s.uncheck()):s.is.radio()||s.debug("Checkbox is read-only or disabled, ignoring toggle")},setting:function(e,t){if(s.debug("Changing setting",e,t),T.isPlainObject(e))T.extend(!0,i,e);else{if(t===P)return i[e];T.isPlainObject(i[e])?T.extend(!0,i[e],t):i[e]=t}},internal:function(e,t){if(T.isPlainObject(e))T.extend(!0,s,e);else{if(t===P)return s[e];s[e]=t}},debug:function(){!i.silent&&i.debug&&(i.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,i.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!i.silent&&i.verbose&&i.debug&&(i.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,i.name+":"),s.verbose.apply(console,arguments)))},error:function(){i.silent||(s.error=Function.prototype.bind.call(console.error,console,i.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;i.performance&&(n=(t=(new Date).getTime())-(x||t),x=t,C.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:h,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=i.name+":",n=0;x=!1,clearTimeout(s.performance.timer),T.each(C,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",y&&(e+=" '"+y+"'"),(console.group!==P||console.table!==P)&&0<C.length&&(console.groupCollapsed(e),console.table?console.table(C):T.each(C,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),C=[]}},invoke:function(i,e,t){var o,a,n,r=p;return e=e||k,t=h||t,"string"==typeof i&&r!==P&&(i=i.split(/[\. ]/),o=i.length-1,T.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(T.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==P)return a=r[n],!1;if(!T.isPlainObject(r[t])||e==o)return r[t]!==P?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),T.isFunction(a)?n=a.apply(t,e):a!==P&&(n=a),T.isArray(b)?b.push(n):b!==P?b=[b,n]:n!==P&&(b=n),a}},S?(p===P&&s.initialize(),s.invoke(w)):(p!==P&&p.invoke("destroy"),s.initialize())}),b!==P?b:this},T.fn.checkbox.settings={name:"Checkbox",namespace:"checkbox",silent:!1,debug:!1,verbose:!0,performance:!0,uncheckable:"auto",fireOnInit:!1,onChange:function(){},beforeChecked:function(){},beforeUnchecked:function(){},beforeDeterminate:function(){},beforeIndeterminate:function(){},onChecked:function(){},onUnchecked:function(){},onDeterminate:function(){},onIndeterminate:function(){},onEnable:function(){},onDisable:function(){},onEnabled:function(){},onDisabled:function(){},className:{checked:"checked",indeterminate:"indeterminate",disabled:"disabled",hidden:"hidden",radio:"radio",readOnly:"read-only"},error:{method:"The method you called is not defined"},selector:{checkbox:".ui.checkbox",label:"label, .box",input:'input[type="checkbox"], input[type="radio"]',link:"a[href]"}}}(jQuery,window,document),function(S,e,k,T){"use strict";e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),S.fn.dimmer=function(p){var h,v=S(this),b=(new Date).getTime(),y=[],x=p,C="string"==typeof x,w=[].slice.call(arguments,1);return v.each(function(){var a,t,s,r=S.isPlainObject(p)?S.extend(!0,{},S.fn.dimmer.settings,p):S.extend({},S.fn.dimmer.settings),n=r.selector,e=r.namespace,i=r.className,l=r.error,o="."+e,c="module-"+e,u=v.selector||"",d="ontouchstart"in k.documentElement?"touchstart":"click",f=S(this),m=this,g=f.data(c);(s={preinitialize:function(){a=s.is.dimmer()?(t=f.parent(),f):(t=f,s.has.dimmer()?r.dimmerName?t.find(n.dimmer).filter("."+r.dimmerName):t.find(n.dimmer):s.create())},initialize:function(){s.debug("Initializing dimmer",r),s.bind.events(),s.set.dimmable(),s.instantiate()},instantiate:function(){s.verbose("Storing instance of module",s),g=s,f.data(c,g)},destroy:function(){s.verbose("Destroying previous module",a),s.unbind.events(),s.remove.variation(),t.off(o)},bind:{events:function(){"hover"==r.on?t.on("mouseenter"+o,s.show).on("mouseleave"+o,s.hide):"click"==r.on&&t.on(d+o,s.toggle),s.is.page()&&(s.debug("Setting as a page dimmer",t),s.set.pageDimmer()),s.is.closable()&&(s.verbose("Adding dimmer close event",a),t.on(d+o,n.dimmer,s.event.click))}},unbind:{events:function(){f.removeData(c),t.off(o)}},event:{click:function(e){s.verbose("Determining if event occured on dimmer",e),(0===a.find(e.target).length||S(e.target).is(n.content))&&(s.hide(),e.stopImmediatePropagation())}},addContent:function(e){var t=S(e);s.debug("Add content to dimmer",t),t.parent()[0]!==a[0]&&t.detach().appendTo(a)},create:function(){var e=S(r.template.dimmer());return r.dimmerName&&(s.debug("Creating named dimmer",r.dimmerName),e.addClass(r.dimmerName)),e.appendTo(t),e},show:function(e){e=S.isFunction(e)?e:function(){},s.debug("Showing dimmer",a,r),s.set.variation(),s.is.dimmed()&&!s.is.animating()||!s.is.enabled()?s.debug("Dimmer is already shown or disabled"):(s.animate.show(e),r.onShow.call(m),r.onChange.call(m))},hide:function(e){e=S.isFunction(e)?e:function(){},s.is.dimmed()||s.is.animating()?(s.debug("Hiding dimmer",a),s.animate.hide(e),r.onHide.call(m),r.onChange.call(m)):s.debug("Dimmer is not visible")},toggle:function(){s.verbose("Toggling dimmer visibility",a),s.is.dimmed()?s.hide():s.show()},animate:{show:function(e){e=S.isFunction(e)?e:function(){},r.useCSS&&S.fn.transition!==T&&a.transition("is supported")?(r.useFlex?(s.debug("Using flex dimmer"),s.remove.legacy()):(s.debug("Using legacy non-flex dimmer"),s.set.legacy()),"auto"!==r.opacity&&s.set.opacity(),a.transition({displayType:r.useFlex?"flex":"block",animation:r.transition+" in",queue:!1,duration:s.get.duration(),useFailSafe:!0,onStart:function(){s.set.dimmed()},onComplete:function(){s.set.active(),e()}})):(s.verbose("Showing dimmer animation with javascript"),s.set.dimmed(),"auto"==r.opacity&&(r.opacity=.8),a.stop().css({opacity:0,width:"100%",height:"100%"}).fadeTo(s.get.duration(),r.opacity,function(){a.removeAttr("style"),s.set.active(),e()}))},hide:function(e){e=S.isFunction(e)?e:function(){},r.useCSS&&S.fn.transition!==T&&a.transition("is supported")?(s.verbose("Hiding dimmer with css"),a.transition({displayType:r.useFlex?"flex":"block",animation:r.transition+" out",queue:!1,duration:s.get.duration(),useFailSafe:!0,onStart:function(){s.remove.dimmed()},onComplete:function(){s.remove.variation(),s.remove.active(),e()}})):(s.verbose("Hiding dimmer with javascript"),s.remove.dimmed(),a.stop().fadeOut(s.get.duration(),function(){s.remove.active(),a.removeAttr("style"),e()}))}},get:{dimmer:function(){return a},duration:function(){return"object"==typeof r.duration?s.is.active()?r.duration.hide:r.duration.show:r.duration}},has:{dimmer:function(){return r.dimmerName?0<f.find(n.dimmer).filter("."+r.dimmerName).length:0<f.find(n.dimmer).length}},is:{active:function(){return a.hasClass(i.active)},animating:function(){return a.is(":animated")||a.hasClass(i.animating)},closable:function(){return"auto"==r.closable?"hover"!=r.on:r.closable},dimmer:function(){return f.hasClass(i.dimmer)},dimmable:function(){return f.hasClass(i.dimmable)},dimmed:function(){return t.hasClass(i.dimmed)},disabled:function(){return t.hasClass(i.disabled)},enabled:function(){return!s.is.disabled()},page:function(){return t.is("body")},pageDimmer:function(){return a.hasClass(i.pageDimmer)}},can:{show:function(){return!a.hasClass(i.disabled)}},set:{opacity:function(e){var t=a.css("background-color"),n=t.split(","),i=n&&3==n.length,o=n&&4==n.length;e=0===r.opacity?0:r.opacity||e,t=i||o?(n[3]=e+")",n.join(",")):"rgba(0, 0, 0, "+e+")",s.debug("Setting opacity to",e),a.css("background-color",t)},legacy:function(){a.addClass(i.legacy)},active:function(){a.addClass(i.active)},dimmable:function(){t.addClass(i.dimmable)},dimmed:function(){t.addClass(i.dimmed)},pageDimmer:function(){a.addClass(i.pageDimmer)},disabled:function(){a.addClass(i.disabled)},variation:function(e){(e=e||r.variation)&&a.addClass(e)}},remove:{active:function(){a.removeClass(i.active)},legacy:function(){a.removeClass(i.legacy)},dimmed:function(){t.removeClass(i.dimmed)},disabled:function(){a.removeClass(i.disabled)},variation:function(e){(e=e||r.variation)&&a.removeClass(e)}},setting:function(e,t){if(s.debug("Changing setting",e,t),S.isPlainObject(e))S.extend(!0,r,e);else{if(t===T)return r[e];S.isPlainObject(r[e])?S.extend(!0,r[e],t):r[e]=t}},internal:function(e,t){if(S.isPlainObject(e))S.extend(!0,s,e);else{if(t===T)return s[e];s[e]=t}},debug:function(){!r.silent&&r.debug&&(r.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,r.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!r.silent&&r.verbose&&r.debug&&(r.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,r.name+":"),s.verbose.apply(console,arguments)))},error:function(){r.silent||(s.error=Function.prototype.bind.call(console.error,console,r.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;r.performance&&(n=(t=(new Date).getTime())-(b||t),b=t,y.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:m,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=r.name+":",n=0;b=!1,clearTimeout(s.performance.timer),S.each(y,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",u&&(e+=" '"+u+"'"),1<v.length&&(e+=" ("+v.length+")"),(console.group!==T||console.table!==T)&&0<y.length&&(console.groupCollapsed(e),console.table?console.table(y):S.each(y,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),y=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||w,t=m||t,"string"==typeof i&&r!==T&&(i=i.split(/[\. ]/),o=i.length-1,S.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(S.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==T)return a=r[n],!1;if(!S.isPlainObject(r[t])||e==o)return r[t]!==T?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),S.isFunction(a)?n=a.apply(t,e):a!==T&&(n=a),S.isArray(h)?h.push(n):h!==T?h=[h,n]:n!==T&&(h=n),a}}).preinitialize(),C?(g===T&&s.initialize(),s.invoke(x)):(g!==T&&g.invoke("destroy"),s.initialize())}),h!==T?h:this},S.fn.dimmer.settings={name:"Dimmer",namespace:"dimmer",silent:!1,debug:!1,verbose:!1,performance:!0,useFlex:!0,dimmerName:!1,variation:!1,closable:"auto",useCSS:!0,transition:"fade",on:!1,opacity:"auto",duration:{show:500,hide:500},onChange:function(){},onShow:function(){},onHide:function(){},error:{method:"The method you called is not defined."},className:{active:"active",animating:"animating",dimmable:"dimmable",dimmed:"dimmed",dimmer:"dimmer",disabled:"disabled",hide:"hide",legacy:"legacy",pageDimmer:"page",show:"show"},selector:{dimmer:"> .ui.dimmer",content:".ui.dimmer > .content, .ui.dimmer > .content > .center"},template:{dimmer:function(){return S("<div />").attr("class","ui dimmer")}}}}(jQuery,window,document),function(Y,Z,K,J){"use strict";Z=void 0!==Z&&Z.Math==Math?Z:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),Y.fn.dropdown=function(M){var L,V=Y(this),N=Y(K),H=V.selector||"",U="ontouchstart"in K.documentElement,W=(new Date).getTime(),B=[],Q=M,X="string"==typeof Q,$=[].slice.call(arguments,1);return V.each(function(n){var e,t,i,o,a,r,s,g,p=Y.isPlainObject(M)?Y.extend(!0,{},Y.fn.dropdown.settings,M):Y.extend({},Y.fn.dropdown.settings),h=p.className,c=p.message,l=p.fields,v=p.keys,b=p.metadata,u=p.namespace,d=p.regExp,y=p.selector,f=p.error,m=p.templates,x="."+u,C="module-"+u,w=Y(this),S=Y(p.context),k=w.find(y.text),T=w.find(y.search),A=w.find(y.sizer),R=w.find(y.input),P=w.find(y.icon),E=0<w.prev().find(y.text).length?w.prev().find(y.text):w.prev(),F=w.children(y.menu),O=F.find(y.item),D=!1,q=!1,j=!1,z=this,I=w.data(C);g={initialize:function(){g.debug("Initializing dropdown",p),g.is.alreadySetup()?g.setup.reference():(g.setup.layout(),p.values&&g.change.values(p.values),g.refreshData(),g.save.defaults(),g.restore.selected(),g.create.id(),g.bind.events(),g.observeChanges(),g.instantiate())},instantiate:function(){g.verbose("Storing instance of dropdown",g),I=g,w.data(C,g)},destroy:function(){g.verbose("Destroying previous dropdown",w),g.remove.tabbable(),w.off(x).removeData(C),F.off(x),N.off(o),g.disconnect.menuObserver(),g.disconnect.selectObserver()},observeChanges:function(){"MutationObserver"in Z&&(r=new MutationObserver(g.event.select.mutation),s=new MutationObserver(g.event.menu.mutation),g.debug("Setting up mutation observer",r,s),g.observe.select(),g.observe.menu())},disconnect:{menuObserver:function(){s&&s.disconnect()},selectObserver:function(){r&&r.disconnect()}},observe:{select:function(){g.has.input()&&r.observe(w[0],{childList:!0,subtree:!0})},menu:function(){g.has.menu()&&s.observe(F[0],{childList:!0,subtree:!0})}},create:{id:function(){a=(Math.random().toString(16)+"000000000").substr(2,8),o="."+a,g.verbose("Creating unique id for element",a)},userChoice:function(e){var n,i,o;return!!(e=e||g.get.userValues())&&(e=Y.isArray(e)?e:[e],Y.each(e,function(e,t){!1===g.get.item(t)&&(o=p.templates.addition(g.add.variables(c.addResult,t)),i=Y("<div />").html(o).attr("data-"+b.value,t).attr("data-"+b.text,t).addClass(h.addition).addClass(h.item),p.hideAdditions&&i.addClass(h.hidden),n=n===J?i:n.add(i),g.verbose("Creating user choices for value",t,i))}),n)},userLabels:function(e){var t=g.get.userValues();t&&(g.debug("Adding user labels",t),Y.each(t,function(e,t){g.verbose("Adding custom user value"),g.add.label(t,t)}))},menu:function(){F=Y("<div />").addClass(h.menu).appendTo(w)},sizer:function(){A=Y("<span />").addClass(h.sizer).insertAfter(T)}},search:function(e){e=e!==J?e:g.get.query(),g.verbose("Searching for query",e),g.has.minCharacters(e)?g.filter(e):g.hide()},select:{firstUnfiltered:function(){g.verbose("Selecting first non-filtered element"),g.remove.selectedItem(),O.not(y.unselectable).not(y.addition+y.hidden).eq(0).addClass(h.selected)},nextAvailable:function(e){var t=(e=e.eq(0)).nextAll(y.item).not(y.unselectable).eq(0),n=e.prevAll(y.item).not(y.unselectable).eq(0);0<t.length?(g.verbose("Moving selection to",t),t.addClass(h.selected)):(g.verbose("Moving selection to",n),n.addClass(h.selected))}},setup:{api:function(){var e={debug:p.debug,urlData:{value:g.get.value(),query:g.get.query()},on:!1};g.verbose("First request, initializing API"),w.api(e)},layout:function(){w.is("select")&&(g.setup.select(),g.setup.returnedObject()),g.has.menu()||g.create.menu(),g.is.search()&&!g.has.search()&&(g.verbose("Adding search input"),T=Y("<input />").addClass(h.search).prop("autocomplete","off").insertBefore(k)),g.is.multiple()&&g.is.searchSelection()&&!g.has.sizer()&&g.create.sizer(),p.allowTab&&g.set.tabbable()},select:function(){var e=g.get.selectValues();g.debug("Dropdown initialized on a select",e),w.is("select")&&(R=w),0<R.parent(y.dropdown).length?(g.debug("UI dropdown already exists. Creating dropdown menu only"),w=R.closest(y.dropdown),g.has.menu()||g.create.menu(),F=w.children(y.menu),g.setup.menu(e)):(g.debug("Creating entire dropdown from select"),w=Y("<div />").attr("class",R.attr("class")).addClass(h.selection).addClass(h.dropdown).html(m.dropdown(e)).insertBefore(R),R.hasClass(h.multiple)&&!1===R.prop("multiple")&&(g.error(f.missingMultiple),R.prop("multiple",!0)),R.is("[multiple]")&&g.set.multiple(),R.prop("disabled")&&(g.debug("Disabling dropdown"),w.addClass(h.disabled)),R.removeAttr("class").detach().prependTo(w)),g.refresh()},menu:function(e){F.html(m.menu(e,l)),O=F.find(y.item)},reference:function(){g.debug("Dropdown behavior was called on select, replacing with closest dropdown"),w=w.parent(y.dropdown),I=w.data(C),z=w.get(0),g.refresh(),g.setup.returnedObject()},returnedObject:function(){var e=V.slice(0,n),t=V.slice(n+1);V=e.add(w).add(t)}},refresh:function(){g.refreshSelectors(),g.refreshData()},refreshItems:function(){O=F.find(y.item)},refreshSelectors:function(){g.verbose("Refreshing selector cache"),k=w.find(y.text),T=w.find(y.search),R=w.find(y.input),P=w.find(y.icon),E=0<w.prev().find(y.text).length?w.prev().find(y.text):w.prev(),F=w.children(y.menu),O=F.find(y.item)},refreshData:function(){g.verbose("Refreshing cached metadata"),O.removeData(b.text).removeData(b.value)},clearData:function(){g.verbose("Clearing metadata"),O.removeData(b.text).removeData(b.value),w.removeData(b.defaultText).removeData(b.defaultValue).removeData(b.placeholderText)},toggle:function(){g.verbose("Toggling menu visibility"),g.is.active()?g.hide():g.show()},show:function(e){if(e=Y.isFunction(e)?e:function(){},!g.can.show()&&g.is.remote()&&(g.debug("No API results retrieved, searching before show"),g.queryRemote(g.get.query(),g.show)),g.can.show()&&!g.is.active()){if(g.debug("Showing dropdown"),!g.has.message()||g.has.maxSelections()||g.has.allResultsFiltered()||g.remove.message(),g.is.allFiltered())return!0;!1!==p.onShow.call(z)&&g.animate.show(function(){g.can.click()&&g.bind.intent(),g.has.menuSearch()&&g.focusSearch(),g.set.visible(),e.call(z)})}},hide:function(e){e=Y.isFunction(e)?e:function(){},g.is.active()&&!g.is.animatingOutward()&&(g.debug("Hiding dropdown"),!1!==p.onHide.call(z)&&g.animate.hide(function(){g.remove.visible(),e.call(z)}))},hideOthers:function(){g.verbose("Finding other dropdowns to hide"),V.not(w).has(y.menu+"."+h.visible).dropdown("hide")},hideMenu:function(){g.verbose("Hiding menu  instantaneously"),g.remove.active(),g.remove.visible(),F.transition("hide")},hideSubMenus:function(){var e=F.children(y.item).find(y.menu);g.verbose("Hiding sub menus",e),e.transition("hide")},bind:{events:function(){U&&g.bind.touchEvents(),g.bind.keyboardEvents(),g.bind.inputEvents(),g.bind.mouseEvents()},touchEvents:function(){g.debug("Touch device detected binding additional touch events"),g.is.searchSelection()||g.is.single()&&w.on("touchstart"+x,g.event.test.toggle),F.on("touchstart"+x,y.item,g.event.item.mouseenter)},keyboardEvents:function(){g.verbose("Binding keyboard events"),w.on("keydown"+x,g.event.keydown),g.has.search()&&w.on(g.get.inputEvent()+x,y.search,g.event.input),g.is.multiple()&&N.on("keydown"+o,g.event.document.keydown)},inputEvents:function(){g.verbose("Binding input change events"),w.on("change"+x,y.input,g.event.change)},mouseEvents:function(){g.verbose("Binding mouse events"),g.is.multiple()&&w.on("click"+x,y.label,g.event.label.click).on("click"+x,y.remove,g.event.remove.click),g.is.searchSelection()?(w.on("mousedown"+x,g.event.mousedown).on("mouseup"+x,g.event.mouseup).on("mousedown"+x,y.menu,g.event.menu.mousedown).on("mouseup"+x,y.menu,g.event.menu.mouseup).on("click"+x,y.icon,g.event.icon.click).on("focus"+x,y.search,g.event.search.focus).on("click"+x,y.search,g.event.search.focus).on("blur"+x,y.search,g.event.search.blur).on("click"+x,y.text,g.event.text.focus),g.is.multiple()&&w.on("click"+x,g.event.click)):("click"==p.on?w.on("click"+x,g.event.test.toggle):"hover"==p.on?w.on("mouseenter"+x,g.delay.show).on("mouseleave"+x,g.delay.hide):w.on(p.on+x,g.toggle),w.on("click"+x,y.icon,g.event.icon.click).on("mousedown"+x,g.event.mousedown).on("mouseup"+x,g.event.mouseup).on("focus"+x,g.event.focus),g.has.menuSearch()?w.on("blur"+x,y.search,g.event.search.blur):w.on("blur"+x,g.event.blur)),F.on("mouseenter"+x,y.item,g.event.item.mouseenter).on("mouseleave"+x,y.item,g.event.item.mouseleave).on("click"+x,y.item,g.event.item.click)},intent:function(){g.verbose("Binding hide intent event to document"),U&&N.on("touchstart"+o,g.event.test.touch).on("touchmove"+o,g.event.test.touch),N.on("click"+o,g.event.test.hide)}},unbind:{intent:function(){g.verbose("Removing hide intent event from document"),U&&N.off("touchstart"+o).off("touchmove"+o),N.off("click"+o)}},filter:function(e){var t=e!==J?e:g.get.query(),n=function(){g.is.multiple()&&g.filterActive(),(e||!e&&0==g.get.activeItem().length)&&g.select.firstUnfiltered(),g.has.allResultsFiltered()?p.onNoResults.call(z,t)?p.allowAdditions?p.hideAdditions&&(g.verbose("User addition with no menu, setting empty style"),g.set.empty(),g.hideMenu()):(g.verbose("All items filtered, showing message",t),g.add.message(c.noResults)):(g.verbose("All items filtered, hiding dropdown",t),g.hideMenu()):(g.remove.empty(),g.remove.message()),p.allowAdditions&&g.add.userSuggestion(e),g.is.searchSelection()&&g.can.show()&&g.is.focusedOnSearch()&&g.show()};p.useLabels&&g.has.maxSelections()||(p.apiSettings?g.can.useAPI()?g.queryRemote(t,function(){p.filterRemoteData&&g.filterItems(t),n()}):g.error(f.noAPI):(g.filterItems(t),n()))},queryRemote:function(e,n){var t={errorDuration:!1,cache:"local",throttle:p.throttle,urlData:{query:e},onError:function(){g.add.message(c.serverError),n()},onFailure:function(){g.add.message(c.serverError),n()},onSuccess:function(e){var t=e[l.remoteValues];Y.isArray(t)&&0<t.length?(g.remove.message(),g.setup.menu({values:e[l.remoteValues]})):g.add.message(c.noResults),n()}};w.api("get request")||g.setup.api(),t=Y.extend(!0,{},t,p.apiSettings),w.api("setting",t).api("query")},filterItems:function(e){var i=e!==J?e:g.get.query(),o=null,t=g.escape.string(i),a=new RegExp("^"+t,"igm");g.has.query()&&(o=[],g.verbose("Searching for matching values",i),O.each(function(){var e,t,n=Y(this);if("both"==p.match||"text"==p.match){if(-1!==(e=String(g.get.choiceText(n,!1))).search(a))return o.push(this),!0;if("exact"===p.fullTextSearch&&g.exactSearch(i,e))return o.push(this),!0;if(!0===p.fullTextSearch&&g.fuzzySearch(i,e))return o.push(this),!0}if("both"==p.match||"value"==p.match){if(-1!==(t=String(g.get.choiceValue(n,e))).search(a))return o.push(this),!0;if("exact"===p.fullTextSearch&&g.exactSearch(i,t))return o.push(this),!0;if(!0===p.fullTextSearch&&g.fuzzySearch(i,t))return o.push(this),!0}})),g.debug("Showing only matched items",i),g.remove.filteredItem(),o&&O.not(o).addClass(h.filtered)},fuzzySearch:function(e,t){var n=t.length,i=e.length;if(e=e.toLowerCase(),t=t.toLowerCase(),n<i)return!1;if(i===n)return e===t;e:for(var o=0,a=0;o<i;o++){for(var r=e.charCodeAt(o);a<n;)if(t.charCodeAt(a++)===r)continue e;return!1}return!0},exactSearch:function(e,t){return e=e.toLowerCase(),-1<(t=t.toLowerCase()).indexOf(e)},filterActive:function(){p.useLabels&&O.filter("."+h.active).addClass(h.filtered)},focusSearch:function(e){g.has.search()&&!g.is.focusedOnSearch()&&(e?(w.off("focus"+x,y.search),T.focus(),w.on("focus"+x,y.search,g.event.search.focus)):T.focus())},forceSelection:function(){var e=O.not(h.filtered).filter("."+h.selected).eq(0),t=O.not(h.filtered).filter("."+h.active).eq(0),n=0<e.length?e:t;if(0<n.length&&!g.is.multiple())return g.debug("Forcing partial selection to selected item",n),void g.event.item.click.call(n,{},!0);p.allowAdditions&&g.set.selected(g.get.query()),g.remove.searchTerm()},change:{values:function(e){p.allowAdditions||g.clear(),g.debug("Creating dropdown with specified values",e),g.setup.menu({values:e}),Y.each(e,function(e,t){if(1==t.selected)return g.debug("Setting initial selection to",t.value),g.set.selected(t.value),!0})}},event:{change:function(){j||(g.debug("Input changed, updating selection"),g.set.selected())},focus:function(){p.showOnFocus&&!D&&g.is.hidden()&&!t&&g.show()},blur:function(e){t=K.activeElement===this,D||t||(g.remove.activeLabel(),g.hide())},mousedown:function(){g.is.searchSelection()?i=!0:D=!0},mouseup:function(){g.is.searchSelection()?i=!1:D=!1},click:function(e){Y(e.target).is(w)&&(g.is.focusedOnSearch()?g.show():g.focusSearch())},search:{focus:function(){D=!0,g.is.multiple()&&g.remove.activeLabel(),p.showOnFocus&&g.search()},blur:function(e){t=K.activeElement===this,g.is.searchSelection()&&!i&&(q||t||(p.forceSelection&&g.forceSelection(),g.hide())),i=!1}},icon:{click:function(e){P.hasClass(h.clear)?g.clear():g.can.click()&&g.toggle()}},text:{focus:function(e){D=!0,g.focusSearch()}},input:function(e){(g.is.multiple()||g.is.searchSelection())&&g.set.filtered(),clearTimeout(g.timer),g.timer=setTimeout(g.search,p.delay.search)},label:{click:function(e){var t=Y(this),n=w.find(y.label),i=n.filter("."+h.active),o=t.nextAll("."+h.active),a=t.prevAll("."+h.active),r=0<o.length?t.nextUntil(o).add(i).add(t):t.prevUntil(a).add(i).add(t);e.shiftKey?(i.removeClass(h.active),r.addClass(h.active)):e.ctrlKey?t.toggleClass(h.active):(i.removeClass(h.active),t.addClass(h.active)),p.onLabelSelect.apply(this,n.filter("."+h.active))}},remove:{click:function(){var e=Y(this).parent();e.hasClass(h.active)?g.remove.activeLabels():g.remove.activeLabels(e)}},test:{toggle:function(e){var t=g.is.multiple()?g.show:g.toggle;g.is.bubbledLabelClick(e)||g.is.bubbledIconClick(e)||g.determine.eventOnElement(e,t)&&e.preventDefault()},touch:function(e){g.determine.eventOnElement(e,function(){"touchstart"==e.type?g.timer=setTimeout(function(){g.hide()},p.delay.touch):"touchmove"==e.type&&clearTimeout(g.timer)}),e.stopPropagation()},hide:function(e){g.determine.eventInModule(e,g.hide)}},select:{mutation:function(e){g.debug("<select> modified, recreating menu");var n=!1;Y.each(e,function(e,t){if(Y(t.target).is("select")||Y(t.addedNodes).is("select"))return n=!0}),n&&(g.disconnect.selectObserver(),g.refresh(),g.setup.select(),g.set.selected(),g.observe.select())}},menu:{mutation:function(e){var t=e[0],n=t.addedNodes?Y(t.addedNodes[0]):Y(!1),i=t.removedNodes?Y(t.removedNodes[0]):Y(!1),o=n.add(i),a=o.is(y.addition)||0<o.closest(y.addition).length,r=o.is(y.message)||0<o.closest(y.message).length;a||r?(g.debug("Updating item selector cache"),g.refreshItems()):(g.debug("Menu modified, updating selector cache"),g.refresh())},mousedown:function(){q=!0},mouseup:function(){q=!1}},item:{mouseenter:function(e){var t=Y(e.target),n=Y(this),i=n.children(y.menu),o=n.siblings(y.item).children(y.menu),a=0<i.length;!(0<i.find(t).length)&&a&&(clearTimeout(g.itemTimer),g.itemTimer=setTimeout(function(){g.verbose("Showing sub-menu",i),Y.each(o,function(){g.animate.hide(!1,Y(this))}),g.animate.show(!1,i)},p.delay.show),e.preventDefault())},mouseleave:function(e){var t=Y(this).children(y.menu);0<t.length&&(clearTimeout(g.itemTimer),g.itemTimer=setTimeout(function(){g.verbose("Hiding sub-menu",t),g.animate.hide(!1,t)},p.delay.hide))},click:function(e,t){var n=Y(this),i=Y(e?e.target:""),o=n.find(y.menu),a=g.get.choiceText(n),r=g.get.choiceValue(n,a),s=0<o.length,l=0<o.find(i).length;g.has.menuSearch()&&Y(K.activeElement).blur(),l||s&&!p.allowCategorySelection||(g.is.searchSelection()&&(p.allowAdditions&&g.remove.userAddition(),g.remove.searchTerm(),g.is.focusedOnSearch()||1==t||g.focusSearch(!0)),p.useLabels||(g.remove.filteredItem(),g.set.scrollPosition(n)),g.determine.selectAction.call(this,a,r))}},document:{keydown:function(e){var t=e.which;if(g.is.inObject(t,v)){var n=w.find(y.label),i=n.filter("."+h.active),o=(i.data(b.value),n.index(i)),a=n.length,r=0<i.length,s=1<i.length,l=0===o,c=o+1==a,u=g.is.searchSelection(),d=g.is.focusedOnSearch(),f=g.is.focused(),m=d&&0===g.get.caretPosition();if(u&&!r&&!d)return;t==v.leftArrow?!f&&!m||r?r&&(e.shiftKey?g.verbose("Adding previous label to selection"):(g.verbose("Selecting previous label"),n.removeClass(h.active)),l&&!s?i.addClass(h.active):i.prev(y.siblingLabel).addClass(h.active).end(),e.preventDefault()):(g.verbose("Selecting previous label"),n.last().addClass(h.active)):t==v.rightArrow?(f&&!r&&n.first().addClass(h.active),r&&(e.shiftKey?g.verbose("Adding next label to selection"):(g.verbose("Selecting next label"),n.removeClass(h.active)),c?u?d?n.removeClass(h.active):g.focusSearch():s?i.next(y.siblingLabel).addClass(h.active):i.addClass(h.active):i.next(y.siblingLabel).addClass(h.active),e.preventDefault())):t==v.deleteKey||t==v.backspace?r?(g.verbose("Removing active labels"),c&&u&&!d&&g.focusSearch(),i.last().next(y.siblingLabel).addClass(h.active),g.remove.activeLabels(i),e.preventDefault()):m&&!r&&t==v.backspace&&(g.verbose("Removing last label on input backspace"),i=n.last().addClass(h.active),g.remove.activeLabels(i)):i.removeClass(h.active)}}},keydown:function(e){var t=e.which;if(g.is.inObject(t,v)){var n,i=O.not(y.unselectable).filter("."+h.selected).eq(0),o=F.children("."+h.active).eq(0),a=0<i.length?i:o,r=0<a.length?a.siblings(":not(."+h.filtered+")").addBack():F.children(":not(."+h.filtered+")"),s=a.children(y.menu),l=a.closest(y.menu),c=l.hasClass(h.visible)||l.hasClass(h.animating)||0<l.parent(y.menu).length,u=0<s.length,d=0<a.length,f=0<a.not(y.unselectable).length,m=t==v.delimiter&&p.allowAdditions&&g.is.multiple();if(p.allowAdditions&&p.hideAdditions&&(t==v.enter||m)&&f&&(g.verbose("Selecting item from keyboard shortcut",a),g.event.item.click.call(a,e),g.is.searchSelection()&&g.remove.searchTerm()),g.is.visible()){if((t==v.enter||m)&&(t==v.enter&&d&&u&&!p.allowCategorySelection?(g.verbose("Pressed enter on unselectable category, opening sub menu"),t=v.rightArrow):f&&(g.verbose("Selecting item from keyboard shortcut",a),g.event.item.click.call(a,e),g.is.searchSelection()&&g.remove.searchTerm()),e.preventDefault()),d&&(t==v.leftArrow&&l[0]!==F[0]&&(g.verbose("Left key pressed, closing sub-menu"),g.animate.hide(!1,l),a.removeClass(h.selected),l.closest(y.item).addClass(h.selected),e.preventDefault()),t==v.rightArrow&&u&&(g.verbose("Right key pressed, opening sub-menu"),g.animate.show(!1,s),a.removeClass(h.selected),s.find(y.item).eq(0).addClass(h.selected),e.preventDefault())),t==v.upArrow){if(n=d&&c?a.prevAll(y.item+":not("+y.unselectable+")").eq(0):O.eq(0),r.index(n)<0)return g.verbose("Up key pressed but reached top of current menu"),void e.preventDefault();g.verbose("Up key pressed, changing active item"),a.removeClass(h.selected),n.addClass(h.selected),g.set.scrollPosition(n),p.selectOnKeydown&&g.is.single()&&g.set.selectedItem(n),e.preventDefault()}if(t==v.downArrow){if(0===(n=d&&c?n=a.nextAll(y.item+":not("+y.unselectable+")").eq(0):O.eq(0)).length)return g.verbose("Down key pressed but reached bottom of current menu"),void e.preventDefault();g.verbose("Down key pressed, changing active item"),O.removeClass(h.selected),n.addClass(h.selected),g.set.scrollPosition(n),p.selectOnKeydown&&g.is.single()&&g.set.selectedItem(n),e.preventDefault()}t==v.pageUp&&(g.scrollPage("up"),e.preventDefault()),t==v.pageDown&&(g.scrollPage("down"),e.preventDefault()),t==v.escape&&(g.verbose("Escape key pressed, closing dropdown"),g.hide())}else m&&e.preventDefault(),t!=v.downArrow||g.is.visible()||(g.verbose("Down key pressed, showing dropdown"),g.show(),e.preventDefault())}else g.has.search()||g.set.selectedLetter(String.fromCharCode(t))}},trigger:{change:function(){var e=K.createEvent("HTMLEvents"),t=R[0];t&&(g.verbose("Triggering native change event"),e.initEvent("change",!0,!1),t.dispatchEvent(e))}},determine:{selectAction:function(e,t){g.verbose("Determining action",p.action),Y.isFunction(g.action[p.action])?(g.verbose("Triggering preset action",p.action,e,t),g.action[p.action].call(z,e,t,this)):Y.isFunction(p.action)?(g.verbose("Triggering user action",p.action,e,t),p.action.call(z,e,t,this)):g.error(f.action,p.action)},eventInModule:function(e,t){var n=Y(e.target),i=0<n.closest(K.documentElement).length,o=0<n.closest(w).length;return t=Y.isFunction(t)?t:function(){},i&&!o?(g.verbose("Triggering event",t),t(),!0):(g.verbose("Event occurred in dropdown, canceling callback"),!1)},eventOnElement:function(e,t){var n=Y(e.target),i=n.closest(y.siblingLabel),o=K.body.contains(e.target),a=0===w.find(i).length,r=0===n.closest(F).length;return t=Y.isFunction(t)?t:function(){},o&&a&&r?(g.verbose("Triggering event",t),t(),!0):(g.verbose("Event occurred in dropdown menu, canceling callback"),!1)}},action:{nothing:function(){},activate:function(e,t,n){if(t=t!==J?t:e,g.can.activate(Y(n))){if(g.set.selected(t,Y(n)),g.is.multiple()&&!g.is.allFiltered())return;g.hideAndClear()}},select:function(e,t,n){if(t=t!==J?t:e,g.can.activate(Y(n))){if(g.set.value(t,e,Y(n)),g.is.multiple()&&!g.is.allFiltered())return;g.hideAndClear()}},combo:function(e,t,n){t=t!==J?t:e,g.set.selected(t,Y(n)),g.hideAndClear()},hide:function(e,t,n){g.set.value(t,e,Y(n)),g.hideAndClear()}},get:{id:function(){return a},defaultText:function(){return w.data(b.defaultText)},defaultValue:function(){return w.data(b.defaultValue)},placeholderText:function(){return"auto"!=p.placeholder&&"string"==typeof p.placeholder?p.placeholder:w.data(b.placeholderText)||""},text:function(){return k.text()},query:function(){return Y.trim(T.val())},searchWidth:function(e){return e=e!==J?e:T.val(),A.text(e),Math.ceil(A.width()+1)},selectionCount:function(){var e=g.get.values();return g.is.multiple()?Y.isArray(e)?e.length:0:""!==g.get.value()?1:0},transition:function(e){return"auto"==p.transition?g.is.upward(e)?"slide up":"slide down":p.transition},userValues:function(){var e=g.get.values();return!!e&&(e=Y.isArray(e)?e:[e],Y.grep(e,function(e){return!1===g.get.item(e)}))},uniqueArray:function(n){return Y.grep(n,function(e,t){return Y.inArray(e,n)===t})},caretPosition:function(){var e,t,n=T.get(0);return"selectionStart"in n?n.selectionStart:K.selection?(n.focus(),t=(e=K.selection.createRange()).text.length,e.moveStart("character",-n.value.length),e.text.length-t):void 0},value:function(){var e=0<R.length?R.val():w.data(b.value),t=Y.isArray(e)&&1===e.length&&""===e[0];return e===J||t?"":e},values:function(){var e=g.get.value();return""===e?"":!g.has.selectInput()&&g.is.multiple()?"string"==typeof e?e.split(p.delimiter):"":e},remoteValues:function(){var e=g.get.values(),i=!1;return e&&("string"==typeof e&&(e=[e]),Y.each(e,function(e,t){var n=g.read.remoteData(t);g.verbose("Restoring value from session data",n,t),n&&(i||(i={}),i[t]=n)})),i},choiceText:function(e,t){if(t=t!==J?t:p.preserveHTML,e)return 0<e.find(y.menu).length&&(g.verbose("Retrieving text of element with sub-menu"),(e=e.clone()).find(y.menu).remove(),e.find(y.menuIcon).remove()),e.data(b.text)!==J?e.data(b.text):t?Y.trim(e.html()):Y.trim(e.text())},choiceValue:function(e,t){return t=t||g.get.choiceText(e),!!e&&(e.data(b.value)!==J?String(e.data(b.value)):"string"==typeof t?Y.trim(t.toLowerCase()):String(t))},inputEvent:function(){var e=T[0];return!!e&&(e.oninput!==J?"input":e.onpropertychange!==J?"propertychange":"keyup")},selectValues:function(){var o={values:[]};return w.find("option").each(function(){var e=Y(this),t=e.html(),n=e.attr("disabled"),i=e.attr("value")!==J?e.attr("value"):t;"auto"===p.placeholder&&""===i?o.placeholder=t:o.values.push({name:t,value:i,disabled:n})}),p.placeholder&&"auto"!==p.placeholder&&(g.debug("Setting placeholder value to",p.placeholder),o.placeholder=p.placeholder),p.sortSelect?(o.values.sort(function(e,t){return e.name>t.name?1:-1}),g.debug("Retrieved and sorted values from select",o)):g.debug("Retrieved values from select",o),o},activeItem:function(){return O.filter("."+h.active)},selectedItem:function(){var e=O.not(y.unselectable).filter("."+h.selected);return 0<e.length?e:O.eq(0)},itemWithAdditions:function(e){var t=g.get.item(e),n=g.create.userChoice(e);return n&&0<n.length&&(t=0<t.length?t.add(n):n),t},item:function(i,o){var e,a,r=!1;return i=i!==J?i:g.get.values()!==J?g.get.values():g.get.text(),e=a?0<i.length:i!==J&&null!==i,a=g.is.multiple()&&Y.isArray(i),o=""===i||0===i||(o||!1),e&&O.each(function(){var e=Y(this),t=g.get.choiceText(e),n=g.get.choiceValue(e,t);if(null!==n&&n!==J)if(a)-1===Y.inArray(String(n),i)&&-1===Y.inArray(t,i)||(r=r?r.add(e):e);else if(o){if(g.verbose("Ambiguous dropdown value using strict type check",e,i),n===i||t===i)return r=e,!0}else if(String(n)==String(i)||t==i)return g.verbose("Found select item by value",n,i),r=e,!0}),r}},check:{maxSelections:function(e){return!p.maxSelections||((e=e!==J?e:g.get.selectionCount())>=p.maxSelections?(g.debug("Maximum selection count reached"),p.useLabels&&(O.addClass(h.filtered),g.add.message(c.maxSelections)),!0):(g.verbose("No longer at maximum selection count"),g.remove.message(),g.remove.filteredItem(),g.is.searchSelection()&&g.filterItems(),!1))}},restore:{defaults:function(){g.clear(),g.restore.defaultText(),g.restore.defaultValue()},defaultText:function(){var e=g.get.defaultText();e===g.get.placeholderText?(g.debug("Restoring default placeholder text",e),g.set.placeholderText(e)):(g.debug("Restoring default text",e),g.set.text(e))},placeholderText:function(){g.set.placeholderText()},defaultValue:function(){var e=g.get.defaultValue();e!==J&&(g.debug("Restoring default value",e),""!==e?(g.set.value(e),g.set.selected()):(g.remove.activeItem(),g.remove.selectedItem()))},labels:function(){p.allowAdditions&&(p.useLabels||(g.error(f.labels),p.useLabels=!0),g.debug("Restoring selected values"),g.create.userLabels()),g.check.maxSelections()},selected:function(){g.restore.values(),g.is.multiple()?(g.debug("Restoring previously selected values and labels"),g.restore.labels()):g.debug("Restoring previously selected values")},values:function(){g.set.initialLoad(),p.apiSettings&&p.saveRemoteData&&g.get.remoteValues()?g.restore.remoteValues():g.set.selected(),g.remove.initialLoad()},remoteValues:function(){var e=g.get.remoteValues();g.debug("Recreating selected from session data",e),e&&(g.is.single()?Y.each(e,function(e,t){g.set.text(t)}):Y.each(e,function(e,t){g.add.label(e,t)}))}},read:{remoteData:function(e){var t;if(Z.Storage!==J)return(t=sessionStorage.getItem(e))!==J&&t;g.error(f.noStorage)}},save:{defaults:function(){g.save.defaultText(),g.save.placeholderText(),g.save.defaultValue()},defaultValue:function(){var e=g.get.value();g.verbose("Saving default value as",e),w.data(b.defaultValue,e)},defaultText:function(){var e=g.get.text();g.verbose("Saving default text as",e),w.data(b.defaultText,e)},placeholderText:function(){var e;!1!==p.placeholder&&k.hasClass(h.placeholder)&&(e=g.get.text(),g.verbose("Saving placeholder text as",e),w.data(b.placeholderText,e))},remoteData:function(e,t){Z.Storage!==J?(g.verbose("Saving remote data to session storage",t,e),sessionStorage.setItem(t,e)):g.error(f.noStorage)}},clear:function(){g.is.multiple()&&p.useLabels?g.remove.labels():(g.remove.activeItem(),g.remove.selectedItem()),g.set.placeholderText(),g.clearValue()},clearValue:function(){g.set.value("")},scrollPage:function(e,t){var n,i,o=t||g.get.selectedItem(),a=o.closest(y.menu),r=a.outerHeight(),s=a.scrollTop(),l=O.eq(0).outerHeight(),c=Math.floor(r/l),u=(a.prop("scrollHeight"),"up"==e?s-l*c:s+l*c),d=O.not(y.unselectable);i="up"==e?d.index(o)-c:d.index(o)+c,0<(n=("up"==e?0<=i:i<d.length)?d.eq(i):"up"==e?d.first():d.last()).length&&(g.debug("Scrolling page",e,n),o.removeClass(h.selected),n.addClass(h.selected),p.selectOnKeydown&&g.is.single()&&g.set.selectedItem(n),a.scrollTop(u))},set:{filtered:function(){var e=g.is.multiple(),t=g.is.searchSelection(),n=e&&t,i=t?g.get.query():"",o="string"==typeof i&&0<i.length,a=g.get.searchWidth(),r=""!==i;e&&o&&(g.verbose("Adjusting input width",a,p.glyphWidth),T.css("width",a)),o||n&&r?(g.verbose("Hiding placeholder text"),k.addClass(h.filtered)):(!e||n&&!r)&&(g.verbose("Showing placeholder text"),k.removeClass(h.filtered))},empty:function(){w.addClass(h.empty)},loading:function(){w.addClass(h.loading)},placeholderText:function(e){e=e||g.get.placeholderText(),g.debug("Setting placeholder text",e),g.set.text(e),k.addClass(h.placeholder)},tabbable:function(){g.is.searchSelection()?(g.debug("Added tabindex to searchable dropdown"),T.val("").attr("tabindex",0),F.attr("tabindex",-1)):(g.debug("Added tabindex to dropdown"),w.attr("tabindex")===J&&(w.attr("tabindex",0),F.attr("tabindex",-1)))},initialLoad:function(){g.verbose("Setting initial load"),e=!0},activeItem:function(e){p.allowAdditions&&0<e.filter(y.addition).length?e.addClass(h.filtered):e.addClass(h.active)},partialSearch:function(e){var t=g.get.query().length;T.val(e.substr(0,t))},scrollPosition:function(e,t){var n,i,o,a,r,s;n=(e=e||g.get.selectedItem()).closest(y.menu),i=e&&0<e.length,t=t!==J&&t,e&&0<n.length&&i&&(e.position().top,n.addClass(h.loading),o=(a=n.scrollTop())-n.offset().top+e.offset().top,t||(s=a+n.height()<o+5,r=o-5<a),g.debug("Scrolling to active item",o),(t||r||s)&&n.scrollTop(o),n.removeClass(h.loading))},text:function(e){"select"!==p.action&&("combo"==p.action?(g.debug("Changing combo button text",e,E),p.preserveHTML?E.html(e):E.text(e)):(e!==g.get.placeholderText()&&k.removeClass(h.placeholder),g.debug("Changing text",e,k),k.removeClass(h.filtered),p.preserveHTML?k.html(e):k.text(e)))},selectedItem:function(e){var t=g.get.choiceValue(e),n=g.get.choiceText(e,!1),i=g.get.choiceText(e,!0);g.debug("Setting user selection to item",e),g.remove.activeItem(),g.set.partialSearch(n),g.set.activeItem(e),g.set.selected(t,e),g.set.text(i)},selectedLetter:function(e){var t,n=O.filter("."+h.selected),i=0<n.length&&g.has.firstLetter(n,e),o=!1;i&&(t=n.nextAll(O).eq(0),g.has.firstLetter(t,e)&&(o=t)),o||O.each(function(){if(g.has.firstLetter(Y(this),e))return o=Y(this),!1}),o&&(g.verbose("Scrolling to next value with letter",e),g.set.scrollPosition(o),n.removeClass(h.selected),o.addClass(h.selected),p.selectOnKeydown&&g.is.single()&&g.set.selectedItem(o))},direction:function(e){"auto"==p.direction?(g.remove.upward(),g.can.openDownward(e)?g.remove.upward(e):g.set.upward(e),g.is.leftward(e)||g.can.openRightward(e)||g.set.leftward(e)):"upward"==p.direction&&g.set.upward(e)},upward:function(e){(e||w).addClass(h.upward)},leftward:function(e){(e||F).addClass(h.leftward)},value:function(e,t,n){var i=g.escape.value(e),o=0<R.length,a=g.get.values(),r=e!==J?String(e):e;if(o){if(!p.allowReselection&&r==a&&(g.verbose("Skipping value update already same value",e,a),!g.is.initialLoad()))return;g.is.single()&&g.has.selectInput()&&g.can.extendSelect()&&(g.debug("Adding user option",e),g.add.optionValue(e)),g.debug("Updating input value",i,a),j=!0,R.val(i),!1===p.fireOnInit&&g.is.initialLoad()?g.debug("Input native change event ignored on initial load"):g.trigger.change(),j=!1}else g.verbose("Storing value in metadata",i,R),i!==a&&w.data(b.value,r);g.is.single()&&p.clearable&&(i?g.set.clearable():g.remove.clearable()),!1===p.fireOnInit&&g.is.initialLoad()?g.verbose("No callback on initial load",p.onChange):p.onChange.call(z,e,t,n)},active:function(){w.addClass(h.active)},multiple:function(){w.addClass(h.multiple)},visible:function(){w.addClass(h.visible)},exactly:function(e,t){g.debug("Setting selected to exact values"),g.clear(),g.set.selected(e,t)},selected:function(e,s){var l=g.is.multiple();(s=p.allowAdditions?s||g.get.itemWithAdditions(e):s||g.get.item(e))&&(g.debug("Setting selected menu item to",s),g.is.multiple()&&g.remove.searchWidth(),g.is.single()?(g.remove.activeItem(),g.remove.selectedItem()):p.useLabels&&g.remove.selectedItem(),s.each(function(){var e=Y(this),t=g.get.choiceText(e),n=g.get.choiceValue(e,t),i=e.hasClass(h.filtered),o=e.hasClass(h.active),a=e.hasClass(h.addition),r=l&&1==s.length;l?!o||a?(p.apiSettings&&p.saveRemoteData&&g.save.remoteData(t,n),p.useLabels?(g.add.label(n,t,r),g.add.value(n,t,e),g.set.activeItem(e),g.filterActive(),g.select.nextAvailable(s)):(g.add.value(n,t,e),g.set.text(g.add.variables(c.count)),g.set.activeItem(e))):i||(g.debug("Selected active value, removing label"),g.remove.selected(n)):(p.apiSettings&&p.saveRemoteData&&g.save.remoteData(t,n),g.set.text(t),g.set.value(n,t,e),e.addClass(h.active).addClass(h.selected))}))},clearable:function(){P.addClass(h.clear)}},add:{label:function(e,t,n){var i,o=g.is.searchSelection()?T:k,a=g.escape.value(e);p.ignoreCase&&(a=a.toLowerCase()),i=Y("<a />").addClass(h.label).attr("data-"+b.value,a).html(m.label(a,t)),i=p.onLabelCreate.call(i,a,t),g.has.label(e)?g.debug("User selection already exists, skipping",a):(p.label.variation&&i.addClass(p.label.variation),!0===n?(g.debug("Animating in label",i),i.addClass(h.hidden).insertBefore(o).transition(p.label.transition,p.label.duration)):(g.debug("Adding selection label",i),i.insertBefore(o)))},message:function(e){var t=F.children(y.message),n=p.templates.message(g.add.variables(e));0<t.length?t.html(n):t=Y("<div/>").html(n).addClass(h.message).appendTo(F)},optionValue:function(e){var t=g.escape.value(e);0<R.find('option[value="'+g.escape.string(t)+'"]').length||(g.disconnect.selectObserver(),g.is.single()&&(g.verbose("Removing previous user addition"),R.find("option."+h.addition).remove()),Y("<option/>").prop("value",t).addClass(h.addition).html(e).appendTo(R),g.verbose("Adding user addition as an <option>",e),g.observe.select())},userSuggestion:function(e){var t,n=F.children(y.addition),i=g.get.item(e),o=i&&i.not(y.addition).length,a=0<n.length;p.useLabels&&g.has.maxSelections()||(""===e||o?n.remove():(a?(n.data(b.value,e).data(b.text,e).attr("data-"+b.value,e).attr("data-"+b.text,e).removeClass(h.filtered),p.hideAdditions||(t=p.templates.addition(g.add.variables(c.addResult,e)),n.html(t)),g.verbose("Replacing user suggestion with new value",n)):((n=g.create.userChoice(e)).prependTo(F),g.verbose("Adding item choice to menu corresponding with user choice addition",n)),p.hideAdditions&&!g.is.allFiltered()||n.addClass(h.selected).siblings().removeClass(h.selected),g.refreshItems()))},variables:function(e,t){var n,i,o=-1!==e.search("{count}"),a=-1!==e.search("{maxCount}"),r=-1!==e.search("{term}");return g.verbose("Adding templated variables to message",e),o&&(n=g.get.selectionCount(),e=e.replace("{count}",n)),a&&(n=g.get.selectionCount(),e=e.replace("{maxCount}",p.maxSelections)),r&&(i=t||g.get.query(),e=e.replace("{term}",i)),e},value:function(e,t,n){var i,o=g.get.values();g.has.value(e)?g.debug("Value already selected"):""!==e?(i=Y.isArray(o)?(i=o.concat([e]),g.get.uniqueArray(i)):[e],g.has.selectInput()?g.can.extendSelect()&&(g.debug("Adding value to select",e,i,R),g.add.optionValue(e)):(i=i.join(p.delimiter),g.debug("Setting hidden input to delimited value",i,R)),!1===p.fireOnInit&&g.is.initialLoad()?g.verbose("Skipping onadd callback on initial load",p.onAdd):p.onAdd.call(z,e,t,n),g.set.value(i,e,t,n),g.check.maxSelections()):g.debug("Cannot select blank values from multiselect")}},remove:{active:function(){w.removeClass(h.active)},activeLabel:function(){w.find(y.label).removeClass(h.active)},empty:function(){w.removeClass(h.empty)},loading:function(){w.removeClass(h.loading)},initialLoad:function(){e=!1},upward:function(e){(e||w).removeClass(h.upward)},leftward:function(e){(e||F).removeClass(h.leftward)},visible:function(){w.removeClass(h.visible)},activeItem:function(){O.removeClass(h.active)},filteredItem:function(){p.useLabels&&g.has.maxSelections()||(p.useLabels&&g.is.multiple()?O.not("."+h.active).removeClass(h.filtered):O.removeClass(h.filtered),g.remove.empty())},optionValue:function(e){var t=g.escape.value(e),n=R.find('option[value="'+g.escape.string(t)+'"]');0<n.length&&n.hasClass(h.addition)&&(r&&(r.disconnect(),g.verbose("Temporarily disconnecting mutation observer")),n.remove(),g.verbose("Removing user addition as an <option>",t),r&&r.observe(R[0],{childList:!0,subtree:!0}))},message:function(){F.children(y.message).remove()},searchWidth:function(){T.css("width","")},searchTerm:function(){g.verbose("Cleared search term"),T.val(""),g.set.filtered()},userAddition:function(){O.filter(y.addition).remove()},selected:function(e,t){if(!(t=p.allowAdditions?t||g.get.itemWithAdditions(e):t||g.get.item(e)))return!1;t.each(function(){var e=Y(this),t=g.get.choiceText(e),n=g.get.choiceValue(e,t);g.is.multiple()?p.useLabels?(g.remove.value(n,t,e),g.remove.label(n)):(g.remove.value(n,t,e),0===g.get.selectionCount()?g.set.placeholderText():g.set.text(g.add.variables(c.count))):g.remove.value(n,t,e),e.removeClass(h.filtered).removeClass(h.active),p.useLabels&&e.removeClass(h.selected)})},selectedItem:function(){O.removeClass(h.selected)},value:function(e,t,n){var i,o=g.get.values();g.has.selectInput()?(g.verbose("Input is <select> removing selected option",e),i=g.remove.arrayValue(e,o),g.remove.optionValue(e)):(g.verbose("Removing from delimited values",e),i=(i=g.remove.arrayValue(e,o)).join(p.delimiter)),!1===p.fireOnInit&&g.is.initialLoad()?g.verbose("No callback on initial load",p.onRemove):p.onRemove.call(z,e,t,n),g.set.value(i,t,n),g.check.maxSelections()},arrayValue:function(t,e){return Y.isArray(e)||(e=[e]),e=Y.grep(e,function(e){return t!=e}),g.verbose("Removed value from delimited string",t,e),e},label:function(e,t){var n=w.find(y.label).filter("[data-"+b.value+'="'+g.escape.string(e)+'"]');g.verbose("Removing label",n),n.remove()},activeLabels:function(e){e=e||w.find(y.label).filter("."+h.active),g.verbose("Removing active label selections",e),g.remove.labels(e)},labels:function(e){e=e||w.find(y.label),g.verbose("Removing labels",e),e.each(function(){var e=Y(this),t=e.data(b.value),n=t!==J?String(t):t,i=g.is.userValue(n);!1!==p.onLabelRemove.call(e,t)?(g.remove.message(),i?(g.remove.value(n),g.remove.label(n)):g.remove.selected(n)):g.debug("Label remove callback cancelled removal")})},tabbable:function(){g.is.searchSelection()?(g.debug("Searchable dropdown initialized"),T.removeAttr("tabindex")):(g.debug("Simple selection dropdown initialized"),w.removeAttr("tabindex")),F.removeAttr("tabindex")},clearable:function(){P.removeClass(h.clear)}},has:{menuSearch:function(){return g.has.search()&&0<T.closest(F).length},search:function(){return 0<T.length},sizer:function(){return 0<A.length},selectInput:function(){return R.is("select")},minCharacters:function(e){return!p.minCharacters||(e=e!==J?String(e):String(g.get.query())).length>=p.minCharacters},firstLetter:function(e,t){var n;return!(!e||0===e.length||"string"!=typeof t)&&(n=g.get.choiceText(e,!1),(t=t.toLowerCase())==String(n).charAt(0).toLowerCase())},input:function(){return 0<R.length},items:function(){return 0<O.length},menu:function(){return 0<F.length},message:function(){return 0!==F.children(y.message).length},label:function(e){var t=g.escape.value(e),n=w.find(y.label);return p.ignoreCase&&(t=t.toLowerCase()),0<n.filter("[data-"+b.value+'="'+g.escape.string(t)+'"]').length},maxSelections:function(){return p.maxSelections&&g.get.selectionCount()>=p.maxSelections},allResultsFiltered:function(){var e=O.not(y.addition);return e.filter(y.unselectable).length===e.length},userSuggestion:function(){return 0<F.children(y.addition).length},query:function(){return""!==g.get.query()},value:function(e){return p.ignoreCase?g.has.valueIgnoringCase(e):g.has.valueMatchingCase(e)},valueMatchingCase:function(e){var t=g.get.values();return!!(Y.isArray(t)?t&&-1!==Y.inArray(e,t):t==e)},valueIgnoringCase:function(n){var e=g.get.values(),i=!1;return Y.isArray(e)||(e=[e]),Y.each(e,function(e,t){if(String(n).toLowerCase()==String(t).toLowerCase())return!(i=!0)}),i}},is:{active:function(){return w.hasClass(h.active)},animatingInward:function(){return F.transition("is inward")},animatingOutward:function(){return F.transition("is outward")},bubbledLabelClick:function(e){return Y(e.target).is("select, input")&&0<w.closest("label").length},bubbledIconClick:function(e){return 0<Y(e.target).closest(P).length},alreadySetup:function(){return w.is("select")&&w.parent(y.dropdown).data(C)!==J&&0===w.prev().length},animating:function(e){return e?e.transition&&e.transition("is animating"):F.transition&&F.transition("is animating")},leftward:function(e){return(e||F).hasClass(h.leftward)},disabled:function(){return w.hasClass(h.disabled)},focused:function(){return K.activeElement===w[0]},focusedOnSearch:function(){return K.activeElement===T[0]},allFiltered:function(){return(g.is.multiple()||g.has.search())&&!(0==p.hideAdditions&&g.has.userSuggestion())&&!g.has.message()&&g.has.allResultsFiltered()},hidden:function(e){return!g.is.visible(e)},initialLoad:function(){return e},inObject:function(n,e){var i=!1;return Y.each(e,function(e,t){if(t==n)return i=!0}),i},multiple:function(){return w.hasClass(h.multiple)},remote:function(){return p.apiSettings&&g.can.useAPI()},single:function(){return!g.is.multiple()},selectMutation:function(e){var n=!1;return Y.each(e,function(e,t){if(t.target&&Y(t.target).is("select"))return n=!0}),n},search:function(){return w.hasClass(h.search)},searchSelection:function(){return g.has.search()&&1===T.parent(y.dropdown).length},selection:function(){return w.hasClass(h.selection)},userValue:function(e){return-1!==Y.inArray(e,g.get.userValues())},upward:function(e){return(e||w).hasClass(h.upward)},visible:function(e){return e?e.hasClass(h.visible):F.hasClass(h.visible)},verticallyScrollableContext:function(){var e=S.get(0)!==Z&&S.css("overflow-y");return"auto"==e||"scroll"==e},horizontallyScrollableContext:function(){var e=S.get(0)!==Z&&S.css("overflow-X");return"auto"==e||"scroll"==e}},can:{activate:function(e){return!!p.useLabels||(!g.has.maxSelections()||!(!g.has.maxSelections()||!e.hasClass(h.active)))},openDownward:function(e){var t,n,i=e||F,o=!0;return i.addClass(h.loading),n={context:{offset:S.get(0)===Z?{top:0,left:0}:S.offset(),scrollTop:S.scrollTop(),height:S.outerHeight()},menu:{offset:i.offset(),height:i.outerHeight()}},g.is.verticallyScrollableContext()&&(n.menu.offset.top+=n.context.scrollTop),o=(t={above:n.context.scrollTop<=n.menu.offset.top-n.context.offset.top-n.menu.height,below:n.context.scrollTop+n.context.height>=n.menu.offset.top-n.context.offset.top+n.menu.height}).below?(g.verbose("Dropdown can fit in context downward",t),!0):t.below||t.above?(g.verbose("Dropdown cannot fit below, opening upward",t),!1):(g.verbose("Dropdown cannot fit in either direction, favoring downward",t),!0),i.removeClass(h.loading),o},openRightward:function(e){var t,n,i=e||F,o=!0;return i.addClass(h.loading),n={context:{offset:S.get(0)===Z?{top:0,left:0}:S.offset(),scrollLeft:S.scrollLeft(),width:S.outerWidth()},menu:{offset:i.offset(),width:i.outerWidth()}},g.is.horizontallyScrollableContext()&&(n.menu.offset.left+=n.context.scrollLeft),(t=n.menu.offset.left-n.context.offset.left+n.menu.width>=n.context.scrollLeft+n.context.width)&&(g.verbose("Dropdown cannot fit in context rightward",t),o=!1),i.removeClass(h.loading),o},click:function(){return U||"click"==p.on},extendSelect:function(){return p.allowAdditions||p.apiSettings},show:function(){return!g.is.disabled()&&(g.has.items()||g.has.message())},useAPI:function(){return Y.fn.api!==J}},animate:{show:function(e,t){var n,i=t||F,o=t?function(){}:function(){g.hideSubMenus(),g.hideOthers(),g.set.active()};e=Y.isFunction(e)?e:function(){},g.verbose("Doing menu show animation",i),g.set.direction(t),n=g.get.transition(t),g.is.selection()&&g.set.scrollPosition(g.get.selectedItem(),!0),(g.is.hidden(i)||g.is.animating(i))&&("none"==n?(o(),i.transition("show"),e.call(z)):Y.fn.transition!==J&&w.transition("is supported")?i.transition({animation:n+" in",debug:p.debug,verbose:p.verbose,duration:p.duration,queue:!0,onStart:o,onComplete:function(){e.call(z)}}):g.error(f.noTransition,n))},hide:function(e,t){var n=t||F,i=(t?p.duration:p.duration,t?function(){}:function(){g.can.click()&&g.unbind.intent(),g.remove.active()}),o=g.get.transition(t);e=Y.isFunction(e)?e:function(){},(g.is.visible(n)||g.is.animating(n))&&(g.verbose("Doing menu hide animation",n),"none"==o?(i(),n.transition("hide"),e.call(z)):Y.fn.transition!==J&&w.transition("is supported")?n.transition({animation:o+" out",duration:p.duration,debug:p.debug,verbose:p.verbose,queue:!1,onStart:i,onComplete:function(){e.call(z)}}):g.error(f.transition))}},hideAndClear:function(){g.remove.searchTerm(),g.has.maxSelections()||(g.has.search()?g.hide(function(){g.remove.filteredItem()}):g.hide())},delay:{show:function(){g.verbose("Delaying show event to ensure user intent"),clearTimeout(g.timer),g.timer=setTimeout(g.show,p.delay.show)},hide:function(){g.verbose("Delaying hide event to ensure user intent"),clearTimeout(g.timer),g.timer=setTimeout(g.hide,p.delay.hide)}},escape:{value:function(e){var t=Y.isArray(e),n="string"==typeof e,i=!n&&!t,o=n&&-1!==e.search(d.quote),a=[];return i||!o?e:(g.debug("Encoding quote values for use in select",e),t?(Y.each(e,function(e,t){a.push(t.replace(d.quote,"&quot;"))}),a):e.replace(d.quote,"&quot;"))},string:function(e){return(e=String(e)).replace(d.escape,"\\$&")}},setting:function(e,t){if(g.debug("Changing setting",e,t),Y.isPlainObject(e))Y.extend(!0,p,e);else{if(t===J)return p[e];Y.isPlainObject(p[e])?Y.extend(!0,p[e],t):p[e]=t}},internal:function(e,t){if(Y.isPlainObject(e))Y.extend(!0,g,e);else{if(t===J)return g[e];g[e]=t}},debug:function(){!p.silent&&p.debug&&(p.performance?g.performance.log(arguments):(g.debug=Function.prototype.bind.call(console.info,console,p.name+":"),g.debug.apply(console,arguments)))},verbose:function(){!p.silent&&p.verbose&&p.debug&&(p.performance?g.performance.log(arguments):(g.verbose=Function.prototype.bind.call(console.info,console,p.name+":"),g.verbose.apply(console,arguments)))},error:function(){p.silent||(g.error=Function.prototype.bind.call(console.error,console,p.name+":"),g.error.apply(console,arguments))},performance:{log:function(e){var t,n;p.performance&&(n=(t=(new Date).getTime())-(W||t),W=t,B.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:z,"Execution Time":n})),clearTimeout(g.performance.timer),g.performance.timer=setTimeout(g.performance.display,500)},display:function(){var e=p.name+":",n=0;W=!1,clearTimeout(g.performance.timer),Y.each(B,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",H&&(e+=" '"+H+"'"),(console.group!==J||console.table!==J)&&0<B.length&&(console.groupCollapsed(e),console.table?console.table(B):Y.each(B,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),B=[]}},invoke:function(i,e,t){var o,a,n,r=I;return e=e||$,t=z||t,"string"==typeof i&&r!==J&&(i=i.split(/[\. ]/),o=i.length-1,Y.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(Y.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==J)return a=r[n],!1;if(!Y.isPlainObject(r[t])||e==o)return r[t]!==J?a=r[t]:g.error(f.method,i),!1;r=r[t]}})),Y.isFunction(a)?n=a.apply(t,e):a!==J&&(n=a),Y.isArray(L)?L.push(n):L!==J?L=[L,n]:n!==J&&(L=n),a}},X?(I===J&&g.initialize(),g.invoke(Q)):(I!==J&&I.invoke("destroy"),g.initialize())}),L!==J?L:V},Y.fn.dropdown.settings={silent:!1,debug:!1,verbose:!1,performance:!0,on:"click",action:"activate",values:!1,clearable:!1,apiSettings:!1,selectOnKeydown:!0,minCharacters:0,filterRemoteData:!1,saveRemoteData:!0,throttle:200,context:Z,direction:"auto",keepOnScreen:!0,match:"both",fullTextSearch:!1,placeholder:"auto",preserveHTML:!0,sortSelect:!1,forceSelection:!0,allowAdditions:!1,ignoreCase:!1,hideAdditions:!0,maxSelections:!1,useLabels:!0,delimiter:",",showOnFocus:!0,allowReselection:!1,allowTab:!0,allowCategorySelection:!1,fireOnInit:!1,transition:"auto",duration:200,glyphWidth:1.037,label:{transition:"scale",duration:200,variation:!1},delay:{hide:300,show:200,search:20,touch:50},onChange:function(e,t,n){},onAdd:function(e,t,n){},onRemove:function(e,t,n){},onLabelSelect:function(e){},onLabelCreate:function(e,t){return Y(this)},onLabelRemove:function(e){return!0},onNoResults:function(e){return!0},onShow:function(){},onHide:function(){},name:"Dropdown",namespace:"dropdown",message:{addResult:"Add <b>{term}</b>",count:"{count} selected",maxSelections:"Max {maxCount} selections",noResults:"No results found.",serverError:"There was an error contacting the server"},error:{action:"You called a dropdown action that was not defined",alreadySetup:"Once a select has been initialized behaviors must be called on the created ui dropdown",labels:"Allowing user additions currently requires the use of labels.",missingMultiple:"<select> requires multiple property to be set to correctly preserve multiple values",method:"The method you called is not defined.",noAPI:"The API module is required to load resources remotely",noStorage:"Saving remote data requires session storage",noTransition:"This module requires ui transitions <https://github.com/Semantic-Org/UI-Transition>"},regExp:{escape:/[-[\]{}()*+?.,\\^$|#\s]/g,quote:/"/g},metadata:{defaultText:"defaultText",defaultValue:"defaultValue",placeholderText:"placeholder",text:"text",value:"value"},fields:{remoteValues:"results",values:"values",disabled:"disabled",name:"name",value:"value",text:"text"},keys:{backspace:8,delimiter:188,deleteKey:46,enter:13,escape:27,pageUp:33,pageDown:34,leftArrow:37,upArrow:38,rightArrow:39,downArrow:40},selector:{addition:".addition",dropdown:".ui.dropdown",hidden:".hidden",icon:"> .dropdown.icon",input:'> input[type="hidden"], > select',item:".item",label:"> .label",remove:"> .label > .delete.icon",siblingLabel:".label",menu:".menu",message:".message",menuIcon:".dropdown.icon",search:"input.search, .menu > .search > input, .menu input.search",sizer:"> input.sizer",text:"> .text:not(.icon)",unselectable:".disabled, .filtered"},className:{active:"active",addition:"addition",animating:"animating",clear:"clear",disabled:"disabled",empty:"empty",dropdown:"ui dropdown",filtered:"filtered",hidden:"hidden transition",item:"item",label:"ui label",loading:"loading",menu:"menu",message:"message",multiple:"multiple",placeholder:"default",sizer:"sizer",search:"search",selected:"selected",selection:"selection",upward:"upward",leftward:"left",visible:"visible"}},Y.fn.dropdown.settings.templates={dropdown:function(e){var t=e.placeholder||!1,n=(e.values,"");return n+='<i class="dropdown icon"></i>',e.placeholder?n+='<div class="default text">'+t+"</div>":n+='<div class="text"></div>',n+='<div class="menu">',Y.each(e.values,function(e,t){n+=t.disabled?'<div class="disabled item" data-value="'+t.value+'">'+t.name+"</div>":'<div class="item" data-value="'+t.value+'">'+t.name+"</div>"}),n+="</div>"},menu:function(e,o){var t=e[o.values]||{},a="";return Y.each(t,function(e,t){var n=t[o.text]?'data-text="'+t[o.text]+'"':"",i=t[o.disabled]?"disabled ":"";a+='<div class="'+i+'item" data-value="'+t[o.value]+'"'+n+">",a+=t[o.name],a+="</div>"}),a},label:function(e,t){return t+'<i class="delete icon"></i>'},message:function(e){return e},addition:function(e){return e}}}(jQuery,window,document),function(k,T,e,A){"use strict";T=void 0!==T&&T.Math==Math?T:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),k.fn.embed=function(p){var h,v=k(this),b=v.selector||"",y=(new Date).getTime(),x=[],C=p,w="string"==typeof C,S=[].slice.call(arguments,1);return v.each(function(){var s,i=k.isPlainObject(p)?k.extend(!0,{},k.fn.embed.settings,p):k.extend({},k.fn.embed.settings),e=i.selector,t=i.className,o=i.sources,l=i.error,a=i.metadata,n=i.namespace,r=i.templates,c="."+n,u="module-"+n,d=(k(T),k(this)),f=(d.find(e.placeholder),d.find(e.icon),d.find(e.embed)),m=this,g=d.data(u);s={initialize:function(){s.debug("Initializing embed"),s.determine.autoplay(),s.create(),s.bind.events(),s.instantiate()},instantiate:function(){s.verbose("Storing instance of module",s),g=s,d.data(u,s)},destroy:function(){s.verbose("Destroying previous instance of embed"),s.reset(),d.removeData(u).off(c)},refresh:function(){s.verbose("Refreshing selector cache"),d.find(e.placeholder),d.find(e.icon),f=d.find(e.embed)},bind:{events:function(){s.has.placeholder()&&(s.debug("Adding placeholder events"),d.on("click"+c,e.placeholder,s.createAndShow).on("click"+c,e.icon,s.createAndShow))}},create:function(){s.get.placeholder()?s.createPlaceholder():s.createAndShow()},createPlaceholder:function(e){var t=s.get.icon(),n=s.get.url();s.generate.embed(n);e=e||s.get.placeholder(),d.html(r.placeholder(e,t)),s.debug("Creating placeholder for embed",e,t)},createEmbed:function(e){s.refresh(),e=e||s.get.url(),f=k("<div/>").addClass(t.embed).html(s.generate.embed(e)).appendTo(d),i.onCreate.call(m,e),s.debug("Creating embed object",f)},changeEmbed:function(e){f.html(s.generate.embed(e))},createAndShow:function(){s.createEmbed(),s.show()},change:function(e,t,n){s.debug("Changing video to ",e,t,n),d.data(a.source,e).data(a.id,t),n?d.data(a.url,n):d.removeData(a.url),s.has.embed()?s.changeEmbed():s.create()},reset:function(){s.debug("Clearing embed and showing placeholder"),s.remove.data(),s.remove.active(),s.remove.embed(),s.showPlaceholder(),i.onReset.call(m)},show:function(){s.debug("Showing embed"),s.set.active(),i.onDisplay.call(m)},hide:function(){s.debug("Hiding embed"),s.showPlaceholder()},showPlaceholder:function(){s.debug("Showing placeholder image"),s.remove.active(),i.onPlaceholderDisplay.call(m)},get:{id:function(){return i.id||d.data(a.id)},placeholder:function(){return i.placeholder||d.data(a.placeholder)},icon:function(){return i.icon?i.icon:d.data(a.icon)!==A?d.data(a.icon):s.determine.icon()},source:function(e){return i.source?i.source:d.data(a.source)!==A?d.data(a.source):s.determine.source()},type:function(){var e=s.get.source();return o[e]!==A&&o[e].type},url:function(){return i.url?i.url:d.data(a.url)!==A?d.data(a.url):s.determine.url()}},determine:{autoplay:function(){s.should.autoplay()&&(i.autoplay=!0)},source:function(n){var i=!1;return(n=n||s.get.url())&&k.each(o,function(e,t){if(-1!==n.search(t.domain))return i=e,!1}),i},icon:function(){var e=s.get.source();return o[e]!==A&&o[e].icon},url:function(){var e,t=i.id||d.data(a.id),n=i.source||d.data(a.source);return(e=o[n]!==A&&o[n].url.replace("{id}",t))&&d.data(a.url,e),e}},set:{active:function(){d.addClass(t.active)}},remove:{data:function(){d.removeData(a.id).removeData(a.icon).removeData(a.placeholder).removeData(a.source).removeData(a.url)},active:function(){d.removeClass(t.active)},embed:function(){f.empty()}},encode:{parameters:function(e){var t,n=[];for(t in e)n.push(encodeURIComponent(t)+"="+encodeURIComponent(e[t]));return n.join("&amp;")}},generate:{embed:function(e){s.debug("Generating embed html");var t,n,i=s.get.source();return(e=s.get.url(e))?(n=s.generate.parameters(i),t=r.iframe(e,n)):s.error(l.noURL,d),t},parameters:function(e,t){var n=o[e]&&o[e].parameters!==A?o[e].parameters(i):{};return(t=t||i.parameters)&&(n=k.extend({},n,t)),n=i.onEmbed(n),s.encode.parameters(n)}},has:{embed:function(){return 0<f.length},placeholder:function(){return i.placeholder||d.data(a.placeholder)}},should:{autoplay:function(){return"auto"===i.autoplay?i.placeholder||d.data(a.placeholder)!==A:i.autoplay}},is:{video:function(){return"video"==s.get.type()}},setting:function(e,t){if(s.debug("Changing setting",e,t),k.isPlainObject(e))k.extend(!0,i,e);else{if(t===A)return i[e];k.isPlainObject(i[e])?k.extend(!0,i[e],t):i[e]=t}},internal:function(e,t){if(k.isPlainObject(e))k.extend(!0,s,e);else{if(t===A)return s[e];s[e]=t}},debug:function(){!i.silent&&i.debug&&(i.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,i.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!i.silent&&i.verbose&&i.debug&&(i.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,i.name+":"),s.verbose.apply(console,arguments)))},error:function(){i.silent||(s.error=Function.prototype.bind.call(console.error,console,i.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;i.performance&&(n=(t=(new Date).getTime())-(y||t),y=t,x.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:m,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=i.name+":",n=0;y=!1,clearTimeout(s.performance.timer),k.each(x,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",b&&(e+=" '"+b+"'"),1<v.length&&(e+=" ("+v.length+")"),(console.group!==A||console.table!==A)&&0<x.length&&(console.groupCollapsed(e),console.table?console.table(x):k.each(x,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),x=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||S,t=m||t,"string"==typeof i&&r!==A&&(i=i.split(/[\. ]/),o=i.length-1,k.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(k.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==A)return a=r[n],!1;if(!k.isPlainObject(r[t])||e==o)return r[t]!==A?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),k.isFunction(a)?n=a.apply(t,e):a!==A&&(n=a),k.isArray(h)?h.push(n):h!==A?h=[h,n]:n!==A&&(h=n),a}},w?(g===A&&s.initialize(),s.invoke(C)):(g!==A&&g.invoke("destroy"),s.initialize())}),h!==A?h:this},k.fn.embed.settings={name:"Embed",namespace:"embed",silent:!1,debug:!1,verbose:!1,performance:!0,icon:!1,source:!1,url:!1,id:!1,autoplay:"auto",color:"#444444",hd:!0,brandedUI:!1,parameters:!1,onDisplay:function(){},onPlaceholderDisplay:function(){},onReset:function(){},onCreate:function(e){},onEmbed:function(e){return e},metadata:{id:"id",icon:"icon",placeholder:"placeholder",source:"source",url:"url"},error:{noURL:"No URL specified",method:"The method you called is not defined"},className:{active:"active",embed:"embed"},selector:{embed:".embed",placeholder:".placeholder",icon:".icon"},sources:{youtube:{name:"youtube",type:"video",icon:"video play",domain:"youtube.com",url:"//www.youtube.com/embed/{id}",parameters:function(e){return{autohide:!e.brandedUI,autoplay:e.autoplay,color:e.color||A,hq:e.hd,jsapi:e.api,modestbranding:!e.brandedUI}}},vimeo:{name:"vimeo",type:"video",icon:"video play",domain:"vimeo.com",url:"//player.vimeo.com/video/{id}",parameters:function(e){return{api:e.api,autoplay:e.autoplay,byline:e.brandedUI,color:e.color||A,portrait:e.brandedUI,title:e.brandedUI}}}},templates:{iframe:function(e,t){var n=e;return t&&(n+="?"+t),'<iframe src="'+n+'" width="100%" height="100%" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'},placeholder:function(e,t){var n="";return t&&(n+='<i class="'+t+' icon"></i>'),e&&(n+='<img class="placeholder" src="'+e+'">'),n}},api:!1,onPause:function(){},onPlay:function(){},onStop:function(){}}}(jQuery,window,document),function(j,z,I,M){"use strict";z=void 0!==z&&z.Math==Math?z:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),j.fn.suimodal=function(w){var S,e=j(this),k=j(z),T=j(I),A=j("body"),R=e.selector||"",P=(new Date).getTime(),E=[],F=w,O="string"==typeof F,D=[].slice.call(arguments,1),q=z.requestAnimationFrame||z.mozRequestAnimationFrame||z.webkitRequestAnimationFrame||z.msRequestAnimationFrame||function(e){setTimeout(e,0)};return e.each(function(){var n,i,e,o,a,t,r,s,l,c=j.isPlainObject(w)?j.extend(!0,{},j.fn.suimodal.settings,w):j.extend({},j.fn.suimodal.settings),u=c.selector,d=c.className,f=c.namespace,m=c.error,g="."+f,p="module-"+f,h=j(this),v=j(c.context),b=h.find(u.close),y=this,x=h.data(p),C=!1;l={initialize:function(){l.verbose("Initializing dimmer",v),l.create.id(),l.create.dimmer(),l.refreshsuimodals(),l.bind.events(),c.observeChanges&&l.observeChanges(),l.instantiate()},instantiate:function(){l.verbose("Storing instance of suimodal"),x=l,h.data(p,x)},create:{dimmer:function(){var e={debug:c.debug,variation:!c.centered&&"top aligned",dimmerName:"suimodals"},t=j.extend(!0,e,c.dimmerSettings);j.fn.dimmer!==M?(l.debug("Creating dimmer"),o=v.dimmer(t),c.detachable?(l.verbose("suimodal is detachable, moving content into dimmer"),o.dimmer("add content",h)):l.set.undetached(),a=o.dimmer("get dimmer")):l.error(m.dimmer)},id:function(){r=(Math.random().toString(16)+"000000000").substr(2,8),t="."+r,l.verbose("Creating unique id for element",r)}},destroy:function(){l.verbose("Destroying previous suimodal"),h.removeData(p).off(g),k.off(t),a.off(t),b.off(g),v.dimmer("destroy")},observeChanges:function(){"MutationObserver"in z&&((s=new MutationObserver(function(e){l.debug("DOM tree modified, refreshing"),l.refresh()})).observe(y,{childList:!0,subtree:!0}),l.debug("Setting up mutation observer",s))},refresh:function(){l.remove.scrolling(),l.cacheSizes(),l.can.useFlex()||l.set.suimodalOffset(),l.set.screenHeight(),l.set.type()},refreshsuimodals:function(){i=h.siblings(u.suimodal),n=i.add(h)},attachEvents:function(e,t){var n=j(e);t=j.isFunction(l[t])?l[t]:l.toggle,0<n.length?(l.debug("Attaching suimodal events to element",e,t),n.off(g).on("click"+g,t)):l.error(m.notFound,e)},bind:{events:function(){l.verbose("Attaching events"),h.on("click"+g,u.close,l.event.close).on("click"+g,u.approve,l.event.approve).on("click"+g,u.deny,l.event.deny),k.on("resize"+t,l.event.resize)},scrollLock:function(){o.get(0).addEventListener("touchmove",l.event.preventScroll,{passive:!1})}},unbind:{scrollLock:function(){o.get(0).removeEventListener("touchmove",l.event.preventScroll,{passive:!1})}},get:{id:function(){return(Math.random().toString(16)+"000000000").substr(2,8)}},event:{approve:function(){C||!1===c.onApprove.call(y,j(this))?l.verbose("Approve callback returned false cancelling hide"):(C=!0,l.hide(function(){C=!1}))},preventScroll:function(e){e.preventDefault()},deny:function(){C||!1===c.onDeny.call(y,j(this))?l.verbose("Deny callback returned false cancelling hide"):(C=!0,l.hide(function(){C=!1}))},close:function(){l.hide()},click:function(e){if(c.closable){var t=0<j(e.target).closest(u.suimodal).length,n=j.contains(I.documentElement,e.target);!t&&n&&l.is.active()&&(l.debug("Dimmer clicked, hiding all suimodals"),l.remove.clickaway(),c.allowMultiple?l.hide():l.hideAll())}else l.verbose("Dimmer clicked but closable setting is disabled")},debounce:function(e,t){clearTimeout(l.timer),l.timer=setTimeout(e,t)},keyboard:function(e){27==e.which&&(c.closable?(l.debug("Escape key pressed hiding suimodal"),l.hide()):l.debug("Escape key pressed, but closable is set to false"),e.preventDefault())},resize:function(){o.dimmer("is active")&&(l.is.animating()||l.is.active())&&q(l.refresh)}},toggle:function(){l.is.active()||l.is.animating()?l.hide():l.show()},show:function(e){e=j.isFunction(e)?e:function(){},l.refreshsuimodals(),l.set.dimmerSettings(),l.set.dimmerStyles(),l.showsuimodal(e)},hide:function(e){e=j.isFunction(e)?e:function(){},l.refreshsuimodals(),l.hidesuimodal(e)},showsuimodal:function(e){e=j.isFunction(e)?e:function(){},l.is.animating()||!l.is.active()?(l.showDimmer(),l.cacheSizes(),l.can.useFlex()?l.remove.legacy():(l.set.legacy(),l.set.suimodalOffset(),l.debug("Using non-flex legacy suimodal positioning.")),l.set.screenHeight(),l.set.type(),l.set.clickaway(),!c.allowMultiple&&l.others.active()?l.hideOthers(l.showsuimodal):(c.allowMultiple&&c.detachable&&h.detach().appendTo(a),c.onShow.call(y),c.transition&&j.fn.transition!==M&&h.transition("is supported")?(l.debug("Showing suimodal with css animations"),h.transition({debug:c.debug,animation:c.transition+" in",queue:c.queue,duration:c.duration,useFailSafe:!0,onComplete:function(){c.onVisible.apply(y),c.keyboardShortcuts&&l.add.keyboardShortcuts(),l.save.focus(),l.set.active(),c.autofocus&&l.set.autofocus(),e()}})):l.error(m.noTransition))):l.debug("suimodal is already visible")},hidesuimodal:function(e,t){e=j.isFunction(e)?e:function(){},l.debug("Hiding suimodal"),!1!==c.onHide.call(y,j(this))?(l.is.animating()||l.is.active())&&(c.transition&&j.fn.transition!==M&&h.transition("is supported")?(l.remove.active(),h.transition({debug:c.debug,animation:c.transition+" out",queue:c.queue,duration:c.duration,useFailSafe:!0,onStart:function(){l.others.active()||t||l.hideDimmer(),c.keyboardShortcuts&&l.remove.keyboardShortcuts()},onComplete:function(){c.onHidden.call(y),l.remove.dimmerStyles(),l.restore.focus(),e()}})):l.error(m.noTransition)):l.verbose("Hide callback returned false cancelling hide")},showDimmer:function(){o.dimmer("is animating")||!o.dimmer("is active")?(l.debug("Showing dimmer"),o.dimmer("show")):l.debug("Dimmer already visible")},hideDimmer:function(){o.dimmer("is animating")||o.dimmer("is active")?(l.unbind.scrollLock(),o.dimmer("hide",function(){l.remove.clickaway(),l.remove.screenHeight()})):l.debug("Dimmer is not visible cannot hide")},hideAll:function(e){var t=n.filter("."+d.active+", ."+d.animating);e=j.isFunction(e)?e:function(){},0<t.length&&(l.debug("Hiding all visible suimodals"),l.hideDimmer(),t.suimodal("hide suimodal",e))},hideOthers:function(e){var t=i.filter("."+d.active+", ."+d.animating);e=j.isFunction(e)?e:function(){},0<t.length&&(l.debug("Hiding other suimodals",i),t.suimodal("hide suimodal",e,!0))},others:{active:function(){return 0<i.filter("."+d.active).length},animating:function(){return 0<i.filter("."+d.animating).length}},add:{keyboardShortcuts:function(){l.verbose("Adding keyboard shortcuts"),T.on("keyup"+g,l.event.keyboard)}},save:{focus:function(){0<j(I.activeElement).closest(h).length||(e=j(I.activeElement).blur())}},restore:{focus:function(){e&&0<e.length&&e.focus()}},remove:{active:function(){h.removeClass(d.active)},legacy:function(){h.removeClass(d.legacy)},clickaway:function(){a.off("click"+t)},dimmerStyles:function(){a.removeClass(d.inverted),o.removeClass(d.blurring)},bodyStyle:function(){""===A.attr("style")&&(l.verbose("Removing style attribute"),A.removeAttr("style"))},screenHeight:function(){l.debug("Removing page height"),A.css("height","")},keyboardShortcuts:function(){l.verbose("Removing keyboard shortcuts"),T.off("keyup"+g)},scrolling:function(){o.removeClass(d.scrolling),h.removeClass(d.scrolling)}},cacheSizes:function(){h.addClass(d.loading);var e=h.prop("scrollHeight"),t=h.outerWidth(),n=h.outerHeight();l.cache!==M&&0===n||(l.cache={pageHeight:j(I).outerHeight(),width:t,height:n+c.offset,scrollHeight:e+c.offset,contextHeight:"body"==c.context?j(z).height():o.height()},l.cache.topOffset=-l.cache.height/2),h.removeClass(d.loading),l.debug("Caching suimodal and container sizes",l.cache)},can:{useFlex:function(){return"auto"==c.useFlex?c.detachable&&!l.is.ie():c.useFlex},fit:function(){var e=l.cache.contextHeight,t=l.cache.contextHeight/2,n=l.cache.topOffset,i=l.cache.scrollHeight,o=l.cache.height,a=c.padding;return o<i?t+n+i+a<e:o+2*a<e}},is:{active:function(){return h.hasClass(d.active)},ie:function(){return!z.ActiveXObject&&"ActiveXObject"in z||"ActiveXObject"in z},animating:function(){return h.transition("is supported")?h.transition("is animating"):h.is(":visible")},scrolling:function(){return o.hasClass(d.scrolling)},modernBrowser:function(){return!(z.ActiveXObject||"ActiveXObject"in z)}},set:{autofocus:function(){var e=h.find("[tabindex], :input").filter(":visible"),t=e.filter("[autofocus]"),n=0<t.length?t.first():e.first();0<n.length&&n.focus()},clickaway:function(){a.on("click"+t,l.event.click)},dimmerSettings:function(){if(j.fn.dimmer!==M){var e={debug:c.debug,dimmerName:"suimodals",closable:"auto",useFlex:l.can.useFlex(),variation:!c.centered&&"top aligned",duration:{show:c.duration,hide:c.duration}},t=j.extend(!0,e,c.dimmerSettings);c.inverted&&(t.variation=t.variation!==M?t.variation+" inverted":"inverted"),v.dimmer("setting",t)}else l.error(m.dimmer)},dimmerStyles:function(){c.inverted?a.addClass(d.inverted):a.removeClass(d.inverted),c.blurring?o.addClass(d.blurring):o.removeClass(d.blurring)},suimodalOffset:function(){var e=l.cache.width,t=l.cache.height;h.css({marginTop:c.centered&&l.can.fit()?-t/2:0,marginLeft:-e/2}),l.verbose("Setting suimodal offset for legacy mode")},screenHeight:function(){l.can.fit()?A.css("height",""):(l.debug("suimodal is taller than page content, resizing page height"),A.css("height",l.cache.height+2*c.padding))},active:function(){h.addClass(d.active)},scrolling:function(){o.addClass(d.scrolling),h.addClass(d.scrolling),l.unbind.scrollLock()},legacy:function(){h.addClass(d.legacy)},type:function(){l.can.fit()?(l.verbose("suimodal fits on screen"),l.others.active()||l.others.animating()||(l.remove.scrolling(),l.bind.scrollLock())):(l.verbose("suimodal cannot fit on screen setting to scrolling"),l.set.scrolling())},undetached:function(){o.addClass(d.undetached)}},setting:function(e,t){if(l.debug("Changing setting",e,t),j.isPlainObject(e))j.extend(!0,c,e);else{if(t===M)return c[e];j.isPlainObject(c[e])?j.extend(!0,c[e],t):c[e]=t}},internal:function(e,t){if(j.isPlainObject(e))j.extend(!0,l,e);else{if(t===M)return l[e];l[e]=t}},debug:function(){!c.silent&&c.debug&&(c.performance?l.performance.log(arguments):(l.debug=Function.prototype.bind.call(console.info,console,c.name+":"),l.debug.apply(console,arguments)))},verbose:function(){!c.silent&&c.verbose&&c.debug&&(c.performance?l.performance.log(arguments):(l.verbose=Function.prototype.bind.call(console.info,console,c.name+":"),l.verbose.apply(console,arguments)))},error:function(){c.silent||(l.error=Function.prototype.bind.call(console.error,console,c.name+":"),l.error.apply(console,arguments))},performance:{log:function(e){var t,n;c.performance&&(n=(t=(new Date).getTime())-(P||t),P=t,E.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:y,"Execution Time":n})),clearTimeout(l.performance.timer),l.performance.timer=setTimeout(l.performance.display,500)},display:function(){var e=c.name+":",n=0;P=!1,clearTimeout(l.performance.timer),j.each(E,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",R&&(e+=" '"+R+"'"),(console.group!==M||console.table!==M)&&0<E.length&&(console.groupCollapsed(e),console.table?console.table(E):j.each(E,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),E=[]}},invoke:function(i,e,t){var o,a,n,r=x;return e=e||D,t=y||t,"string"==typeof i&&r!==M&&(i=i.split(/[\. ]/),o=i.length-1,j.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(j.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==M)return a=r[n],!1;if(!j.isPlainObject(r[t])||e==o)return r[t]!==M&&(a=r[t]),!1;r=r[t]}})),j.isFunction(a)?n=a.apply(t,e):a!==M&&(n=a),j.isArray(S)?S.push(n):S!==M?S=[S,n]:n!==M&&(S=n),a}},O?(x===M&&l.initialize(),l.invoke(F)):(x!==M&&x.invoke("destroy"),l.initialize())}),S!==M?S:this},j.fn.suimodal.settings={name:"suimodal",namespace:"suimodal",useFlex:"auto",offset:0,silent:!1,debug:!1,verbose:!1,performance:!0,observeChanges:!1,allowMultiple:!1,detachable:!0,closable:!0,autofocus:!0,inverted:!1,blurring:!1,centered:!0,dimmerSettings:{closable:!1,useCSS:!0},keyboardShortcuts:!0,context:"body",queue:!1,duration:500,transition:"scale",padding:50,onShow:function(){},onVisible:function(){},onHide:function(){return!0},onHidden:function(){},onApprove:function(){return!0},onDeny:function(){return!0},selector:{close:"> .close",approve:".actions .positive, .actions .approve, .actions .ok",deny:".actions .negative, .actions .deny, .actions .cancel",suimodal:".ui.suimodal"},error:{dimmer:"UI Dimmer, a required component is not included in this page",method:"The method you called is not defined.",notFound:"The element you specified could not be found"},className:{active:"active",animating:"animating",blurring:"blurring",inverted:"inverted",legacy:"legacy",loading:"loading",scrolling:"scrolling",undetached:"undetached"}}}(jQuery,window,document),function(y,x,e,C){"use strict";x=void 0!==x&&x.Math==Math?x:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),y.fn.nag=function(d){var f,e=y(this),m=e.selector||"",g=(new Date).getTime(),p=[],h=d,v="string"==typeof h,b=[].slice.call(arguments,1);return e.each(function(){var s,i=y.isPlainObject(d)?y.extend(!0,{},y.fn.nag.settings,d):y.extend({},y.fn.nag.settings),e=(i.className,i.selector),l=i.error,t=i.namespace,n="."+t,o=t+"-module",a=y(this),r=(a.find(e.close),i.context?y(i.context):y("body")),c=this,u=a.data(o);x.requestAnimationFrame||x.mozRequestAnimationFrame||x.webkitRequestAnimationFrame||x.msRequestAnimationFrame;s={initialize:function(){s.verbose("Initializing element"),a.on("click"+n,e.close,s.dismiss).data(o,s),i.detachable&&a.parent()[0]!==r[0]&&a.detach().prependTo(r),0<i.displayTime&&setTimeout(s.hide,i.displayTime),s.show()},destroy:function(){s.verbose("Destroying instance"),a.removeData(o).off(n)},show:function(){s.should.show()&&!a.is(":visible")&&(s.debug("Showing nag",i.animation.show),"fade"==i.animation.show?a.fadeIn(i.duration,i.easing):a.slideDown(i.duration,i.easing))},hide:function(){s.debug("Showing nag",i.animation.hide),"fade"==i.animation.show?a.fadeIn(i.duration,i.easing):a.slideUp(i.duration,i.easing)},onHide:function(){s.debug("Removing nag",i.animation.hide),a.remove(),i.onHide&&i.onHide()},dismiss:function(e){i.storageMethod&&s.storage.set(i.key,i.value),s.hide(),e.stopImmediatePropagation(),e.preventDefault()},should:{show:function(){return i.persist?(s.debug("Persistent nag is set, can show nag"),!0):s.storage.get(i.key)!=i.value.toString()?(s.debug("Stored value is not set, can show nag",s.storage.get(i.key)),!0):(s.debug("Stored value is set, cannot show nag",s.storage.get(i.key)),!1)}},get:{storageOptions:function(){var e={};return i.expires&&(e.expires=i.expires),i.domain&&(e.domain=i.domain),i.path&&(e.path=i.path),e}},clear:function(){s.storage.remove(i.key)},storage:{set:function(e,t){var n=s.get.storageOptions();if("localstorage"==i.storageMethod&&x.localStorage!==C)x.localStorage.setItem(e,t),s.debug("Value stored using local storage",e,t);else if("sessionstorage"==i.storageMethod&&x.sessionStorage!==C)x.sessionStorage.setItem(e,t),s.debug("Value stored using session storage",e,t);else{if(y.cookie===C)return void s.error(l.noCookieStorage);y.cookie(e,t,n),s.debug("Value stored using cookie",e,t,n)}},get:function(e,t){var n;return"localstorage"==i.storageMethod&&x.localStorage!==C?n=x.localStorage.getItem(e):"sessionstorage"==i.storageMethod&&x.sessionStorage!==C?n=x.sessionStorage.getItem(e):y.cookie!==C?n=y.cookie(e):s.error(l.noCookieStorage),"undefined"!=n&&"null"!=n&&n!==C&&null!==n||(n=C),n},remove:function(e){var t=s.get.storageOptions();"localstorage"==i.storageMethod&&x.localStorage!==C?x.localStorage.removeItem(e):"sessionstorage"==i.storageMethod&&x.sessionStorage!==C?x.sessionStorage.removeItem(e):y.cookie!==C?y.removeCookie(e,t):s.error(l.noStorage)}},setting:function(e,t){if(s.debug("Changing setting",e,t),y.isPlainObject(e))y.extend(!0,i,e);else{if(t===C)return i[e];y.isPlainObject(i[e])?y.extend(!0,i[e],t):i[e]=t}},internal:function(e,t){if(y.isPlainObject(e))y.extend(!0,s,e);else{if(t===C)return s[e];s[e]=t}},debug:function(){!i.silent&&i.debug&&(i.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,i.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!i.silent&&i.verbose&&i.debug&&(i.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,i.name+":"),s.verbose.apply(console,arguments)))},error:function(){i.silent||(s.error=Function.prototype.bind.call(console.error,console,i.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;i.performance&&(n=(t=(new Date).getTime())-(g||t),g=t,p.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:c,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=i.name+":",n=0;g=!1,clearTimeout(s.performance.timer),y.each(p,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",m&&(e+=" '"+m+"'"),(console.group!==C||console.table!==C)&&0<p.length&&(console.groupCollapsed(e),console.table?console.table(p):y.each(p,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),p=[]}},invoke:function(i,e,t){var o,a,n,r=u;return e=e||b,t=c||t,"string"==typeof i&&r!==C&&(i=i.split(/[\. ]/),o=i.length-1,y.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(y.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==C)return a=r[n],!1;if(!y.isPlainObject(r[t])||e==o)return r[t]!==C?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),y.isFunction(a)?n=a.apply(t,e):a!==C&&(n=a),y.isArray(f)?f.push(n):f!==C?f=[f,n]:n!==C&&(f=n),a}},v?(u===C&&s.initialize(),s.invoke(h)):(u!==C&&u.invoke("destroy"),s.initialize())}),f!==C?f:this},y.fn.nag.settings={name:"Nag",silent:!1,debug:!1,verbose:!1,performance:!0,namespace:"Nag",persist:!1,displayTime:0,animation:{show:"slide",hide:"slide"},context:!1,detachable:!1,expires:30,domain:!1,path:"/",storageMethod:"cookie",key:"nag",value:"dismiss",error:{noCookieStorage:"$.cookie is not included. A storage solution is required.",noStorage:"Neither $.cookie or store is defined. A storage solution is required for storing state",method:"The method you called is not defined."},className:{bottom:"bottom",fixed:"fixed"},selector:{close:".close.icon"},speed:500,easing:"easeOutQuad",onHide:function(){}},y.extend(y.easing,{easeOutQuad:function(e,t,n,i,o){return-i*(t/=o)*(t-2)+n}})}(jQuery,window,document),function(z,I,M,L){"use strict";I=void 0!==I&&I.Math==Math?I:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),z.fn.popup=function(k){var T,e=z(this),A=z(M),R=z(I),P=z("body"),E=e.selector||"",F=(new Date).getTime(),O=[],D=k,q="string"==typeof D,j=[].slice.call(arguments,1);return e.each(function(){var u,c,e,t,n,d,f=z.isPlainObject(k)?z.extend(!0,{},z.fn.popup.settings,k):z.extend({},z.fn.popup.settings),o=f.selector,m=f.className,g=f.error,p=f.metadata,i=f.namespace,a="."+f.namespace,r="module-"+i,h=z(this),s=z(f.context),l=z(f.scrollContext),v=z(f.boundary),b=f.target?z(f.target):h,y=0,x=!1,C=!1,w=this,S=h.data(r);d={initialize:function(){d.debug("Initializing",h),d.createID(),d.bind.events(),!d.exists()&&f.preserve&&d.create(),f.observeChanges&&d.observeChanges(),d.instantiate()},instantiate:function(){d.verbose("Storing instance",d),S=d,h.data(r,S)},observeChanges:function(){"MutationObserver"in I&&((e=new MutationObserver(d.event.documentChanged)).observe(M,{childList:!0,subtree:!0}),d.debug("Setting up mutation observer",e))},refresh:function(){f.popup?u=z(f.popup).eq(0):f.inline&&(u=b.nextAll(o.popup).eq(0),f.popup=u),f.popup?(u.addClass(m.loading),c=d.get.offsetParent(),u.removeClass(m.loading),f.movePopup&&d.has.popup()&&d.get.offsetParent(u)[0]!==c[0]&&(d.debug("Moving popup to the same offset parent as target"),u.detach().appendTo(c))):c=f.inline?d.get.offsetParent(b):d.has.popup()?d.get.offsetParent(u):P,c.is("html")&&c[0]!==P[0]&&(d.debug("Setting page as offset parent"),c=P),d.get.variation()&&d.set.variation()},reposition:function(){d.refresh(),d.set.position()},destroy:function(){d.debug("Destroying previous module"),e&&e.disconnect(),u&&!f.preserve&&d.removePopup(),clearTimeout(d.hideTimer),clearTimeout(d.showTimer),d.unbind.close(),d.unbind.events(),h.removeData(r)},event:{start:function(e){var t=z.isPlainObject(f.delay)?f.delay.show:f.delay;clearTimeout(d.hideTimer),C||(d.showTimer=setTimeout(d.show,t))},end:function(){var e=z.isPlainObject(f.delay)?f.delay.hide:f.delay;clearTimeout(d.showTimer),d.hideTimer=setTimeout(d.hide,e)},touchstart:function(e){C=!0,d.show()},resize:function(){d.is.visible()&&d.set.position()},documentChanged:function(e){[].forEach.call(e,function(e){e.removedNodes&&[].forEach.call(e.removedNodes,function(e){(e==w||0<z(e).find(w).length)&&(d.debug("Element removed from DOM, tearing down events"),d.destroy())})})},hideGracefully:function(e){var t=z(e.target),n=z.contains(M.documentElement,e.target),i=0<t.closest(o.popup).length;e&&!i&&n?(d.debug("Click occurred outside popup hiding popup"),d.hide()):d.debug("Click was inside popup, keeping popup open")}},create:function(){var e=d.get.html(),t=d.get.title(),n=d.get.content();e||n||t?(d.debug("Creating pop-up html"),e||(e=f.templates.popup({title:t,content:n})),u=z("<div/>").addClass(m.popup).data(p.activator,h).html(e),f.inline?(d.verbose("Inserting popup element inline",u),u.insertAfter(h)):(d.verbose("Appending popup element to body",u),u.appendTo(s)),d.refresh(),d.set.variation(),f.hoverable&&d.bind.popup(),f.onCreate.call(u,w)):0!==b.next(o.popup).length?(d.verbose("Pre-existing popup found"),f.inline=!0,f.popup=b.next(o.popup).data(p.activator,h),d.refresh(),f.hoverable&&d.bind.popup()):f.popup?(z(f.popup).data(p.activator,h),d.verbose("Used popup specified in settings"),d.refresh(),f.hoverable&&d.bind.popup()):d.debug("No content specified skipping display",w)},createID:function(){n=(Math.random().toString(16)+"000000000").substr(2,8),t="."+n,d.verbose("Creating unique id for element",n)},toggle:function(){d.debug("Toggling pop-up"),d.is.hidden()?(d.debug("Popup is hidden, showing pop-up"),d.unbind.close(),d.show()):(d.debug("Popup is visible, hiding pop-up"),d.hide())},show:function(e){if(e=e||function(){},d.debug("Showing pop-up",f.transition),d.is.hidden()&&(!d.is.active()||!d.is.dropdown())){if(d.exists()||d.create(),!1===f.onShow.call(u,w))return void d.debug("onShow callback returned false, cancelling popup animation");f.preserve||f.popup||d.refresh(),u&&d.set.position()&&(d.save.conditions(),f.exclusive&&d.hideAll(),d.animate.show(e))}},hide:function(e){if(e=e||function(){},d.is.visible()||d.is.animating()){if(!1===f.onHide.call(u,w))return void d.debug("onHide callback returned false, cancelling popup animation");d.remove.visible(),d.unbind.close(),d.restore.conditions(),d.animate.hide(e)}},hideAll:function(){z(o.popup).filter("."+m.popupVisible).each(function(){z(this).data(p.activator).popup("hide")})},exists:function(){return!!u&&(f.inline||f.popup?d.has.popup():1<=u.closest(s).length)},removePopup:function(){d.has.popup()&&!f.popup&&(d.debug("Removing popup",u),u.remove(),u=L,f.onRemove.call(u,w))},save:{conditions:function(){d.cache={title:h.attr("title")},d.cache.title&&h.removeAttr("title"),d.verbose("Saving original attributes",d.cache.title)}},restore:{conditions:function(){return d.cache&&d.cache.title&&(h.attr("title",d.cache.title),d.verbose("Restoring original attributes",d.cache.title)),!0}},supports:{svg:function(){return"undefined"==typeof SVGGraphicsElement}},animate:{show:function(e){e=z.isFunction(e)?e:function(){},f.transition&&z.fn.transition!==L&&h.transition("is supported")?(d.set.visible(),u.transition({animation:f.transition+" in",queue:!1,debug:f.debug,verbose:f.verbose,duration:f.duration,onComplete:function(){d.bind.close(),e.call(u,w),f.onVisible.call(u,w)}})):d.error(g.noTransition)},hide:function(e){e=z.isFunction(e)?e:function(){},d.debug("Hiding pop-up"),!1!==f.onHide.call(u,w)?f.transition&&z.fn.transition!==L&&h.transition("is supported")?u.transition({animation:f.transition+" out",queue:!1,duration:f.duration,debug:f.debug,verbose:f.verbose,onComplete:function(){d.reset(),e.call(u,w),f.onHidden.call(u,w)}}):d.error(g.noTransition):d.debug("onHide callback returned false, cancelling popup animation")}},change:{content:function(e){u.html(e)}},get:{html:function(){return h.removeData(p.html),h.data(p.html)||f.html},title:function(){return h.removeData(p.title),h.data(p.title)||f.title},content:function(){return h.removeData(p.content),h.data(p.content)||f.content||h.attr("title")},variation:function(){return h.removeData(p.variation),h.data(p.variation)||f.variation},popup:function(){return u},popupOffset:function(){return u.offset()},calculations:function(){var e,t=d.get.offsetParent(u),n=b[0],i=v[0]==I,o=f.inline||f.popup&&f.movePopup?b.position():b.offset(),a=i?{top:0,left:0}:v.offset(),r={},s=i?{top:R.scrollTop(),left:R.scrollLeft()}:{top:0,left:0};if(r={target:{element:b[0],width:b.outerWidth(),height:b.outerHeight(),top:o.top,left:o.left,margin:{}},popup:{width:u.outerWidth(),height:u.outerHeight()},parent:{width:c.outerWidth(),height:c.outerHeight()},screen:{top:a.top,left:a.left,scroll:{top:s.top,left:s.left},width:v.width(),height:v.height()}},t.get(0)!==c.get(0)){var l=t.offset();r.target.top-=l.top,r.target.left-=l.left,r.parent.width=t.outerWidth(),r.parent.height=t.outerHeight()}return f.setFluidWidth&&d.is.fluid()&&(r.container={width:u.parent().outerWidth()},r.popup.width=r.container.width),r.target.margin.top=f.inline?parseInt(I.getComputedStyle(n).getPropertyValue("margin-top"),10):0,r.target.margin.left=f.inline?d.is.rtl()?parseInt(I.getComputedStyle(n).getPropertyValue("margin-right"),10):parseInt(I.getComputedStyle(n).getPropertyValue("margin-left"),10):0,e=r.screen,r.boundary={top:e.top+e.scroll.top,bottom:e.top+e.scroll.top+e.height,left:e.left+e.scroll.left,right:e.left+e.scroll.left+e.width},r},id:function(){return n},startEvent:function(){return"hover"==f.on?"mouseenter":"focus"==f.on&&"focus"},scrollEvent:function(){return"scroll"},endEvent:function(){return"hover"==f.on?"mouseleave":"focus"==f.on&&"blur"},distanceFromBoundary:function(e,t){var n,i,o={};return n=(t=t||d.get.calculations()).popup,i=t.boundary,e&&(o={top:e.top-i.top,left:e.left-i.left,right:i.right-(e.left+n.width),bottom:i.bottom-(e.top+n.height)},d.verbose("Distance from boundaries determined",e,o)),o},offsetParent:function(e){var t=(e!==L?e[0]:b[0]).parentNode,n=z(t);if(t)for(var i="none"===n.css("transform"),o="static"===n.css("position"),a=n.is("body");t&&!a&&o&&i;)t=t.parentNode,i="none"===(n=z(t)).css("transform"),o="static"===n.css("position"),a=n.is("body");return n&&0<n.length?n:z()},positions:function(){return{"top left":!1,"top center":!1,"top right":!1,"bottom left":!1,"bottom center":!1,"bottom right":!1,"left center":!1,"right center":!1}},nextPosition:function(e){var t=e.split(" "),n=t[0],i=t[1],o="top"==n||"bottom"==n,a=!1,r=!1,s=!1;return x||(d.verbose("All available positions available"),x=d.get.positions()),d.debug("Recording last position tried",e),x[e]=!0,"opposite"===f.prefer&&(s=(s=[{top:"bottom",bottom:"top",left:"right",right:"left"}[n],i]).join(" "),a=!0===x[s],d.debug("Trying opposite strategy",s)),"adjacent"===f.prefer&&o&&(s=(s=[n,{left:"center",center:"right",right:"left"}[i]]).join(" "),r=!0===x[s],d.debug("Trying adjacent strategy",s)),(r||a)&&(d.debug("Using backup position",s),s={"top left":"top center","top center":"top right","top right":"right center","right center":"bottom right","bottom right":"bottom center","bottom center":"bottom left","bottom left":"left center","left center":"top left"}[e]),s}},set:{position:function(e,t){if(0!==b.length&&0!==u.length){var n,i,o,a,r,s,l,c;if(t=t||d.get.calculations(),e=e||h.data(p.position)||f.position,n=h.data(p.offset)||f.offset,i=f.distanceAway,o=t.target,a=t.popup,r=t.parent,d.should.centerArrow(t)&&(d.verbose("Adjusting offset to center arrow on small target element"),"top left"!=e&&"bottom left"!=e||(n+=o.width/2,n-=f.arrowPixelsFromEdge),"top right"!=e&&"bottom right"!=e||(n-=o.width/2,n+=f.arrowPixelsFromEdge)),0===o.width&&0===o.height&&!d.is.svg(o.element))return d.debug("Popup target is hidden, no action taken"),!1;switch(f.inline&&(d.debug("Adding margin to calculation",o.margin),"left center"==e||"right center"==e?(n+=o.margin.top,i+=-o.margin.left):"top left"==e||"top center"==e||"top right"==e?(n+=o.margin.left,i-=o.margin.top):(n+=o.margin.left,i+=o.margin.top)),d.debug("Determining popup position from calculations",e,t),d.is.rtl()&&(e=e.replace(/left|right/g,function(e){return"left"==e?"right":"left"}),d.debug("RTL: Popup position updated",e)),y==f.maxSearchDepth&&"string"==typeof f.lastResort&&(e=f.lastResort),e){case"top left":s={top:"auto",bottom:r.height-o.top+i,left:o.left+n,right:"auto"};break;case"top center":s={bottom:r.height-o.top+i,left:o.left+o.width/2-a.width/2+n,top:"auto",right:"auto"};break;case"top right":s={bottom:r.height-o.top+i,right:r.width-o.left-o.width-n,top:"auto",left:"auto"};break;case"left center":s={top:o.top+o.height/2-a.height/2+n,right:r.width-o.left+i,left:"auto",bottom:"auto"};break;case"right center":s={top:o.top+o.height/2-a.height/2+n,left:o.left+o.width+i,bottom:"auto",right:"auto"};break;case"bottom left":s={top:o.top+o.height+i,left:o.left+n,bottom:"auto",right:"auto"};break;case"bottom center":s={top:o.top+o.height+i,left:o.left+o.width/2-a.width/2+n,bottom:"auto",right:"auto"};break;case"bottom right":s={top:o.top+o.height+i,right:r.width-o.left-o.width-n,left:"auto",bottom:"auto"}}if(s===L&&d.error(g.invalidPosition,e),d.debug("Calculated popup positioning values",s),u.css(s).removeClass(m.position).addClass(e).addClass(m.loading),l=d.get.popupOffset(),c=d.get.distanceFromBoundary(l,t),d.is.offstage(c,e)){if(d.debug("Position is outside viewport",e),y<f.maxSearchDepth)return y++,e=d.get.nextPosition(e),d.debug("Trying new position",e),!!u&&d.set.position(e,t);if(!f.lastResort)return d.debug("Popup could not find a position to display",u),d.error(g.cannotPlace,w),d.remove.attempts(),d.remove.loading(),d.reset(),f.onUnplaceable.call(u,w),!1;d.debug("No position found, showing with last position")}return d.debug("Position is on stage",e),d.remove.attempts(),d.remove.loading(),f.setFluidWidth&&d.is.fluid()&&d.set.fluidWidth(t),!0}d.error(g.notFound)},fluidWidth:function(e){e=e||d.get.calculations(),d.debug("Automatically setting element width to parent width",e.parent.width),u.css("width",e.container.width)},variation:function(e){(e=e||d.get.variation())&&d.has.popup()&&(d.verbose("Adding variation to popup",e),u.addClass(e))},visible:function(){h.addClass(m.visible)}},remove:{loading:function(){u.removeClass(m.loading)},variation:function(e){(e=e||d.get.variation())&&(d.verbose("Removing variation",e),u.removeClass(e))},visible:function(){h.removeClass(m.visible)},attempts:function(){d.verbose("Resetting all searched positions"),y=0,x=!1}},bind:{events:function(){d.debug("Binding popup events to module"),"click"==f.on&&h.on("click"+a,d.toggle),"hover"==f.on&&h.on("touchstart"+a,d.event.touchstart),d.get.startEvent()&&h.on(d.get.startEvent()+a,d.event.start).on(d.get.endEvent()+a,d.event.end),f.target&&d.debug("Target set to element",b),R.on("resize"+t,d.event.resize)},popup:function(){d.verbose("Allowing hover events on popup to prevent closing"),u&&d.has.popup()&&u.on("mouseenter"+a,d.event.start).on("mouseleave"+a,d.event.end)},close:function(){(!0===f.hideOnScroll||"auto"==f.hideOnScroll&&"click"!=f.on)&&d.bind.closeOnScroll(),d.is.closable()?d.bind.clickaway():"hover"==f.on&&C&&d.bind.touchClose()},closeOnScroll:function(){d.verbose("Binding scroll close event to document"),l.one(d.get.scrollEvent()+t,d.event.hideGracefully)},touchClose:function(){d.verbose("Binding popup touchclose event to document"),A.on("touchstart"+t,function(e){d.verbose("Touched away from popup"),d.event.hideGracefully.call(w,e)})},clickaway:function(){d.verbose("Binding popup close event to document"),A.on("click"+t,function(e){d.verbose("Clicked away from popup"),d.event.hideGracefully.call(w,e)})}},unbind:{events:function(){R.off(t),h.off(a)},close:function(){A.off(t),l.off(t)}},has:{popup:function(){return u&&0<u.length}},should:{centerArrow:function(e){return!d.is.basic()&&e.target.width<=2*f.arrowPixelsFromEdge}},is:{closable:function(){return"auto"==f.closable?"hover"!=f.on:f.closable},offstage:function(e,n){var i=[];return z.each(e,function(e,t){t<-f.jitter&&(d.debug("Position exceeds allowable distance from edge",e,t,n),i.push(e))}),0<i.length},svg:function(e){return d.supports.svg()&&e instanceof SVGGraphicsElement},basic:function(){return h.hasClass(m.basic)},active:function(){return h.hasClass(m.active)},animating:function(){return u!==L&&u.hasClass(m.animating)},fluid:function(){return u!==L&&u.hasClass(m.fluid)},visible:function(){return u!==L&&u.hasClass(m.popupVisible)},dropdown:function(){return h.hasClass(m.dropdown)},hidden:function(){return!d.is.visible()},rtl:function(){return"rtl"==h.css("direction")}},reset:function(){d.remove.visible(),f.preserve?z.fn.transition!==L&&u.transition("remove transition"):d.removePopup()},setting:function(e,t){if(z.isPlainObject(e))z.extend(!0,f,e);else{if(t===L)return f[e];f[e]=t}},internal:function(e,t){if(z.isPlainObject(e))z.extend(!0,d,e);else{if(t===L)return d[e];d[e]=t}},debug:function(){!f.silent&&f.debug&&(f.performance?d.performance.log(arguments):(d.debug=Function.prototype.bind.call(console.info,console,f.name+":"),d.debug.apply(console,arguments)))},verbose:function(){!f.silent&&f.verbose&&f.debug&&(f.performance?d.performance.log(arguments):(d.verbose=Function.prototype.bind.call(console.info,console,f.name+":"),d.verbose.apply(console,arguments)))},error:function(){f.silent||(d.error=Function.prototype.bind.call(console.error,console,f.name+":"),d.error.apply(console,arguments))},performance:{log:function(e){var t,n;f.performance&&(n=(t=(new Date).getTime())-(F||t),F=t,O.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:w,"Execution Time":n})),clearTimeout(d.performance.timer),d.performance.timer=setTimeout(d.performance.display,500)},display:function(){var e=f.name+":",n=0;F=!1,clearTimeout(d.performance.timer),z.each(O,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",E&&(e+=" '"+E+"'"),(console.group!==L||console.table!==L)&&0<O.length&&(console.groupCollapsed(e),console.table?console.table(O):z.each(O,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),O=[]}},invoke:function(i,e,t){var o,a,n,r=S;return e=e||j,t=w||t,"string"==typeof i&&r!==L&&(i=i.split(/[\. ]/),o=i.length-1,z.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(z.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==L)return a=r[n],!1;if(!z.isPlainObject(r[t])||e==o)return r[t]!==L&&(a=r[t]),!1;r=r[t]}})),z.isFunction(a)?n=a.apply(t,e):a!==L&&(n=a),z.isArray(T)?T.push(n):T!==L?T=[T,n]:n!==L&&(T=n),a}},q?(S===L&&d.initialize(),d.invoke(D)):(S!==L&&S.invoke("destroy"),d.initialize())}),T!==L?T:this},z.fn.popup.settings={name:"Popup",silent:!1,debug:!1,verbose:!1,performance:!0,namespace:"popup",observeChanges:!0,onCreate:function(){},onRemove:function(){},onShow:function(){},onVisible:function(){},onHide:function(){},onUnplaceable:function(){},onHidden:function(){},on:"hover",boundary:I,addTouchEvents:!0,position:"top left",variation:"",movePopup:!0,target:!1,popup:!1,inline:!1,preserve:!1,hoverable:!1,content:!1,html:!1,title:!1,closable:!0,hideOnScroll:"auto",exclusive:!1,context:"body",scrollContext:I,prefer:"opposite",lastResort:!1,arrowPixelsFromEdge:20,delay:{show:50,hide:70},setFluidWidth:!0,duration:200,transition:"scale",distanceAway:0,jitter:2,offset:0,maxSearchDepth:15,error:{invalidPosition:"The position you specified is not a valid position",cannotPlace:"Popup does not fit within the boundaries of the viewport",method:"The method you called is not defined.",noTransition:"This module requires ui transitions <https://github.com/Semantic-Org/UI-Transition>",notFound:"The target or popup you specified does not exist on the page"},metadata:{activator:"activator",content:"content",html:"html",offset:"offset",position:"position",title:"title",variation:"variation"},className:{active:"active",basic:"basic",animating:"animating",dropdown:"dropdown",fluid:"fluid",loading:"loading",popup:"ui popup",position:"top left center bottom right",visible:"visible",popupVisible:"visible"},selector:{popup:".ui.popup"},templates:{escape:function(e){var t={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"};return/[&<>"'`]/.test(e)?e.replace(/[&<>"'`]/g,function(e){return t[e]}):e},popup:function(e){var t="",n=z.fn.popup.settings.templates.escape;return typeof e!==L&&(typeof e.title!==L&&e.title&&(e.title=n(e.title),t+='<div class="header">'+e.title+"</div>"),typeof e.content!==L&&e.content&&(e.content=n(e.content),t+='<div class="content">'+e.content+"</div>")),t}}}}(jQuery,window,document),function(k,e,T,A){"use strict";void 0!==(e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")())&&e.Math==Math||("undefined"!=typeof self&&self.Math==Math?self:Function("return this")());k.fn.progress=function(h){var v,e=k(this),b=e.selector||"",y=(new Date).getTime(),x=[],C=h,w="string"==typeof C,S=[].slice.call(arguments,1);return e.each(function(){var s,i=k.isPlainObject(h)?k.extend(!0,{},k.fn.progress.settings,h):k.extend({},k.fn.progress.settings),t=i.className,n=i.metadata,e=i.namespace,o=i.selector,l=i.error,a="."+e,r="module-"+e,c=k(this),u=k(this).find(o.bar),d=k(this).find(o.progress),f=k(this).find(o.label),m=this,g=c.data(r),p=!1;s={initialize:function(){s.debug("Initializing progress bar",i),s.set.duration(),s.set.transitionEvent(),s.read.metadata(),s.read.settings(),s.instantiate()},instantiate:function(){s.verbose("Storing instance of progress",s),g=s,c.data(r,s)},destroy:function(){s.verbose("Destroying previous progress for",c),clearInterval(g.interval),s.remove.state(),c.removeData(r),g=A},reset:function(){s.remove.nextValue(),s.update.progress(0)},complete:function(){(s.percent===A||s.percent<100)&&(s.remove.progressPoll(),s.set.percent(100))},read:{metadata:function(){var e={percent:c.data(n.percent),total:c.data(n.total),value:c.data(n.value)};e.percent&&(s.debug("Current percent value set from metadata",e.percent),s.set.percent(e.percent)),e.total&&(s.debug("Total value set from metadata",e.total),s.set.total(e.total)),e.value&&(s.debug("Current value set from metadata",e.value),s.set.value(e.value),s.set.progress(e.value))},settings:function(){!1!==i.total&&(s.debug("Current total set in settings",i.total),s.set.total(i.total)),!1!==i.value&&(s.debug("Current value set in settings",i.value),s.set.value(i.value),s.set.progress(s.value)),!1!==i.percent&&(s.debug("Current percent set in settings",i.percent),s.set.percent(i.percent))}},bind:{transitionEnd:function(t){var e=s.get.transitionEnd();u.one(e+a,function(e){clearTimeout(s.failSafeTimer),t.call(this,e)}),s.failSafeTimer=setTimeout(function(){u.triggerHandler(e)},i.duration+i.failSafeDelay),s.verbose("Adding fail safe timer",s.timer)}},increment:function(e){var t,n;s.has.total()?n=(t=s.get.value())+(e=e||1):(n=(t=s.get.percent())+(e=e||s.get.randomValue()),100,s.debug("Incrementing percentage by",t,n)),n=s.get.normalizedValue(n),s.set.progress(n)},decrement:function(e){var t,n;s.get.total()?(n=(t=s.get.value())-(e=e||1),s.debug("Decrementing value by",e,t)):(n=(t=s.get.percent())-(e=e||s.get.randomValue()),s.debug("Decrementing percentage by",e,t)),n=s.get.normalizedValue(n),s.set.progress(n)},has:{progressPoll:function(){return s.progressPoll},total:function(){return!1!==s.get.total()}},get:{text:function(e){var t=s.value||0,n=s.total||0,i=p?s.get.displayPercent():s.percent||0,o=0<s.total?n-t:100-i;return e=(e=e||"").replace("{value}",t).replace("{total}",n).replace("{left}",o).replace("{percent}",i),s.verbose("Adding variables to progress bar text",e),e},normalizedValue:function(e){if(e<0)return s.debug("Value cannot decrement below 0"),0;if(s.has.total()){if(e>s.total)return s.debug("Value cannot increment above total",s.total),s.total}else if(100<e)return s.debug("Value cannot increment above 100 percent"),100;return e},updateInterval:function(){return"auto"==i.updateInterval?i.duration:i.updateInterval},randomValue:function(){return s.debug("Generating random increment percentage"),Math.floor(Math.random()*i.random.max+i.random.min)},numericValue:function(e){return"string"==typeof e?""!==e.replace(/[^\d.]/g,"")&&+e.replace(/[^\d.]/g,""):e},transitionEnd:function(){var e,t=T.createElement("element"),n={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(e in n)if(t.style[e]!==A)return n[e]},displayPercent:function(){var e=u.width(),t=c.width(),n=parseInt(u.css("min-width"),10)<e?e/t*100:s.percent;return 0<i.precision?Math.round(n*(10*i.precision))/(10*i.precision):Math.round(n)},percent:function(){return s.percent||0},value:function(){return s.nextValue||s.value||0},total:function(){return s.total||!1}},create:{progressPoll:function(){s.progressPoll=setTimeout(function(){s.update.toNextValue(),s.remove.progressPoll()},s.get.updateInterval())}},is:{complete:function(){return s.is.success()||s.is.warning()||s.is.error()},success:function(){return c.hasClass(t.success)},warning:function(){return c.hasClass(t.warning)},error:function(){return c.hasClass(t.error)},active:function(){return c.hasClass(t.active)},visible:function(){return c.is(":visible")}},remove:{progressPoll:function(){s.verbose("Removing progress poll timer"),s.progressPoll&&(clearTimeout(s.progressPoll),delete s.progressPoll)},nextValue:function(){s.verbose("Removing progress value stored for next update"),delete s.nextValue},state:function(){s.verbose("Removing stored state"),delete s.total,delete s.percent,delete s.value},active:function(){s.verbose("Removing active state"),c.removeClass(t.active)},success:function(){s.verbose("Removing success state"),c.removeClass(t.success)},warning:function(){s.verbose("Removing warning state"),c.removeClass(t.warning)},error:function(){s.verbose("Removing error state"),c.removeClass(t.error)}},set:{barWidth:function(e){100<e?s.error(l.tooHigh,e):e<0?s.error(l.tooLow,e):(u.css("width",e+"%"),c.attr("data-percent",parseInt(e,10)))},duration:function(e){e="number"==typeof(e=e||i.duration)?e+"ms":e,s.verbose("Setting progress bar transition duration",e),u.css({"transition-duration":e})},percent:function(e){e="string"==typeof e?+e.replace("%",""):e,e=0<i.precision?Math.round(e*(10*i.precision))/(10*i.precision):Math.round(e),s.percent=e,s.has.total()||(s.value=0<i.precision?Math.round(e/100*s.total*(10*i.precision))/(10*i.precision):Math.round(e/100*s.total*10)/10,i.limitValues&&(s.value=100<s.value?100:s.value<0?0:s.value)),s.set.barWidth(e),s.set.labelInterval(),s.set.labels(),i.onChange.call(m,e,s.value,s.total)},labelInterval:function(){clearInterval(s.interval),s.bind.transitionEnd(function(){s.verbose("Bar finished animating, removing continuous label updates"),clearInterval(s.interval),p=!1,s.set.labels()}),p=!0,s.interval=setInterval(function(){k.contains(T.documentElement,m)||(clearInterval(s.interval),p=!1),s.set.labels()},i.framerate)},labels:function(){s.verbose("Setting both bar progress and outer label text"),s.set.barLabel(),s.set.state()},label:function(e){(e=e||"")&&(e=s.get.text(e),s.verbose("Setting label to text",e),f.text(e))},state:function(e){100===(e=e!==A?e:s.percent)?i.autoSuccess&&!(s.is.warning()||s.is.error()||s.is.success())?(s.set.success(),s.debug("Automatically triggering success at 100%")):(s.verbose("Reached 100% removing active state"),s.remove.active(),s.remove.progressPoll()):0<e?(s.verbose("Adjusting active progress bar label",e),s.set.active()):(s.remove.active(),s.set.label(i.text.active))},barLabel:function(e){e!==A?d.text(s.get.text(e)):"ratio"==i.label&&s.total?(s.verbose("Adding ratio to bar label"),d.text(s.get.text(i.text.ratio))):"percent"==i.label&&(s.verbose("Adding percentage to bar label"),d.text(s.get.text(i.text.percent)))},active:function(e){e=e||i.text.active,s.debug("Setting active state"),i.showActivity&&!s.is.active()&&c.addClass(t.active),s.remove.warning(),s.remove.error(),s.remove.success(),(e=i.onLabelUpdate("active",e,s.value,s.total))&&s.set.label(e),s.bind.transitionEnd(function(){i.onActive.call(m,s.value,s.total)})},success:function(e){e=e||i.text.success||i.text.active,s.debug("Setting success state"),c.addClass(t.success),s.remove.active(),s.remove.warning(),s.remove.error(),s.complete(),e=i.text.success?i.onLabelUpdate("success",e,s.value,s.total):i.onLabelUpdate("active",e,s.value,s.total),s.set.label(e),s.bind.transitionEnd(function(){i.onSuccess.call(m,s.total)})},warning:function(e){e=e||i.text.warning,s.debug("Setting warning state"),c.addClass(t.warning),s.remove.active(),s.remove.success(),s.remove.error(),s.complete(),(e=i.onLabelUpdate("warning",e,s.value,s.total))&&s.set.label(e),s.bind.transitionEnd(function(){i.onWarning.call(m,s.value,s.total)})},error:function(e){e=e||i.text.error,s.debug("Setting error state"),c.addClass(t.error),s.remove.active(),s.remove.success(),s.remove.warning(),s.complete(),(e=i.onLabelUpdate("error",e,s.value,s.total))&&s.set.label(e),s.bind.transitionEnd(function(){i.onError.call(m,s.value,s.total)})},transitionEvent:function(){s.get.transitionEnd()},total:function(e){s.total=e},value:function(e){s.value=e},progress:function(e){s.has.progressPoll()?(s.debug("Updated within interval, setting next update to use new value",e),s.set.nextValue(e)):(s.debug("First update in progress update interval, immediately updating",e),s.update.progress(e),s.create.progressPoll())},nextValue:function(e){s.nextValue=e}},update:{toNextValue:function(){var e=s.nextValue;e&&(s.debug("Update interval complete using last updated value",e),s.update.progress(e),s.remove.nextValue())},progress:function(e){var t;!1===(e=s.get.numericValue(e))&&s.error(l.nonNumeric,e),e=s.get.normalizedValue(e),s.has.total()?(s.set.value(e),t=e/s.total*100,s.debug("Calculating percent complete from total",t)):(t=e,s.debug("Setting value to exact percentage value",t)),s.set.percent(t)}},setting:function(e,t){if(s.debug("Changing setting",e,t),k.isPlainObject(e))k.extend(!0,i,e);else{if(t===A)return i[e];k.isPlainObject(i[e])?k.extend(!0,i[e],t):i[e]=t}},internal:function(e,t){if(k.isPlainObject(e))k.extend(!0,s,e);else{if(t===A)return s[e];s[e]=t}},debug:function(){!i.silent&&i.debug&&(i.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,i.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!i.silent&&i.verbose&&i.debug&&(i.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,i.name+":"),s.verbose.apply(console,arguments)))},error:function(){i.silent||(s.error=Function.prototype.bind.call(console.error,console,i.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;i.performance&&(n=(t=(new Date).getTime())-(y||t),y=t,x.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:m,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=i.name+":",n=0;y=!1,clearTimeout(s.performance.timer),k.each(x,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",b&&(e+=" '"+b+"'"),(console.group!==A||console.table!==A)&&0<x.length&&(console.groupCollapsed(e),console.table?console.table(x):k.each(x,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),x=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||S,t=m||t,"string"==typeof i&&r!==A&&(i=i.split(/[\. ]/),o=i.length-1,k.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(k.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==A)return a=r[n],!1;if(!k.isPlainObject(r[t])||e==o)return r[t]!==A?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),k.isFunction(a)?n=a.apply(t,e):a!==A&&(n=a),k.isArray(v)?v.push(n):v!==A?v=[v,n]:n!==A&&(v=n),a}},w?(g===A&&s.initialize(),s.invoke(C)):(g!==A&&g.invoke("destroy"),s.initialize())}),v!==A?v:this},k.fn.progress.settings={name:"Progress",namespace:"progress",silent:!1,debug:!1,verbose:!1,performance:!0,random:{min:2,max:5},duration:300,updateInterval:"auto",autoSuccess:!0,showActivity:!0,limitValues:!0,label:"percent",precision:0,framerate:1e3/30,percent:!1,total:!1,value:!1,failSafeDelay:100,onLabelUpdate:function(e,t,n,i){return t},onChange:function(e,t,n){},onSuccess:function(e){},onActive:function(e,t){},onError:function(e,t){},onWarning:function(e,t){},error:{method:"The method you called is not defined.",nonNumeric:"Progress value is non numeric",tooHigh:"Value specified is above 100%",tooLow:"Value specified is below 0%"},regExp:{variable:/\{\$*[A-z0-9]+\}/g},metadata:{percent:"percent",total:"total",value:"value"},selector:{bar:"> .bar",label:"> .label",progress:".bar > .progress"},text:{active:!1,error:!1,success:!1,warning:!1,percent:"{percent}%",ratio:"{value} of {total}"},className:{active:"active",error:"error",success:"success",warning:"warning"}}}(jQuery,window,document),function(w,e,t,S){"use strict";e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),w.fn.rating=function(m){var g,p=w(this),h=p.selector||"",v=(new Date).getTime(),b=[],y=m,x="string"==typeof y,C=[].slice.call(arguments,1);return p.each(function(){var e,i,o=w.isPlainObject(m)?w.extend(!0,{},w.fn.rating.settings,m):w.extend({},w.fn.rating.settings),t=o.namespace,a=o.className,n=o.metadata,r=o.selector,s=(o.error,"."+t),l="module-"+t,c=this,u=w(this).data(l),d=w(this),f=d.find(r.icon);i={initialize:function(){i.verbose("Initializing rating module",o),0===f.length&&i.setup.layout(),o.interactive?i.enable():i.disable(),i.set.initialLoad(),i.set.rating(i.get.initialRating()),i.remove.initialLoad(),i.instantiate()},instantiate:function(){i.verbose("Instantiating module",o),u=i,d.data(l,i)},destroy:function(){i.verbose("Destroying previous instance",u),i.remove.events(),d.removeData(l)},refresh:function(){f=d.find(r.icon)},setup:{layout:function(){var e=i.get.maxRating(),t=w.fn.rating.settings.templates.icon(e);i.debug("Generating icon html dynamically"),d.html(t),i.refresh()}},event:{mouseenter:function(){var e=w(this);e.nextAll().removeClass(a.selected),d.addClass(a.selected),e.addClass(a.selected).prevAll().addClass(a.selected)},mouseleave:function(){d.removeClass(a.selected),f.removeClass(a.selected)},click:function(){var e=w(this),t=i.get.rating(),n=f.index(e)+1;("auto"==o.clearable?1===f.length:o.clearable)&&t==n?i.clearRating():i.set.rating(n)}},clearRating:function(){i.debug("Clearing current rating"),i.set.rating(0)},bind:{events:function(){i.verbose("Binding events"),d.on("mouseenter"+s,r.icon,i.event.mouseenter).on("mouseleave"+s,r.icon,i.event.mouseleave).on("click"+s,r.icon,i.event.click)}},remove:{events:function(){i.verbose("Removing events"),d.off(s)},initialLoad:function(){e=!1}},enable:function(){i.debug("Setting rating to interactive mode"),i.bind.events(),d.removeClass(a.disabled)},disable:function(){i.debug("Setting rating to read-only mode"),i.remove.events(),d.addClass(a.disabled)},is:{initialLoad:function(){return e}},get:{initialRating:function(){return d.data(n.rating)!==S?(d.removeData(n.rating),d.data(n.rating)):o.initialRating},maxRating:function(){return d.data(n.maxRating)!==S?(d.removeData(n.maxRating),d.data(n.maxRating)):o.maxRating},rating:function(){var e=f.filter("."+a.active).length;return i.verbose("Current rating retrieved",e),e}},set:{rating:function(e){var t=0<=e-1?e-1:0,n=f.eq(t);d.removeClass(a.selected),f.removeClass(a.selected).removeClass(a.active),0<e&&(i.verbose("Setting current rating to",e),n.prevAll().addBack().addClass(a.active)),i.is.initialLoad()||o.onRate.call(c,e)},initialLoad:function(){e=!0}},setting:function(e,t){if(i.debug("Changing setting",e,t),w.isPlainObject(e))w.extend(!0,o,e);else{if(t===S)return o[e];w.isPlainObject(o[e])?w.extend(!0,o[e],t):o[e]=t}},internal:function(e,t){if(w.isPlainObject(e))w.extend(!0,i,e);else{if(t===S)return i[e];i[e]=t}},debug:function(){!o.silent&&o.debug&&(o.performance?i.performance.log(arguments):(i.debug=Function.prototype.bind.call(console.info,console,o.name+":"),i.debug.apply(console,arguments)))},verbose:function(){!o.silent&&o.verbose&&o.debug&&(o.performance?i.performance.log(arguments):(i.verbose=Function.prototype.bind.call(console.info,console,o.name+":"),i.verbose.apply(console,arguments)))},error:function(){o.silent||(i.error=Function.prototype.bind.call(console.error,console,o.name+":"),i.error.apply(console,arguments))},performance:{log:function(e){var t,n;o.performance&&(n=(t=(new Date).getTime())-(v||t),v=t,b.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:c,"Execution Time":n})),clearTimeout(i.performance.timer),i.performance.timer=setTimeout(i.performance.display,500)},display:function(){var e=o.name+":",n=0;v=!1,clearTimeout(i.performance.timer),w.each(b,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",h&&(e+=" '"+h+"'"),1<p.length&&(e+=" ("+p.length+")"),(console.group!==S||console.table!==S)&&0<b.length&&(console.groupCollapsed(e),console.table?console.table(b):w.each(b,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),b=[]}},invoke:function(i,e,t){var o,a,n,r=u;return e=e||C,t=c||t,"string"==typeof i&&r!==S&&(i=i.split(/[\. ]/),o=i.length-1,w.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(w.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==S)return a=r[n],!1;if(!w.isPlainObject(r[t])||e==o)return r[t]!==S&&(a=r[t]),!1;r=r[t]}})),w.isFunction(a)?n=a.apply(t,e):a!==S&&(n=a),w.isArray(g)?g.push(n):g!==S?g=[g,n]:n!==S&&(g=n),a}},x?(u===S&&i.initialize(),i.invoke(y)):(u!==S&&u.invoke("destroy"),i.initialize())}),g!==S?g:this},w.fn.rating.settings={name:"Rating",namespace:"rating",slent:!1,debug:!1,verbose:!1,performance:!0,initialRating:0,interactive:!0,maxRating:4,clearable:"auto",fireOnInit:!1,onRate:function(e){},error:{method:"The method you called is not defined",noMaximum:"No maximum rating specified. Cannot generate HTML automatically"},metadata:{rating:"rating",maxRating:"maxRating"},className:{active:"active",disabled:"disabled",selected:"selected",loading:"loading"},selector:{icon:".icon"},templates:{icon:function(e){for(var t=1,n="";t<=e;)n+='<i class="icon"></i>',t++;return n}}}}(jQuery,window,document),function(E,F,O,D){"use strict";F=void 0!==F&&F.Math==Math?F:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),E.fn.search=function(l){var C,w=E(this),S=w.selector||"",k=(new Date).getTime(),T=[],A=l,R="string"==typeof A,P=[].slice.call(arguments,1);return E(this).each(function(){var f,c=E.isPlainObject(l)?E.extend(!0,{},E.fn.search.settings,l):E.extend({},E.fn.search.settings),m=c.className,u=c.metadata,d=c.regExp,a=c.fields,g=c.selector,p=c.error,e=c.namespace,i="."+e,t=e+"-module",h=E(this),v=h.find(g.prompt),n=h.find(g.searchButton),o=h.find(g.results),r=h.find(g.result),b=(h.find(g.category),this),s=h.data(t),y=!1,x=!1;f={initialize:function(){f.verbose("Initializing module"),f.get.settings(),f.determine.searchFields(),f.bind.events(),f.set.type(),f.create.results(),f.instantiate()},instantiate:function(){f.verbose("Storing instance of module",f),s=f,h.data(t,f)},destroy:function(){f.verbose("Destroying instance"),h.off(i).removeData(t)},refresh:function(){f.debug("Refreshing selector cache"),v=h.find(g.prompt),n=h.find(g.searchButton),h.find(g.category),o=h.find(g.results),r=h.find(g.result)},refreshResults:function(){o=h.find(g.results),r=h.find(g.result)},bind:{events:function(){f.verbose("Binding events to search"),c.automatic&&(h.on(f.get.inputEvent()+i,g.prompt,f.event.input),v.attr("autocomplete","off")),h.on("focus"+i,g.prompt,f.event.focus).on("blur"+i,g.prompt,f.event.blur).on("keydown"+i,g.prompt,f.handleKeyboard).on("click"+i,g.searchButton,f.query).on("mousedown"+i,g.results,f.event.result.mousedown).on("mouseup"+i,g.results,f.event.result.mouseup).on("click"+i,g.result,f.event.result.click)}},determine:{searchFields:function(){l&&l.searchFields!==D&&(c.searchFields=l.searchFields)}},event:{input:function(){c.searchDelay?(clearTimeout(f.timer),f.timer=setTimeout(function(){f.is.focused()&&f.query()},c.searchDelay)):f.query()},focus:function(){f.set.focus(),c.searchOnFocus&&f.has.minimumCharacters()&&f.query(function(){f.can.show()&&f.showResults()})},blur:function(e){var t=O.activeElement===this,n=function(){f.cancel.query(),f.remove.focus(),f.timer=setTimeout(f.hideResults,c.hideDelay)};t||(x=!1,f.resultsClicked?(f.debug("Determining if user action caused search to close"),h.one("click.close"+i,g.results,function(e){f.is.inMessage(e)||y?v.focus():(y=!1,f.is.animating()||f.is.hidden()||n())})):(f.debug("Input blurred without user action, closing results"),n()))},result:{mousedown:function(){f.resultsClicked=!0},mouseup:function(){f.resultsClicked=!1},click:function(e){f.debug("Search result selected");var t=E(this),n=t.find(g.title).eq(0),i=t.is("a[href]")?t:t.find("a[href]").eq(0),o=i.attr("href")||!1,a=i.attr("target")||!1,r=(n.html(),0<n.length&&n.text()),s=f.get.results(),l=t.data(u.result)||f.get.result(r,s);if(E.isFunction(c.onSelect)&&!1===c.onSelect.call(b,l,s))return f.debug("Custom onSelect callback cancelled default select action"),void(y=!0);f.hideResults(),r&&f.set.value(r),o&&(f.verbose("Opening search link found in result",i),"_blank"==a||e.ctrlKey?F.open(o):F.location.href=o)}}},handleKeyboard:function(e){var t,n=h.find(g.result),i=h.find(g.category),o=n.filter("."+m.active),a=n.index(o),r=n.length,s=0<o.length,l=e.which,c=13,u=38,d=40;if(l==27&&(f.verbose("Escape key pressed, blurring search field"),f.hideResults(),x=!0),f.is.visible())if(l==c){if(f.verbose("Enter key pressed, selecting active result"),0<n.filter("."+m.active).length)return f.event.result.click.call(n.filter("."+m.active),e),e.preventDefault(),!1}else l==u&&s?(f.verbose("Up key pressed, changing active result"),t=a-1<0?a:a-1,i.removeClass(m.active),n.removeClass(m.active).eq(t).addClass(m.active).closest(i).addClass(m.active),e.preventDefault()):l==d&&(f.verbose("Down key pressed, changing active result"),t=r<=a+1?a:a+1,i.removeClass(m.active),n.removeClass(m.active).eq(t).addClass(m.active).closest(i).addClass(m.active),e.preventDefault());else l==c&&(f.verbose("Enter key pressed, executing query"),f.query(),f.set.buttonPressed(),v.one("keyup",f.remove.buttonFocus))},setup:{api:function(t,n){var e={debug:c.debug,on:!1,cache:c.cache,action:"search",urlData:{query:t},onSuccess:function(e){f.parse.response.call(b,e,t),n()},onFailure:function(){f.displayMessage(p.serverError),n()},onAbort:function(e){},onError:f.error};E.extend(!0,e,c.apiSettings),f.verbose("Setting up API request",e),h.api(e)}},can:{useAPI:function(){return E.fn.api!==D},show:function(){return f.is.focused()&&!f.is.visible()&&!f.is.empty()},transition:function(){return c.transition&&E.fn.transition!==D&&h.transition("is supported")}},is:{animating:function(){return o.hasClass(m.animating)},hidden:function(){return o.hasClass(m.hidden)},inMessage:function(e){if(e.target){var t=E(e.target);return E.contains(O.documentElement,e.target)&&0<t.closest(g.message).length}},empty:function(){return""===o.html()},visible:function(){return 0<o.filter(":visible").length},focused:function(){return 0<v.filter(":focus").length}},get:{settings:function(){E.isPlainObject(l)&&l.searchFullText&&(c.fullTextSearch=l.searchFullText,f.error(c.error.oldSearchSyntax,b))},inputEvent:function(){var e=v[0];return e!==D&&e.oninput!==D?"input":e!==D&&e.onpropertychange!==D?"propertychange":"keyup"},value:function(){return v.val()},results:function(){return h.data(u.results)},result:function(n,e){var i=["title","id"],o=!1;return n=n!==D?n:f.get.value(),e=e!==D?e:f.get.results(),"category"===c.type?(f.debug("Finding result that matches",n),E.each(e,function(e,t){if(E.isArray(t.results)&&(o=f.search.object(n,t.results,i)[0]))return!1})):(f.debug("Finding result in results object",n),o=f.search.object(n,e,i)[0]),o||!1}},select:{firstResult:function(){f.verbose("Selecting first result"),r.first().addClass(m.active)}},set:{focus:function(){h.addClass(m.focus)},loading:function(){h.addClass(m.loading)},value:function(e){f.verbose("Setting search input value",e),v.val(e)},type:function(e){e=e||c.type,"category"==c.type&&h.addClass(c.type)},buttonPressed:function(){n.addClass(m.pressed)}},remove:{loading:function(){h.removeClass(m.loading)},focus:function(){h.removeClass(m.focus)},buttonPressed:function(){n.removeClass(m.pressed)}},query:function(e){e=E.isFunction(e)?e:function(){};var t=f.get.value(),n=f.read.cache(t);e=e||function(){},f.has.minimumCharacters()?(n?(f.debug("Reading result from cache",t),f.save.results(n.results),f.addResults(n.html),f.inject.id(n.results),e()):(f.debug("Querying for",t),E.isPlainObject(c.source)||E.isArray(c.source)?(f.search.local(t),e()):f.can.useAPI()?f.search.remote(t,e):(f.error(p.source),e())),c.onSearchQuery.call(b,t)):f.hideResults()},search:{local:function(e){var t,n=f.search.object(e,c.content);f.set.loading(),f.save.results(n),f.debug("Returned full local search results",n),0<c.maxResults&&(f.debug("Using specified max results",n),n=n.slice(0,c.maxResults)),"category"==c.type&&(n=f.create.categoryResults(n)),t=f.generateResults({results:n}),f.remove.loading(),f.addResults(t),f.inject.id(n),f.write.cache(e,{html:t,results:n})},remote:function(e,t){t=E.isFunction(t)?t:function(){},h.api("is loading")&&h.api("abort"),f.setup.api(e,t),h.api("query")},object:function(i,t,e){var a=[],r=[],s=[],n=i.toString().replace(d.escape,"\\$&"),o=new RegExp(d.beginsWith+n,"i"),l=function(e,t){var n=-1==E.inArray(t,a),i=-1==E.inArray(t,s),o=-1==E.inArray(t,r);n&&i&&o&&e.push(t)};return t=t||c.source,e=e!==D?e:c.searchFields,E.isArray(e)||(e=[e]),t===D||!1===t?(f.error(p.source),[]):(E.each(e,function(e,n){E.each(t,function(e,t){"string"==typeof t[n]&&(-1!==t[n].search(o)?l(a,t):"exact"===c.fullTextSearch&&f.exactSearch(i,t[n])?l(r,t):1==c.fullTextSearch&&f.fuzzySearch(i,t[n])&&l(s,t))})}),E.merge(r,s),E.merge(a,r),a)}},exactSearch:function(e,t){return e=e.toLowerCase(),-1<(t=t.toLowerCase()).indexOf(e)},fuzzySearch:function(e,t){var n=t.length,i=e.length;if("string"!=typeof e)return!1;if(e=e.toLowerCase(),t=t.toLowerCase(),n<i)return!1;if(i===n)return e===t;e:for(var o=0,a=0;o<i;o++){for(var r=e.charCodeAt(o);a<n;)if(t.charCodeAt(a++)===r)continue e;return!1}return!0},parse:{response:function(e,t){var n=f.generateResults(e);f.verbose("Parsing server response",e),e!==D&&t!==D&&e[a.results]!==D&&(f.addResults(n),f.inject.id(e[a.results]),f.write.cache(t,{html:n,results:e[a.results]}),f.save.results(e[a.results]))}},cancel:{query:function(){f.can.useAPI()&&h.api("abort")}},has:{minimumCharacters:function(){return f.get.value().length>=c.minCharacters},results:function(){return 0!==o.length&&""!=o.html()}},clear:{cache:function(e){var t=h.data(u.cache);e?e&&t&&t[e]&&(f.debug("Removing value from cache",e),delete t[e],h.data(u.cache,t)):(f.debug("Clearing cache",e),h.removeData(u.cache))}},read:{cache:function(e){var t=h.data(u.cache);return!!c.cache&&(f.verbose("Checking cache for generated html for query",e),"object"==typeof t&&t[e]!==D&&t[e])}},create:{categoryResults:function(e){var n={};return E.each(e,function(e,t){t.category&&(n[t.category]===D?(f.verbose("Creating new category of results",t.category),n[t.category]={name:t.category,results:[t]}):n[t.category].results.push(t))}),n},id:function(e,t){var n,i=e+1;return t!==D?(n=String.fromCharCode(97+t)+i,f.verbose("Creating category result id",n)):(n=i,f.verbose("Creating result id",n)),n},results:function(){0===o.length&&(o=E("<div />").addClass(m.results).appendTo(h))}},inject:{result:function(e,t,n){f.verbose("Injecting result into results");var i=n!==D?o.children().eq(n).children(g.results).first().children(g.result).eq(t):o.children(g.result).eq(t);f.verbose("Injecting results metadata",i),i.data(u.result,e)},id:function(i){f.debug("Injecting unique ids into results");var o=0,a=0;return"category"===c.type?E.each(i,function(e,i){a=0,E.each(i.results,function(e,t){var n=i.results[e];n.id===D&&(n.id=f.create.id(a,o)),f.inject.result(n,a,o),a++}),o++}):E.each(i,function(e,t){var n=i[e];n.id===D&&(n.id=f.create.id(a)),f.inject.result(n,a),a++}),i}},save:{results:function(e){f.verbose("Saving current search results to metadata",e),h.data(u.results,e)}},write:{cache:function(e,t){var n=h.data(u.cache)!==D?h.data(u.cache):{};c.cache&&(f.verbose("Writing generated html to cache",e,t),n[e]=t,h.data(u.cache,n))}},addResults:function(e){if(E.isFunction(c.onResultsAdd)&&!1===c.onResultsAdd.call(o,e))return f.debug("onResultsAdd callback cancelled default action"),!1;e?(o.html(e),f.refreshResults(),c.selectFirstResult&&f.select.firstResult(),f.showResults()):f.hideResults(function(){o.empty()})},showResults:function(e){e=E.isFunction(e)?e:function(){},x||!f.is.visible()&&f.has.results()&&(f.can.transition()?(f.debug("Showing results with css animations"),o.transition({animation:c.transition+" in",debug:c.debug,verbose:c.verbose,duration:c.duration,onComplete:function(){e()},queue:!0})):(f.debug("Showing results with javascript"),o.stop().fadeIn(c.duration,c.easing)),c.onResultsOpen.call(o))},hideResults:function(e){e=E.isFunction(e)?e:function(){},f.is.visible()&&(f.can.transition()?(f.debug("Hiding results with css animations"),o.transition({animation:c.transition+" out",debug:c.debug,verbose:c.verbose,duration:c.duration,onComplete:function(){e()},queue:!0})):(f.debug("Hiding results with javascript"),o.stop().fadeOut(c.duration,c.easing)),c.onResultsClose.call(o))},generateResults:function(e){f.debug("Generating html from response",e);var t=c.templates[c.type],n=E.isPlainObject(e[a.results])&&!E.isEmptyObject(e[a.results]),i=E.isArray(e[a.results])&&0<e[a.results].length,o="";return n||i?(0<c.maxResults&&(n?"standard"==c.type&&f.error(p.maxResults):e[a.results]=e[a.results].slice(0,c.maxResults)),E.isFunction(t)?o=t(e,a):f.error(p.noTemplate,!1)):c.showNoResults&&(o=f.displayMessage(p.noResults,"empty")),c.onResults.call(b,e),o},displayMessage:function(e,t){return t=t||"standard",f.debug("Displaying message",e,t),f.addResults(c.templates.message(e,t)),c.templates.message(e,t)},setting:function(e,t){if(E.isPlainObject(e))E.extend(!0,c,e);else{if(t===D)return c[e];c[e]=t}},internal:function(e,t){if(E.isPlainObject(e))E.extend(!0,f,e);else{if(t===D)return f[e];f[e]=t}},debug:function(){!c.silent&&c.debug&&(c.performance?f.performance.log(arguments):(f.debug=Function.prototype.bind.call(console.info,console,c.name+":"),f.debug.apply(console,arguments)))},verbose:function(){!c.silent&&c.verbose&&c.debug&&(c.performance?f.performance.log(arguments):(f.verbose=Function.prototype.bind.call(console.info,console,c.name+":"),f.verbose.apply(console,arguments)))},error:function(){c.silent||(f.error=Function.prototype.bind.call(console.error,console,c.name+":"),f.error.apply(console,arguments))},performance:{log:function(e){var t,n;c.performance&&(n=(t=(new Date).getTime())-(k||t),k=t,T.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:b,"Execution Time":n})),clearTimeout(f.performance.timer),f.performance.timer=setTimeout(f.performance.display,500)},display:function(){var e=c.name+":",n=0;k=!1,clearTimeout(f.performance.timer),E.each(T,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",S&&(e+=" '"+S+"'"),1<w.length&&(e+=" ("+w.length+")"),(console.group!==D||console.table!==D)&&0<T.length&&(console.groupCollapsed(e),console.table?console.table(T):E.each(T,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),T=[]}},invoke:function(i,e,t){var o,a,n,r=s;return e=e||P,t=b||t,"string"==typeof i&&r!==D&&(i=i.split(/[\. ]/),o=i.length-1,E.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(E.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==D)return a=r[n],!1;if(!E.isPlainObject(r[t])||e==o)return r[t]!==D&&(a=r[t]),!1;r=r[t]}})),E.isFunction(a)?n=a.apply(t,e):a!==D&&(n=a),E.isArray(C)?C.push(n):C!==D?C=[C,n]:n!==D&&(C=n),a}},R?(s===D&&f.initialize(),f.invoke(A)):(s!==D&&s.invoke("destroy"),f.initialize())}),C!==D?C:this},E.fn.search.settings={name:"Search",namespace:"search",silent:!1,debug:!1,verbose:!1,performance:!0,type:"standard",minCharacters:1,selectFirstResult:!1,apiSettings:!1,source:!1,searchOnFocus:!0,searchFields:["title","description"],displayField:"",fullTextSearch:"exact",automatic:!0,hideDelay:0,searchDelay:200,maxResults:7,cache:!0,showNoResults:!0,transition:"scale",duration:200,easing:"easeOutExpo",onSelect:!1,onResultsAdd:!1,onSearchQuery:function(e){},onResults:function(e){},onResultsOpen:function(){},onResultsClose:function(){},className:{animating:"animating",active:"active",empty:"empty",focus:"focus",hidden:"hidden",loading:"loading",results:"results",pressed:"down"},error:{source:"Cannot search. No source used, and Semantic API module was not included",noResults:"Your search returned no results",logging:"Error in debug logging, exiting.",noEndpoint:"No search endpoint was specified",noTemplate:"A valid template name was not specified.",oldSearchSyntax:"searchFullText setting has been renamed fullTextSearch for consistency, please adjust your settings.",serverError:"There was an issue querying the server.",maxResults:"Results must be an array to use maxResults setting",method:"The method you called is not defined."},metadata:{cache:"cache",results:"results",result:"result"},regExp:{escape:/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,beginsWith:"(?:s|^)"},fields:{categories:"results",categoryName:"name",categoryResults:"results",description:"description",image:"image",price:"price",results:"results",title:"title",url:"url",action:"action",actionText:"text",actionURL:"url"},selector:{prompt:".prompt",searchButton:".search.button",results:".results",message:".results > .message",category:".category",result:".result",title:".title, .name"},templates:{escape:function(e){var t={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"};return/[&<>"'`]/.test(e)?e.replace(/[&<>"'`]/g,function(e){return t[e]}):e},message:function(e,t){var n="";return e!==D&&t!==D&&(n+='<div class="message '+t+'">',n+="empty"==t?'<div class="header">No Results</div class="header"><div class="description">'+e+'</div class="description">':' <div class="description">'+e+"</div>",n+="</div>"),n},category:function(e,n){var i="";E.fn.search.settings.templates.escape;return e[n.categoryResults]!==D&&(E.each(e[n.categoryResults],function(e,t){t[n.results]!==D&&0<t.results.length&&(i+='<div class="category">',t[n.categoryName]!==D&&(i+='<div class="name">'+t[n.categoryName]+"</div>"),i+='<div class="results">',E.each(t.results,function(e,t){t[n.url]?i+='<a class="result" href="'+t[n.url]+'">':i+='<a class="result">',t[n.image]!==D&&(i+='<div class="image"> <img src="'+t[n.image]+'"></div>'),i+='<div class="content">',t[n.price]!==D&&(i+='<div class="price">'+t[n.price]+"</div>"),t[n.title]!==D&&(i+='<div class="title">'+t[n.title]+"</div>"),t[n.description]!==D&&(i+='<div class="description">'+t[n.description]+"</div>"),i+="</div>",i+="</a>"}),i+="</div>",i+="</div>")}),e[n.action]&&(i+='<a href="'+e[n.action][n.actionURL]+'" class="action">'+e[n.action][n.actionText]+"</a>"),i)},standard:function(e,n){var i="";return e[n.results]!==D&&(E.each(e[n.results],function(e,t){t[n.url]?i+='<a class="result" href="'+t[n.url]+'">':i+='<a class="result">',t[n.image]!==D&&(i+='<div class="image"> <img src="'+t[n.image]+'"></div>'),i+='<div class="content">',t[n.price]!==D&&(i+='<div class="price">'+t[n.price]+"</div>"),t[n.title]!==D&&(i+='<div class="title">'+t[n.title]+"</div>"),t[n.description]!==D&&(i+='<div class="description">'+t[n.description]+"</div>"),i+="</div>",i+="</a>"}),e[n.action]&&(i+='<a href="'+e[n.action][n.actionURL]+'" class="action">'+e[n.action][n.actionText]+"</a>"),i)}}}}(jQuery,window,document),function(A,e,R,P){"use strict";e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),A.fn.shape=function(v){var b,y=A(this),x=(A("body"),(new Date).getTime()),C=[],w=v,S="string"==typeof w,k=[].slice.call(arguments,1),T=e.requestAnimationFrame||e.mozRequestAnimationFrame||e.webkitRequestAnimationFrame||e.msRequestAnimationFrame||function(e){setTimeout(e,0)};return y.each(function(){var i,o,a,t=y.selector||"",r=A.isPlainObject(v)?A.extend(!0,{},A.fn.shape.settings,v):A.extend({},A.fn.shape.settings),e=r.namespace,s=r.selector,n=r.error,l=r.className,c="."+e,u="module-"+e,d=A(this),f=d.find(s.sides),m=d.find(s.side),g=!1,p=this,h=d.data(u);a={initialize:function(){a.verbose("Initializing module for",p),a.set.defaultSide(),a.instantiate()},instantiate:function(){a.verbose("Storing instance of module",a),h=a,d.data(u,h)},destroy:function(){a.verbose("Destroying previous module for",p),d.removeData(u).off(c)},refresh:function(){a.verbose("Refreshing selector cache for",p),d=A(p),f=A(this).find(s.shape),m=A(this).find(s.side)},repaint:function(){a.verbose("Forcing repaint event");(f[0]||R.createElement("div")).offsetWidth},animate:function(e,t){a.verbose("Animating box with properties",e),t=t||function(e){a.verbose("Executing animation callback"),e!==P&&e.stopPropagation(),a.reset(),a.set.active()},r.beforeChange.call(o[0]),a.get.transitionEvent()?(a.verbose("Starting CSS animation"),d.addClass(l.animating),f.css(e).one(a.get.transitionEvent(),t),a.set.duration(r.duration),T(function(){d.addClass(l.animating),i.addClass(l.hidden)})):t()},queue:function(e){a.debug("Queueing animation of",e),f.one(a.get.transitionEvent(),function(){a.debug("Executing queued animation"),setTimeout(function(){d.shape(e)},0)})},reset:function(){a.verbose("Animating states reset"),d.removeClass(l.animating).attr("style","").removeAttr("style"),f.attr("style","").removeAttr("style"),m.attr("style","").removeAttr("style").removeClass(l.hidden),o.removeClass(l.animating).attr("style","").removeAttr("style")},is:{complete:function(){return m.filter("."+l.active)[0]==o[0]},animating:function(){return d.hasClass(l.animating)}},set:{defaultSide:function(){i=d.find("."+r.className.active),o=0<i.next(s.side).length?i.next(s.side):d.find(s.side).first(),g=!1,a.verbose("Active side set to",i),a.verbose("Next side set to",o)},duration:function(e){e="number"==typeof(e=e||r.duration)?e+"ms":e,a.verbose("Setting animation duration",e),(r.duration||0===r.duration)&&f.add(m).css({"-webkit-transition-duration":e,"-moz-transition-duration":e,"-ms-transition-duration":e,"-o-transition-duration":e,"transition-duration":e})},currentStageSize:function(){var e=d.find("."+r.className.active),t=e.outerWidth(!0),n=e.outerHeight(!0);d.css({width:t,height:n})},stageSize:function(){var e=d.clone().addClass(l.loading),t=e.find("."+r.className.active),n=g?e.find(s.side).eq(g):0<t.next(s.side).length?t.next(s.side):e.find(s.side).first(),i="next"==r.width?n.outerWidth(!0):"initial"==r.width?d.width():r.width,o="next"==r.height?n.outerHeight(!0):"initial"==r.height?d.height():r.height;t.removeClass(l.active),n.addClass(l.active),e.insertAfter(d),e.remove(),"auto"!=r.width&&(d.css("width",i+r.jitter),a.verbose("Specifying width during animation",i)),"auto"!=r.height&&(d.css("height",o+r.jitter),a.verbose("Specifying height during animation",o))},nextSide:function(e){g=e,o=m.filter(e),g=m.index(o),0===o.length&&(a.set.defaultSide(),a.error(n.side)),a.verbose("Next side manually set to",o)},active:function(){a.verbose("Setting new side to active",o),m.removeClass(l.active),o.addClass(l.active),r.onChange.call(o[0]),a.set.defaultSide()}},flip:{up:function(){if(!a.is.complete()||a.is.animating()||r.allowRepeats)if(a.is.animating())a.queue("flip up");else{a.debug("Flipping up",o);var e=a.get.transform.up();a.set.stageSize(),a.stage.above(),a.animate(e)}else a.debug("Side already visible",o)},down:function(){if(!a.is.complete()||a.is.animating()||r.allowRepeats)if(a.is.animating())a.queue("flip down");else{a.debug("Flipping down",o);var e=a.get.transform.down();a.set.stageSize(),a.stage.below(),a.animate(e)}else a.debug("Side already visible",o)},left:function(){if(!a.is.complete()||a.is.animating()||r.allowRepeats)if(a.is.animating())a.queue("flip left");else{a.debug("Flipping left",o);var e=a.get.transform.left();a.set.stageSize(),a.stage.left(),a.animate(e)}else a.debug("Side already visible",o)},right:function(){if(!a.is.complete()||a.is.animating()||r.allowRepeats)if(a.is.animating())a.queue("flip right");else{a.debug("Flipping right",o);var e=a.get.transform.right();a.set.stageSize(),a.stage.right(),a.animate(e)}else a.debug("Side already visible",o)},over:function(){!a.is.complete()||a.is.animating()||r.allowRepeats?a.is.animating()?a.queue("flip over"):(a.debug("Flipping over",o),a.set.stageSize(),a.stage.behind(),a.animate(a.get.transform.over())):a.debug("Side already visible",o)},back:function(){!a.is.complete()||a.is.animating()||r.allowRepeats?a.is.animating()?a.queue("flip back"):(a.debug("Flipping back",o),a.set.stageSize(),a.stage.behind(),a.animate(a.get.transform.back())):a.debug("Side already visible",o)}},get:{transform:{up:function(){return{transform:"translateY("+-(i.outerHeight(!0)-o.outerHeight(!0))/2+"px) translateZ("+-i.outerHeight(!0)/2+"px) rotateX(-90deg)"}},down:function(){return{transform:"translateY("+-(i.outerHeight(!0)-o.outerHeight(!0))/2+"px) translateZ("+-i.outerHeight(!0)/2+"px) rotateX(90deg)"}},left:function(){return{transform:"translateX("+-(i.outerWidth(!0)-o.outerWidth(!0))/2+"px) translateZ("+-i.outerWidth(!0)/2+"px) rotateY(90deg)"}},right:function(){return{transform:"translateX("+-(i.outerWidth(!0)-o.outerWidth(!0))/2+"px) translateZ("+-i.outerWidth(!0)/2+"px) rotateY(-90deg)"}},over:function(){return{transform:"translateX("+-(i.outerWidth(!0)-o.outerWidth(!0))/2+"px) rotateY(180deg)"}},back:function(){return{transform:"translateX("+-(i.outerWidth(!0)-o.outerWidth(!0))/2+"px) rotateY(-180deg)"}}},transitionEvent:function(){var e,t=R.createElement("element"),n={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(e in n)if(t.style[e]!==P)return n[e]},nextSide:function(){return 0<i.next(s.side).length?i.next(s.side):d.find(s.side).first()}},stage:{above:function(){var e={origin:(i.outerHeight(!0)-o.outerHeight(!0))/2,depth:{active:o.outerHeight(!0)/2,next:i.outerHeight(!0)/2}};a.verbose("Setting the initial animation position as above",o,e),f.css({transform:"translateZ(-"+e.depth.active+"px)"}),i.css({transform:"rotateY(0deg) translateZ("+e.depth.active+"px)"}),o.addClass(l.animating).css({top:e.origin+"px",transform:"rotateX(90deg) translateZ("+e.depth.next+"px)"})},below:function(){var e={origin:(i.outerHeight(!0)-o.outerHeight(!0))/2,depth:{active:o.outerHeight(!0)/2,next:i.outerHeight(!0)/2}};a.verbose("Setting the initial animation position as below",o,e),f.css({transform:"translateZ(-"+e.depth.active+"px)"}),i.css({transform:"rotateY(0deg) translateZ("+e.depth.active+"px)"}),o.addClass(l.animating).css({top:e.origin+"px",transform:"rotateX(-90deg) translateZ("+e.depth.next+"px)"})},left:function(){var e=i.outerWidth(!0),t=o.outerWidth(!0),n={origin:(e-t)/2,depth:{active:t/2,next:e/2}};a.verbose("Setting the initial animation position as left",o,n),f.css({transform:"translateZ(-"+n.depth.active+"px)"}),i.css({transform:"rotateY(0deg) translateZ("+n.depth.active+"px)"}),o.addClass(l.animating).css({left:n.origin+"px",transform:"rotateY(-90deg) translateZ("+n.depth.next+"px)"})},right:function(){var e=i.outerWidth(!0),t=o.outerWidth(!0),n={origin:(e-t)/2,depth:{active:t/2,next:e/2}};a.verbose("Setting the initial animation position as left",o,n),f.css({transform:"translateZ(-"+n.depth.active+"px)"}),i.css({transform:"rotateY(0deg) translateZ("+n.depth.active+"px)"}),o.addClass(l.animating).css({left:n.origin+"px",transform:"rotateY(90deg) translateZ("+n.depth.next+"px)"})},behind:function(){var e=i.outerWidth(!0),t=o.outerWidth(!0),n={origin:(e-t)/2,depth:{active:t/2,next:e/2}};a.verbose("Setting the initial animation position as behind",o,n),i.css({transform:"rotateY(0deg)"}),o.addClass(l.animating).css({left:n.origin+"px",transform:"rotateY(-180deg)"})}},setting:function(e,t){if(a.debug("Changing setting",e,t),A.isPlainObject(e))A.extend(!0,r,e);else{if(t===P)return r[e];A.isPlainObject(r[e])?A.extend(!0,r[e],t):r[e]=t}},internal:function(e,t){if(A.isPlainObject(e))A.extend(!0,a,e);else{if(t===P)return a[e];a[e]=t}},debug:function(){!r.silent&&r.debug&&(r.performance?a.performance.log(arguments):(a.debug=Function.prototype.bind.call(console.info,console,r.name+":"),a.debug.apply(console,arguments)))},verbose:function(){!r.silent&&r.verbose&&r.debug&&(r.performance?a.performance.log(arguments):(a.verbose=Function.prototype.bind.call(console.info,console,r.name+":"),a.verbose.apply(console,arguments)))},error:function(){r.silent||(a.error=Function.prototype.bind.call(console.error,console,r.name+":"),a.error.apply(console,arguments))},performance:{log:function(e){var t,n;r.performance&&(n=(t=(new Date).getTime())-(x||t),x=t,C.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:p,"Execution Time":n})),clearTimeout(a.performance.timer),a.performance.timer=setTimeout(a.performance.display,500)},display:function(){var e=r.name+":",n=0;x=!1,clearTimeout(a.performance.timer),A.each(C,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",t&&(e+=" '"+t+"'"),1<y.length&&(e+=" ("+y.length+")"),(console.group!==P||console.table!==P)&&0<C.length&&(console.groupCollapsed(e),console.table?console.table(C):A.each(C,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),C=[]}},invoke:function(i,e,t){var o,a,n,r=h;return e=e||k,t=p||t,"string"==typeof i&&r!==P&&(i=i.split(/[\. ]/),o=i.length-1,A.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(A.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==P)return a=r[n],!1;if(!A.isPlainObject(r[t])||e==o)return r[t]!==P&&(a=r[t]),!1;r=r[t]}})),A.isFunction(a)?n=a.apply(t,e):a!==P&&(n=a),A.isArray(b)?b.push(n):b!==P?b=[b,n]:n!==P&&(b=n),a}},S?(h===P&&a.initialize(),a.invoke(w)):(h!==P&&h.invoke("destroy"),a.initialize())}),b!==P?b:this},A.fn.shape.settings={name:"Shape",silent:!1,debug:!1,verbose:!1,jitter:0,performance:!0,namespace:"shape",width:"initial",height:"initial",beforeChange:function(){},onChange:function(){},allowRepeats:!1,duration:!1,error:{side:"You tried to switch to a side that does not exist.",method:"The method you called is not defined"},className:{animating:"animating",hidden:"hidden",loading:"loading",active:"active"},selector:{sides:".sides",side:".side"}}}(jQuery,window,document),function(q,j,z,I){"use strict";j=void 0!==j&&j.Math==Math?j:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),q.fn.sidebar=function(x){var C,e=q(this),w=q(j),S=q(z),k=q("html"),T=q("head"),A=e.selector||"",R=(new Date).getTime(),P=[],E=x,F="string"==typeof E,O=[].slice.call(arguments,1),D=j.requestAnimationFrame||j.mozRequestAnimationFrame||j.webkitRequestAnimationFrame||j.msRequestAnimationFrame||function(e){setTimeout(e,0)};return e.each(function(){var r,s,e,t,l,c,u=q.isPlainObject(x)?q.extend(!0,{},q.fn.sidebar.settings,x):q.extend({},q.fn.sidebar.settings),n=u.selector,a=u.className,i=u.namespace,o=u.regExp,d=u.error,f="."+i,m="module-"+i,g=q(this),p=q(u.context),h=g.children(n.sidebar),v=(p.children(n.fixed),p.children(n.pusher)),b=this,y=g.data(m);c={initialize:function(){c.debug("Initializing sidebar",x),c.create.id(),l=c.get.transitionEvent(),u.delaySetup?D(c.setup.layout):c.setup.layout(),D(function(){c.setup.cache()}),c.instantiate()},instantiate:function(){c.verbose("Storing instance of module",c),y=c,g.data(m,c)},create:{id:function(){e=(Math.random().toString(16)+"000000000").substr(2,8),s="."+e,c.verbose("Creating unique id for element",e)}},destroy:function(){c.verbose("Destroying previous module for",g),g.off(f).removeData(m),c.is.ios()&&c.remove.ios(),p.off(s),w.off(s),S.off(s)},event:{clickaway:function(e){var t=0<v.find(e.target).length||v.is(e.target),n=p.is(e.target);t&&(c.verbose("User clicked on dimmed page"),c.hide()),n&&(c.verbose("User clicked on dimmable context (scaled out page)"),c.hide())},touch:function(e){},containScroll:function(e){b.scrollTop<=0&&(b.scrollTop=1),b.scrollTop+b.offsetHeight>=b.scrollHeight&&(b.scrollTop=b.scrollHeight-b.offsetHeight-1)},scroll:function(e){0===q(e.target).closest(n.sidebar).length&&e.preventDefault()}},bind:{clickaway:function(){c.verbose("Adding clickaway events to context",p),u.closable&&p.on("click"+s,c.event.clickaway).on("touchend"+s,c.event.clickaway)},scrollLock:function(){u.scrollLock&&(c.debug("Disabling page scroll"),w.on("DOMMouseScroll"+s,c.event.scroll)),c.verbose("Adding events to contain sidebar scroll"),S.on("touchmove"+s,c.event.touch),g.on("scroll"+f,c.event.containScroll)}},unbind:{clickaway:function(){c.verbose("Removing clickaway events from context",p),p.off(s)},scrollLock:function(){c.verbose("Removing scroll lock from page"),S.off(s),w.off(s),g.off("scroll"+f)}},add:{inlineCSS:function(){var e,t=c.cache.width||g.outerWidth(),n=c.cache.height||g.outerHeight(),i=c.is.rtl(),o=c.get.direction(),a={left:t,right:-t,top:n,bottom:-n};i&&(c.verbose("RTL detected, flipping widths"),a.left=-t,a.right=t),e="<style>","left"===o||"right"===o?(c.debug("Adding CSS rules for animation distance",t),e+=" .ui.visible."+o+".sidebar ~ .fixed, .ui.visible."+o+".sidebar ~ .pusher {   -webkit-transform: translate3d("+a[o]+"px, 0, 0);           transform: translate3d("+a[o]+"px, 0, 0); }"):"top"!==o&&"bottom"!=o||(e+=" .ui.visible."+o+".sidebar ~ .fixed, .ui.visible."+o+".sidebar ~ .pusher {   -webkit-transform: translate3d(0, "+a[o]+"px, 0);           transform: translate3d(0, "+a[o]+"px, 0); }"),c.is.ie()&&("left"===o||"right"===o?(c.debug("Adding CSS rules for animation distance",t),e+=" body.pushable > .ui.visible."+o+".sidebar ~ .pusher:after {   -webkit-transform: translate3d("+a[o]+"px, 0, 0);           transform: translate3d("+a[o]+"px, 0, 0); }"):"top"!==o&&"bottom"!=o||(e+=" body.pushable > .ui.visible."+o+".sidebar ~ .pusher:after {   -webkit-transform: translate3d(0, "+a[o]+"px, 0);           transform: translate3d(0, "+a[o]+"px, 0); }"),e+=" body.pushable > .ui.visible.left.sidebar ~ .ui.visible.right.sidebar ~ .pusher:after, body.pushable > .ui.visible.right.sidebar ~ .ui.visible.left.sidebar ~ .pusher:after {   -webkit-transform: translate3d(0px, 0, 0);           transform: translate3d(0px, 0, 0); }"),r=q(e+="</style>").appendTo(T),c.debug("Adding sizing css to head",r)}},refresh:function(){c.verbose("Refreshing selector cache"),p=q(u.context),h=p.children(n.sidebar),v=p.children(n.pusher),p.children(n.fixed),c.clear.cache()},refreshSidebars:function(){c.verbose("Refreshing other sidebars"),h=p.children(n.sidebar)},repaint:function(){c.verbose("Forcing repaint event"),b.style.display="none";b.offsetHeight;b.scrollTop=b.scrollTop,b.style.display=""},setup:{cache:function(){c.cache={width:g.outerWidth(),height:g.outerHeight(),rtl:"rtl"==g.css("direction")}},layout:function(){0===p.children(n.pusher).length&&(c.debug("Adding wrapper element for sidebar"),c.error(d.pusher),v=q('<div class="pusher" />'),p.children().not(n.omitted).not(h).wrapAll(v),c.refresh()),0!==g.nextAll(n.pusher).length&&g.nextAll(n.pusher)[0]===v[0]||(c.debug("Moved sidebar to correct parent element"),c.error(d.movedSidebar,b),g.detach().prependTo(p),c.refresh()),c.clear.cache(),c.set.pushable(),c.set.direction()}},attachEvents:function(e,t){var n=q(e);t=q.isFunction(c[t])?c[t]:c.toggle,0<n.length?(c.debug("Attaching sidebar events to element",e,t),n.on("click"+f,t)):c.error(d.notFound,e)},show:function(e){if(e=q.isFunction(e)?e:function(){},c.is.hidden()){if(c.refreshSidebars(),u.overlay&&(c.error(d.overlay),u.transition="overlay"),c.refresh(),c.othersActive())if(c.debug("Other sidebars currently visible"),u.exclusive){if("overlay"!=u.transition)return void c.hideOthers(c.show);c.hideOthers()}else u.transition="overlay";c.pushPage(function(){e.call(b),u.onShow.call(b)}),u.onChange.call(b),u.onVisible.call(b)}else c.debug("Sidebar is already visible")},hide:function(e){e=q.isFunction(e)?e:function(){},(c.is.visible()||c.is.animating())&&(c.debug("Hiding sidebar",e),c.refreshSidebars(),c.pullPage(function(){e.call(b),u.onHidden.call(b)}),u.onChange.call(b),u.onHide.call(b))},othersAnimating:function(){return 0<h.not(g).filter("."+a.animating).length},othersVisible:function(){return 0<h.not(g).filter("."+a.visible).length},othersActive:function(){return c.othersVisible()||c.othersAnimating()},hideOthers:function(e){var t=h.not(g).filter("."+a.visible),n=t.length,i=0;e=e||function(){},t.sidebar("hide",function(){++i==n&&e()})},toggle:function(){c.verbose("Determining toggled direction"),c.is.hidden()?c.show():c.hide()},pushPage:function(t){var e,n,i,o=c.get.transition(),a="overlay"===o||c.othersActive()?g:v;t=q.isFunction(t)?t:function(){},"scale down"==u.transition&&c.scrollToTop(),c.set.transition(o),c.repaint(),e=function(){c.bind.clickaway(),c.add.inlineCSS(),c.set.animating(),c.set.visible()},n=function(){c.set.dimmed()},i=function(e){e.target==a[0]&&(a.off(l+s,i),c.remove.animating(),c.bind.scrollLock(),t.call(b))},a.off(l+s),a.on(l+s,i),D(e),u.dimPage&&!c.othersVisible()&&D(n)},pullPage:function(t){var e,n,i=c.get.transition(),o="overlay"==i||c.othersActive()?g:v;t=q.isFunction(t)?t:function(){},c.verbose("Removing context push state",c.get.direction()),c.unbind.clickaway(),c.unbind.scrollLock(),e=function(){c.set.transition(i),c.set.animating(),c.remove.visible(),u.dimPage&&!c.othersVisible()&&v.removeClass(a.dimmed)},n=function(e){e.target==o[0]&&(o.off(l+s,n),c.remove.animating(),c.remove.transition(),c.remove.inlineCSS(),("scale down"==i||u.returnScroll&&c.is.mobile())&&c.scrollBack(),t.call(b))},o.off(l+s),o.on(l+s,n),D(e)},scrollToTop:function(){c.verbose("Scrolling to top of page to avoid animation issues"),t=q(j).scrollTop(),g.scrollTop(0),j.scrollTo(0,0)},scrollBack:function(){c.verbose("Scrolling back to original page position"),j.scrollTo(0,t)},clear:{cache:function(){c.verbose("Clearing cached dimensions"),c.cache={}}},set:{ios:function(){k.addClass(a.ios)},pushed:function(){p.addClass(a.pushed)},pushable:function(){p.addClass(a.pushable)},dimmed:function(){v.addClass(a.dimmed)},active:function(){g.addClass(a.active)},animating:function(){g.addClass(a.animating)},transition:function(e){e=e||c.get.transition(),g.addClass(e)},direction:function(e){e=e||c.get.direction(),g.addClass(a[e])},visible:function(){g.addClass(a.visible)},overlay:function(){g.addClass(a.overlay)}},remove:{inlineCSS:function(){c.debug("Removing inline css styles",r),r&&0<r.length&&r.remove()},ios:function(){k.removeClass(a.ios)},pushed:function(){p.removeClass(a.pushed)},pushable:function(){p.removeClass(a.pushable)},active:function(){g.removeClass(a.active)},animating:function(){g.removeClass(a.animating)},transition:function(e){e=e||c.get.transition(),g.removeClass(e)},direction:function(e){e=e||c.get.direction(),g.removeClass(a[e])},visible:function(){g.removeClass(a.visible)},overlay:function(){g.removeClass(a.overlay)}},get:{direction:function(){return g.hasClass(a.top)?a.top:g.hasClass(a.right)?a.right:g.hasClass(a.bottom)?a.bottom:a.left},transition:function(){var e,t=c.get.direction();return e=c.is.mobile()?"auto"==u.mobileTransition?u.defaultTransition.mobile[t]:u.mobileTransition:"auto"==u.transition?u.defaultTransition.computer[t]:u.transition,c.verbose("Determined transition",e),e},transitionEvent:function(){var e,t=z.createElement("element"),n={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(e in n)if(t.style[e]!==I)return n[e]}},is:{ie:function(){return!j.ActiveXObject&&"ActiveXObject"in j||"ActiveXObject"in j},ios:function(){var e=navigator.userAgent,t=e.match(o.ios),n=e.match(o.mobileChrome);return!(!t||n)&&(c.verbose("Browser was found to be iOS",e),!0)},mobile:function(){var e=navigator.userAgent;return e.match(o.mobile)?(c.verbose("Browser was found to be mobile",e),!0):(c.verbose("Browser is not mobile, using regular transition",e),!1)},hidden:function(){return!c.is.visible()},visible:function(){return g.hasClass(a.visible)},open:function(){return c.is.visible()},closed:function(){return c.is.hidden()},vertical:function(){return g.hasClass(a.top)},animating:function(){return p.hasClass(a.animating)},rtl:function(){return c.cache.rtl===I&&(c.cache.rtl="rtl"==g.css("direction")),c.cache.rtl}},setting:function(e,t){if(c.debug("Changing setting",e,t),q.isPlainObject(e))q.extend(!0,u,e);else{if(t===I)return u[e];q.isPlainObject(u[e])?q.extend(!0,u[e],t):u[e]=t}},internal:function(e,t){if(q.isPlainObject(e))q.extend(!0,c,e);else{if(t===I)return c[e];c[e]=t}},debug:function(){!u.silent&&u.debug&&(u.performance?c.performance.log(arguments):(c.debug=Function.prototype.bind.call(console.info,console,u.name+":"),c.debug.apply(console,arguments)))},verbose:function(){!u.silent&&u.verbose&&u.debug&&(u.performance?c.performance.log(arguments):(c.verbose=Function.prototype.bind.call(console.info,console,u.name+":"),c.verbose.apply(console,arguments)))},error:function(){u.silent||(c.error=Function.prototype.bind.call(console.error,console,u.name+":"),c.error.apply(console,arguments))},performance:{log:function(e){var t,n;u.performance&&(n=(t=(new Date).getTime())-(R||t),R=t,P.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:b,"Execution Time":n})),clearTimeout(c.performance.timer),c.performance.timer=setTimeout(c.performance.display,500)},display:function(){var e=u.name+":",n=0;R=!1,clearTimeout(c.performance.timer),q.each(P,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",A&&(e+=" '"+A+"'"),(console.group!==I||console.table!==I)&&0<P.length&&(console.groupCollapsed(e),console.table?console.table(P):q.each(P,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),P=[]}},invoke:function(i,e,t){var o,a,n,r=y;return e=e||O,t=b||t,"string"==typeof i&&r!==I&&(i=i.split(/[\. ]/),o=i.length-1,q.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(q.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==I)return a=r[n],!1;if(!q.isPlainObject(r[t])||e==o)return r[t]!==I?a=r[t]:c.error(d.method,i),!1;r=r[t]}})),q.isFunction(a)?n=a.apply(t,e):a!==I&&(n=a),q.isArray(C)?C.push(n):C!==I?C=[C,n]:n!==I&&(C=n),a}},F?(y===I&&c.initialize(),c.invoke(E)):(y!==I&&c.invoke("destroy"),c.initialize())}),C!==I?C:this},q.fn.sidebar.settings={name:"Sidebar",namespace:"sidebar",silent:!1,debug:!1,verbose:!1,performance:!0,transition:"auto",mobileTransition:"auto",defaultTransition:{computer:{left:"uncover",right:"uncover",top:"overlay",bottom:"overlay"},mobile:{left:"uncover",right:"uncover",top:"overlay",bottom:"overlay"}},context:"body",exclusive:!1,closable:!0,dimPage:!0,scrollLock:!1,returnScroll:!1,delaySetup:!1,duration:500,onChange:function(){},onShow:function(){},onHide:function(){},onHidden:function(){},onVisible:function(){},className:{active:"active",animating:"animating",dimmed:"dimmed",ios:"ios",pushable:"pushable",pushed:"pushed",right:"right",top:"top",left:"left",bottom:"bottom",visible:"visible"},selector:{fixed:".fixed",omitted:"script, link, style, .ui.suimodal, .ui.dimmer, .ui.nag, .ui.fixed",pusher:".pusher",sidebar:".ui.sidebar"},regExp:{ios:/(iPad|iPhone|iPod)/g,mobileChrome:/(CriOS)/g,mobile:/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune/g},error:{method:"The method you called is not defined.",pusher:"Had to add pusher element. For optimal performance make sure body content is inside a pusher element",movedSidebar:"Had to move sidebar. For optimal performance make sure sidebar and pusher are direct children of your body tag",overlay:"The overlay setting is no longer supported, use animation: overlay",notFound:"There were no elements that matched the specified selector"}}}(jQuery,window,document),function(T,A,R,P){"use strict";A=void 0!==A&&A.Math==Math?A:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),T.fn.sticky=function(v){var b,e=T(this),y=e.selector||"",x=(new Date).getTime(),C=[],w=v,S="string"==typeof w,k=[].slice.call(arguments,1);return e.each(function(){var i,o,e,t,d,f=T.isPlainObject(v)?T.extend(!0,{},T.fn.sticky.settings,v):T.extend({},T.fn.sticky.settings),n=f.className,a=f.namespace,r=f.error,s="."+a,l="module-"+a,c=T(this),u=T(A),m=T(f.scrollContext),g=(c.selector,c.data(l)),p=A.requestAnimationFrame||A.mozRequestAnimationFrame||A.webkitRequestAnimationFrame||A.msRequestAnimationFrame||function(e){setTimeout(e,0)},h=this;d={initialize:function(){d.determineContainer(),d.determineContext(),d.verbose("Initializing sticky",f,i),d.save.positions(),d.checkErrors(),d.bind.events(),f.observeChanges&&d.observeChanges(),d.instantiate()},instantiate:function(){d.verbose("Storing instance of module",d),g=d,c.data(l,d)},destroy:function(){d.verbose("Destroying previous instance"),d.reset(),e&&e.disconnect(),t&&t.disconnect(),u.off("load"+s,d.event.load).off("resize"+s,d.event.resize),m.off("scrollchange"+s,d.event.scrollchange),c.removeData(l)},observeChanges:function(){"MutationObserver"in A&&(e=new MutationObserver(d.event.documentChanged),t=new MutationObserver(d.event.changed),e.observe(R,{childList:!0,subtree:!0}),t.observe(h,{childList:!0,subtree:!0}),t.observe(o[0],{childList:!0,subtree:!0}),d.debug("Setting up mutation observer",t))},determineContainer:function(){i=f.container?T(f.container):c.offsetParent()},determineContext:function(){0!==(o=f.context?T(f.context):i).length||d.error(r.invalidContext,f.context,c)},checkErrors:function(){if(d.is.hidden()&&d.error(r.visible,c),d.cache.element.height>d.cache.context.height)return d.reset(),void d.error(r.elementSize,c)},bind:{events:function(){u.on("load"+s,d.event.load).on("resize"+s,d.event.resize),m.off("scroll"+s).on("scroll"+s,d.event.scroll).on("scrollchange"+s,d.event.scrollchange)}},event:{changed:function(e){clearTimeout(d.timer),d.timer=setTimeout(function(){d.verbose("DOM tree modified, updating sticky menu",e),d.refresh()},100)},documentChanged:function(e){[].forEach.call(e,function(e){e.removedNodes&&[].forEach.call(e.removedNodes,function(e){(e==h||0<T(e).find(h).length)&&(d.debug("Element removed from DOM, tearing down events"),d.destroy())})})},load:function(){d.verbose("Page contents finished loading"),p(d.refresh)},resize:function(){d.verbose("Window resized"),p(d.refresh)},scroll:function(){p(function(){m.triggerHandler("scrollchange"+s,m.scrollTop())})},scrollchange:function(e,t){d.stick(t),f.onScroll.call(h)}},refresh:function(e){d.reset(),f.context||d.determineContext(),e&&d.determineContainer(),d.save.positions(),d.stick(),f.onReposition.call(h)},supports:{sticky:function(){var e=T("<div/>");e[0];return e.addClass(n.supported),e.css("position").match("sticky")}},save:{lastScroll:function(e){d.lastScroll=e},elementScroll:function(e){d.elementScroll=e},positions:function(){var e={height:m.height()},t={margin:{top:parseInt(c.css("margin-top"),10),bottom:parseInt(c.css("margin-bottom"),10)},offset:c.offset(),width:c.outerWidth(),height:c.outerHeight()},n={offset:o.offset(),height:o.outerHeight()};i.outerHeight();d.is.standardScroll()||(d.debug("Non-standard scroll. Removing scroll offset from element offset"),e.top=m.scrollTop(),e.left=m.scrollLeft(),t.offset.top+=e.top,n.offset.top+=e.top,t.offset.left+=e.left,n.offset.left+=e.left),d.cache={fits:t.height+f.offset<=e.height,sameHeight:t.height==n.height,scrollContext:{height:e.height},element:{margin:t.margin,top:t.offset.top-t.margin.top,left:t.offset.left,width:t.width,height:t.height,bottom:t.offset.top+t.height},context:{top:n.offset.top,height:n.height,bottom:n.offset.top+n.height}},d.set.containerSize(),d.stick(),d.debug("Caching element positions",d.cache)}},get:{direction:function(e){var t="down";return e=e||m.scrollTop(),d.lastScroll!==P&&(d.lastScroll<e?t="down":d.lastScroll>e&&(t="up")),t},scrollChange:function(e){return e=e||m.scrollTop(),d.lastScroll?e-d.lastScroll:0},currentElementScroll:function(){return d.elementScroll?d.elementScroll:d.is.top()?Math.abs(parseInt(c.css("top"),10))||0:Math.abs(parseInt(c.css("bottom"),10))||0},elementScroll:function(e){e=e||m.scrollTop();var t=d.cache.element,n=d.cache.scrollContext,i=d.get.scrollChange(e),o=t.height-n.height+f.offset,a=d.get.currentElementScroll(),r=a+i;return a=d.cache.fits||r<0?0:o<r?o:r}},remove:{lastScroll:function(){delete d.lastScroll},elementScroll:function(e){delete d.elementScroll},minimumSize:function(){i.css("min-height","")},offset:function(){c.css("margin-top","")}},set:{offset:function(){d.verbose("Setting offset on element",f.offset),c.css("margin-top",f.offset)},containerSize:function(){var e=i.get(0).tagName;"HTML"===e||"body"==e?d.determineContainer():Math.abs(i.outerHeight()-d.cache.context.height)>f.jitter&&(d.debug("Context has padding, specifying exact height for container",d.cache.context.height),i.css({height:d.cache.context.height}))},minimumSize:function(){var e=d.cache.element;i.css("min-height",e.height)},scroll:function(e){d.debug("Setting scroll on element",e),d.elementScroll!=e&&(d.is.top()&&c.css("bottom","").css("top",-e),d.is.bottom()&&c.css("top","").css("bottom",e))},size:function(){0!==d.cache.element.height&&0!==d.cache.element.width&&(h.style.setProperty("width",d.cache.element.width+"px","important"),h.style.setProperty("height",d.cache.element.height+"px","important"))}},is:{standardScroll:function(){return m[0]==A},top:function(){return c.hasClass(n.top)},bottom:function(){return c.hasClass(n.bottom)},initialPosition:function(){return!d.is.fixed()&&!d.is.bound()},hidden:function(){return!c.is(":visible")},bound:function(){return c.hasClass(n.bound)},fixed:function(){return c.hasClass(n.fixed)}},stick:function(e){var t=e||m.scrollTop(),n=d.cache,i=n.fits,o=n.sameHeight,a=n.element,r=n.scrollContext,s=n.context,l=d.is.bottom()&&f.pushing?f.bottomOffset:f.offset,c=(e={top:t+l,bottom:t+l+r.height},d.get.direction(e.top),i?0:d.get.elementScroll(e.top)),u=!i;0!==a.height&&!o&&(d.is.initialPosition()?e.top>=s.bottom?(d.debug("Initial element position is bottom of container"),d.bindBottom()):e.top>a.top&&(a.height+e.top-c>=s.bottom?(d.debug("Initial element position is bottom of container"),d.bindBottom()):(d.debug("Initial element position is fixed"),d.fixTop())):d.is.fixed()?d.is.top()?e.top<=a.top?(d.debug("Fixed element reached top of container"),d.setInitialPosition()):a.height+e.top-c>=s.bottom?(d.debug("Fixed element reached bottom of container"),d.bindBottom()):u&&(d.set.scroll(c),d.save.lastScroll(e.top),d.save.elementScroll(c)):d.is.bottom()&&(e.bottom-a.height<=a.top?(d.debug("Bottom fixed rail has reached top of container"),d.setInitialPosition()):e.bottom>=s.bottom?(d.debug("Bottom fixed rail has reached bottom of container"),d.bindBottom()):u&&(d.set.scroll(c),d.save.lastScroll(e.top),d.save.elementScroll(c))):d.is.bottom()&&(e.top<=a.top?(d.debug("Jumped from bottom fixed to top fixed, most likely used home/end button"),d.setInitialPosition()):f.pushing?d.is.bound()&&e.bottom<=s.bottom&&(d.debug("Fixing bottom attached element to bottom of browser."),d.fixBottom()):d.is.bound()&&e.top<=s.bottom-a.height&&(d.debug("Fixing bottom attached element to top of browser."),d.fixTop())))},bindTop:function(){d.debug("Binding element to top of parent container"),d.remove.offset(),c.css({left:"",top:"",marginBottom:""}).removeClass(n.fixed).removeClass(n.bottom).addClass(n.bound).addClass(n.top),f.onTop.call(h),f.onUnstick.call(h)},bindBottom:function(){d.debug("Binding element to bottom of parent container"),d.remove.offset(),c.css({left:"",top:""}).removeClass(n.fixed).removeClass(n.top).addClass(n.bound).addClass(n.bottom),f.onBottom.call(h),f.onUnstick.call(h)},setInitialPosition:function(){d.debug("Returning to initial position"),d.unfix(),d.unbind()},fixTop:function(){d.debug("Fixing element to top of page"),f.setSize&&d.set.size(),d.set.minimumSize(),d.set.offset(),c.css({left:d.cache.element.left,bottom:"",marginBottom:""}).removeClass(n.bound).removeClass(n.bottom).addClass(n.fixed).addClass(n.top),f.onStick.call(h)},fixBottom:function(){d.debug("Sticking element to bottom of page"),f.setSize&&d.set.size(),d.set.minimumSize(),d.set.offset(),c.css({left:d.cache.element.left,bottom:"",marginBottom:""}).removeClass(n.bound).removeClass(n.top).addClass(n.fixed).addClass(n.bottom),f.onStick.call(h)},unbind:function(){d.is.bound()&&(d.debug("Removing container bound position on element"),d.remove.offset(),c.removeClass(n.bound).removeClass(n.top).removeClass(n.bottom))},unfix:function(){d.is.fixed()&&(d.debug("Removing fixed position on element"),d.remove.minimumSize(),d.remove.offset(),c.removeClass(n.fixed).removeClass(n.top).removeClass(n.bottom),f.onUnstick.call(h))},reset:function(){d.debug("Resetting elements position"),d.unbind(),d.unfix(),d.resetCSS(),d.remove.offset(),d.remove.lastScroll()},resetCSS:function(){c.css({width:"",height:""}),i.css({height:""})},setting:function(e,t){if(T.isPlainObject(e))T.extend(!0,f,e);else{if(t===P)return f[e];f[e]=t}},internal:function(e,t){if(T.isPlainObject(e))T.extend(!0,d,e);else{if(t===P)return d[e];d[e]=t}},debug:function(){!f.silent&&f.debug&&(f.performance?d.performance.log(arguments):(d.debug=Function.prototype.bind.call(console.info,console,f.name+":"),d.debug.apply(console,arguments)))},verbose:function(){!f.silent&&f.verbose&&f.debug&&(f.performance?d.performance.log(arguments):(d.verbose=Function.prototype.bind.call(console.info,console,f.name+":"),d.verbose.apply(console,arguments)))},error:function(){f.silent||(d.error=Function.prototype.bind.call(console.error,console,f.name+":"),d.error.apply(console,arguments))},performance:{log:function(e){var t,n;f.performance&&(n=(t=(new Date).getTime())-(x||t),x=t,C.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:h,"Execution Time":n})),clearTimeout(d.performance.timer),d.performance.timer=setTimeout(d.performance.display,0)},display:function(){var e=f.name+":",n=0;x=!1,clearTimeout(d.performance.timer),T.each(C,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",y&&(e+=" '"+y+"'"),(console.group!==P||console.table!==P)&&0<C.length&&(console.groupCollapsed(e),console.table?console.table(C):T.each(C,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),C=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||k,t=h||t,"string"==typeof i&&r!==P&&(i=i.split(/[\. ]/),o=i.length-1,T.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(T.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==P)return a=r[n],!1;if(!T.isPlainObject(r[t])||e==o)return r[t]!==P&&(a=r[t]),!1;r=r[t]}})),T.isFunction(a)?n=a.apply(t,e):a!==P&&(n=a),T.isArray(b)?b.push(n):b!==P?b=[b,n]:n!==P&&(b=n),a}},S?(g===P&&d.initialize(),d.invoke(w)):(g!==P&&g.invoke("destroy"),d.initialize())}),b!==P?b:this},T.fn.sticky.settings={name:"Sticky",namespace:"sticky",silent:!1,debug:!1,verbose:!0,performance:!0,pushing:!1,context:!1,container:!1,scrollContext:A,offset:0,bottomOffset:0,jitter:5,setSize:!0,observeChanges:!1,onReposition:function(){},onScroll:function(){},onStick:function(){},onUnstick:function(){},onTop:function(){},onBottom:function(){},error:{container:"Sticky element must be inside a relative container",visible:"Element is hidden, you must call refresh after element becomes visible. Use silent setting to surpress this warning in production.",method:"The method you called is not defined.",invalidContext:"Context specified does not exist",elementSize:"Sticky element is larger than its container, cannot create sticky."},className:{bound:"bound",fixed:"fixed",supported:"native",top:"top",bottom:"bottom"}}}(jQuery,window,document),function(E,F,O,D){"use strict";F=void 0!==F&&F.Math==Math?F:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),E.fn.tab=function(r){var c,u=E.isFunction(this)?E(F):E(this),d=u.selector||"",f=(new Date).getTime(),m=[],g=r,A="string"==typeof g,R=[].slice.call(arguments,1),P=!1;return u.each(function(){var p,a,h,v,b,y,x=E.isPlainObject(r)?E.extend(!0,{},E.fn.tab.settings,r):E.extend({},E.fn.tab.settings),C=x.className,w=x.metadata,t=x.selector,S=x.error,e="."+x.namespace,n="module-"+x.namespace,k=E(this),i={},T=!0,o=0,s=this,l=k.data(n);b={initialize:function(){b.debug("Initializing tab menu item",k),b.fix.callbacks(),b.determineTabs(),b.debug("Determining tabs",x.context,a),x.auto&&b.set.auto(),b.bind.events(),x.history&&!P&&(b.initializeHistory(),P=!0),b.instantiate()},instantiate:function(){b.verbose("Storing instance of module",b),l=b,k.data(n,b)},destroy:function(){b.debug("Destroying tabs",k),k.removeData(n).off(e)},bind:{events:function(){E.isWindow(s)||(b.debug("Attaching tab activation events to element",k),k.on("click"+e,b.event.click))}},determineTabs:function(){var e;"parent"===x.context?(0<k.closest(t.ui).length?(e=k.closest(t.ui),b.verbose("Using closest UI element as parent",e)):e=k,p=e.parent(),b.verbose("Determined parent element for creating context",p)):x.context?(p=E(x.context),b.verbose("Using selector for tab context",x.context,p)):p=E("body"),x.childrenOnly?(a=p.children(t.tabs),b.debug("Searching tab context children for tabs",p,a)):(a=p.find(t.tabs),b.debug("Searching tab context for tabs",p,a))},fix:{callbacks:function(){E.isPlainObject(r)&&(r.onTabLoad||r.onTabInit)&&(r.onTabLoad&&(r.onLoad=r.onTabLoad,delete r.onTabLoad,b.error(S.legacyLoad,r.onLoad)),r.onTabInit&&(r.onFirstLoad=r.onTabInit,delete r.onTabInit,b.error(S.legacyInit,r.onFirstLoad)),x=E.extend(!0,{},E.fn.tab.settings,r))}},initializeHistory:function(){if(b.debug("Initializing page state"),E.address===D)return b.error(S.state),!1;if("state"==x.historyType){if(b.debug("Using HTML5 to manage state"),!1===x.path)return b.error(S.path),!1;E.address.history(!0).state(x.path)}E.address.bind("change",b.event.history.change)},event:{click:function(e){var t=E(this).data(w.tab);t!==D?(x.history?(b.verbose("Updating page state",e),E.address.value(t)):(b.verbose("Changing tab",e),b.changeTab(t)),e.preventDefault()):b.debug("No tab specified")},history:{change:function(e){var t=e.pathNames.join("/")||b.get.initialPath(),n=x.templates.determineTitle(t)||!1;b.performance.display(),b.debug("History change event",t,e),y=e,t!==D&&b.changeTab(t),n&&E.address.title(n)}}},refresh:function(){h&&(b.debug("Refreshing tab",h),b.changeTab(h))},cache:{read:function(e){return e!==D&&i[e]},add:function(e,t){e=e||h,b.debug("Adding cached content for",e),i[e]=t},remove:function(e){e=e||h,b.debug("Removing cached content for",e),delete i[e]}},set:{auto:function(){var e="string"==typeof x.path?x.path.replace(/\/$/,"")+"/{$tab}":"/{$tab}";b.verbose("Setting up automatic tab retrieval from server",e),E.isPlainObject(x.apiSettings)?x.apiSettings.url=e:x.apiSettings={url:e}},loading:function(e){var t=b.get.tabElement(e);t.hasClass(C.loading)||(b.verbose("Setting loading state for",t),t.addClass(C.loading).siblings(a).removeClass(C.active+" "+C.loading),0<t.length&&x.onRequest.call(t[0],e))},state:function(e){E.address.value(e)}},changeTab:function(d){var f=F.history&&F.history.pushState&&x.ignoreFirstLoad&&T,m=x.auto||E.isPlainObject(x.apiSettings),g=m&&!f?b.utilities.pathToArray(d):b.get.defaultPathArray(d);d=b.utilities.arrayToPath(g),E.each(g,function(e,t){var n,i,o,a,r=g.slice(0,e+1),s=b.utilities.arrayToPath(r),l=b.is.tab(s),c=e+1==g.length,u=b.get.tabElement(s);if(b.verbose("Looking for tab",t),l){if(b.verbose("Tab was found",t),h=s,v=b.utilities.filterArray(g,r),c?a=!0:(i=g.slice(0,e+2),o=b.utilities.arrayToPath(i),(a=!b.is.tab(o))&&b.verbose("Tab parameters found",i)),a&&m)return f?(b.debug("Ignoring remote content on first tab load",s),T=!1,b.cache.add(d,u.html()),b.activate.all(s),x.onFirstLoad.call(u[0],s,v,y),x.onLoad.call(u[0],s,v,y)):(b.activate.navigation(s),b.fetch.content(s,d)),!1;b.debug("Opened local tab",s),b.activate.all(s),b.cache.read(s)||(b.cache.add(s,!0),b.debug("First time tab loaded calling tab init"),x.onFirstLoad.call(u[0],s,v,y)),x.onLoad.call(u[0],s,v,y)}else{if(-1!=d.search("/")||""===d)return b.error(S.missingTab,k,p,s),!1;if(s=(n=E("#"+d+', a[name="'+d+'"]')).closest("[data-tab]").data(w.tab),u=b.get.tabElement(s),n&&0<n.length&&s)return b.debug("Anchor link used, opening parent tab",u,n),u.hasClass(C.active)||setTimeout(function(){b.scrollTo(n)},0),b.activate.all(s),b.cache.read(s)||(b.cache.add(s,!0),b.debug("First time tab loaded calling tab init"),x.onFirstLoad.call(u[0],s,v,y)),x.onLoad.call(u[0],s,v,y),!1}})},scrollTo:function(e){var t=!!(e&&0<e.length)&&e.offset().top;!1!==t&&(b.debug("Forcing scroll to an in-page link in a hidden tab",t,e),E(O).scrollTop(t))},update:{content:function(e,t,n){var i=b.get.tabElement(e),o=i[0];n=n!==D?n:x.evaluateScripts,"string"==typeof x.cacheType&&"dom"==x.cacheType.toLowerCase()&&"string"!=typeof t?i.empty().append(E(t).clone(!0)):n?(b.debug("Updating HTML and evaluating inline scripts",e,t),i.html(t)):(b.debug("Updating HTML",e,t),o.innerHTML=t)}},fetch:{content:function(t,n){var e,i,o=b.get.tabElement(t),a={dataType:"html",encodeParameters:!1,on:"now",cache:x.alwaysRefresh,headers:{"X-Remote":!0},onSuccess:function(e){"response"==x.cacheType&&b.cache.add(n,e),b.update.content(t,e),t==h?(b.debug("Content loaded",t),b.activate.tab(t)):b.debug("Content loaded in background",t),x.onFirstLoad.call(o[0],t,v,y),x.onLoad.call(o[0],t,v,y),x.loadOnce?b.cache.add(n,!0):"string"==typeof x.cacheType&&"dom"==x.cacheType.toLowerCase()&&0<o.children().length?setTimeout(function(){var e=o.children().clone(!0);e=e.not("script"),b.cache.add(n,e)},0):b.cache.add(n,o.html())},urlData:{tab:n}},r=o.api("get request")||!1,s=r&&"pending"===r.state();n=n||t,i=b.cache.read(n),x.cache&&i?(b.activate.tab(t),b.debug("Adding cached content",n),x.loadOnce||("once"==x.evaluateScripts?b.update.content(t,i,!1):b.update.content(t,i)),x.onLoad.call(o[0],t,v,y)):s?(b.set.loading(t),b.debug("Content is already loading",n)):E.api!==D?(e=E.extend(!0,{},x.apiSettings,a),b.debug("Retrieving remote content",n,e),b.set.loading(t),o.api(e)):b.error(S.api)}},activate:{all:function(e){b.activate.tab(e),b.activate.navigation(e)},tab:function(e){var t=b.get.tabElement(e),n="siblings"==x.deactivate?t.siblings(a):a.not(t),i=t.hasClass(C.active);b.verbose("Showing tab content for",t),i||(t.addClass(C.active),n.removeClass(C.active+" "+C.loading),0<t.length&&x.onVisible.call(t[0],e))},navigation:function(e){var t=b.get.navElement(e),n="siblings"==x.deactivate?t.siblings(u):u.not(t),i=t.hasClass(C.active);b.verbose("Activating tab navigation for",t,e),i||(t.addClass(C.active),n.removeClass(C.active+" "+C.loading))}},deactivate:{all:function(){b.deactivate.navigation(),b.deactivate.tabs()},navigation:function(){u.removeClass(C.active)},tabs:function(){a.removeClass(C.active+" "+C.loading)}},is:{tab:function(e){return e!==D&&0<b.get.tabElement(e).length}},get:{initialPath:function(){return u.eq(0).data(w.tab)||a.eq(0).data(w.tab)},path:function(){return E.address.value()},defaultPathArray:function(e){return b.utilities.pathToArray(b.get.defaultPath(e))},defaultPath:function(e){var t=u.filter("[data-"+w.tab+'^="'+e+'/"]').eq(0).data(w.tab)||!1;if(t){if(b.debug("Found default tab",t),o<x.maxDepth)return o++,b.get.defaultPath(t);b.error(S.recursion)}else b.debug("No default tabs found for",e,a);return o=0,e},navElement:function(e){return e=e||h,u.filter("[data-"+w.tab+'="'+e+'"]')},tabElement:function(e){var t,n,i,o;return e=e||h,i=b.utilities.pathToArray(e),o=b.utilities.last(i),t=a.filter("[data-"+w.tab+'="'+e+'"]'),n=a.filter("[data-"+w.tab+'="'+o+'"]'),0<t.length?t:n},tab:function(){return h}},utilities:{filterArray:function(e,t){return E.grep(e,function(e){return-1==E.inArray(e,t)})},last:function(e){return!!E.isArray(e)&&e[e.length-1]},pathToArray:function(e){return e===D&&(e=h),"string"==typeof e?e.split("/"):[e]},arrayToPath:function(e){return!!E.isArray(e)&&e.join("/")}},setting:function(e,t){if(b.debug("Changing setting",e,t),E.isPlainObject(e))E.extend(!0,x,e);else{if(t===D)return x[e];E.isPlainObject(x[e])?E.extend(!0,x[e],t):x[e]=t}},internal:function(e,t){if(E.isPlainObject(e))E.extend(!0,b,e);else{if(t===D)return b[e];b[e]=t}},debug:function(){!x.silent&&x.debug&&(x.performance?b.performance.log(arguments):(b.debug=Function.prototype.bind.call(console.info,console,x.name+":"),b.debug.apply(console,arguments)))},verbose:function(){!x.silent&&x.verbose&&x.debug&&(x.performance?b.performance.log(arguments):(b.verbose=Function.prototype.bind.call(console.info,console,x.name+":"),b.verbose.apply(console,arguments)))},error:function(){x.silent||(b.error=Function.prototype.bind.call(console.error,console,x.name+":"),b.error.apply(console,arguments))},performance:{log:function(e){var t,n;x.performance&&(n=(t=(new Date).getTime())-(f||t),f=t,m.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:s,"Execution Time":n})),clearTimeout(b.performance.timer),b.performance.timer=setTimeout(b.performance.display,500)},display:function(){var e=x.name+":",n=0;f=!1,clearTimeout(b.performance.timer),E.each(m,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",d&&(e+=" '"+d+"'"),(console.group!==D||console.table!==D)&&0<m.length&&(console.groupCollapsed(e),console.table?console.table(m):E.each(m,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),m=[]}},invoke:function(i,e,t){var o,a,n,r=l;return e=e||R,t=s||t,"string"==typeof i&&r!==D&&(i=i.split(/[\. ]/),o=i.length-1,E.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(E.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==D)return a=r[n],!1;if(!E.isPlainObject(r[t])||e==o)return r[t]!==D?a=r[t]:b.error(S.method,i),!1;r=r[t]}})),E.isFunction(a)?n=a.apply(t,e):a!==D&&(n=a),E.isArray(c)?c.push(n):c!==D?c=[c,n]:n!==D&&(c=n),a}},A?(l===D&&b.initialize(),b.invoke(g)):(l!==D&&l.invoke("destroy"),b.initialize())}),c!==D?c:this},E.tab=function(){E(F).tab.apply(this,arguments)},E.fn.tab.settings={name:"Tab",namespace:"tab",silent:!1,debug:!1,verbose:!1,performance:!0,auto:!1,history:!1,historyType:"hash",path:!1,context:!1,childrenOnly:!1,maxDepth:25,deactivate:"siblings",alwaysRefresh:!1,cache:!0,loadOnce:!1,cacheType:"response",ignoreFirstLoad:!1,apiSettings:!1,evaluateScripts:"once",onFirstLoad:function(e,t,n){},onLoad:function(e,t,n){},onVisible:function(e,t,n){},onRequest:function(e,t,n){},templates:{determineTitle:function(e){}},error:{api:"You attempted to load content without API module",method:"The method you called is not defined",missingTab:"Activated tab cannot be found. Tabs are case-sensitive.",noContent:"The tab you specified is missing a content url.",path:"History enabled, but no path was specified",recursion:"Max recursive depth reached",legacyInit:"onTabInit has been renamed to onFirstLoad in 2.0, please adjust your code.",legacyLoad:"onTabLoad has been renamed to onLoad in 2.0. Please adjust your code",state:"History requires Asual's Address library <https://github.com/asual/jquery-address>"},metadata:{tab:"tab",loaded:"loaded",promise:"promise"},className:{loading:"loading",active:"active"},selector:{tabs:".ui.tab",ui:".ui"}}}(jQuery,window,document),function(C,e,w,S){"use strict";e=void 0!==e&&e.Math==Math?e:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),C.fn.transition=function(){var c,r=C(this),g=r.selector||"",p=(new Date).getTime(),h=[],v=arguments,b=v[0],y=[].slice.call(arguments,1),x="string"==typeof b;e.requestAnimationFrame||e.mozRequestAnimationFrame||e.webkitRequestAnimationFrame||e.msRequestAnimationFrame;return r.each(function(i){var u,s,t,d,n,o,e,a,f,m=C(this),l=this;(f={initialize:function(){u=f.get.settings.apply(l,v),d=u.className,t=u.error,n=u.metadata,a="."+u.namespace,e="module-"+u.namespace,s=m.data(e)||f,o=f.get.animationEndEvent(),x&&(x=f.invoke(b)),!1===x&&(f.verbose("Converted arguments into settings object",u),u.interval?f.delay(u.animate):f.animate(),f.instantiate())},instantiate:function(){f.verbose("Storing instance of module",f),s=f,m.data(e,s)},destroy:function(){f.verbose("Destroying previous module for",l),m.removeData(e)},refresh:function(){f.verbose("Refreshing display type on next animation"),delete f.displayType},forceRepaint:function(){f.verbose("Forcing element repaint");var e=m.parent(),t=m.next();0===t.length?m.detach().appendTo(e):m.detach().insertBefore(t)},repaint:function(){f.verbose("Repainting element");l.offsetWidth},delay:function(e){var t,n=f.get.animationDirection();n||(n=f.can.transition()?f.get.direction():"static"),e=e!==S?e:u.interval,t="auto"==u.reverse&&n==d.outward||1==u.reverse?(r.length-i)*u.interval:i*u.interval,f.debug("Delaying animation by",t),setTimeout(f.animate,t)},animate:function(e){if(u=e||u,!f.is.supported())return f.error(t.support),!1;if(f.debug("Preparing animation",u.animation),f.is.animating()){if(u.queue)return!u.allowRepeats&&f.has.direction()&&f.is.occurring()&&!0!==f.queuing?f.debug("Animation is currently occurring, preventing queueing same animation",u.animation):f.queue(u.animation),!1;if(!u.allowRepeats&&f.is.occurring())return f.debug("Animation is already occurring, will not execute repeated animation",u.animation),!1;f.debug("New animation started, completing previous early",u.animation),s.complete()}f.can.animate()?f.set.animating(u.animation):f.error(t.noAnimation,u.animation,l)},reset:function(){f.debug("Resetting animation to beginning conditions"),f.remove.animationCallbacks(),f.restore.conditions(),f.remove.animating()},queue:function(e){f.debug("Queueing animation of",e),f.queuing=!0,m.one(o+".queue"+a,function(){f.queuing=!1,f.repaint(),f.animate.apply(this,u)})},complete:function(e){f.debug("Animation complete",u.animation),f.remove.completeCallback(),f.remove.failSafe(),f.is.looping()||(f.is.outward()?(f.verbose("Animation is outward, hiding element"),f.restore.conditions(),f.hide()):f.is.inward()?(f.verbose("Animation is outward, showing element"),f.restore.conditions(),f.show()):(f.verbose("Static animation completed"),f.restore.conditions(),u.onComplete.call(l)))},force:{visible:function(){var e=m.attr("style"),t=f.get.userStyle(),n=f.get.displayType(),i=t+"display: "+n+" !important;",o=m.css("display"),a=e===S||""===e;o!==n?(f.verbose("Overriding default display to show element",n),m.attr("style",i)):a&&m.removeAttr("style")},hidden:function(){var e=m.attr("style"),t=m.css("display"),n=e===S||""===e;"none"===t||f.is.hidden()?n&&m.removeAttr("style"):(f.verbose("Overriding default display to hide element"),m.css("display","none"))}},has:{direction:function(e){var n=!1;return"string"==typeof(e=e||u.animation)&&(e=e.split(" "),C.each(e,function(e,t){t!==d.inward&&t!==d.outward||(n=!0)})),n},inlineDisplay:function(){var e=m.attr("style")||"";return C.isArray(e.match(/display.*?;/,""))}},set:{animating:function(e){var t;f.remove.completeCallback(),e=e||u.animation,t=f.get.animationClass(e),f.save.animation(t),f.force.visible(),f.remove.hidden(),f.remove.direction(),f.start.animation(t)},duration:function(e,t){((t="number"==typeof(t=t||u.duration)?t+"ms":t)||0===t)&&(f.verbose("Setting animation duration",t),m.css({"animation-duration":t}))},direction:function(e){(e=e||f.get.direction())==d.inward?f.set.inward():f.set.outward()},looping:function(){f.debug("Transition set to loop"),m.addClass(d.looping)},hidden:function(){m.addClass(d.transition).addClass(d.hidden)},inward:function(){f.debug("Setting direction to inward"),m.removeClass(d.outward).addClass(d.inward)},outward:function(){f.debug("Setting direction to outward"),m.removeClass(d.inward).addClass(d.outward)},visible:function(){m.addClass(d.transition).addClass(d.visible)}},start:{animation:function(e){e=e||f.get.animationClass(),f.debug("Starting tween",e),m.addClass(e).one(o+".complete"+a,f.complete),u.useFailSafe&&f.add.failSafe(),f.set.duration(u.duration),u.onStart.call(l)}},save:{animation:function(e){f.cache||(f.cache={}),f.cache.animation=e},displayType:function(e){"none"!==e&&m.data(n.displayType,e)},transitionExists:function(e,t){C.fn.transition.exists[e]=t,f.verbose("Saving existence of transition",e,t)}},restore:{conditions:function(){var e=f.get.currentAnimation();e&&(m.removeClass(e),f.verbose("Removing animation class",f.cache)),f.remove.duration()}},add:{failSafe:function(){var e=f.get.duration();f.timer=setTimeout(function(){m.triggerHandler(o)},e+u.failSafeDelay),f.verbose("Adding fail safe timer",f.timer)}},remove:{animating:function(){m.removeClass(d.animating)},animationCallbacks:function(){f.remove.queueCallback(),f.remove.completeCallback()},queueCallback:function(){m.off(".queue"+a)},completeCallback:function(){m.off(".complete"+a)},display:function(){m.css("display","")},direction:function(){m.removeClass(d.inward).removeClass(d.outward)},duration:function(){m.css("animation-duration","")},failSafe:function(){f.verbose("Removing fail safe timer",f.timer),f.timer&&clearTimeout(f.timer)},hidden:function(){m.removeClass(d.hidden)},visible:function(){m.removeClass(d.visible)},looping:function(){f.debug("Transitions are no longer looping"),f.is.looping()&&(f.reset(),m.removeClass(d.looping))},transition:function(){m.removeClass(d.visible).removeClass(d.hidden)}},get:{settings:function(e,t,n){return"object"==typeof e?C.extend(!0,{},C.fn.transition.settings,e):"function"==typeof n?C.extend({},C.fn.transition.settings,{animation:e,onComplete:n,duration:t}):"string"==typeof t||"number"==typeof t?C.extend({},C.fn.transition.settings,{animation:e,duration:t}):"object"==typeof t?C.extend({},C.fn.transition.settings,t,{animation:e}):"function"==typeof t?C.extend({},C.fn.transition.settings,{animation:e,onComplete:t}):C.extend({},C.fn.transition.settings,{animation:e})},animationClass:function(e){var t=e||u.animation,n=f.can.transition()&&!f.has.direction()?f.get.direction()+" ":"";return d.animating+" "+d.transition+" "+n+t},currentAnimation:function(){return!(!f.cache||f.cache.animation===S)&&f.cache.animation},currentDirection:function(){return f.is.inward()?d.inward:d.outward},direction:function(){return f.is.hidden()||!f.is.visible()?d.inward:d.outward},animationDirection:function(e){var n;return"string"==typeof(e=e||u.animation)&&(e=e.split(" "),C.each(e,function(e,t){t===d.inward?n=d.inward:t===d.outward&&(n=d.outward)})),n||!1},duration:function(e){return!1===(e=e||u.duration)&&(e=m.css("animation-duration")||0),"string"==typeof e?-1<e.indexOf("ms")?parseFloat(e):1e3*parseFloat(e):e},displayType:function(e){return e=e===S||e,u.displayType?u.displayType:(e&&m.data(n.displayType)===S&&f.can.transition(!0),m.data(n.displayType))},userStyle:function(e){return(e=e||m.attr("style")||"").replace(/display.*?;/,"")},transitionExists:function(e){return C.fn.transition.exists[e]},animationStartEvent:function(){var e,t=w.createElement("div"),n={animation:"animationstart",OAnimation:"oAnimationStart",MozAnimation:"mozAnimationStart",WebkitAnimation:"webkitAnimationStart"};for(e in n)if(t.style[e]!==S)return n[e];return!1},animationEndEvent:function(){var e,t=w.createElement("div"),n={animation:"animationend",OAnimation:"oAnimationEnd",MozAnimation:"mozAnimationEnd",WebkitAnimation:"webkitAnimationEnd"};for(e in n)if(t.style[e]!==S)return n[e];return!1}},can:{transition:function(e){var t,n,i,o,a,r,s=u.animation,l=f.get.transitionExists(s),c=f.get.displayType(!1);if(l===S||e){if(f.verbose("Determining whether animation exists"),t=m.attr("class"),n=m.prop("tagName"),o=(i=C("<"+n+" />").addClass(t).insertAfter(m)).addClass(s).removeClass(d.inward).removeClass(d.outward).addClass(d.animating).addClass(d.transition).css("animationName"),a=i.addClass(d.inward).css("animationName"),c||(c=i.attr("class",t).removeAttr("style").removeClass(d.hidden).removeClass(d.visible).show().css("display"),f.verbose("Determining final display state",c),f.save.displayType(c)),i.remove(),o!=a)f.debug("Direction exists for animation",s),r=!0;else{if("none"==o||!o)return void f.debug("No animation defined in css",s);f.debug("Static animation found",s,c),r=!1}f.save.transitionExists(s,r)}return l!==S?l:r},animate:function(){return f.can.transition()!==S}},is:{animating:function(){return m.hasClass(d.animating)},inward:function(){return m.hasClass(d.inward)},outward:function(){return m.hasClass(d.outward)},looping:function(){return m.hasClass(d.looping)},occurring:function(e){return e="."+(e=e||u.animation).replace(" ","."),0<m.filter(e).length},visible:function(){return m.is(":visible")},hidden:function(){return"hidden"===m.css("visibility")},supported:function(){return!1!==o}},hide:function(){f.verbose("Hiding element"),f.is.animating()&&f.reset(),l.blur(),f.remove.display(),f.remove.visible(),f.set.hidden(),f.force.hidden(),u.onHide.call(l),u.onComplete.call(l)},show:function(e){f.verbose("Showing element",e),f.remove.hidden(),f.set.visible(),f.force.visible(),u.onShow.call(l),u.onComplete.call(l)},toggle:function(){f.is.visible()?f.hide():f.show()},stop:function(){f.debug("Stopping current animation"),m.triggerHandler(o)},stopAll:function(){f.debug("Stopping all animation"),f.remove.queueCallback(),m.triggerHandler(o)},clear:{queue:function(){f.debug("Clearing animation queue"),f.remove.queueCallback()}},enable:function(){f.verbose("Starting animation"),m.removeClass(d.disabled)},disable:function(){f.debug("Stopping animation"),m.addClass(d.disabled)},setting:function(e,t){if(f.debug("Changing setting",e,t),C.isPlainObject(e))C.extend(!0,u,e);else{if(t===S)return u[e];C.isPlainObject(u[e])?C.extend(!0,u[e],t):u[e]=t}},internal:function(e,t){if(C.isPlainObject(e))C.extend(!0,f,e);else{if(t===S)return f[e];f[e]=t}},debug:function(){!u.silent&&u.debug&&(u.performance?f.performance.log(arguments):(f.debug=Function.prototype.bind.call(console.info,console,u.name+":"),f.debug.apply(console,arguments)))},verbose:function(){!u.silent&&u.verbose&&u.debug&&(u.performance?f.performance.log(arguments):(f.verbose=Function.prototype.bind.call(console.info,console,u.name+":"),f.verbose.apply(console,arguments)))},error:function(){u.silent||(f.error=Function.prototype.bind.call(console.error,console,u.name+":"),f.error.apply(console,arguments))},performance:{log:function(e){var t,n;u.performance&&(n=(t=(new Date).getTime())-(p||t),p=t,h.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:l,"Execution Time":n})),clearTimeout(f.performance.timer),f.performance.timer=setTimeout(f.performance.display,500)},display:function(){var e=u.name+":",n=0;p=!1,clearTimeout(f.performance.timer),C.each(h,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",g&&(e+=" '"+g+"'"),1<r.length&&(e+=" ("+r.length+")"),(console.group!==S||console.table!==S)&&0<h.length&&(console.groupCollapsed(e),console.table?console.table(h):C.each(h,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),h=[]}},invoke:function(i,e,t){var o,a,n,r=s;return e=e||y,t=l||t,"string"==typeof i&&r!==S&&(i=i.split(/[\. ]/),o=i.length-1,C.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(C.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==S)return a=r[n],!1;if(!C.isPlainObject(r[t])||e==o)return r[t]!==S&&(a=r[t]),!1;r=r[t]}})),C.isFunction(a)?n=a.apply(t,e):a!==S&&(n=a),C.isArray(c)?c.push(n):c!==S?c=[c,n]:n!==S&&(c=n),a!==S&&a}}).initialize()}),c!==S?c:this},C.fn.transition.exists={},C.fn.transition.settings={name:"Transition",silent:!1,debug:!1,verbose:!1,performance:!0,namespace:"transition",interval:0,reverse:"auto",onStart:function(){},onComplete:function(){},onShow:function(){},onHide:function(){},useFailSafe:!0,failSafeDelay:100,allowRepeats:!1,displayType:!1,animation:"fade",duration:!1,queue:!0,metadata:{displayType:"display"},className:{animating:"animating",disabled:"disabled",hidden:"hidden",inward:"in",loading:"loading",looping:"looping",outward:"out",transition:"transition",visible:"visible"},error:{noAnimation:"Element is no longer attached to DOM. Unable to animate.  Use silent setting to surpress this warning in production.",repeated:"That animation is already occurring, cancelling repeated animation",method:"The method you called is not defined",support:"This browser does not support CSS animations"}}}(jQuery,window,document),function(P,E,e,F){"use strict";E=void 0!==E&&E.Math==Math?E:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();P.api=P.fn.api=function(x){var C,e=P.isFunction(this)?P(E):P(this),w=e.selector||"",S=(new Date).getTime(),k=[],T=x,A="string"==typeof T,R=[].slice.call(arguments,1);return e.each(function(){var a,r,n,e,s,l,c=P.isPlainObject(x)?P.extend(!0,{},P.fn.api.settings,x):P.extend({},P.fn.api.settings),t=c.namespace,i=c.metadata,o=c.selector,u=c.error,d=c.className,f="."+t,m="module-"+t,g=P(this),p=g.closest(o.form),h=c.stateContext?P(c.stateContext):g,v=this,b=h[0],y=g.data(m);l={initialize:function(){A||l.bind.events(),l.instantiate()},instantiate:function(){l.verbose("Storing instance of module",l),y=l,g.data(m,y)},destroy:function(){l.verbose("Destroying previous module for",v),g.removeData(m).off(f)},bind:{events:function(){var e=l.get.event();e?(l.verbose("Attaching API events to element",e),g.on(e+f,l.event.trigger)):"now"==c.on&&(l.debug("Querying API endpoint immediately"),l.query())}},decode:{json:function(e){if(e!==F&&"string"==typeof e)try{e=JSON.parse(e)}catch(e){}return e}},read:{cachedResponse:function(e){var t;if(E.Storage!==F)return t=sessionStorage.getItem(e),l.debug("Using cached response",e,t),t=l.decode.json(t);l.error(u.noStorage)}},write:{cachedResponse:function(e,t){t&&""===t?l.debug("Response empty, not caching",t):E.Storage!==F?(P.isPlainObject(t)&&(t=JSON.stringify(t)),sessionStorage.setItem(e,t),l.verbose("Storing cached response for url",e,t)):l.error(u.noStorage)}},query:function(){if(l.is.disabled())l.debug("Element is disabled API request aborted");else{if(l.is.loading()){if(!c.interruptRequests)return void l.debug("Cancelling request, previous request is still pending");l.debug("Interrupting previous request"),l.abort()}if(c.defaultData&&P.extend(!0,c.urlData,l.get.defaultData()),c.serializeForm&&(c.data=l.add.formData(c.data)),!1===(r=l.get.settings()))return l.cancelled=!0,void l.error(u.beforeSend);if(l.cancelled=!1,(n=l.get.templatedURL())||l.is.mocked()){if((n=l.add.urlData(n))||l.is.mocked()){if(r.url=c.base+n,a=P.extend(!0,{},c,{type:c.method||c.type,data:e,url:c.base+n,beforeSend:c.beforeXHR,success:function(){},failure:function(){},complete:function(){}}),l.debug("Querying URL",a.url),l.verbose("Using AJAX settings",a),"local"===c.cache&&l.read.cachedResponse(n))return l.debug("Response returned from local cache"),l.request=l.create.request(),void l.request.resolveWith(b,[l.read.cachedResponse(n)]);c.throttle?c.throttleFirstRequest||l.timer?(l.debug("Throttling request",c.throttle),clearTimeout(l.timer),l.timer=setTimeout(function(){l.timer&&delete l.timer,l.debug("Sending throttled request",e,a.method),l.send.request()},c.throttle)):(l.debug("Sending request",e,a.method),l.send.request(),l.timer=setTimeout(function(){},c.throttle)):(l.debug("Sending request",e,a.method),l.send.request())}}else l.error(u.missingURL)}},should:{removeError:function(){return!0===c.hideError||"auto"===c.hideError&&!l.is.form()}},is:{disabled:function(){return 0<g.filter(o.disabled).length},expectingJSON:function(){return"json"===c.dataType||"jsonp"===c.dataType},form:function(){return g.is("form")||h.is("form")},mocked:function(){return c.mockResponse||c.mockResponseAsync||c.response||c.responseAsync},input:function(){return g.is("input")},loading:function(){return!!l.request&&"pending"==l.request.state()},abortedRequest:function(e){return e&&e.readyState!==F&&0===e.readyState?(l.verbose("XHR request determined to be aborted"),!0):(l.verbose("XHR request was not aborted"),!1)},validResponse:function(e){return l.is.expectingJSON()&&P.isFunction(c.successTest)?(l.debug("Checking JSON returned success",c.successTest,e),c.successTest(e)?(l.debug("Response passed success test",e),!0):(l.debug("Response failed success test",e),!1)):(l.verbose("Response is not JSON, skipping validation",c.successTest,e),!0)}},was:{cancelled:function(){return l.cancelled||!1},succesful:function(){return l.request&&"resolved"==l.request.state()},failure:function(){return l.request&&"rejected"==l.request.state()},complete:function(){return l.request&&("resolved"==l.request.state()||"rejected"==l.request.state())}},add:{urlData:function(o,a){var e,t;return o&&(e=o.match(c.regExp.required),t=o.match(c.regExp.optional),a=a||c.urlData,e&&(l.debug("Looking for required URL variables",e),P.each(e,function(e,t){var n=-1!==t.indexOf("$")?t.substr(2,t.length-3):t.substr(1,t.length-2),i=P.isPlainObject(a)&&a[n]!==F?a[n]:g.data(n)!==F?g.data(n):h.data(n)!==F?h.data(n):a[n];if(i===F)return l.error(u.requiredParameter,n,o),o=!1;l.verbose("Found required variable",n,i),i=c.encodeParameters?l.get.urlEncodedValue(i):i,o=o.replace(t,i)})),t&&(l.debug("Looking for optional URL variables",e),P.each(t,function(e,t){var n=-1!==t.indexOf("$")?t.substr(3,t.length-4):t.substr(2,t.length-3),i=P.isPlainObject(a)&&a[n]!==F?a[n]:g.data(n)!==F?g.data(n):h.data(n)!==F?h.data(n):a[n];o=i!==F?(l.verbose("Optional variable Found",n,i),o.replace(t,i)):(l.verbose("Optional variable not found",n),-1!==o.indexOf("/"+t)?o.replace("/"+t,""):o.replace(t,""))}))),o},formData:function(e){var t=P.fn.serializeObject!==F,n=t?p.serializeObject():p.serialize();return e=e||c.data,e=P.isPlainObject(e)?t?(l.debug("Extending existing data with form data",e,n),P.extend(!0,{},e,n)):(l.error(u.missingSerialize),l.debug("Cant extend data. Replacing data with form data",e,n),n):(l.debug("Adding form data",n),n)}},send:{request:function(){l.set.loading(),l.request=l.create.request(),l.is.mocked()?l.mockedXHR=l.create.mockedXHR():l.xhr=l.create.xhr(),c.onRequest.call(b,l.request,l.xhr)}},event:{trigger:function(e){l.query(),"submit"!=e.type&&"click"!=e.type||e.preventDefault()},xhr:{always:function(){},done:function(e,t,n){var i=this,o=(new Date).getTime()-s,a=c.loadingDuration-o,r=!!P.isFunction(c.onResponse)&&(l.is.expectingJSON()?c.onResponse.call(i,P.extend(!0,{},e)):c.onResponse.call(i,e));a=0<a?a:0,r&&(l.debug("Modified API response in onResponse callback",c.onResponse,r,e),e=r),0<a&&l.debug("Response completed early delaying state change by",a),setTimeout(function(){l.is.validResponse(e)?l.request.resolveWith(i,[e,n]):l.request.rejectWith(i,[n,"invalid"])},a)},fail:function(e,t,n){var i=this,o=(new Date).getTime()-s,a=c.loadingDuration-o;0<(a=0<a?a:0)&&l.debug("Response completed early delaying state change by",a),setTimeout(function(){l.is.abortedRequest(e)?l.request.rejectWith(i,[e,"aborted",n]):l.request.rejectWith(i,[e,"error",t,n])},a)}},request:{done:function(e,t){l.debug("Successful API Response",e),"local"===c.cache&&n&&(l.write.cachedResponse(n,e),l.debug("Saving server response locally",l.cache)),c.onSuccess.call(b,e,g,t)},complete:function(e,t){var n,i;l.was.succesful()?(i=e,n=t):(n=e,i=l.get.responseFromXHR(n)),l.remove.loading(),c.onComplete.call(b,i,g,n)},fail:function(e,t,n){var i=l.get.responseFromXHR(e),o=l.get.errorFromRequest(i,t,n);if("aborted"==t)return l.debug("XHR Aborted (Most likely caused by page navigation or CORS Policy)",t,n),c.onAbort.call(b,t,g,e),!0;"invalid"==t?l.debug("JSON did not pass success test. A server-side error has most likely occurred",i):"error"==t&&e!==F&&(l.debug("XHR produced a server error",t,n),200!=e.status&&n!==F&&""!==n&&l.error(u.statusMessage+n,a.url),c.onError.call(b,o,g,e)),c.errorDuration&&"aborted"!==t&&(l.debug("Adding error state"),l.set.error(),l.should.removeError()&&setTimeout(l.remove.error,c.errorDuration)),l.debug("API Request failed",o,e),c.onFailure.call(b,i,g,e)}}},create:{request:function(){return P.Deferred().always(l.event.request.complete).done(l.event.request.done).fail(l.event.request.fail)},mockedXHR:function(){var e,t,n,i=c.mockResponse||c.response,o=c.mockResponseAsync||c.responseAsync;return n=P.Deferred().always(l.event.xhr.complete).done(l.event.xhr.done).fail(l.event.xhr.fail),i?(t=P.isFunction(i)?(l.debug("Using specified synchronous callback",i),i.call(b,r)):(l.debug("Using settings specified response",i),i),n.resolveWith(b,[t,!1,{responseText:t}])):P.isFunction(o)&&(e=function(e){l.debug("Async callback returned response",e),e?n.resolveWith(b,[e,!1,{responseText:e}]):n.rejectWith(b,[{responseText:e},!1,!1])},l.debug("Using specified async response callback",o),o.call(b,r,e)),n},xhr:function(){var e;return e=P.ajax(a).always(l.event.xhr.always).done(l.event.xhr.done).fail(l.event.xhr.fail),l.verbose("Created server request",e,a),e}},set:{error:function(){l.verbose("Adding error state to element",h),h.addClass(d.error)},loading:function(){l.verbose("Adding loading state to element",h),h.addClass(d.loading),s=(new Date).getTime()}},remove:{error:function(){l.verbose("Removing error state from element",h),h.removeClass(d.error)},loading:function(){l.verbose("Removing loading state from element",h),h.removeClass(d.loading)}},get:{responseFromXHR:function(e){return!!P.isPlainObject(e)&&(l.is.expectingJSON()?l.decode.json(e.responseText):e.responseText)},errorFromRequest:function(e,t,n){return P.isPlainObject(e)&&e.error!==F?e.error:c.error[t]!==F?c.error[t]:n},request:function(){return l.request||!1},xhr:function(){return l.xhr||!1},settings:function(){var e;return(e=c.beforeSend.call(b,c))&&(e.success!==F&&(l.debug("Legacy success callback detected",e),l.error(u.legacyParameters,e.success),e.onSuccess=e.success),e.failure!==F&&(l.debug("Legacy failure callback detected",e),l.error(u.legacyParameters,e.failure),e.onFailure=e.failure),e.complete!==F&&(l.debug("Legacy complete callback detected",e),l.error(u.legacyParameters,e.complete),e.onComplete=e.complete)),e===F&&l.error(u.noReturnedValue),!1===e?e:e!==F?P.extend(!0,{},e):P.extend(!0,{},c)},urlEncodedValue:function(e){var t=E.decodeURIComponent(e),n=E.encodeURIComponent(e);return t!==e?(l.debug("URL value is already encoded, avoiding double encoding",e),e):(l.verbose("Encoding value using encodeURIComponent",e,n),n)},defaultData:function(){var e={};return P.isWindow(v)||(l.is.input()?e.value=g.val():l.is.form()||(e.text=g.text())),e},event:function(){return P.isWindow(v)||"now"==c.on?(l.debug("API called without element, no events attached"),!1):"auto"==c.on?g.is("input")?v.oninput!==F?"input":v.onpropertychange!==F?"propertychange":"keyup":g.is("form")?"submit":"click":c.on},templatedURL:function(e){if(e=e||g.data(i.action)||c.action||!1,n=g.data(i.url)||c.url||!1)return l.debug("Using specified url",n),n;if(e){if(l.debug("Looking up url for action",e,c.api),c.api[e]===F&&!l.is.mocked())return void l.error(u.missingAction,c.action,c.api);n=c.api[e]}else l.is.form()&&(n=g.attr("action")||h.attr("action")||!1,l.debug("No url or action specified, defaulting to form action",n));return n}},abort:function(){var e=l.get.xhr();e&&"resolved"!==e.state()&&(l.debug("Cancelling API request"),e.abort())},reset:function(){l.remove.error(),l.remove.loading()},setting:function(e,t){if(l.debug("Changing setting",e,t),P.isPlainObject(e))P.extend(!0,c,e);else{if(t===F)return c[e];P.isPlainObject(c[e])?P.extend(!0,c[e],t):c[e]=t}},internal:function(e,t){if(P.isPlainObject(e))P.extend(!0,l,e);else{if(t===F)return l[e];l[e]=t}},debug:function(){!c.silent&&c.debug&&(c.performance?l.performance.log(arguments):(l.debug=Function.prototype.bind.call(console.info,console,c.name+":"),l.debug.apply(console,arguments)))},verbose:function(){!c.silent&&c.verbose&&c.debug&&(c.performance?l.performance.log(arguments):(l.verbose=Function.prototype.bind.call(console.info,console,c.name+":"),l.verbose.apply(console,arguments)))},error:function(){c.silent||(l.error=Function.prototype.bind.call(console.error,console,c.name+":"),l.error.apply(console,arguments))},performance:{log:function(e){var t,n;c.performance&&(n=(t=(new Date).getTime())-(S||t),S=t,k.push({Name:e[0],Arguments:[].slice.call(e,1)||"","Execution Time":n})),clearTimeout(l.performance.timer),l.performance.timer=setTimeout(l.performance.display,500)},display:function(){var e=c.name+":",n=0;S=!1,clearTimeout(l.performance.timer),P.each(k,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",w&&(e+=" '"+w+"'"),(console.group!==F||console.table!==F)&&0<k.length&&(console.groupCollapsed(e),console.table?console.table(k):P.each(k,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),k=[]}},invoke:function(i,e,t){var o,a,n,r=y;return e=e||R,t=v||t,"string"==typeof i&&r!==F&&(i=i.split(/[\. ]/),o=i.length-1,P.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(P.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==F)return a=r[n],!1;if(!P.isPlainObject(r[t])||e==o)return r[t]!==F?a=r[t]:l.error(u.method,i),!1;r=r[t]}})),P.isFunction(a)?n=a.apply(t,e):a!==F&&(n=a),P.isArray(C)?C.push(n):C!==F?C=[C,n]:n!==F&&(C=n),a}},A?(y===F&&l.initialize(),l.invoke(T)):(y!==F&&y.invoke("destroy"),l.initialize())}),C!==F?C:this},P.api.settings={name:"API",namespace:"api",debug:!1,verbose:!1,performance:!0,api:{},cache:!0,interruptRequests:!0,on:"auto",stateContext:!1,loadingDuration:0,hideError:"auto",errorDuration:2e3,encodeParameters:!0,action:!1,url:!1,base:"",urlData:{},defaultData:!0,serializeForm:!1,throttle:0,throttleFirstRequest:!0,method:"get",data:{},dataType:"json",mockResponse:!1,mockResponseAsync:!1,response:!1,responseAsync:!1,beforeSend:function(e){return e},beforeXHR:function(e){},onRequest:function(e,t){},onResponse:!1,onSuccess:function(e,t){},onComplete:function(e,t){},onFailure:function(e,t){},onError:function(e,t){},onAbort:function(e,t){},successTest:!1,error:{beforeSend:"The before send function has aborted the request",error:"There was an error with your request",exitConditions:"API Request Aborted. Exit conditions met",JSONParse:"JSON could not be parsed during error handling",legacyParameters:"You are using legacy API success callback names",method:"The method you called is not defined",missingAction:"API action used but no url was defined",missingSerialize:"jquery-serialize-object is required to add form data to an existing data object",missingURL:"No URL specified for api event",noReturnedValue:"The beforeSend callback must return a settings object, beforeSend ignored.",noStorage:"Caching responses locally requires session storage",parseError:"There was an error parsing your request",requiredParameter:"Missing a required URL parameter: ",statusMessage:"Server gave an error: ",timeout:"Your request timed out"},regExp:{required:/\{\$*[A-z0-9]+\}/g,optional:/\{\/\$*[A-z0-9]+\}/g},className:{loading:"loading",error:"error"},selector:{disabled:".disabled",form:"form"},metadata:{action:"action",url:"url"}}}(jQuery,window,document),function(P,E,F,O){"use strict";E=void 0!==E&&E.Math==Math?E:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")(),P.fn.visibility=function(b){var y,e=P(this),x=e.selector||"",C=(new Date).getTime(),w=[],S=b,k="string"==typeof S,T=[].slice.call(arguments,1),A=e.length,R=0;return e.each(function(){var e,t,n,s,o=P.isPlainObject(b)?P.extend(!0,{},P.fn.visibility.settings,b):P.extend({},P.fn.visibility.settings),i=o.className,a=o.namespace,l=o.error,r=o.metadata,c="."+a,u="module-"+a,d=P(E),f=P(this),m=P(o.context),g=(f.selector,f.data(u)),p=E.requestAnimationFrame||E.mozRequestAnimationFrame||E.webkitRequestAnimationFrame||E.msRequestAnimationFrame||function(e){setTimeout(e,0)},h=this,v=!1;s={initialize:function(){s.debug("Initializing",o),s.setup.cache(),s.should.trackChanges()&&("image"==o.type&&s.setup.image(),"fixed"==o.type&&s.setup.fixed(),o.observeChanges&&s.observeChanges(),s.bind.events()),s.save.position(),s.is.visible()||s.error(l.visible,f),o.initialCheck&&s.checkVisibility(),s.instantiate()},instantiate:function(){s.debug("Storing instance",s),f.data(u,s),g=s},destroy:function(){s.verbose("Destroying previous module"),n&&n.disconnect(),t&&t.disconnect(),d.off("load"+c,s.event.load).off("resize"+c,s.event.resize),m.off("scroll"+c,s.event.scroll).off("scrollchange"+c,s.event.scrollchange),"fixed"==o.type&&(s.resetFixed(),s.remove.placeholder()),f.off(c).removeData(u)},observeChanges:function(){"MutationObserver"in E&&(t=new MutationObserver(s.event.contextChanged),n=new MutationObserver(s.event.changed),t.observe(F,{childList:!0,subtree:!0}),n.observe(h,{childList:!0,subtree:!0}),s.debug("Setting up mutation observer",n))},bind:{events:function(){s.verbose("Binding visibility events to scroll and resize"),o.refreshOnLoad&&d.on("load"+c,s.event.load),d.on("resize"+c,s.event.resize),m.off("scroll"+c).on("scroll"+c,s.event.scroll).on("scrollchange"+c,s.event.scrollchange)}},event:{changed:function(e){s.verbose("DOM tree modified, updating visibility calculations"),s.timer=setTimeout(function(){s.verbose("DOM tree modified, updating sticky menu"),s.refresh()},100)},contextChanged:function(e){[].forEach.call(e,function(e){e.removedNodes&&[].forEach.call(e.removedNodes,function(e){(e==h||0<P(e).find(h).length)&&(s.debug("Element removed from DOM, tearing down events"),s.destroy())})})},resize:function(){s.debug("Window resized"),o.refreshOnResize&&p(s.refresh)},load:function(){s.debug("Page finished loading"),p(s.refresh)},scroll:function(){o.throttle?(clearTimeout(s.timer),s.timer=setTimeout(function(){m.triggerHandler("scrollchange"+c,[m.scrollTop()])},o.throttle)):p(function(){m.triggerHandler("scrollchange"+c,[m.scrollTop()])})},scrollchange:function(e,t){s.checkVisibility(t)}},precache:function(e,t){e instanceof Array||(e=[e]);for(var n=e.length,i=0,o=[],a=F.createElement("img"),r=function(){++i>=e.length&&P.isFunction(t)&&t()};n--;)(a=F.createElement("img")).onload=r,a.onerror=r,a.src=e[n],o.push(a)},enableCallbacks:function(){s.debug("Allowing callbacks to occur"),v=!1},disableCallbacks:function(){s.debug("Disabling all callbacks temporarily"),v=!0},should:{trackChanges:function(){return k?(s.debug("One time query, no need to bind events"),!1):(s.debug("Callbacks being attached"),!0)}},setup:{cache:function(){s.cache={occurred:{},screen:{},element:{}}},image:function(){var e=f.data(r.src);e&&(s.verbose("Lazy loading image",e),o.once=!0,o.observeChanges=!1,o.onOnScreen=function(){s.debug("Image on screen",h),s.precache(e,function(){s.set.image(e,function(){++R==A&&o.onAllLoaded.call(this),o.onLoad.call(this)})})})},fixed:function(){s.debug("Setting up fixed"),o.once=!1,o.observeChanges=!1,o.initialCheck=!0,o.refreshOnLoad=!0,b.transition||(o.transition=!1),s.create.placeholder(),s.debug("Added placeholder",e),o.onTopPassed=function(){s.debug("Element passed, adding fixed position",f),s.show.placeholder(),s.set.fixed(),o.transition&&P.fn.transition!==O&&f.transition(o.transition,o.duration)},o.onTopPassedReverse=function(){s.debug("Element returned to position, removing fixed",f),s.hide.placeholder(),s.remove.fixed()}}},create:{placeholder:function(){s.verbose("Creating fixed position placeholder"),e=f.clone(!1).css("display","none").addClass(i.placeholder).insertAfter(f)}},show:{placeholder:function(){s.verbose("Showing placeholder"),e.css("display","block").css("visibility","hidden")}},hide:{placeholder:function(){s.verbose("Hiding placeholder"),e.css("display","none").css("visibility","")}},set:{fixed:function(){s.verbose("Setting element to fixed position"),f.addClass(i.fixed).css({position:"fixed",top:o.offset+"px",left:"auto",zIndex:o.zIndex}),o.onFixed.call(h)},image:function(e,t){if(f.attr("src",e),o.transition)if(P.fn.transition!==O){if(f.hasClass(i.visible))return void s.debug("Transition already occurred on this image, skipping animation");f.transition(o.transition,o.duration,t)}else f.fadeIn(o.duration,t);else f.show()}},is:{onScreen:function(){return s.get.elementCalculations().onScreen},offScreen:function(){return s.get.elementCalculations().offScreen},visible:function(){return!(!s.cache||!s.cache.element)&&!(0===s.cache.element.width&&0===s.cache.element.offset.top)},verticallyScrollableContext:function(){var e=m.get(0)!==E&&m.css("overflow-y");return"auto"==e||"scroll"==e},horizontallyScrollableContext:function(){var e=m.get(0)!==E&&m.css("overflow-x");return"auto"==e||"scroll"==e}},refresh:function(){s.debug("Refreshing constants (width/height)"),"fixed"==o.type&&s.resetFixed(),s.reset(),s.save.position(),o.checkOnRefresh&&s.checkVisibility(),o.onRefresh.call(h)},resetFixed:function(){s.remove.fixed(),s.remove.occurred()},reset:function(){s.verbose("Resetting all cached values"),P.isPlainObject(s.cache)&&(s.cache.screen={},s.cache.element={})},checkVisibility:function(e){s.verbose("Checking visibility of element",s.cache.element),!v&&s.is.visible()&&(s.save.scroll(e),s.save.calculations(),s.passed(),s.passingReverse(),s.topVisibleReverse(),s.bottomVisibleReverse(),s.topPassedReverse(),s.bottomPassedReverse(),s.onScreen(),s.offScreen(),s.passing(),s.topVisible(),s.bottomVisible(),s.topPassed(),s.bottomPassed(),o.onUpdate&&o.onUpdate.call(h,s.get.elementCalculations()))},passed:function(e,t){var n=s.get.elementCalculations();if(e&&t)o.onPassed[e]=t;else{if(e!==O)return s.get.pixelsPassed(e)>n.pixelsPassed;n.passing&&P.each(o.onPassed,function(e,t){n.bottomVisible||n.pixelsPassed>s.get.pixelsPassed(e)?s.execute(t,e):o.once||s.remove.occurred(t)})}},onScreen:function(e){var t=s.get.elementCalculations(),n=e||o.onOnScreen,i="onScreen";if(e&&(s.debug("Adding callback for onScreen",e),o.onOnScreen=e),t.onScreen?s.execute(n,i):o.once||s.remove.occurred(i),e!==O)return t.onOnScreen},offScreen:function(e){var t=s.get.elementCalculations(),n=e||o.onOffScreen,i="offScreen";if(e&&(s.debug("Adding callback for offScreen",e),o.onOffScreen=e),t.offScreen?s.execute(n,i):o.once||s.remove.occurred(i),e!==O)return t.onOffScreen},passing:function(e){var t=s.get.elementCalculations(),n=e||o.onPassing,i="passing";if(e&&(s.debug("Adding callback for passing",e),o.onPassing=e),t.passing?s.execute(n,i):o.once||s.remove.occurred(i),e!==O)return t.passing},topVisible:function(e){var t=s.get.elementCalculations(),n=e||o.onTopVisible,i="topVisible";if(e&&(s.debug("Adding callback for top visible",e),o.onTopVisible=e),t.topVisible?s.execute(n,i):o.once||s.remove.occurred(i),e===O)return t.topVisible},bottomVisible:function(e){var t=s.get.elementCalculations(),n=e||o.onBottomVisible,i="bottomVisible";if(e&&(s.debug("Adding callback for bottom visible",e),o.onBottomVisible=e),t.bottomVisible?s.execute(n,i):o.once||s.remove.occurred(i),e===O)return t.bottomVisible},topPassed:function(e){var t=s.get.elementCalculations(),n=e||o.onTopPassed,i="topPassed";if(e&&(s.debug("Adding callback for top passed",e),o.onTopPassed=e),t.topPassed?s.execute(n,i):o.once||s.remove.occurred(i),e===O)return t.topPassed},bottomPassed:function(e){var t=s.get.elementCalculations(),n=e||o.onBottomPassed,i="bottomPassed";if(e&&(s.debug("Adding callback for bottom passed",e),o.onBottomPassed=e),t.bottomPassed?s.execute(n,i):o.once||s.remove.occurred(i),e===O)return t.bottomPassed},passingReverse:function(e){var t=s.get.elementCalculations(),n=e||o.onPassingReverse,i="passingReverse";if(e&&(s.debug("Adding callback for passing reverse",e),o.onPassingReverse=e),t.passing?o.once||s.remove.occurred(i):s.get.occurred("passing")&&s.execute(n,i),e!==O)return!t.passing},topVisibleReverse:function(e){var t=s.get.elementCalculations(),n=e||o.onTopVisibleReverse,i="topVisibleReverse";if(e&&(s.debug("Adding callback for top visible reverse",e),o.onTopVisibleReverse=e),t.topVisible?o.once||s.remove.occurred(i):s.get.occurred("topVisible")&&s.execute(n,i),e===O)return!t.topVisible},bottomVisibleReverse:function(e){var t=s.get.elementCalculations(),n=e||o.onBottomVisibleReverse,i="bottomVisibleReverse";if(e&&(s.debug("Adding callback for bottom visible reverse",e),o.onBottomVisibleReverse=e),t.bottomVisible?o.once||s.remove.occurred(i):s.get.occurred("bottomVisible")&&s.execute(n,i),e===O)return!t.bottomVisible},topPassedReverse:function(e){var t=s.get.elementCalculations(),n=e||o.onTopPassedReverse,i="topPassedReverse";if(e&&(s.debug("Adding callback for top passed reverse",e),o.onTopPassedReverse=e),t.topPassed?o.once||s.remove.occurred(i):s.get.occurred("topPassed")&&s.execute(n,i),e===O)return!t.onTopPassed},bottomPassedReverse:function(e){var t=s.get.elementCalculations(),n=e||o.onBottomPassedReverse,i="bottomPassedReverse";if(e&&(s.debug("Adding callback for bottom passed reverse",e),o.onBottomPassedReverse=e),t.bottomPassed?o.once||s.remove.occurred(i):s.get.occurred("bottomPassed")&&s.execute(n,i),e===O)return!t.bottomPassed},execute:function(e,t){var n=s.get.elementCalculations(),i=s.get.screenCalculations();(e=e||!1)&&(o.continuous?(s.debug("Callback being called continuously",t,n),e.call(h,n,i)):s.get.occurred(t)||(s.debug("Conditions met",t,n),e.call(h,n,i))),s.save.occurred(t)},remove:{fixed:function(){s.debug("Removing fixed position"),f.removeClass(i.fixed).css({position:"",top:"",left:"",zIndex:""}),o.onUnfixed.call(h)},placeholder:function(){s.debug("Removing placeholder content"),e&&e.remove()},occurred:function(e){if(e){var t=s.cache.occurred;t[e]!==O&&!0===t[e]&&(s.debug("Callback can now be called again",e),s.cache.occurred[e]=!1)}else s.cache.occurred={}}},save:{calculations:function(){s.verbose("Saving all calculations necessary to determine positioning"),s.save.direction(),s.save.screenCalculations(),s.save.elementCalculations()},occurred:function(e){e&&(s.cache.occurred[e]!==O&&!0===s.cache.occurred[e]||(s.verbose("Saving callback occurred",e),s.cache.occurred[e]=!0))},scroll:function(e){e=e+o.offset||m.scrollTop()+o.offset,s.cache.scroll=e},direction:function(){var e,t=s.get.scroll(),n=s.get.lastScroll();return e=n<t&&n?"down":t<n&&n?"up":"static",s.cache.direction=e,s.cache.direction},elementPosition:function(){var e=s.cache.element,t=s.get.screenSize();return s.verbose("Saving element position"),e.fits=e.height<t.height,e.offset=f.offset(),e.width=f.outerWidth(),e.height=f.outerHeight(),s.is.verticallyScrollableContext()&&(e.offset.top+=m.scrollTop()-m.offset().top),s.is.horizontallyScrollableContext()&&(e.offset.left+=m.scrollLeft-m.offset().left),s.cache.element=e},elementCalculations:function(){var e=s.get.screenCalculations(),t=s.get.elementPosition();return o.includeMargin?(t.margin={},t.margin.top=parseInt(f.css("margin-top"),10),t.margin.bottom=parseInt(f.css("margin-bottom"),10),t.top=t.offset.top-t.margin.top,t.bottom=t.offset.top+t.height+t.margin.bottom):(t.top=t.offset.top,t.bottom=t.offset.top+t.height),t.topPassed=e.top>=t.top,t.bottomPassed=e.top>=t.bottom,t.topVisible=e.bottom>=t.top&&!t.topPassed,t.bottomVisible=e.bottom>=t.bottom&&!t.bottomPassed,t.pixelsPassed=0,t.percentagePassed=0,t.onScreen=(t.topVisible||t.passing)&&!t.bottomPassed,t.passing=t.topPassed&&!t.bottomPassed,t.offScreen=!t.onScreen,t.passing&&(t.pixelsPassed=e.top-t.top,t.percentagePassed=(e.top-t.top)/t.height),s.cache.element=t,s.verbose("Updated element calculations",t),t},screenCalculations:function(){var e=s.get.scroll();return s.save.direction(),s.cache.screen.top=e,s.cache.screen.bottom=e+s.cache.screen.height,s.cache.screen},screenSize:function(){s.verbose("Saving window position"),s.cache.screen={height:m.height()}},position:function(){s.save.screenSize(),s.save.elementPosition()}},get:{pixelsPassed:function(e){var t=s.get.elementCalculations();return-1<e.search("%")?t.height*(parseInt(e,10)/100):parseInt(e,10)},occurred:function(e){return s.cache.occurred!==O&&s.cache.occurred[e]||!1},direction:function(){return s.cache.direction===O&&s.save.direction(),s.cache.direction},elementPosition:function(){return s.cache.element===O&&s.save.elementPosition(),s.cache.element},elementCalculations:function(){return s.cache.element===O&&s.save.elementCalculations(),s.cache.element},screenCalculations:function(){return s.cache.screen===O&&s.save.screenCalculations(),s.cache.screen},screenSize:function(){return s.cache.screen===O&&s.save.screenSize(),s.cache.screen},scroll:function(){return s.cache.scroll===O&&s.save.scroll(),s.cache.scroll},lastScroll:function(){return s.cache.screen===O?(s.debug("First scroll event, no last scroll could be found"),!1):s.cache.screen.top}},setting:function(e,t){if(P.isPlainObject(e))P.extend(!0,o,e);else{if(t===O)return o[e];o[e]=t}},internal:function(e,t){if(P.isPlainObject(e))P.extend(!0,s,e);else{if(t===O)return s[e];s[e]=t}},debug:function(){!o.silent&&o.debug&&(o.performance?s.performance.log(arguments):(s.debug=Function.prototype.bind.call(console.info,console,o.name+":"),s.debug.apply(console,arguments)))},verbose:function(){!o.silent&&o.verbose&&o.debug&&(o.performance?s.performance.log(arguments):(s.verbose=Function.prototype.bind.call(console.info,console,o.name+":"),s.verbose.apply(console,arguments)))},error:function(){o.silent||(s.error=Function.prototype.bind.call(console.error,console,o.name+":"),s.error.apply(console,arguments))},performance:{log:function(e){var t,n;o.performance&&(n=(t=(new Date).getTime())-(C||t),C=t,w.push({Name:e[0],Arguments:[].slice.call(e,1)||"",Element:h,"Execution Time":n})),clearTimeout(s.performance.timer),s.performance.timer=setTimeout(s.performance.display,500)},display:function(){var e=o.name+":",n=0;C=!1,clearTimeout(s.performance.timer),P.each(w,function(e,t){n+=t["Execution Time"]}),e+=" "+n+"ms",x&&(e+=" '"+x+"'"),(console.group!==O||console.table!==O)&&0<w.length&&(console.groupCollapsed(e),console.table?console.table(w):P.each(w,function(e,t){console.log(t.Name+": "+t["Execution Time"]+"ms")}),console.groupEnd()),w=[]}},invoke:function(i,e,t){var o,a,n,r=g;return e=e||T,t=h||t,"string"==typeof i&&r!==O&&(i=i.split(/[\. ]/),o=i.length-1,P.each(i,function(e,t){var n=e!=o?t+i[e+1].charAt(0).toUpperCase()+i[e+1].slice(1):i;if(P.isPlainObject(r[n])&&e!=o)r=r[n];else{if(r[n]!==O)return a=r[n],!1;if(!P.isPlainObject(r[t])||e==o)return r[t]!==O?a=r[t]:s.error(l.method,i),!1;r=r[t]}})),P.isFunction(a)?n=a.apply(t,e):a!==O&&(n=a),P.isArray(y)?y.push(n):y!==O?y=[y,n]:n!==O&&(y=n),a}},k?(g===O&&s.initialize(),g.save.scroll(),g.save.calculations(),s.invoke(S)):(g!==O&&g.invoke("destroy"),s.initialize())}),y!==O?y:this},P.fn.visibility.settings={name:"Visibility",namespace:"visibility",debug:!1,verbose:!1,performance:!0,observeChanges:!0,initialCheck:!0,refreshOnLoad:!0,refreshOnResize:!0,checkOnRefresh:!0,once:!0,continuous:!1,offset:0,includeMargin:!1,context:E,throttle:!1,type:!1,zIndex:"10",transition:"fade in",duration:1e3,onPassed:{},onOnScreen:!1,onOffScreen:!1,onPassing:!1,onTopVisible:!1,onBottomVisible:!1,onTopPassed:!1,onBottomPassed:!1,onPassingReverse:!1,onTopVisibleReverse:!1,onBottomVisibleReverse:!1,onTopPassedReverse:!1,onBottomPassedReverse:!1,onLoad:function(){},onAllLoaded:function(){},onFixed:function(){},onUnfixed:function(){},onUpdate:!1,onRefresh:function(){},metadata:{src:"src"},className:{fixed:"fixed",placeholder:"placeholder",visible:"visible"},error:{method:"The method you called is not defined.",visible:"Element is hidden, you must call refresh after element becomes visible"}}}(jQuery,window,document);

// === Dropdown === //
$('.ui.dropdown')
    .dropdown();

// === Model === //
$('.ui.modal')
    .modal({
        blurring: true
    })
    .modal('show');

// === Tab === //
$('.menu .item')
    .tab();

// === checkbox Toggle === //
$('.ui.checkbox')
    .checkbox();

// === Toggle === //
$('.enable.button')
    .on('click', function() {
        $(this)
            .nextAll('.checkbox')
            .checkbox('enable');
    });


// Featured Courses home
$('.featured_courses').owlCarousel({
    items:10,
    loop:false,
    margin:20,
    nav:true,
    dots:false,
    navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:4
        },
        1200:{
            items:4
        },
        1400:{
            items:5
        }
    }
});

// Featured Courses home
$('.top_instrutors').owlCarousel({
    items:10,
    loop:false,
    margin:20,
    nav:true,
    dots:false,
    navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:1
        },
        1200:{
            items:2
        },
        1400:{
            items:3
        }
    }
});

// Student Says
$('.Student_says').owlCarousel({
    items:10,
    loop:false,
    margin:30,
    nav:true,
    dots:false,
    navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:2
        },
        1200:{
            items:3
        },
        1400:{
            items:3
        }
    }
});

// features Careers
$('.feature_careers').owlCarousel({
    items:4,
    loop:false,
    margin:20,
    nav:true,
    dots:false,
    navText: ["<i class='uil uil-angle-left'></i>", "<i class='uil uil-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        },
        1200:{
            items:1
        },
        1400:{
            items:1
        }
    }
});

/*Floating Code for Iframe Start*/
if (jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"],iframe[src*="https://player.vimeo.com/"]').length > 0) {
    /*Wrap (all code inside div) all vedio code inside div*/
    jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"]').wrap("<div class='iframe-parent-class'></div>");
    /*main code of each (particular) vedio*/
    jQuery('iframe[src*="https://www.youtube.com/embed/"],iframe[src*="https://player.vimeo.com/"]').each(function(index) {

        /*Floating js Start*/
        var windows = jQuery(window);
        var iframeWrap = jQuery(this).parent();
        var iframe = jQuery(this);
        var iframeHeight = iframe.outerHeight();
        var iframeElement = iframe.get(0);
        windows.on('scroll', function() {
            var windowScrollTop = windows.scrollTop();
            var iframeBottom = iframeHeight + iframeWrap.offset().top;
            //alert(iframeBottom);

            if ((windowScrollTop > iframeBottom)) {
                iframeWrap.height(iframeHeight);
                iframe.addClass('stuck');
                jQuery(".scrolldown").css({"display": "none"});
            } else {
                iframeWrap.height('auto');
                iframe.removeClass('stuck');
            }
        });
        /*Floating js End*/
    });
}

/*Floating Code for Iframe End*/

// expand/collapse all Start

var headers = $('#accordion .accordion-header');
var contentAreas = $('#accordion .ui-accordion-content ').hide()
    .first().show().end();
var expandLink = $('.accordion-expand-all');

// add the accordion functionality
headers.click(function() {
    // close all panels
    contentAreas.slideUp();
    // open the appropriate panel
    $(this).next().slideDown();
    // reset Expand all button
    expandLink.text('Expand all')
        .data('isAllOpen', false);
    // stop page scroll
    return false;
});

// hook up the expand/collapse all
expandLink.click(function(){
    var isAllOpen = !$(this).data('isAllOpen');
    console.log({isAllOpen: isAllOpen, contentAreas: contentAreas});
    contentAreas[isAllOpen? 'slideDown': 'slideUp']();
    expandLink.text(isAllOpen? 'Collapse All': 'Expand all')
        .data('isAllOpen', isAllOpen);
});


// Payment Method Accordion
$('input[name="paymentmethod"]').on('click', function () {
    var $value = $(this).attr('value');
    $('.return-departure-dts').slideUp();
    $('[data-method="' + $value + '"]').slideDown();
});

//Infinity Load
//$('ul.pagination').hide();
$(function() {
    $('.infinite-scroll').jscroll({
        autoTrigger: true,
        loadingHtml: '<div class="col-md-12">\n' +
            '                            <div class="main-loader mt-50">\n' +
            '                                <div class="spinner">\n' +
            '                                    <div class="bounce1"></div>\n' +
            '                                    <div class="bounce2"></div>\n' +
            '                                    <div class="bounce3"></div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.infinite-scroll',
        callback: function() {
            //$('ul.pagination').remove();
            $('.more-loader').show();
        },
        loadingFunction: function () {
            $('.more-loader').hide();
        },
    });
});

// Night Mode JS
(function (window, document, undefined) {
		'use strict';
		if (!('localStorage' in window)) return;
		var nightMode = localStorage.getItem('gmtNightMode');
		if (nightMode) {
			document.documentElement.className += ' night-mode';
		}
	})(window, document);


	(function (window, document, undefined) {

		'use strict';

		// Feature test
		if (!('localStorage' in window)) return;

		// Get our newly insert toggle
		var nightMode = document.querySelector('#night-mode');
		if (!nightMode) return;

		// When clicked, toggle night mode on or off
		nightMode.addEventListener('click', function (event) {
			event.preventDefault();
			document.documentElement.classList.toggle('night-mode');
			if (document.documentElement.classList.contains('night-mode')) {
				localStorage.setItem('gmtNightMode', true);
				return;
			}
			localStorage.removeItem('gmtNightMode');
		}, false);

	})(window, document);
/*!
 * Datepicker for Bootstrap v1.9.0 ()
 *
 * Licensed under the Apache License v2.0 ()
 */

!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a,b){function c(){return new Date(Date.UTC.apply(Date,arguments))}function d(){var a=new Date;return c(a.getFullYear(),a.getMonth(),a.getDate())}function e(a,b){return a.getUTCFullYear()===b.getUTCFullYear()&&a.getUTCMonth()===b.getUTCMonth()&&a.getUTCDate()===b.getUTCDate()}function f(c,d){return function(){return d!==b&&a.fn.datepicker.deprecated(d),this[c].apply(this,arguments)}}function g(a){return a&&!isNaN(a.getTime())}function h(b,c){function d(a,b){return b.toLowerCase()}var e,f=a(b).data(),g={},h=new RegExp("^"+c.toLowerCase()+"([A-Z])");c=new RegExp("^"+c.toLowerCase());for(var i in f)c.test(i)&&(e=i.replace(h,d),g[e]=f[i]);return g}function i(b){var c={};if(q[b]||(b=b.split("-")[0],q[b])){var d=q[b];return a.each(p,function(a,b){b in d&&(c[b]=d[b])}),c}}var j=function(){var b={get:function(a){return this.slice(a)[0]},contains:function(a){for(var b=a&&a.valueOf(),c=0,d=this.length;c<d;c++)if(0<=this[c].valueOf()-b&&this[c].valueOf()-b<864e5)return c;return-1},remove:function(a){this.splice(a,1)},replace:function(b){b&&(a.isArray(b)||(b=[b]),this.clear(),this.push.apply(this,b))},clear:function(){this.length=0},copy:function(){var a=new j;return a.replace(this),a}};return function(){var c=[];return c.push.apply(c,arguments),a.extend(c,b),c}}(),k=function(b,c){a.data(b,"datepicker",this),this._events=[],this._secondaryEvents=[],this._process_options(c),this.dates=new j,this.viewDate=this.o.defaultViewDate,this.focusDate=null,this.element=a(b),this.isInput=this.element.is("input"),this.inputField=this.isInput?this.element:this.element.find("input"),this.component=!!this.element.hasClass("date")&&this.element.find(".add-on, .input-group-addon, .input-group-append, .input-group-prepend, .btn"),this.component&&0===this.component.length&&(this.component=!1),this.isInline=!this.component&&this.element.is("div"),this.picker=a(r.template),this._check_template(this.o.templates.leftArrow)&&this.picker.find(".prev").html(this.o.templates.leftArrow),this._check_template(this.o.templates.rightArrow)&&this.picker.find(".next").html(this.o.templates.rightArrow),this._buildEvents(),this._attachEvents(),this.isInline?this.picker.addClass("datepicker-inline").appendTo(this.element):this.picker.addClass("datepicker-dropdown dropdown-menu"),this.o.rtl&&this.picker.addClass("datepicker-rtl"),this.o.calendarWeeks&&this.picker.find(".datepicker-days .datepicker-switch, thead .datepicker-title, tfoot .today, tfoot .clear").attr("colspan",function(a,b){return Number(b)+1}),this._process_options({startDate:this._o.startDate,endDate:this._o.endDate,daysOfWeekDisabled:this.o.daysOfWeekDisabled,daysOfWeekHighlighted:this.o.daysOfWeekHighlighted,datesDisabled:this.o.datesDisabled}),this._allow_update=!1,this.setViewMode(this.o.startView),this._allow_update=!0,this.fillDow(),this.fillMonths(),this.update(),this.isInline&&this.show()};k.prototype={constructor:k,_resolveViewName:function(b){return a.each(r.viewModes,function(c,d){if(b===c||-1!==a.inArray(b,d.names))return b=c,!1}),b},_resolveDaysOfWeek:function(b){return a.isArray(b)||(b=b.split(/[,\s]*/)),a.map(b,Number)},_check_template:function(c){try{if(c===b||""===c)return!1;if((c.match(/[<>]/g)||[]).length<=0)return!0;return a(c).length>0}catch(a){return!1}},_process_options:function(b){this._o=a.extend({},this._o,b);var e=this.o=a.extend({},this._o),f=e.language;q[f]||(f=f.split("-")[0],q[f]||(f=o.language)),e.language=f,e.startView=this._resolveViewName(e.startView),e.minViewMode=this._resolveViewName(e.minViewMode),e.maxViewMode=this._resolveViewName(e.maxViewMode),e.startView=Math.max(this.o.minViewMode,Math.min(this.o.maxViewMode,e.startView)),!0!==e.multidate&&(e.multidate=Number(e.multidate)||!1,!1!==e.multidate&&(e.multidate=Math.max(0,e.multidate))),e.multidateSeparator=String(e.multidateSeparator),e.weekStart%=7,e.weekEnd=(e.weekStart+6)%7;var g=r.parseFormat(e.format);e.startDate!==-1/0&&(e.startDate?e.startDate instanceof Date?e.startDate=this._local_to_utc(this._zero_time(e.startDate)):e.startDate=r.parseDate(e.startDate,g,e.language,e.assumeNearbyYear):e.startDate=-1/0),e.endDate!==1/0&&(e.endDate?e.endDate instanceof Date?e.endDate=this._local_to_utc(this._zero_time(e.endDate)):e.endDate=r.parseDate(e.endDate,g,e.language,e.assumeNearbyYear):e.endDate=1/0),e.daysOfWeekDisabled=this._resolveDaysOfWeek(e.daysOfWeekDisabled||[]),e.daysOfWeekHighlighted=this._resolveDaysOfWeek(e.daysOfWeekHighlighted||[]),e.datesDisabled=e.datesDisabled||[],a.isArray(e.datesDisabled)||(e.datesDisabled=e.datesDisabled.split(",")),e.datesDisabled=a.map(e.datesDisabled,function(a){return r.parseDate(a,g,e.language,e.assumeNearbyYear)});var h=String(e.orientation).toLowerCase().split(/\s+/g),i=e.orientation.toLowerCase();if(h=a.grep(h,function(a){return/^auto|left|right|top|bottom$/.test(a)}),e.orientation={x:"auto",y:"auto"},i&&"auto"!==i)if(1===h.length)switch(h[0]){case"top":case"bottom":e.orientation.y=h[0];break;case"left":case"right":e.orientation.x=h[0]}else i=a.grep(h,function(a){return/^left|right$/.test(a)}),e.orientation.x=i[0]||"auto",i=a.grep(h,function(a){return/^top|bottom$/.test(a)}),e.orientation.y=i[0]||"auto";else;if(e.defaultViewDate instanceof Date||"string"==typeof e.defaultViewDate)e.defaultViewDate=r.parseDate(e.defaultViewDate,g,e.language,e.assumeNearbyYear);else if(e.defaultViewDate){var j=e.defaultViewDate.year||(new Date).getFullYear(),k=e.defaultViewDate.month||0,l=e.defaultViewDate.day||1;e.defaultViewDate=c(j,k,l)}else e.defaultViewDate=d()},_applyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(d=b,e=a[f][1]):3===a[f].length&&(d=a[f][1],e=a[f][2]),c.on(e,d)},_unapplyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(e=b,d=a[f][1]):3===a[f].length&&(e=a[f][1],d=a[f][2]),c.off(d,e)},_buildEvents:function(){var b={keyup:a.proxy(function(b){-1===a.inArray(b.keyCode,[27,37,39,38,40,32,13,9])&&this.update()},this),keydown:a.proxy(this.keydown,this),paste:a.proxy(this.paste,this)};!0===this.o.showOnFocus&&(b.focus=a.proxy(this.show,this)),this.isInput?this._events=[[this.element,b]]:this.component&&this.inputField.length?this._events=[[this.inputField,b],[this.component,{click:a.proxy(this.show,this)}]]:this._events=[[this.element,{click:a.proxy(this.show,this),keydown:a.proxy(this.keydown,this)}]],this._events.push([this.element,"*",{blur:a.proxy(function(a){this._focused_from=a.target},this)}],[this.element,{blur:a.proxy(function(a){this._focused_from=a.target},this)}]),this.o.immediateUpdates&&this._events.push([this.element,{"changeYear changeMonth":a.proxy(function(a){this.update(a.date)},this)}]),this._secondaryEvents=[[this.picker,{click:a.proxy(this.click,this)}],[this.picker,".prev, .next",{click:a.proxy(this.navArrowsClick,this)}],[this.picker,".day:not(.disabled)",{click:a.proxy(this.dayCellClick,this)}],[a(window),{resize:a.proxy(this.place,this)}],[a(document),{"mousedown touchstart":a.proxy(function(a){this.element.is(a.target)||this.element.find(a.target).length||this.picker.is(a.target)||this.picker.find(a.target).length||this.isInline||this.hide()},this)}]]},_attachEvents:function(){this._detachEvents(),this._applyEvents(this._events)},_detachEvents:function(){this._unapplyEvents(this._events)},_attachSecondaryEvents:function(){this._detachSecondaryEvents(),this._applyEvents(this._secondaryEvents)},_detachSecondaryEvents:function(){this._unapplyEvents(this._secondaryEvents)},_trigger:function(b,c){var d=c||this.dates.get(-1),e=this._utc_to_local(d);this.element.trigger({type:b,date:e,viewMode:this.viewMode,dates:a.map(this.dates,this._utc_to_local),format:a.proxy(function(a,b){0===arguments.length?(a=this.dates.length-1,b=this.o.format):"string"==typeof a&&(b=a,a=this.dates.length-1),b=b||this.o.format;var c=this.dates.get(a);return r.formatDate(c,b,this.o.language)},this)})},show:function(){if(!(this.inputField.is(":disabled")||this.inputField.prop("readonly")&&!1===this.o.enableOnReadonly))return this.isInline||this.picker.appendTo(this.o.container),this.place(),this.picker.show(),this._attachSecondaryEvents(),this._trigger("show"),(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&this.o.disableTouchKeyboard&&a(this.element).blur(),this},hide:function(){return this.isInline||!this.picker.is(":visible")?this:(this.focusDate=null,this.picker.hide().detach(),this._detachSecondaryEvents(),this.setViewMode(this.o.startView),this.o.forceParse&&this.inputField.val()&&this.setValue(),this._trigger("hide"),this)},destroy:function(){return this.hide(),this._detachEvents(),this._detachSecondaryEvents(),this.picker.remove(),delete this.element.data().datepicker,this.isInput||delete this.element.data().date,this},paste:function(b){var c;if(b.originalEvent.clipboardData&&b.originalEvent.clipboardData.types&&-1!==a.inArray("text/plain",b.originalEvent.clipboardData.types))c=b.originalEvent.clipboardData.getData("text/plain");else{if(!window.clipboardData)return;c=window.clipboardData.getData("Text")}this.setDate(c),this.update(),b.preventDefault()},_utc_to_local:function(a){if(!a)return a;var b=new Date(a.getTime()+6e4*a.getTimezoneOffset());return b.getTimezoneOffset()!==a.getTimezoneOffset()&&(b=new Date(a.getTime()+6e4*b.getTimezoneOffset())),b},_local_to_utc:function(a){return a&&new Date(a.getTime()-6e4*a.getTimezoneOffset())},_zero_time:function(a){return a&&new Date(a.getFullYear(),a.getMonth(),a.getDate())},_zero_utc_time:function(a){return a&&c(a.getUTCFullYear(),a.getUTCMonth(),a.getUTCDate())},getDates:function(){return a.map(this.dates,this._utc_to_local)},getUTCDates:function(){return a.map(this.dates,function(a){return new Date(a)})},getDate:function(){return this._utc_to_local(this.getUTCDate())},getUTCDate:function(){var a=this.dates.get(-1);return a!==b?new Date(a):null},clearDates:function(){this.inputField.val(""),this.update(),this._trigger("changeDate"),this.o.autoclose&&this.hide()},setDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,b),this._trigger("changeDate"),this.setValue(),this},setUTCDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.setDates.apply(this,a.map(b,this._utc_to_local)),this},setDate:f("setDates"),setUTCDate:f("setUTCDates"),remove:f("destroy","Method `remove` is deprecated and will be removed in version 2.0. Use `destroy` instead"),setValue:function(){var a=this.getFormattedDate();return this.inputField.val(a),this},getFormattedDate:function(c){c===b&&(c=this.o.format);var d=this.o.language;return a.map(this.dates,function(a){return r.formatDate(a,c,d)}).join(this.o.multidateSeparator)},getStartDate:function(){return this.o.startDate},setStartDate:function(a){return this._process_options({startDate:a}),this.update(),this.updateNavArrows(),this},getEndDate:function(){return this.o.endDate},setEndDate:function(a){return this._process_options({endDate:a}),this.update(),this.updateNavArrows(),this},setDaysOfWeekDisabled:function(a){return this._process_options({daysOfWeekDisabled:a}),this.update(),this},setDaysOfWeekHighlighted:function(a){return this._process_options({daysOfWeekHighlighted:a}),this.update(),this},setDatesDisabled:function(a){return this._process_options({datesDisabled:a}),this.update(),this},place:function(){if(this.isInline)return this;var b=this.picker.outerWidth(),c=this.picker.outerHeight(),d=a(this.o.container),e=d.width(),f="body"===this.o.container?a(document).scrollTop():d.scrollTop(),g=d.offset(),h=[0];this.element.parents().each(function(){var b=a(this).css("z-index");"auto"!==b&&0!==Number(b)&&h.push(Number(b))});var i=Math.max.apply(Math,h)+this.o.zIndexOffset,j=this.component?this.component.parent().offset():this.element.offset(),k=this.component?this.component.outerHeight(!0):this.element.outerHeight(!1),l=this.component?this.component.outerWidth(!0):this.element.outerWidth(!1),m=j.left-g.left,n=j.top-g.top;"body"!==this.o.container&&(n+=f),this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"),"auto"!==this.o.orientation.x?(this.picker.addClass("datepicker-orient-"+this.o.orientation.x),"right"===this.o.orientation.x&&(m-=b-l)):j.left<0?(this.picker.addClass("datepicker-orient-left"),m-=j.left-10):m+b>e?(this.picker.addClass("datepicker-orient-right"),m+=l-b):this.o.rtl?this.picker.addClass("datepicker-orient-right"):this.picker.addClass("datepicker-orient-left");var o,p=this.o.orientation.y;if("auto"===p&&(o=-f+n-c,p=o<0?"bottom":"top"),this.picker.addClass("datepicker-orient-"+p),"top"===p?n-=c+parseInt(this.picker.css("padding-top")):n+=k,this.o.rtl){var q=e-(m+l);this.picker.css({top:n,right:q,zIndex:i})}else this.picker.css({top:n,left:m,zIndex:i});return this},_allow_update:!0,update:function(){if(!this._allow_update)return this;var b=this.dates.copy(),c=[],d=!1;return arguments.length?(a.each(arguments,a.proxy(function(a,b){b instanceof Date&&(b=this._local_to_utc(b)),c.push(b)},this)),d=!0):(c=this.isInput?this.element.val():this.element.data("date")||this.inputField.val(),c=c&&this.o.multidate?c.split(this.o.multidateSeparator):[c],delete this.element.data().date),c=a.map(c,a.proxy(function(a){return r.parseDate(a,this.o.format,this.o.language,this.o.assumeNearbyYear)},this)),c=a.grep(c,a.proxy(function(a){return!this.dateWithinRange(a)||!a},this),!0),this.dates.replace(c),this.o.updateViewDate&&(this.dates.length?this.viewDate=new Date(this.dates.get(-1)):this.viewDate<this.o.startDate?this.viewDate=new Date(this.o.startDate):this.viewDate>this.o.endDate?this.viewDate=new Date(this.o.endDate):this.viewDate=this.o.defaultViewDate),d?(this.setValue(),this.element.change()):this.dates.length&&String(b)!==String(this.dates)&&d&&(this._trigger("changeDate"),this.element.change()),!this.dates.length&&b.length&&(this._trigger("clearDate"),this.element.change()),this.fill(),this},fillDow:function(){if(this.o.showWeekDays){var b=this.o.weekStart,c="<tr>";for(this.o.calendarWeeks&&(c+='<th class="cw">&#160;</th>');b<this.o.weekStart+7;)c+='<th class="dow',-1!==a.inArray(b,this.o.daysOfWeekDisabled)&&(c+=" disabled"),c+='">'+q[this.o.language].daysMin[b++%7]+"</th>";c+="</tr>",this.picker.find(".datepicker-days thead").append(c)}},fillMonths:function(){for(var a,b=this._utc_to_local(this.viewDate),c="",d=0;d<12;d++)a=b&&b.getMonth()===d?" focused":"",c+='<span class="month'+a+'">'+q[this.o.language].monthsShort[d]+"</span>";this.picker.find(".datepicker-months td").html(c)},setRange:function(b){b&&b.length?this.range=a.map(b,function(a){return a.valueOf()}):delete this.range,this.fill()},getClassNames:function(b){var c=[],f=this.viewDate.getUTCFullYear(),g=this.viewDate.getUTCMonth(),h=d();return b.getUTCFullYear()<f||b.getUTCFullYear()===f&&b.getUTCMonth()<g?c.push("old"):(b.getUTCFullYear()>f||b.getUTCFullYear()===f&&b.getUTCMonth()>g)&&c.push("new"),this.focusDate&&b.valueOf()===this.focusDate.valueOf()&&c.push("focused"),this.o.todayHighlight&&e(b,h)&&c.push("today"),-1!==this.dates.contains(b)&&c.push("active"),this.dateWithinRange(b)||c.push("disabled"),this.dateIsDisabled(b)&&c.push("disabled","disabled-date"),-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekHighlighted)&&c.push("highlighted"),this.range&&(b>this.range[0]&&b<this.range[this.range.length-1]&&c.push("range"),-1!==a.inArray(b.valueOf(),this.range)&&c.push("selected"),b.valueOf()===this.range[0]&&c.push("range-start"),b.valueOf()===this.range[this.range.length-1]&&c.push("range-end")),c},_fill_yearsView:function(c,d,e,f,g,h,i){for(var j,k,l,m="",n=e/10,o=this.picker.find(c),p=Math.floor(f/e)*e,q=p+9*n,r=Math.floor(this.viewDate.getFullYear()/n)*n,s=a.map(this.dates,function(a){return Math.floor(a.getUTCFullYear()/n)*n}),t=p-n;t<=q+n;t+=n)j=[d],k=null,t===p-n?j.push("old"):t===q+n&&j.push("new"),-1!==a.inArray(t,s)&&j.push("active"),(t<g||t>h)&&j.push("disabled"),t===r&&j.push("focused"),i!==a.noop&&(l=i(new Date(t,0,1)),l===b?l={}:"boolean"==typeof l?l={enabled:l}:"string"==typeof l&&(l={classes:l}),!1===l.enabled&&j.push("disabled"),l.classes&&(j=j.concat(l.classes.split(/\s+/))),l.tooltip&&(k=l.tooltip)),m+='<span class="'+j.join(" ")+'"'+(k?' title="'+k+'"':"")+">"+t+"</span>";o.find(".datepicker-switch").text(p+"-"+q),o.find("td").html(m)},fill:function(){var e,f,g=new Date(this.viewDate),h=g.getUTCFullYear(),i=g.getUTCMonth(),j=this.o.startDate!==-1/0?this.o.startDate.getUTCFullYear():-1/0,k=this.o.startDate!==-1/0?this.o.startDate.getUTCMonth():-1/0,l=this.o.endDate!==1/0?this.o.endDate.getUTCFullYear():1/0,m=this.o.endDate!==1/0?this.o.endDate.getUTCMonth():1/0,n=q[this.o.language].today||q.en.today||"",o=q[this.o.language].clear||q.en.clear||"",p=q[this.o.language].titleFormat||q.en.titleFormat,s=d(),t=(!0===this.o.todayBtn||"linked"===this.o.todayBtn)&&s>=this.o.startDate&&s<=this.o.endDate&&!this.weekOfDateIsDisabled(s);if(!isNaN(h)&&!isNaN(i)){this.picker.find(".datepicker-days .datepicker-switch").text(r.formatDate(g,p,this.o.language)),this.picker.find("tfoot .today").text(n).css("display",t?"table-cell":"none"),this.picker.find("tfoot .clear").text(o).css("display",!0===this.o.clearBtn?"table-cell":"none"),this.picker.find("thead .datepicker-title").text(this.o.title).css("display","string"==typeof this.o.title&&""!==this.o.title?"table-cell":"none"),this.updateNavArrows(),this.fillMonths();var u=c(h,i,0),v=u.getUTCDate();u.setUTCDate(v-(u.getUTCDay()-this.o.weekStart+7)%7);var w=new Date(u);u.getUTCFullYear()<100&&w.setUTCFullYear(u.getUTCFullYear()),w.setUTCDate(w.getUTCDate()+42),w=w.valueOf();for(var x,y,z=[];u.valueOf()<w;){if((x=u.getUTCDay())===this.o.weekStart&&(z.push("<tr>"),this.o.calendarWeeks)){var A=new Date(+u+(this.o.weekStart-x-7)%7*864e5),B=new Date(Number(A)+(11-A.getUTCDay())%7*864e5),C=new Date(Number(C=c(B.getUTCFullYear(),0,1))+(11-C.getUTCDay())%7*864e5),D=(B-C)/864e5/7+1;z.push('<td class="cw">'+D+"</td>")}y=this.getClassNames(u),y.push("day");var E=u.getUTCDate();this.o.beforeShowDay!==a.noop&&(f=this.o.beforeShowDay(this._utc_to_local(u)),f===b?f={}:"boolean"==typeof f?f={enabled:f}:"string"==typeof f&&(f={classes:f}),!1===f.enabled&&y.push("disabled"),f.classes&&(y=y.concat(f.classes.split(/\s+/))),f.tooltip&&(e=f.tooltip),f.content&&(E=f.content)),y=a.isFunction(a.uniqueSort)?a.uniqueSort(y):a.unique(y),z.push('<td class="'+y.join(" ")+'"'+(e?' title="'+e+'"':"")+' data-date="'+u.getTime().toString()+'">'+E+"</td>"),e=null,x===this.o.weekEnd&&z.push("</tr>"),u.setUTCDate(u.getUTCDate()+1)}this.picker.find(".datepicker-days tbody").html(z.join(""));var F=q[this.o.language].monthsTitle||q.en.monthsTitle||"Months",G=this.picker.find(".datepicker-months").find(".datepicker-switch").text(this.o.maxViewMode<2?F:h).end().find("tbody span").removeClass("active");if(a.each(this.dates,function(a,b){b.getUTCFullYear()===h&&G.eq(b.getUTCMonth()).addClass("active")}),(h<j||h>l)&&G.addClass("disabled"),h===j&&G.slice(0,k).addClass("disabled"),h===l&&G.slice(m+1).addClass("disabled"),this.o.beforeShowMonth!==a.noop){var H=this;a.each(G,function(c,d){var e=new Date(h,c,1),f=H.o.beforeShowMonth(e);f===b?f={}:"boolean"==typeof f?f={enabled:f}:"string"==typeof f&&(f={classes:f}),!1!==f.enabled||a(d).hasClass("disabled")||a(d).addClass("disabled"),f.classes&&a(d).addClass(f.classes),f.tooltip&&a(d).prop("title",f.tooltip)})}this._fill_yearsView(".datepicker-years","year",10,h,j,l,this.o.beforeShowYear),this._fill_yearsView(".datepicker-decades","decade",100,h,j,l,this.o.beforeShowDecade),this._fill_yearsView(".datepicker-centuries","century",1e3,h,j,l,this.o.beforeShowCentury)}},updateNavArrows:function(){if(this._allow_update){var a,b,c=new Date(this.viewDate),d=c.getUTCFullYear(),e=c.getUTCMonth(),f=this.o.startDate!==-1/0?this.o.startDate.getUTCFullYear():-1/0,g=this.o.startDate!==-1/0?this.o.startDate.getUTCMonth():-1/0,h=this.o.endDate!==1/0?this.o.endDate.getUTCFullYear():1/0,i=this.o.endDate!==1/0?this.o.endDate.getUTCMonth():1/0,j=1;switch(this.viewMode){case 4:j*=10;case 3:j*=10;case 2:j*=10;case 1:a=Math.floor(d/j)*j<=f,b=Math.floor(d/j)*j+j>h;break;case 0:a=d<=f&&e<=g,b=d>=h&&e>=i}this.picker.find(".prev").toggleClass("disabled",a),this.picker.find(".next").toggleClass("disabled",b)}},click:function(b){b.preventDefault(),b.stopPropagation();var e,f,g,h;e=a(b.target),e.hasClass("datepicker-switch")&&this.viewMode!==this.o.maxViewMode&&this.setViewMode(this.viewMode+1),e.hasClass("today")&&!e.hasClass("day")&&(this.setViewMode(0),this._setDate(d(),"linked"===this.o.todayBtn?null:"view")),e.hasClass("clear")&&this.clearDates(),e.hasClass("disabled")||(e.hasClass("month")||e.hasClass("year")||e.hasClass("decade")||e.hasClass("century"))&&(this.viewDate.setUTCDate(1),f=1,1===this.viewMode?(h=e.parent().find("span").index(e),g=this.viewDate.getUTCFullYear(),this.viewDate.setUTCMonth(h)):(h=0,g=Number(e.text()),this.viewDate.setUTCFullYear(g)),this._trigger(r.viewModes[this.viewMode-1].e,this.viewDate),this.viewMode===this.o.minViewMode?this._setDate(c(g,h,f)):(this.setViewMode(this.viewMode-1),this.fill())),this.picker.is(":visible")&&this._focused_from&&this._focused_from.focus(),delete this._focused_from},dayCellClick:function(b){var c=a(b.currentTarget),d=c.data("date"),e=new Date(d);this.o.updateViewDate&&(e.getUTCFullYear()!==this.viewDate.getUTCFullYear()&&this._trigger("changeYear",this.viewDate),e.getUTCMonth()!==this.viewDate.getUTCMonth()&&this._trigger("changeMonth",this.viewDate)),this._setDate(e)},navArrowsClick:function(b){var c=a(b.currentTarget),d=c.hasClass("prev")?-1:1;0!==this.viewMode&&(d*=12*r.viewModes[this.viewMode].navStep),this.viewDate=this.moveMonth(this.viewDate,d),this._trigger(r.viewModes[this.viewMode].e,this.viewDate),this.fill()},_toggle_multidate:function(a){var b=this.dates.contains(a);if(a||this.dates.clear(),-1!==b?(!0===this.o.multidate||this.o.multidate>1||this.o.toggleActive)&&this.dates.remove(b):!1===this.o.multidate?(this.dates.clear(),this.dates.push(a)):this.dates.push(a),"number"==typeof this.o.multidate)for(;this.dates.length>this.o.multidate;)this.dates.remove(0)},_setDate:function(a,b){b&&"date"!==b||this._toggle_multidate(a&&new Date(a)),(!b&&this.o.updateViewDate||"view"===b)&&(this.viewDate=a&&new Date(a)),this.fill(),this.setValue(),b&&"view"===b||this._trigger("changeDate"),this.inputField.trigger("change"),!this.o.autoclose||b&&"date"!==b||this.hide()},moveDay:function(a,b){var c=new Date(a);return c.setUTCDate(a.getUTCDate()+b),c},moveWeek:function(a,b){return this.moveDay(a,7*b)},moveMonth:function(a,b){if(!g(a))return this.o.defaultViewDate;if(!b)return a;var c,d,e=new Date(a.valueOf()),f=e.getUTCDate(),h=e.getUTCMonth(),i=Math.abs(b);if(b=b>0?1:-1,1===i)d=-1===b?function(){return e.getUTCMonth()===h}:function(){return e.getUTCMonth()!==c},c=h+b,e.setUTCMonth(c),c=(c+12)%12;else{for(var j=0;j<i;j++)e=this.moveMonth(e,b);c=e.getUTCMonth(),e.setUTCDate(f),d=function(){return c!==e.getUTCMonth()}}for(;d();)e.setUTCDate(--f),e.setUTCMonth(c);return e},moveYear:function(a,b){return this.moveMonth(a,12*b)},moveAvailableDate:function(a,b,c){do{if(a=this[c](a,b),!this.dateWithinRange(a))return!1;c="moveDay"}while(this.dateIsDisabled(a));return a},weekOfDateIsDisabled:function(b){return-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekDisabled)},dateIsDisabled:function(b){return this.weekOfDateIsDisabled(b)||a.grep(this.o.datesDisabled,function(a){return e(b,a)}).length>0},dateWithinRange:function(a){return a>=this.o.startDate&&a<=this.o.endDate},keydown:function(a){if(!this.picker.is(":visible"))return void(40!==a.keyCode&&27!==a.keyCode||(this.show(),a.stopPropagation()));var b,c,d=!1,e=this.focusDate||this.viewDate;switch(a.keyCode){case 27:this.focusDate?(this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill()):this.hide(),a.preventDefault(),a.stopPropagation();break;case 37:case 38:case 39:case 40:if(!this.o.keyboardNavigation||7===this.o.daysOfWeekDisabled.length)break;b=37===a.keyCode||38===a.keyCode?-1:1,0===this.viewMode?a.ctrlKey?(c=this.moveAvailableDate(e,b,"moveYear"))&&this._trigger("changeYear",this.viewDate):a.shiftKey?(c=this.moveAvailableDate(e,b,"moveMonth"))&&this._trigger("changeMonth",this.viewDate):37===a.keyCode||39===a.keyCode?c=this.moveAvailableDate(e,b,"moveDay"):this.weekOfDateIsDisabled(e)||(c=this.moveAvailableDate(e,b,"moveWeek")):1===this.viewMode?(38!==a.keyCode&&40!==a.keyCode||(b*=4),c=this.moveAvailableDate(e,b,"moveMonth")):2===this.viewMode&&(38!==a.keyCode&&40!==a.keyCode||(b*=4),c=this.moveAvailableDate(e,b,"moveYear")),c&&(this.focusDate=this.viewDate=c,this.setValue(),this.fill(),a.preventDefault());break;case 13:if(!this.o.forceParse)break;e=this.focusDate||this.dates.get(-1)||this.viewDate,this.o.keyboardNavigation&&(this._toggle_multidate(e),d=!0),this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.setValue(),this.fill(),this.picker.is(":visible")&&(a.preventDefault(),a.stopPropagation(),this.o.autoclose&&this.hide());break;case 9:this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill(),this.hide()}d&&(this.dates.length?this._trigger("changeDate"):this._trigger("clearDate"),this.inputField.trigger("change"))},setViewMode:function(a){this.viewMode=a,this.picker.children("div").hide().filter(".datepicker-"+r.viewModes[this.viewMode].clsName).show(),this.updateNavArrows(),this._trigger("changeViewMode",new Date(this.viewDate))}};var l=function(b,c){a.data(b,"datepicker",this),this.element=a(b),this.inputs=a.map(c.inputs,function(a){return a.jquery?a[0]:a}),delete c.inputs,this.keepEmptyValues=c.keepEmptyValues,delete c.keepEmptyValues,n.call(a(this.inputs),c).on("changeDate",a.proxy(this.dateUpdated,this)),this.pickers=a.map(this.inputs,function(b){return a.data(b,"datepicker")}),this.updateDates()};l.prototype={updateDates:function(){this.dates=a.map(this.pickers,function(a){return a.getUTCDate()}),this.updateRanges()},updateRanges:function(){var b=a.map(this.dates,function(a){return a.valueOf()});a.each(this.pickers,function(a,c){c.setRange(b)})},clearDates:function(){a.each(this.pickers,function(a,b){b.clearDates()})},dateUpdated:function(c){if(!this.updating){this.updating=!0;var d=a.data(c.target,"datepicker");if(d!==b){var e=d.getUTCDate(),f=this.keepEmptyValues,g=a.inArray(c.target,this.inputs),h=g-1,i=g+1,j=this.inputs.length;if(-1!==g){if(a.each(this.pickers,function(a,b){b.getUTCDate()||b!==d&&f||b.setUTCDate(e)}),e<this.dates[h])for(;h>=0&&e<this.dates[h];)this.pickers[h--].setUTCDate(e);else if(e>this.dates[i])for(;i<j&&e>this.dates[i];)this.pickers[i++].setUTCDate(e);this.updateDates(),delete this.updating}}}},destroy:function(){a.map(this.pickers,function(a){a.destroy()}),a(this.inputs).off("changeDate",this.dateUpdated),delete this.element.data().datepicker},remove:f("destroy","Method `remove` is deprecated and will be removed in version 2.0. Use `destroy` instead")};var m=a.fn.datepicker,n=function(c){var d=Array.apply(null,arguments);d.shift();var e;if(this.each(function(){var b=a(this),f=b.data("datepicker"),g="object"==typeof c&&c;if(!f){var j=h(this,"date"),m=a.extend({},o,j,g),n=i(m.language),p=a.extend({},o,n,j,g);b.hasClass("input-daterange")||p.inputs?(a.extend(p,{inputs:p.inputs||b.find("input").toArray()}),f=new l(this,p)):f=new k(this,p),b.data("datepicker",f)}"string"==typeof c&&"function"==typeof f[c]&&(e=f[c].apply(f,d))}),e===b||e instanceof k||e instanceof l)return this;if(this.length>1)throw new Error("Using only allowed for the collection of a single element ("+c+" function)");return e};a.fn.datepicker=n;var o=a.fn.datepicker.defaults={assumeNearbyYear:!1,autoclose:!1,beforeShowDay:a.noop,beforeShowMonth:a.noop,beforeShowYear:a.noop,beforeShowDecade:a.noop,beforeShowCentury:a.noop,calendarWeeks:!1,clearBtn:!1,toggleActive:!1,daysOfWeekDisabled:[],daysOfWeekHighlighted:[],datesDisabled:[],endDate:1/0,forceParse:!0,format:"mm/dd/yyyy",keepEmptyValues:!1,keyboardNavigation:!0,language:"en",minViewMode:0,maxViewMode:4,multidate:!1,multidateSeparator:",",orientation:"auto",rtl:!1,startDate:-1/0,startView:0,todayBtn:!1,todayHighlight:!1,updateViewDate:!0,weekStart:0,disableTouchKeyboard:!1,enableOnReadonly:!0,showOnFocus:!0,zIndexOffset:10,container:"body",immediateUpdates:!1,title:"",templates:{leftArrow:"&#x00AB;",rightArrow:"&#x00BB;"},showWeekDays:!0},p=a.fn.datepicker.locale_opts=["format","rtl","weekStart"];a.fn.datepicker.Constructor=k;var q=a.fn.datepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",clear:"Clear",titleFormat:"MM yyyy"}},r={viewModes:[{names:["days","month"],clsName:"days",e:"changeMonth"},{names:["months","year"],clsName:"months",e:"changeYear",navStep:1},{names:["years","decade"],clsName:"years",e:"changeDecade",navStep:10},{names:["decades","century"],clsName:"decades",e:"changeCentury",navStep:100},{names:["centuries","millennium"],clsName:"centuries",e:"changeMillennium",navStep:1e3}],validParts:/dd?|DD?|mm?|MM?|yy(?:yy)?/g,nonpunctuation:/[^ -\/:-@\u5e74\u6708\u65e5\[-`{-~\t\n\r]+/g,parseFormat:function(a){if("function"==typeof a.toValue&&"function"==typeof a.toDisplay)return a;var b=a.replace(this.validParts,"\0").split("\0"),c=a.match(this.validParts);if(!b||!b.length||!c||0===c.length)throw new Error("Invalid date format.");return{separators:b,parts:c}},parseDate:function(c,e,f,g){function h(a,b){return!0===b&&(b=10),a<100&&(a+=2e3)>(new Date).getFullYear()+b&&(a-=100),a}function i(){var a=this.slice(0,j[n].length),b=j[n].slice(0,a.length);return a.toLowerCase()===b.toLowerCase()}if(!c)return b;if(c instanceof Date)return c;if("string"==typeof e&&(e=r.parseFormat(e)),e.toValue)return e.toValue(c,e,f);var j,l,m,n,o,p={d:"moveDay",m:"moveMonth",w:"moveWeek",y:"moveYear"},s={yesterday:"-1d",today:"+0d",tomorrow:"+1d"};if(c in s&&(c=s[c]),/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/i.test(c)){for(j=c.match(/([\-+]\d+)([dmwy])/gi),c=new Date,n=0;n<j.length;n++)l=j[n].match(/([\-+]\d+)([dmwy])/i),m=Number(l[1]),o=p[l[2].toLowerCase()],c=k.prototype[o](c,m);return k.prototype._zero_utc_time(c)}j=c&&c.match(this.nonpunctuation)||[];var t,u,v={},w=["yyyy","yy","M","MM","m","mm","d","dd"],x={yyyy:function(a,b){return a.setUTCFullYear(g?h(b,g):b)},m:function(a,b){if(isNaN(a))return a;for(b-=1;b<0;)b+=12;for(b%=12,a.setUTCMonth(b);a.getUTCMonth()!==b;)a.setUTCDate(a.getUTCDate()-1);return a},d:function(a,b){return a.setUTCDate(b)}};x.yy=x.yyyy,x.M=x.MM=x.mm=x.m,x.dd=x.d,c=d();var y=e.parts.slice();if(j.length!==y.length&&(y=a(y).filter(function(b,c){return-1!==a.inArray(c,w)}).toArray()),j.length===y.length){var z;for(n=0,z=y.length;n<z;n++){if(t=parseInt(j[n],10),l=y[n],isNaN(t))switch(l){case"MM":u=a(q[f].months).filter(i),t=a.inArray(u[0],q[f].months)+1;break;case"M":u=a(q[f].monthsShort).filter(i),t=a.inArray(u[0],q[f].monthsShort)+1}v[l]=t}var A,B;for(n=0;n<w.length;n++)(B=w[n])in v&&!isNaN(v[B])&&(A=new Date(c),x[B](A,v[B]),isNaN(A)||(c=A))}return c},formatDate:function(b,c,d){if(!b)return"";if("string"==typeof c&&(c=r.parseFormat(c)),c.toDisplay)return c.toDisplay(b,c,d);var e={d:b.getUTCDate(),D:q[d].daysShort[b.getUTCDay()],DD:q[d].days[b.getUTCDay()],m:b.getUTCMonth()+1,M:q[d].monthsShort[b.getUTCMonth()],MM:q[d].months[b.getUTCMonth()],yy:b.getUTCFullYear().toString().substring(2),yyyy:b.getUTCFullYear()};e.dd=(e.d<10?"0":"")+e.d,e.mm=(e.m<10?"0":"")+e.m,b=[];for(var f=a.extend([],c.separators),g=0,h=c.parts.length;g<=h;g++)f.length&&b.push(f.shift()),b.push(e[c.parts[g]]);return b.join("")},
headTemplate:'<thead><tr><th colspan="7" class="datepicker-title"></th></tr><tr><th class="prev">'+o.templates.leftArrow+'</th><th colspan="5" class="datepicker-switch"></th><th class="next">'+o.templates.rightArrow+"</th></tr></thead>",contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};r.template='<div class="datepicker"><div class="datepicker-days"><table class="table-condensed">'+r.headTemplate+"<tbody></tbody>"+r.footTemplate+'</table></div><div class="datepicker-months"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-years"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-decades"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-centuries"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+"</table></div></div>",a.fn.datepicker.DPGlobal=r,a.fn.datepicker.noConflict=function(){return a.fn.datepicker=m,this},a.fn.datepicker.version="1.9.0",a.fn.datepicker.deprecated=function(a){var b=window.console;b&&b.warn&&b.warn("DEPRECATED: "+a)},a(document).on("focus.datepicker.data-api click.datepicker.data-api",'[data-provide="datepicker"]',function(b){var c=a(this);c.data("datepicker")||(b.preventDefault(),n.call(c,"show"))}),a(function(){n.call(a('[data-provide="datepicker-inline"]'))})});

!function(a){a.fn.datepicker.dates.vi={days:["Chủ nhật","Thứ hai","Thứ ba","Thứ tư","Thứ năm","Thứ sáu","Thứ bảy"],daysShort:["CN","Thứ 2","Thứ 3","Thứ 4","Thứ 5","Thứ 6","Thứ 7"],daysMin:["CN","T2","T3","T4","T5","T6","T7"],months:["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6","Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],monthsShort:["Th1","Th2","Th3","Th4","Th5","Th6","Th7","Th8","Th9","Th10","Th11","Th12"],today:"Hôm nay",clear:"Xóa",format:"dd/mm/yyyy"}}(jQuery);
$(document).ready(function(){

    $(".datepicker").datepicker({
        format: "dd/mm/yyyy",
        language: "vi"
    });

    $(".datepicker-month").datepicker({
        format: "mm/yyyy",
        language: "vi"
    });

    $(".datepicker-year").datepicker({
        format: "yyyy",
        language: "vi"
    });
});
$(document).ready(function() {
    $('.select2').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('.select2-default').select2({
        dropdownAutoWidth : true,
        width: '100%',
    });

    $('.select2-json').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: 'auto',
        ajax: {
            url: $(this).data('url'),
            dataType: 'json'
        }
    });
    initSelect2();
    $('body').on('change', '.load-unit',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });
    $('body').on('change', '.load-area',function () {
        var loadchild = $(this).data('loadchild');
        var parent_id = $(this).val();

        if (loadchild) {
            $("#"+loadchild).data('parent', parent_id);
            $("#"+loadchild).trigger('change');
        }
    });
    $('body').on('change', '.load-training-program',function () {
        var loadsubject = $(this).data('loadsubject');
        var parent_id = $(this).val();

        if (loadsubject) {
            $("#"+loadsubject).data('training-program', parent_id);
            $("#"+loadsubject).trigger('change');
        }
    });

    $('.load-coure-bc05').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    course_type:$('select[name=course_type]').val()
                };
                return query;
            }
        }
    });

    $('.load-course-bc07').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: 2,
                };
                return query;
            }
        }
    });

    $('.load-course-bc08').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: 1,
                };
                return query;
            }
        }
    });

    $('.load-course-bc09').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: $('select[name=type]').val(),
                };
                return query;
            }
        }
    });

    $('select[name=teacher]').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'teacher',
                };
                return query;
            }
        }
    });
    $('.load-course-bc43').select2({
        dropdownAutoWidth : true,
        allowClear: true,
        width: '100%',
        ajax: {
            method: 'POST',
            url: base_url + '/admin-cp/report/filter',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    type: 'course',
                    from_date: $('input[name=from_date]').val(),
                    to_date: $('input[name=to_date]').val(),
                    course_type: $('select[name=course_type]').val(),
                };
                return query;
            }
        }
    });


});
function initSelect2() {
    load_title_select2();
    load_title_rank_select2();
    load_user_select2();
    load_area_select2();
    load_subject_select2();
    load_level_subject_select2();
    load_unit_select2();
    load_status_profile_select2();
    load_position_select2();
    load_category_new_select2();
    load_category_new_outside_select2();
    load_teacher_select2();
    load_quiz_select2();
    load_quiz_online_select2();
    load_part_quiz_online_select2();
    load_training_form_select2();
    load_quiz_type_select2();
    load_training_object_select2();
    load_training_partner_select2();
    load_teacher_type_select2();
    load_training_type_select2();
    load_group_permission_select2();
    load_training_program_select2();
    load_table_select2();
}
function load_title_select2() {
    $('.load-title').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTitle',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    position_id: $(this).data('position_id'),
                };

                return query;
            }
        }
    });
}
function load_title_rank_select2() {
    $('.load-title-rank').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTitleRank',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_user_select2() {
    $('.load-user').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadUser',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
    $('.load-all-user').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadAllUser',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_area_select2() {
    $('.load-area').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadAreaByLevel',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                };

                return query;
            }
        }
    });
}
function load_subject_select2() {
    $('.load-subject').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadSubject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program'),
                    level_subject_id: $(this).data('level-subject'),
                    course_type: $(this).data('course_type'),
                };

                return query;
            }
        }
    });
}
function load_level_subject_select2(){
    $('.load-level-subject').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadLevelSubject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program')
                };

                return query;
            }
        }
    });
}
function load_unit_select2(){
    $('.load-unit').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadUnitByLevel',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {

                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    level: $(this).data('level'),
                    parent_id: $(this).data('parent'),
                };

                return query;
            }
        }
    });
}
function load_status_profile_select2(){
    $('.load-status-profile').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadStatusProfile',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_position_select2() {
    $('.load-position').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadPosition',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_category_new_select2() {
    $('.load-category-new').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadCategoryNew',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_category_new_outside_select2() {
    $('.load-category-new-outside').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadCategoryNewOutside',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}

function load_teacher_select2() {
    $('.load-teacher').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTeacher',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_quiz_select2() {
    $('.load-quiz').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizCourse',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    course_id:$(this).data('course'),
                };
                return query;
            }
        }
    });
}
function load_quiz_online_select2() {
    $('.load-quiz-online').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizCourseOnline',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    course_id:$(this).data('course'),
                };
                return query;
            }
        }
    });
}
function load_part_quiz_online_select2() {
    $('.load-part-quiz-online').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadPartQuizCourseOnline',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    quiz_id:$(this).data('quiz_id'),
                };
                return query;
            }
        }
    });
}
function load_training_form_select2(){
    $('.load-training-form').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingForm',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_quiz_type_select2() {
    $('.load-quiz-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadQuizType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                    training_program: $(this).data('training-program')
                };

                return query;
            }
        }
    });
}
function load_training_object_select2(){
    $('.load-training-object').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingObject',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_partner_select2() {
    $('.load-training-partner').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingPartner',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_teacher_type_select2(){
    $('.load-teacher-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTeacherType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_type_select2(){
    $('.load-training-type').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingType',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_group_permission_select2(){
    $('.load-group-permission').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadGroupPermission',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_training_program_select2(){
    $('.load-training-program').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTrainingProgram',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };

                return query;
            }
        }
    });
}
function load_table_select2(){
    $('.load-table').select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
        ajax: {
            method: 'GET',
            url: base_url + '/load-ajax/loadTable',
            dataType: 'json',
            quietMillis: 1000,
            delay: 1000,
            data: function (params) {
                var query = {
                    search: $.trim(params.term),
                    page: params.page,
                };
                return query;
            }
        }
    });
}

$(document).ready(function () {
    $('#user-unit-top').on('change', function (e) {
        e.preventDefault();
        let url = $(this).data('url');
        let data = {'unit-select':$(this).val()};
        $.ajax({
            type: 'post',
            url,
            dataType: 'json',
            data
        }).done(function(response) {
            location.href = response.redirect;
            return false;
        }).fail(function(response) {
            show_message('DATA ERROR', 'error');
            return false;
        });
    });
    $('.approved').on('click',function (e) {
        e.preventDefault();
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var status = $(this).data('status');
        let $this = $(this);
        let model = $this.data('model');
        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất dòng dữ liệu', 'error');
            return false;
        }
        if (!status){
            load_modal_approve_note(model,$this);

            return  false;
        }
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/approve',
            type: 'post',
            // dataType: 'json',
            data: {
                ids: ids,
                status: status,
                model: model
            }
        }).done(function(result) {
            console.log(result);
            if (result.status=='error'){
                show_message(result.message, 'error');
            }else
                $this.closest('.wrapper').find('.bootstrap-table').bootstrapTable('refresh');
            show_message(result.message, result.status);
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            return false;
        }).fail(function(result) {
            show_message('Lỗi dữ liệu', 'error');
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            return false;
        });
    });
    $(document).on('click','#update-note-approved',function () {
        var model = $(this).data('model');
        var table = $('input[name="table"]').val();
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        var note = $('#txta-note-approved').val();
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/approve',
            type: 'post',
            data: {
                ids: ids,
                status: 0,
                note:note,
                model:model
            }
        }).done(function(result) {
            if (result.status=='error'){
                show_message(result.message, 'error');
            }else{
                $('body').find('.bootstrap-table').bootstrapTable('refresh');
                $('.modal-note-approved').modal('hide');
            }
            show_message('Đã từ chối', 'success');
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            return false;
        }).fail(function(result) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
    $(document).on('click','.load-modal-approved-step',function () {
        var $id = $(this).data('id');
        let model = $(this).data('model');
        let item = $(this);
        let text = item.html();
        item.html('<i class ="fa fa-spinner fa-spin"></i>');
        item.find('i').prop("disabled", true);
        $.ajax({
            type: 'POST',
            url: base_url +'/admin-cp/approve/modal-step-approved',
            dataType: 'html',
        }).done(function(data) {
            $("#app-modal").html(data);
            $('.table-approved-step').bootstrapTable("destroy");
            var tableApprovedStep = new LoadBootstrapTable({
                table: '.table-approved-step',
                url: base_url+'/admin-cp/approve/get-approved-step?model='+model+'&model_id='+$id,
            });
            $('#modal-approved-step').modal();
            item.html(text);
        }).fail(function(data) {
            item.html(text);
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
});


/*!
 * dist/inputmask
 * https://github.com/RobinHerbots/Inputmask
 * Copyright (c) 2010 - 2021 Robin Herbots
 * Licensed under the MIT license
 * Version: 5.0.7-beta.0
 */
!function(e, t) {
    if ("object" == typeof exports && "object" == typeof module) module.exports = t(); else if ("function" == typeof define && define.amd) define([], t); else {
        var a = t();
        for (var i in a) ("object" == typeof exports ? exports : e)[i] = a[i];
    }
}(this, (function() {
    return function() {
        "use strict";
        var e = {
            4528: function(e) {
                e.exports = JSON.parse('{"BACKSPACE":8,"BACKSPACE_SAFARI":127,"DELETE":46,"DOWN":40,"END":35,"ENTER":13,"ESCAPE":27,"HOME":36,"INSERT":45,"LEFT":37,"PAGE_DOWN":34,"PAGE_UP":33,"RIGHT":39,"SPACE":32,"TAB":9,"UP":38,"X":88,"Z":90,"CONTROL":17,"PAUSE/BREAK":19,"WINDOWS_LEFT":91,"WINDOWS_RIGHT":92,"KEY_229":229}');
            },
            8741: function(e, t) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0;
                var a = !("undefined" == typeof window || !window.document || !window.document.createElement);
                t.default = a;
            },
            3976: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0;
                var i, n = (i = a(4528)) && i.__esModule ? i : {
                    default: i
                };
                var r = {
                    _maxTestPos: 500,
                    placeholder: "_",
                    optionalmarker: [ "[", "]" ],
                    quantifiermarker: [ "{", "}" ],
                    groupmarker: [ "(", ")" ],
                    alternatormarker: "|",
                    escapeChar: "\\",
                    mask: null,
                    regex: null,
                    oncomplete: function() {},
                    onincomplete: function() {},
                    oncleared: function() {},
                    repeat: 0,
                    greedy: !1,
                    autoUnmask: !1,
                    removeMaskOnSubmit: !1,
                    clearMaskOnLostFocus: !0,
                    insertMode: !0,
                    insertModeVisual: !0,
                    clearIncomplete: !1,
                    alias: null,
                    onKeyDown: function() {},
                    onBeforeMask: null,
                    onBeforePaste: function(e, t) {
                        return "function" == typeof t.onBeforeMask ? t.onBeforeMask.call(this, e, t) : e;
                    },
                    onBeforeWrite: null,
                    onUnMask: null,
                    showMaskOnFocus: !0,
                    showMaskOnHover: !0,
                    onKeyValidation: function() {},
                    skipOptionalPartCharacter: " ",
                    numericInput: !1,
                    rightAlign: !1,
                    undoOnEscape: !0,
                    radixPoint: "",
                    _radixDance: !1,
                    groupSeparator: "",
                    keepStatic: null,
                    positionCaretOnTab: !0,
                    tabThrough: !1,
                    supportsInputType: [ "text", "tel", "url", "password", "search" ],
                    ignorables: [ n.default.BACKSPACE, n.default.TAB, n.default["PAUSE/BREAK"], n.default.ESCAPE, n.default.PAGE_UP, n.default.PAGE_DOWN, n.default.END, n.default.HOME, n.default.LEFT, n.default.UP, n.default.RIGHT, n.default.DOWN, n.default.INSERT, n.default.DELETE, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 0, 229 ],
                    isComplete: null,
                    preValidation: null,
                    postValidation: null,
                    staticDefinitionSymbol: void 0,
                    jitMasking: !1,
                    nullable: !0,
                    inputEventOnly: !1,
                    noValuePatching: !1,
                    positionCaretOnClick: "lvp",
                    casing: null,
                    inputmode: "text",
                    importDataAttributes: !0,
                    shiftPositions: !0,
                    usePrototypeDefinitions: !0,
                    validationEventTimeOut: 3e3
                };
                t.default = r;
            },
            7392: function(e, t) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0;
                t.default = {
                    9: {
                        validator: "[0-9\uff10-\uff19]",
                        definitionSymbol: "*"
                    },
                    a: {
                        validator: "[A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]",
                        definitionSymbol: "*"
                    },
                    "*": {
                        validator: "[0-9\uff10-\uff19A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]"
                    }
                };
            },
            253: function(e, t) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = function(e, t, a) {
                    if (void 0 === a) return e.__data ? e.__data[t] : null;
                    e.__data = e.__data || {}, e.__data[t] = a;
                };
            },
            3776: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.on = function(e, t) {
                    function a(e, a) {
                        n.addEventListener ? n.addEventListener(e, t, !1) : n.attachEvent && n.attachEvent("on" + e, t), 
                        i[e] = i[e] || {}, i[e][a] = i[e][a] || [], i[e][a].push(t);
                    }
                    if (u(this[0])) for (var i = this[0].eventRegistry, n = this[0], r = e.split(" "), o = 0; o < r.length; o++) {
                        var s = r[o].split("."), l = s[0], c = s[1] || "global";
                        a(l, c);
                    }
                    return this;
                }, t.off = function(e, t) {
                    var a, i;
                    function n(e, t, n) {
                        if (e in a == !0) if (i.removeEventListener ? i.removeEventListener(e, n, !1) : i.detachEvent && i.detachEvent("on" + e, n), 
                        "global" === t) for (var r in a[e]) a[e][r].splice(a[e][r].indexOf(n), 1); else a[e][t].splice(a[e][t].indexOf(n), 1);
                    }
                    function r(e, i) {
                        var n, r, o = [];
                        if (e.length > 0) if (void 0 === t) for (n = 0, r = a[e][i].length; n < r; n++) o.push({
                            ev: e,
                            namespace: i && i.length > 0 ? i : "global",
                            handler: a[e][i][n]
                        }); else o.push({
                            ev: e,
                            namespace: i && i.length > 0 ? i : "global",
                            handler: t
                        }); else if (i.length > 0) for (var s in a) for (var l in a[s]) if (l === i) if (void 0 === t) for (n = 0, 
                        r = a[s][l].length; n < r; n++) o.push({
                            ev: s,
                            namespace: l,
                            handler: a[s][l][n]
                        }); else o.push({
                            ev: s,
                            namespace: l,
                            handler: t
                        });
                        return o;
                    }
                    if (u(this[0]) && e) {
                        a = this[0].eventRegistry, i = this[0];
                        for (var o = e.split(" "), s = 0; s < o.length; s++) for (var l = o[s].split("."), c = r(l[0], l[1]), f = 0, d = c.length; f < d; f++) n(c[f].ev, c[f].namespace, c[f].handler);
                    }
                    return this;
                }, t.trigger = function(e) {
                    if (u(this[0])) for (var t = this[0].eventRegistry, a = this[0], i = "string" == typeof e ? e.split(" ") : [ e.type ], r = 0; r < i.length; r++) {
                        var s = i[r].split("."), l = s[0], c = s[1] || "global";
                        if (void 0 !== document && "global" === c) {
                            var f, d, p = {
                                bubbles: !0,
                                cancelable: !0,
                                detail: arguments[1]
                            };
                            if (document.createEvent) {
                                try {
                                    switch (l) {
                                      case "input":
                                        p.inputType = "insertText", f = new InputEvent(l, p);
                                        break;

                                      default:
                                        f = new CustomEvent(l, p);
                                    }
                                } catch (e) {
                                    (f = document.createEvent("CustomEvent")).initCustomEvent(l, p.bubbles, p.cancelable, p.detail);
                                }
                                e.type && (0, n.default)(f, e), a.dispatchEvent(f);
                            } else (f = document.createEventObject()).eventType = l, f.detail = arguments[1], 
                            e.type && (0, n.default)(f, e), a.fireEvent("on" + f.eventType, f);
                        } else if (void 0 !== t[l]) if (arguments[0] = arguments[0].type ? arguments[0] : o.default.Event(arguments[0]), 
                        arguments[0].detail = arguments.slice(1), "global" === c) for (var h in t[l]) for (d = 0; d < t[l][h].length; d++) t[l][h][d].apply(a, arguments); else for (d = 0; d < t[l][c].length; d++) t[l][c][d].apply(a, arguments);
                    }
                    return this;
                }, t.Event = void 0;
                var i, n = l(a(600)), r = l(a(9380)), o = l(a(4963)), s = l(a(8741));
                function l(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                function u(e) {
                    return e instanceof Element;
                }
                t.Event = i, "function" == typeof r.default.CustomEvent ? t.Event = i = r.default.CustomEvent : s.default && (t.Event = i = function(e, t) {
                    t = t || {
                        bubbles: !1,
                        cancelable: !1,
                        detail: void 0
                    };
                    var a = document.createEvent("CustomEvent");
                    return a.initCustomEvent(e, t.bubbles, t.cancelable, t.detail), a;
                }, i.prototype = r.default.Event.prototype);
            },
            600: function(e, t) {
                function a(e) {
                    return (a = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e;
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    })(e);
                }
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = function e() {
                    var t, i, n, r, o, s, l = arguments[0] || {}, u = 1, c = arguments.length, f = !1;
                    "boolean" == typeof l && (f = l, l = arguments[u] || {}, u++);
                    "object" !== a(l) && "function" != typeof l && (l = {});
                    for (;u < c; u++) if (null != (t = arguments[u])) for (i in t) n = l[i], r = t[i], 
                    l !== r && (f && r && ("[object Object]" === Object.prototype.toString.call(r) || (o = Array.isArray(r))) ? (o ? (o = !1, 
                    s = n && Array.isArray(n) ? n : []) : s = n && "[object Object]" === Object.prototype.toString.call(n) ? n : {}, 
                    l[i] = e(f, s, r)) : void 0 !== r && (l[i] = r));
                    return l;
                };
            },
            4963: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0;
                var i = s(a(600)), n = s(a(9380)), r = s(a(253)), o = a(3776);
                function s(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var l = n.default.document;
                function u(e) {
                    return e instanceof u ? e : this instanceof u ? void (null != e && e !== n.default && (this[0] = e.nodeName ? e : void 0 !== e[0] && e[0].nodeName ? e[0] : l.querySelector(e), 
                    void 0 !== this[0] && null !== this[0] && (this[0].eventRegistry = this[0].eventRegistry || {}))) : new u(e);
                }
                u.prototype = {
                    on: o.on,
                    off: o.off,
                    trigger: o.trigger
                }, u.extend = i.default, u.data = r.default, u.Event = o.Event;
                var c = u;
                t.default = c;
            },
            9845: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.iphone = t.iemobile = t.mobile = t.ie = t.ua = void 0;
                var i, n = (i = a(9380)) && i.__esModule ? i : {
                    default: i
                };
                var r = n.default.navigator && n.default.navigator.userAgent || "", o = r.indexOf("MSIE ") > 0 || r.indexOf("Trident/") > 0, s = "ontouchstart" in n.default, l = /iemobile/i.test(r), u = /iphone/i.test(r) && !l;
                t.iphone = u, t.iemobile = l, t.mobile = s, t.ie = o, t.ua = r;
            },
            7184: function(e, t) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = function(e) {
                    return e.replace(a, "\\$1");
                };
                var a = new RegExp("(\\" + [ "/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^" ].join("|\\") + ")", "gim");
            },
            6030: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.EventHandlers = void 0;
                var i, n = a(8711), r = (i = a(4528)) && i.__esModule ? i : {
                    default: i
                }, o = a(9845), s = a(7215), l = a(7760), u = a(4713);
                var c = {
                    keydownEvent: function(e) {
                        var t = this.inputmask, a = t.opts, i = t.dependencyLib, c = t.maskset, f = this, d = i(f), p = e.keyCode, h = n.caret.call(t, f), v = a.onKeyDown.call(this, e, n.getBuffer.call(t), h, a);
                        if (void 0 !== v) return v;
                        if (p === r.default.BACKSPACE || p === r.default.DELETE || o.iphone && p === r.default.BACKSPACE_SAFARI || e.ctrlKey && p === r.default.X && !("oncut" in f)) e.preventDefault(), 
                        s.handleRemove.call(t, f, p, h), (0, l.writeBuffer)(f, n.getBuffer.call(t, !0), c.p, e, f.inputmask._valueGet() !== n.getBuffer.call(t).join("")); else if (p === r.default.END || p === r.default.PAGE_DOWN) {
                            e.preventDefault();
                            var m = n.seekNext.call(t, n.getLastValidPosition.call(t));
                            n.caret.call(t, f, e.shiftKey ? h.begin : m, m, !0);
                        } else p === r.default.HOME && !e.shiftKey || p === r.default.PAGE_UP ? (e.preventDefault(), 
                        n.caret.call(t, f, 0, e.shiftKey ? h.begin : 0, !0)) : a.undoOnEscape && p === r.default.ESCAPE && !0 !== e.altKey ? ((0, 
                        l.checkVal)(f, !0, !1, t.undoValue.split("")), d.trigger("click")) : !0 === a.tabThrough && p === r.default.TAB ? !0 === e.shiftKey ? (h.end = n.seekPrevious.call(t, h.end, !0), 
                        !0 === u.getTest.call(t, h.end - 1).match.static && h.end--, h.begin = n.seekPrevious.call(t, h.end, !0), 
                        h.begin >= 0 && h.end > 0 && (e.preventDefault(), n.caret.call(t, f, h.begin, h.end))) : (h.begin = n.seekNext.call(t, h.begin, !0), 
                        h.end = n.seekNext.call(t, h.begin, !0), h.end < c.maskLength && h.end--, h.begin <= c.maskLength && (e.preventDefault(), 
                        n.caret.call(t, f, h.begin, h.end))) : e.shiftKey || a.insertModeVisual && !1 === a.insertMode && (p === r.default.RIGHT ? setTimeout((function() {
                            var e = n.caret.call(t, f);
                            n.caret.call(t, f, e.begin);
                        }), 0) : p === r.default.LEFT && setTimeout((function() {
                            var e = n.translatePosition.call(t, f.inputmask.caretPos.begin);
                            n.translatePosition.call(t, f.inputmask.caretPos.end);
                            t.isRTL ? n.caret.call(t, f, e + (e === c.maskLength ? 0 : 1)) : n.caret.call(t, f, e - (0 === e ? 0 : 1));
                        }), 0));
                        t.ignorable = a.ignorables.includes(p);
                    },
                    keypressEvent: function(e, t, a, i, o) {
                        var u = this.inputmask || this, c = u.opts, f = u.dependencyLib, d = u.maskset, p = u.el, h = f(p), v = e.which || e.charCode || e.keyCode;
                        if (!(!0 === t || e.ctrlKey && e.altKey) && (e.ctrlKey || e.metaKey || u.ignorable)) return v === r.default.ENTER && u.undoValue !== u._valueGet(!0) && (u.undoValue = u._valueGet(!0), 
                        setTimeout((function() {
                            h.trigger("change");
                        }), 0)), u.skipInputEvent = !0, !0;
                        if (v) {
                            44 !== v && 46 !== v || 3 !== e.location || "" === c.radixPoint || (v = c.radixPoint.charCodeAt(0));
                            var m, g = t ? {
                                begin: o,
                                end: o
                            } : n.caret.call(u, p), k = String.fromCharCode(v);
                            d.writeOutBuffer = !0;
                            var y = s.isValid.call(u, g, k, i, void 0, void 0, void 0, t);
                            if (!1 !== y && (n.resetMaskSet.call(u, !0), m = void 0 !== y.caret ? y.caret : n.seekNext.call(u, y.pos.begin ? y.pos.begin : y.pos), 
                            d.p = m), m = c.numericInput && void 0 === y.caret ? n.seekPrevious.call(u, m) : m, 
                            !1 !== a && (setTimeout((function() {
                                c.onKeyValidation.call(p, v, y);
                            }), 0), d.writeOutBuffer && !1 !== y)) {
                                var b = n.getBuffer.call(u);
                                (0, l.writeBuffer)(p, b, m, e, !0 !== t);
                            }
                            if (e.preventDefault(), t) return !1 !== y && (y.forwardPosition = m), y;
                        }
                    },
                    keyupEvent: function(e) {
                        var t = this.inputmask;
                        !t.isComposing || e.keyCode !== r.default.KEY_229 && e.keyCode !== r.default.ENTER || t.$el.trigger("input");
                    },
                    pasteEvent: function(e) {
                        var t, a = this.inputmask, i = a.opts, r = a._valueGet(!0), o = n.caret.call(a, this);
                        a.isRTL && (t = o.end, o.end = o.begin, o.begin = t);
                        var s = r.substr(0, o.begin), u = r.substr(o.end, r.length);
                        if (s == (a.isRTL ? n.getBufferTemplate.call(a).slice().reverse() : n.getBufferTemplate.call(a)).slice(0, o.begin).join("") && (s = ""), 
                        u == (a.isRTL ? n.getBufferTemplate.call(a).slice().reverse() : n.getBufferTemplate.call(a)).slice(o.end).join("") && (u = ""), 
                        window.clipboardData && window.clipboardData.getData) r = s + window.clipboardData.getData("Text") + u; else {
                            if (!e.clipboardData || !e.clipboardData.getData) return !0;
                            r = s + e.clipboardData.getData("text/plain") + u;
                        }
                        var c = r;
                        if ("function" == typeof i.onBeforePaste) {
                            if (!1 === (c = i.onBeforePaste.call(a, r, i))) return e.preventDefault();
                            c || (c = r);
                        }
                        return (0, l.checkVal)(this, !0, !1, c.toString().split(""), e), e.preventDefault();
                    },
                    inputFallBackEvent: function(e) {
                        var t = this.inputmask, a = t.opts, i = t.dependencyLib;
                        var s = this, f = s.inputmask._valueGet(!0), d = (t.isRTL ? n.getBuffer.call(t).slice().reverse() : n.getBuffer.call(t)).join(""), p = n.caret.call(t, s, void 0, void 0, !0);
                        if (d !== f) {
                            var h = function(e, i, r) {
                                for (var o, s, l, c = e.substr(0, r.begin).split(""), f = e.substr(r.begin).split(""), d = i.substr(0, r.begin).split(""), p = i.substr(r.begin).split(""), h = c.length >= d.length ? c.length : d.length, v = f.length >= p.length ? f.length : p.length, m = "", g = [], k = "~"; c.length < h; ) c.push(k);
                                for (;d.length < h; ) d.push(k);
                                for (;f.length < v; ) f.unshift(k);
                                for (;p.length < v; ) p.unshift(k);
                                var y = c.concat(f), b = d.concat(p);
                                for (s = 0, o = y.length; s < o; s++) switch (l = u.getPlaceholder.call(t, n.translatePosition.call(t, s)), 
                                m) {
                                  case "insertText":
                                    b[s - 1] === y[s] && r.begin == y.length - 1 && g.push(y[s]), s = o;
                                    break;

                                  case "insertReplacementText":
                                  case "deleteContentBackward":
                                    y[s] === k ? r.end++ : s = o;
                                    break;

                                  default:
                                    y[s] !== b[s] && (y[s + 1] !== k && y[s + 1] !== l && void 0 !== y[s + 1] || (b[s] !== l || b[s + 1] !== k) && b[s] !== k ? b[s + 1] === k && b[s] === y[s + 1] ? (m = "insertText", 
                                    g.push(y[s]), r.begin--, r.end--) : y[s] !== l && y[s] !== k && (y[s + 1] === k || b[s] !== y[s] && b[s + 1] === y[s + 1]) ? (m = "insertReplacementText", 
                                    g.push(y[s]), r.begin--) : y[s] === k ? (m = "deleteContentBackward", (n.isMask.call(t, n.translatePosition.call(t, s), !0) || b[s] === a.radixPoint) && r.end++) : s = o : (m = "insertText", 
                                    g.push(y[s]), r.begin--, r.end--));
                                }
                                return {
                                    action: m,
                                    data: g,
                                    caret: r
                                };
                            }(f = function(e, a, i) {
                                if (o.iemobile) {
                                    var r = a.replace(n.getBuffer.call(t).join(""), "");
                                    if (1 === r.length) {
                                        var s = a.split("");
                                        s.splice(i.begin, 0, r), a = s.join("");
                                    }
                                }
                                return a;
                            }(0, f, p), d, p);
                            switch ((s.inputmask.shadowRoot || s.ownerDocument).activeElement !== s && s.focus(), 
                            (0, l.writeBuffer)(s, n.getBuffer.call(t)), n.caret.call(t, s, p.begin, p.end, !0), 
                            h.action) {
                              case "insertText":
                              case "insertReplacementText":
                                h.data.forEach((function(e, a) {
                                    var n = new i.Event("keypress");
                                    n.which = e.charCodeAt(0), t.ignorable = !1, c.keypressEvent.call(s, n);
                                })), setTimeout((function() {
                                    t.$el.trigger("keyup");
                                }), 0);
                                break;

                              case "deleteContentBackward":
                                var v = new i.Event("keydown");
                                v.keyCode = r.default.BACKSPACE, c.keydownEvent.call(s, v);
                                break;

                              default:
                                (0, l.applyInputValue)(s, f);
                            }
                            e.preventDefault();
                        }
                    },
                    compositionendEvent: function(e) {
                        var t = this.inputmask;
                        t.isComposing = !1, t.$el.trigger("input");
                    },
                    setValueEvent: function(e) {
                        var t = this.inputmask, a = this, i = e && e.detail ? e.detail[0] : arguments[1];
                        void 0 === i && (i = a.inputmask._valueGet(!0)), (0, l.applyInputValue)(a, i), (e.detail && void 0 !== e.detail[1] || void 0 !== arguments[2]) && n.caret.call(t, a, e.detail ? e.detail[1] : arguments[2]);
                    },
                    focusEvent: function(e) {
                        var t = this.inputmask, a = t.opts, i = this, r = i.inputmask._valueGet();
                        a.showMaskOnFocus && r !== n.getBuffer.call(t).join("") && (0, l.writeBuffer)(i, n.getBuffer.call(t), n.seekNext.call(t, n.getLastValidPosition.call(t))), 
                        !0 !== a.positionCaretOnTab || !1 !== t.mouseEnter || s.isComplete.call(t, n.getBuffer.call(t)) && -1 !== n.getLastValidPosition.call(t) || c.clickEvent.apply(i, [ e, !0 ]), 
                        t.undoValue = t._valueGet(!0);
                    },
                    invalidEvent: function(e) {
                        this.inputmask.validationEvent = !0;
                    },
                    mouseleaveEvent: function() {
                        var e = this.inputmask, t = e.opts, a = this;
                        e.mouseEnter = !1, t.clearMaskOnLostFocus && (a.inputmask.shadowRoot || a.ownerDocument).activeElement !== a && (0, 
                        l.HandleNativePlaceholder)(a, e.originalPlaceholder);
                    },
                    clickEvent: function(e, t) {
                        var a = this.inputmask, i = this;
                        if ((i.inputmask.shadowRoot || i.ownerDocument).activeElement === i) {
                            var r = n.determineNewCaretPosition.call(a, n.caret.call(a, i), t);
                            void 0 !== r && n.caret.call(a, i, r);
                        }
                    },
                    cutEvent: function(e) {
                        var t = this.inputmask, a = t.maskset, i = this, o = n.caret.call(t, i), u = window.clipboardData || e.clipboardData, c = t.isRTL ? n.getBuffer.call(t).slice(o.end, o.begin) : n.getBuffer.call(t).slice(o.begin, o.end);
                        u.setData("text", t.isRTL ? c.reverse().join("") : c.join("")), document.execCommand && document.execCommand("copy"), 
                        s.handleRemove.call(t, i, r.default.DELETE, o), (0, l.writeBuffer)(i, n.getBuffer.call(t), a.p, e, t.undoValue !== t._valueGet(!0));
                    },
                    blurEvent: function(e) {
                        var t = this.inputmask, a = t.opts, i = (0, t.dependencyLib)(this), r = this;
                        if (r.inputmask) {
                            (0, l.HandleNativePlaceholder)(r, t.originalPlaceholder);
                            var o = r.inputmask._valueGet(), u = n.getBuffer.call(t).slice();
                            "" !== o && (a.clearMaskOnLostFocus && (-1 === n.getLastValidPosition.call(t) && o === n.getBufferTemplate.call(t).join("") ? u = [] : l.clearOptionalTail.call(t, u)), 
                            !1 === s.isComplete.call(t, u) && (setTimeout((function() {
                                i.trigger("incomplete");
                            }), 0), a.clearIncomplete && (n.resetMaskSet.call(t), u = a.clearMaskOnLostFocus ? [] : n.getBufferTemplate.call(t).slice())), 
                            (0, l.writeBuffer)(r, u, void 0, e)), t.undoValue !== t._valueGet(!0) && (t.undoValue = t._valueGet(!0), 
                            i.trigger("change"));
                        }
                    },
                    mouseenterEvent: function() {
                        var e = this.inputmask, t = e.opts, a = this;
                        if (e.mouseEnter = !0, (a.inputmask.shadowRoot || a.ownerDocument).activeElement !== a) {
                            var i = (e.isRTL ? n.getBufferTemplate.call(e).slice().reverse() : n.getBufferTemplate.call(e)).join("");
                            e.placeholder !== i && a.placeholder !== e.originalPlaceholder && (e.originalPlaceholder = a.placeholder), 
                            t.showMaskOnHover && (0, l.HandleNativePlaceholder)(a, i);
                        }
                    },
                    submitEvent: function() {
                        var e = this.inputmask, t = e.opts;
                        e.undoValue !== e._valueGet(!0) && e.$el.trigger("change"), t.clearMaskOnLostFocus && -1 === n.getLastValidPosition.call(e) && e._valueGet && e._valueGet() === n.getBufferTemplate.call(e).join("") && e._valueSet(""), 
                        t.clearIncomplete && !1 === s.isComplete.call(e, n.getBuffer.call(e)) && e._valueSet(""), 
                        t.removeMaskOnSubmit && (e._valueSet(e.unmaskedvalue(), !0), setTimeout((function() {
                            (0, l.writeBuffer)(e.el, n.getBuffer.call(e));
                        }), 0));
                    },
                    resetEvent: function() {
                        var e = this.inputmask;
                        e.refreshValue = !0, setTimeout((function() {
                            (0, l.applyInputValue)(e.el, e._valueGet(!0));
                        }), 0);
                    }
                };
                t.EventHandlers = c;
            },
            9716: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.EventRuler = void 0;
                var i = s(a(2394)), n = s(a(4528)), r = a(8711), o = a(7760);
                function s(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var l = {
                    on: function(e, t, a) {
                        var s = e.inputmask.dependencyLib, l = function(t) {
                            t.originalEvent && (t = t.originalEvent || t, arguments[0] = t);
                            var l, u = this, c = u.inputmask, f = c ? c.opts : void 0;
                            if (void 0 === c && "FORM" !== this.nodeName) {
                                var d = s.data(u, "_inputmask_opts");
                                s(u).off(), d && new i.default(d).mask(u);
                            } else {
                                if ([ "submit", "reset", "setvalue" ].includes(t.type) || "FORM" === this.nodeName || !(u.disabled || u.readOnly && !("keydown" === t.type && t.ctrlKey && 67 === t.keyCode || !1 === f.tabThrough && t.keyCode === n.default.TAB))) {
                                    switch (t.type) {
                                      case "input":
                                        if (!0 === c.skipInputEvent || t.inputType && "insertCompositionText" === t.inputType) return c.skipInputEvent = !1, 
                                        t.preventDefault();
                                        break;

                                      case "keydown":
                                        c.skipKeyPressEvent = !1, c.skipInputEvent = c.isComposing = t.keyCode === n.default.KEY_229;
                                        break;

                                      case "keyup":
                                      case "compositionend":
                                        c.isComposing && (c.skipInputEvent = !1);
                                        break;

                                      case "keypress":
                                        if (!0 === c.skipKeyPressEvent) return t.preventDefault();
                                        c.skipKeyPressEvent = !0;
                                        break;

                                      case "click":
                                      case "focus":
                                        return c.validationEvent ? (c.validationEvent = !1, e.blur(), (0, o.HandleNativePlaceholder)(e, (c.isRTL ? r.getBufferTemplate.call(c).slice().reverse() : r.getBufferTemplate.call(c)).join("")), 
                                        setTimeout((function() {
                                            e.focus();
                                        }), f.validationEventTimeOut), !1) : (l = arguments, setTimeout((function() {
                                            e.inputmask && a.apply(u, l);
                                        }), 0), !1);
                                    }
                                    var p = a.apply(u, arguments);
                                    return !1 === p && (t.preventDefault(), t.stopPropagation()), p;
                                }
                                t.preventDefault();
                            }
                        };
                        [ "submit", "reset" ].includes(t) ? (l = l.bind(e), null !== e.form && s(e.form).on(t, l)) : s(e).on(t, l), 
                        e.inputmask.events[t] = e.inputmask.events[t] || [], e.inputmask.events[t].push(l);
                    },
                    off: function(e, t) {
                        if (e.inputmask && e.inputmask.events) {
                            var a = e.inputmask.dependencyLib, i = e.inputmask.events;
                            for (var n in t && ((i = [])[t] = e.inputmask.events[t]), i) {
                                for (var r = i[n]; r.length > 0; ) {
                                    var o = r.pop();
                                    [ "submit", "reset" ].includes(n) ? null !== e.form && a(e.form).off(n, o) : a(e).off(n, o);
                                }
                                delete e.inputmask.events[n];
                            }
                        }
                    }
                };
                t.EventRuler = l;
            },
            219: function(e, t, a) {
                var i = l(a(2394)), n = l(a(4528)), r = l(a(7184)), o = a(8711);
                function s(e) {
                    return (s = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e;
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    })(e);
                }
                function l(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var u = i.default.dependencyLib, c = (new Date).getFullYear(), f = {
                    d: [ "[1-9]|[12][0-9]|3[01]", Date.prototype.setDate, "day", Date.prototype.getDate ],
                    dd: [ "0[1-9]|[12][0-9]|3[01]", Date.prototype.setDate, "day", function() {
                        return y(Date.prototype.getDate.call(this), 2);
                    } ],
                    ddd: [ "" ],
                    dddd: [ "" ],
                    m: [ "[1-9]|1[012]", Date.prototype.setMonth, "month", function() {
                        return Date.prototype.getMonth.call(this) + 1;
                    } ],
                    mm: [ "0[1-9]|1[012]", Date.prototype.setMonth, "month", function() {
                        return y(Date.prototype.getMonth.call(this) + 1, 2);
                    } ],
                    mmm: [ "" ],
                    mmmm: [ "" ],
                    yy: [ "[0-9]{2}", Date.prototype.setFullYear, "year", function() {
                        return y(Date.prototype.getFullYear.call(this), 2);
                    } ],
                    yyyy: [ "[0-9]{4}", Date.prototype.setFullYear, "year", function() {
                        return y(Date.prototype.getFullYear.call(this), 4);
                    } ],
                    h: [ "[1-9]|1[0-2]", Date.prototype.setHours, "hours", Date.prototype.getHours ],
                    hh: [ "0[1-9]|1[0-2]", Date.prototype.setHours, "hours", function() {
                        return y(Date.prototype.getHours.call(this), 2);
                    } ],
                    hx: [ function(e) {
                        return "[0-9]{".concat(e, "}");
                    }, Date.prototype.setHours, "hours", function(e) {
                        return Date.prototype.getHours;
                    } ],
                    H: [ "1?[0-9]|2[0-3]", Date.prototype.setHours, "hours", Date.prototype.getHours ],
                    HH: [ "0[0-9]|1[0-9]|2[0-3]", Date.prototype.setHours, "hours", function() {
                        return y(Date.prototype.getHours.call(this), 2);
                    } ],
                    Hx: [ function(e) {
                        return "[0-9]{".concat(e, "}");
                    }, Date.prototype.setHours, "hours", function(e) {
                        return function() {
                            return y(Date.prototype.getHours.call(this), e);
                        };
                    } ],
                    M: [ "[1-5]?[0-9]", Date.prototype.setMinutes, "minutes", Date.prototype.getMinutes ],
                    MM: [ "0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9]", Date.prototype.setMinutes, "minutes", function() {
                        return y(Date.prototype.getMinutes.call(this), 2);
                    } ],
                    s: [ "[1-5]?[0-9]", Date.prototype.setSeconds, "seconds", Date.prototype.getSeconds ],
                    ss: [ "0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9]", Date.prototype.setSeconds, "seconds", function() {
                        return y(Date.prototype.getSeconds.call(this), 2);
                    } ],
                    l: [ "[0-9]{3}", Date.prototype.setMilliseconds, "milliseconds", function() {
                        return y(Date.prototype.getMilliseconds.call(this), 3);
                    } ],
                    L: [ "[0-9]{2}", Date.prototype.setMilliseconds, "milliseconds", function() {
                        return y(Date.prototype.getMilliseconds.call(this), 2);
                    } ],
                    t: [ "[ap]", p, "ampm", h, 1 ],
                    tt: [ "[ap]m", p, "ampm", h, 2 ],
                    T: [ "[AP]", p, "ampm", h, 1 ],
                    TT: [ "[AP]M", p, "ampm", h, 2 ],
                    Z: [ "" ],
                    o: [ "" ],
                    S: [ "" ]
                }, d = {
                    isoDate: "yyyy-mm-dd",
                    isoTime: "HH:MM:ss",
                    isoDateTime: "yyyy-mm-dd'T'HH:MM:ss",
                    isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
                };
                function p(e) {
                    e.toLowerCase().includes("p") && this.setHours(this.getHours() + 12);
                }
                function h() {}
                function v(e) {
                    var t = new RegExp("\\d+$").exec(e[0]);
                    if (t && void 0 !== t[0]) {
                        var a = f[e[0][0] + "x"].slice("");
                        return a[0] = a[0](t[0]), a[3] = a[3](t[0]), a;
                    }
                    if (f[e[0]]) return f[e[0]];
                }
                function m(e) {
                    if (!e.tokenizer) {
                        var t = [], a = [];
                        for (var i in f) if (/\.*x$/.test(i)) {
                            var n = i[0] + "\\d+";
                            -1 === a.indexOf(n) && a.push(n);
                        } else -1 === t.indexOf(i[0]) && t.push(i[0]);
                        e.tokenizer = "(" + (a.length > 0 ? a.join("|") + "|" : "") + t.join("+|") + ")+?|.", 
                        e.tokenizer = new RegExp(e.tokenizer, "g");
                    }
                    return e.tokenizer;
                }
                function g(e, t, a) {
                    if (void 0 === e.rawday || !isFinite(e.rawday) && new Date(e.date.getFullYear(), isFinite(e.rawmonth) ? e.month : e.date.getMonth() + 1, 0).getDate() >= e.day || "29" == e.day && !Number.isFinite(e.rawyear) || new Date(e.date.getFullYear(), isFinite(e.rawmonth) ? e.month : e.date.getMonth() + 1, 0).getDate() >= e.day) return t;
                    if ("29" == e.day) {
                        var i = P(t.pos, a);
                        if ("yyyy" === i.targetMatch[0] && t.pos - i.targetMatchIndex == 2) return t.remove = t.pos + 1, 
                        t;
                    } else if ("02" == e.month && "30" == e.day && void 0 !== t.c) return e.day = "03", 
                    e.date.setDate(3), e.date.setMonth(1), t.insert = [ {
                        pos: t.pos,
                        c: "0"
                    }, {
                        pos: t.pos + 1,
                        c: t.c
                    } ], t.caret = o.seekNext.call(this, t.pos + 1), t;
                    return !1;
                }
                function k(e, t, a, i) {
                    var n, o, s = "";
                    for (m(a).lastIndex = 0; n = m(a).exec(e); ) {
                        if (void 0 === t) if (o = v(n)) s += "(" + o[0] + ")"; else switch (n[0]) {
                          case "[":
                            s += "(";
                            break;

                          case "]":
                            s += ")?";
                            break;

                          default:
                            s += (0, r.default)(n[0]);
                        } else if (o = v(n)) if (!0 !== i && o[3]) s += o[3].call(t.date); else o[2] ? s += t["raw" + o[2]] : s += n[0]; else s += n[0];
                    }
                    return s;
                }
                function y(e, t, a) {
                    for (e = String(e), t = t || 2; e.length < t; ) e = a ? e + "0" : "0" + e;
                    return e;
                }
                function b(e, t, a) {
                    var i, n, r, o = {
                        date: new Date(1, 0, 1)
                    }, l = e;
                    function u(e, t, a) {
                        if (e[i] = "ampm" === i ? t : t.replace(/[^0-9]/g, "0"), e["raw" + i] = t, void 0 !== r) {
                            var n = e[i];
                            ("day" === i && 29 === parseInt(n) || "month" === i && 2 === parseInt(n)) && (29 !== parseInt(e.day) || 2 !== parseInt(e.month) || "" !== e.year && void 0 !== e.year || e.date.setFullYear(2012, 1, 29)), 
                            "day" === i && 0 === parseInt(n) && (n = 1), "month" === i && (n = parseInt(n)) > 0 && (n -= 1), 
                            "year" === i && n.length < 4 && (n = y(n, 4, !0)), "" === n || isNaN(n) || r.call(e.date, n), 
                            "ampm" === i && r.call(e.date, n);
                        }
                    }
                    if ("string" == typeof l) {
                        for (m(a).lastIndex = 0; n = m(a).exec(t); ) {
                            var c = new RegExp("\\d+$").exec(n[0]), d = c ? n[0][0] + "x" : n[0], p = void 0;
                            if (c) {
                                var h = m(a).lastIndex, v = P(n.index, a);
                                m(a).lastIndex = h, p = l.slice(0, l.indexOf(v.nextMatch[0]));
                            } else p = l.slice(0, d.length);
                            Object.prototype.hasOwnProperty.call(f, d) && (i = f[d][2], r = f[d][1], u(o, p)), 
                            l = l.slice(p.length);
                        }
                        return o;
                    }
                    if (l && "object" === s(l) && Object.prototype.hasOwnProperty.call(l, "date")) return l;
                }
                function x(e, t) {
                    return k(t.inputFormat, {
                        date: e
                    }, t);
                }
                function P(e, t) {
                    var a, i, n = 0, r = 0;
                    for (m(t).lastIndex = 0; i = m(t).exec(t.inputFormat); ) {
                        var o = new RegExp("\\d+$").exec(i[0]);
                        if ((n += r = o ? parseInt(o[0]) : i[0].length) >= e) {
                            a = i, i = m(t).exec(t.inputFormat);
                            break;
                        }
                    }
                    return {
                        targetMatchIndex: n - r,
                        nextMatch: i,
                        targetMatch: a
                    };
                }
                i.default.extendAliases({
                    datetime: {
                        mask: function(e) {
                            return e.numericInput = !1, f.S = e.i18n.ordinalSuffix.join("|"), e.inputFormat = d[e.inputFormat] || e.inputFormat, 
                            e.displayFormat = d[e.displayFormat] || e.displayFormat || e.inputFormat, e.outputFormat = d[e.outputFormat] || e.outputFormat || e.inputFormat, 
                            e.placeholder = "" !== e.placeholder ? e.placeholder : e.inputFormat.replace(/[[\]]/, ""), 
                            e.regex = k(e.inputFormat, void 0, e), e.min = b(e.min, e.inputFormat, e), e.max = b(e.max, e.inputFormat, e), 
                            null;
                        },
                        placeholder: "",
                        inputFormat: "isoDateTime",
                        displayFormat: void 0,
                        outputFormat: void 0,
                        min: null,
                        max: null,
                        skipOptionalPartCharacter: "",
                        i18n: {
                            dayNames: [ "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ],
                            monthNames: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
                            ordinalSuffix: [ "st", "nd", "rd", "th" ]
                        },
                        preValidation: function(e, t, a, i, n, r, o, s) {
                            if (s) return !0;
                            if (isNaN(a) && e[t] !== a) {
                                var l = P(t, n);
                                if (l.nextMatch && l.nextMatch[0] === a && l.targetMatch[0].length > 1) {
                                    var u = f[l.targetMatch[0]][0];
                                    if (new RegExp(u).test("0" + e[t - 1])) return e[t] = e[t - 1], e[t - 1] = "0", 
                                    {
                                        fuzzy: !0,
                                        buffer: e,
                                        refreshFromBuffer: {
                                            start: t - 1,
                                            end: t + 1
                                        },
                                        pos: t + 1
                                    };
                                }
                            }
                            return !0;
                        },
                        postValidation: function(e, t, a, i, n, r, o, s) {
                            var l, u;
                            if (o) return !0;
                            if (!1 === i && (((l = P(t + 1, n)).targetMatch && l.targetMatchIndex === t && l.targetMatch[0].length > 1 && void 0 !== f[l.targetMatch[0]] || (l = P(t + 2, n)).targetMatch && l.targetMatchIndex === t + 1 && l.targetMatch[0].length > 1 && void 0 !== f[l.targetMatch[0]]) && (u = f[l.targetMatch[0]][0]), 
                            void 0 !== u && (void 0 !== r.validPositions[t + 1] && new RegExp(u).test(a + "0") ? (e[t] = a, 
                            e[t + 1] = "0", i = {
                                pos: t + 2,
                                caret: t
                            }) : new RegExp(u).test("0" + a) && (e[t] = "0", e[t + 1] = a, i = {
                                pos: t + 2
                            })), !1 === i)) return i;
                            if (i.fuzzy && (e = i.buffer, t = i.pos), (l = P(t, n)).targetMatch && l.targetMatch[0] && void 0 !== f[l.targetMatch[0]]) {
                                u = f[l.targetMatch[0]][0];
                                var d = e.slice(l.targetMatchIndex, l.targetMatchIndex + l.targetMatch[0].length);
                                !1 === new RegExp(u).test(d.join("")) && 2 === l.targetMatch[0].length && r.validPositions[l.targetMatchIndex] && r.validPositions[l.targetMatchIndex + 1] && (r.validPositions[l.targetMatchIndex + 1].input = "0");
                            }
                            var p = i, h = b(e.join(""), n.inputFormat, n);
                            return p && h.date.getTime() == h.date.getTime() && (n.prefillYear && (p = function(e, t, a) {
                                if (e.year !== e.rawyear) {
                                    var i = c.toString(), n = e.rawyear.replace(/[^0-9]/g, ""), r = i.slice(0, n.length), o = i.slice(n.length);
                                    if (2 === n.length && n === r) {
                                        var s = new Date(c, e.month - 1, e.day);
                                        e.day == s.getDate() && (!a.max || a.max.date.getTime() >= s.getTime()) && (e.date.setFullYear(c), 
                                        e.year = i, t.insert = [ {
                                            pos: t.pos + 1,
                                            c: o[0]
                                        }, {
                                            pos: t.pos + 2,
                                            c: o[1]
                                        } ]);
                                    }
                                }
                                return t;
                            }(h, p, n)), p = function(e, t, a, i, n) {
                                if (!t) return t;
                                if (a.min) {
                                    if (e.rawyear) {
                                        var r, o = e.rawyear.replace(/[^0-9]/g, ""), s = a.min.year.substr(0, o.length);
                                        if (o < s) {
                                            var l = P(t.pos, a);
                                            if (o = e.rawyear.substr(0, t.pos - l.targetMatchIndex + 1).replace(/[^0-9]/g, "0"), 
                                            (s = a.min.year.substr(0, o.length)) <= o) return t.remove = l.targetMatchIndex + o.length, 
                                            t;
                                            if (o = "yyyy" === l.targetMatch[0] ? e.rawyear.substr(1, 1) : e.rawyear.substr(0, 1), 
                                            s = a.min.year.substr(2, 1), r = a.max ? a.max.year.substr(2, 1) : o, 1 === o.length && s <= o && o <= r && !0 !== n) return "yyyy" === l.targetMatch[0] ? (t.insert = [ {
                                                pos: t.pos + 1,
                                                c: o,
                                                strict: !0
                                            } ], t.caret = t.pos + 2, i.validPositions[t.pos].input = a.min.year[1]) : (t.insert = [ {
                                                pos: t.pos + 1,
                                                c: a.min.year[1],
                                                strict: !0
                                            }, {
                                                pos: t.pos + 2,
                                                c: o,
                                                strict: !0
                                            } ], t.caret = t.pos + 3, i.validPositions[t.pos].input = a.min.year[0]), t;
                                            t = !1;
                                        }
                                    }
                                    for (var u in e) -1 === u.indexOf("raw") && e["raw".concat(u)] && (e[u], e["raw".concat(u)]);
                                    t && e.year && e.year === e.rawyear && a.min.date.getTime() == a.min.date.getTime() && (t = a.min.date.getTime() <= e.date.getTime());
                                }
                                return t && a.max && a.max.date.getTime() == a.max.date.getTime() && (t = a.max.date.getTime() >= e.date.getTime()), 
                                t;
                            }(h, p = g.call(this, h, p, n), n, r, s)), void 0 !== t && p && i.pos !== t ? {
                                buffer: k(n.inputFormat, h, n).split(""),
                                refreshFromBuffer: {
                                    start: t,
                                    end: i.pos
                                },
                                pos: i.caret || i.pos
                            } : p;
                        },
                        onKeyDown: function(e, t, a, i) {
                            e.ctrlKey && e.keyCode === n.default.RIGHT && (this.inputmask._valueSet(x(new Date, i)), 
                            u(this).trigger("setvalue"));
                        },
                        onUnMask: function(e, t, a) {
                            return t ? k(a.outputFormat, b(e, a.inputFormat, a), a, !0) : t;
                        },
                        casing: function(e, t, a, i) {
                            return 0 == t.nativeDef.indexOf("[ap]") ? e.toLowerCase() : 0 == t.nativeDef.indexOf("[AP]") ? e.toUpperCase() : e;
                        },
                        onBeforeMask: function(e, t) {
                            return "[object Date]" === Object.prototype.toString.call(e) && (e = x(e, t)), e;
                        },
                        insertMode: !1,
                        shiftPositions: !1,
                        keepStatic: !1,
                        inputmode: "numeric",
                        prefillYear: !0
                    }
                });
            },
            3851: function(e, t, a) {
                var i, n = (i = a(2394)) && i.__esModule ? i : {
                    default: i
                }, r = a(8711), o = a(4713);
                n.default.extendDefinitions({
                    A: {
                        validator: "[A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]",
                        casing: "upper"
                    },
                    "&": {
                        validator: "[0-9A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5]",
                        casing: "upper"
                    },
                    "#": {
                        validator: "[0-9A-Fa-f]",
                        casing: "upper"
                    }
                });
                var s = new RegExp("25[0-5]|2[0-4][0-9]|[01][0-9][0-9]");
                function l(e, t, a, i, n) {
                    return a - 1 > -1 && "." !== t.buffer[a - 1] ? (e = t.buffer[a - 1] + e, e = a - 2 > -1 && "." !== t.buffer[a - 2] ? t.buffer[a - 2] + e : "0" + e) : e = "00" + e, 
                    s.test(e);
                }
                n.default.extendAliases({
                    cssunit: {
                        regex: "[+-]?[0-9]+\\.?([0-9]+)?(px|em|rem|ex|%|in|cm|mm|pt|pc)"
                    },
                    url: {
                        regex: "(https?|ftp)://.*",
                        autoUnmask: !1,
                        keepStatic: !1,
                        tabThrough: !0
                    },
                    ip: {
                        mask: "i[i[i]].j[j[j]].k[k[k]].l[l[l]]",
                        definitions: {
                            i: {
                                validator: l
                            },
                            j: {
                                validator: l
                            },
                            k: {
                                validator: l
                            },
                            l: {
                                validator: l
                            }
                        },
                        onUnMask: function(e, t, a) {
                            return e;
                        },
                        inputmode: "numeric"
                    },
                    email: {
                        mask: "*{1,64}[.*{1,64}][.*{1,64}][.*{1,63}]@-{1,63}.-{1,63}[.-{1,63}][.-{1,63}]",
                        greedy: !1,
                        casing: "lower",
                        onBeforePaste: function(e, t) {
                            return (e = e.toLowerCase()).replace("mailto:", "");
                        },
                        definitions: {
                            "*": {
                                validator: "[0-9\uff11-\uff19A-Za-z\u0410-\u044f\u0401\u0451\xc0-\xff\xb5!#$%&'*+/=?^_`{|}~-]"
                            },
                            "-": {
                                validator: "[0-9A-Za-z-]"
                            }
                        },
                        onUnMask: function(e, t, a) {
                            return e;
                        },
                        inputmode: "email"
                    },
                    mac: {
                        mask: "##:##:##:##:##:##"
                    },
                    vin: {
                        mask: "V{13}9{4}",
                        definitions: {
                            V: {
                                validator: "[A-HJ-NPR-Za-hj-npr-z\\d]",
                                casing: "upper"
                            }
                        },
                        clearIncomplete: !0,
                        autoUnmask: !0
                    },
                    ssn: {
                        mask: "999-99-9999",
                        postValidation: function(e, t, a, i, n, s, l) {
                            var u = o.getMaskTemplate.call(this, !0, r.getLastValidPosition.call(this), !0, !0);
                            return /^(?!219-09-9999|078-05-1120)(?!666|000|9.{2}).{3}-(?!00).{2}-(?!0{4}).{4}$/.test(u.join(""));
                        }
                    }
                });
            },
            207: function(e, t, a) {
                var i = s(a(2394)), n = s(a(4528)), r = s(a(7184)), o = a(8711);
                function s(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var l = i.default.dependencyLib;
                function u(e, t) {
                    for (var a = "", n = 0; n < e.length; n++) i.default.prototype.definitions[e.charAt(n)] || t.definitions[e.charAt(n)] || t.optionalmarker[0] === e.charAt(n) || t.optionalmarker[1] === e.charAt(n) || t.quantifiermarker[0] === e.charAt(n) || t.quantifiermarker[1] === e.charAt(n) || t.groupmarker[0] === e.charAt(n) || t.groupmarker[1] === e.charAt(n) || t.alternatormarker === e.charAt(n) ? a += "\\" + e.charAt(n) : a += e.charAt(n);
                    return a;
                }
                function c(e, t, a, i) {
                    if (e.length > 0 && t > 0 && (!a.digitsOptional || i)) {
                        var n = e.indexOf(a.radixPoint), r = !1;
                        a.negationSymbol.back === e[e.length - 1] && (r = !0, e.length--), -1 === n && (e.push(a.radixPoint), 
                        n = e.length - 1);
                        for (var o = 1; o <= t; o++) isFinite(e[n + o]) || (e[n + o] = "0");
                    }
                    return r && e.push(a.negationSymbol.back), e;
                }
                function f(e, t) {
                    var a = 0;
                    if ("+" === e) {
                        for (a in t.validPositions) ;
                        a = o.seekNext.call(this, parseInt(a));
                    }
                    for (var i in t.tests) if ((i = parseInt(i)) >= a) for (var n = 0, r = t.tests[i].length; n < r; n++) if ((void 0 === t.validPositions[i] || "-" === e) && t.tests[i][n].match.def === e) return i + (void 0 !== t.validPositions[i] && "-" !== e ? 1 : 0);
                    return a;
                }
                function d(e, t) {
                    var a = -1;
                    for (var i in t.validPositions) {
                        var n = t.validPositions[i];
                        if (n && n.match.def === e) {
                            a = parseInt(i);
                            break;
                        }
                    }
                    return a;
                }
                function p(e, t, a, i, n) {
                    var r = t.buffer ? t.buffer.indexOf(n.radixPoint) : -1, o = (-1 !== r || i && n.jitMasking) && new RegExp(n.definitions[9].validator).test(e);
                    return n._radixDance && -1 !== r && o && null == t.validPositions[r] ? {
                        insert: {
                            pos: r === a ? r + 1 : r,
                            c: n.radixPoint
                        },
                        pos: a
                    } : o;
                }
                i.default.extendAliases({
                    numeric: {
                        mask: function(e) {
                            e.repeat = 0, e.groupSeparator === e.radixPoint && e.digits && "0" !== e.digits && ("." === e.radixPoint ? e.groupSeparator = "," : "," === e.radixPoint ? e.groupSeparator = "." : e.groupSeparator = ""), 
                            " " === e.groupSeparator && (e.skipOptionalPartCharacter = void 0), e.placeholder.length > 1 && (e.placeholder = e.placeholder.charAt(0)), 
                            "radixFocus" === e.positionCaretOnClick && "" === e.placeholder && (e.positionCaretOnClick = "lvp");
                            var t = "0", a = e.radixPoint;
                            !0 === e.numericInput && void 0 === e.__financeInput ? (t = "1", e.positionCaretOnClick = "radixFocus" === e.positionCaretOnClick ? "lvp" : e.positionCaretOnClick, 
                            e.digitsOptional = !1, isNaN(e.digits) && (e.digits = 2), e._radixDance = !1, a = "," === e.radixPoint ? "?" : "!", 
                            "" !== e.radixPoint && void 0 === e.definitions[a] && (e.definitions[a] = {}, e.definitions[a].validator = "[" + e.radixPoint + "]", 
                            e.definitions[a].placeholder = e.radixPoint, e.definitions[a].static = !0, e.definitions[a].generated = !0)) : (e.__financeInput = !1, 
                            e.numericInput = !0);
                            var i, n = "[+]";
                            if (n += u(e.prefix, e), "" !== e.groupSeparator ? (void 0 === e.definitions[e.groupSeparator] && (e.definitions[e.groupSeparator] = {}, 
                            e.definitions[e.groupSeparator].validator = "[" + e.groupSeparator + "]", e.definitions[e.groupSeparator].placeholder = e.groupSeparator, 
                            e.definitions[e.groupSeparator].static = !0, e.definitions[e.groupSeparator].generated = !0), 
                            n += e._mask(e)) : n += "9{+}", void 0 !== e.digits && 0 !== e.digits) {
                                var o = e.digits.toString().split(",");
                                isFinite(o[0]) && o[1] && isFinite(o[1]) ? n += a + t + "{" + e.digits + "}" : (isNaN(e.digits) || parseInt(e.digits) > 0) && (e.digitsOptional || e.jitMasking ? (i = n + a + t + "{0," + e.digits + "}", 
                                e.keepStatic = !0) : n += a + t + "{" + e.digits + "}");
                            } else e.inputmode = "numeric";
                            return n += u(e.suffix, e), n += "[-]", i && (n = [ i + u(e.suffix, e) + "[-]", n ]), 
                            e.greedy = !1, function(e) {
                                void 0 === e.parseMinMaxOptions && (null !== e.min && (e.min = e.min.toString().replace(new RegExp((0, 
                                r.default)(e.groupSeparator), "g"), ""), "," === e.radixPoint && (e.min = e.min.replace(e.radixPoint, ".")), 
                                e.min = isFinite(e.min) ? parseFloat(e.min) : NaN, isNaN(e.min) && (e.min = Number.MIN_VALUE)), 
                                null !== e.max && (e.max = e.max.toString().replace(new RegExp((0, r.default)(e.groupSeparator), "g"), ""), 
                                "," === e.radixPoint && (e.max = e.max.replace(e.radixPoint, ".")), e.max = isFinite(e.max) ? parseFloat(e.max) : NaN, 
                                isNaN(e.max) && (e.max = Number.MAX_VALUE)), e.parseMinMaxOptions = "done");
                            }(e), n;
                        },
                        _mask: function(e) {
                            return "(" + e.groupSeparator + "999){+|1}";
                        },
                        digits: "*",
                        digitsOptional: !0,
                        enforceDigitsOnBlur: !1,
                        radixPoint: ".",
                        positionCaretOnClick: "radixFocus",
                        _radixDance: !0,
                        groupSeparator: "",
                        allowMinus: !0,
                        negationSymbol: {
                            front: "-",
                            back: ""
                        },
                        prefix: "",
                        suffix: "",
                        min: null,
                        max: null,
                        SetMaxOnOverflow: !1,
                        step: 1,
                        inputType: "text",
                        unmaskAsNumber: !1,
                        roundingFN: Math.round,
                        inputmode: "decimal",
                        shortcuts: {
                            k: "000",
                            m: "000000"
                        },
                        placeholder: "0",
                        greedy: !1,
                        rightAlign: !0,
                        insertMode: !0,
                        autoUnmask: !1,
                        skipOptionalPartCharacter: "",
                        usePrototypeDefinitions: !1,
                        definitions: {
                            0: {
                                validator: p
                            },
                            1: {
                                validator: p,
                                definitionSymbol: "9"
                            },
                            9: {
                                validator: "[0-9\uff10-\uff19\u0660-\u0669\u06f0-\u06f9]",
                                definitionSymbol: "*"
                            },
                            "+": {
                                validator: function(e, t, a, i, n) {
                                    return n.allowMinus && ("-" === e || e === n.negationSymbol.front);
                                }
                            },
                            "-": {
                                validator: function(e, t, a, i, n) {
                                    return n.allowMinus && e === n.negationSymbol.back;
                                }
                            }
                        },
                        preValidation: function(e, t, a, i, n, r, o, s) {
                            var l;
                            if (!1 !== n.__financeInput && a === n.radixPoint) return !1;
                            if (l = n.shortcuts && n.shortcuts[a]) {
                                if (l.length > 1) for (var u = [], c = 0; c < l.length; c++) u.push({
                                    pos: t + c,
                                    c: l[c],
                                    strict: !1
                                });
                                return {
                                    insert: u
                                };
                            }
                            var p = e.indexOf(n.radixPoint), h = t;
                            if (t = function(e, t, a, i, n) {
                                return n._radixDance && n.numericInput && t !== n.negationSymbol.back && e <= a && (a > 0 || t == n.radixPoint) && (void 0 === i.validPositions[e - 1] || i.validPositions[e - 1].input !== n.negationSymbol.back) && (e -= 1), 
                                e;
                            }(t, a, p, r, n), "-" === a || a === n.negationSymbol.front) {
                                if (!0 !== n.allowMinus) return !1;
                                var v = !1, m = d("+", r), g = d("-", r);
                                return -1 !== m && (v = [ m, g ]), !1 !== v ? {
                                    remove: v,
                                    caret: h - n.negationSymbol.back.length
                                } : {
                                    insert: [ {
                                        pos: f.call(this, "+", r),
                                        c: n.negationSymbol.front,
                                        fromIsValid: !0
                                    }, {
                                        pos: f.call(this, "-", r),
                                        c: n.negationSymbol.back,
                                        fromIsValid: void 0
                                    } ],
                                    caret: h + n.negationSymbol.back.length
                                };
                            }
                            if (a === n.groupSeparator) return {
                                caret: h
                            };
                            if (s) return !0;
                            if (-1 !== p && !0 === n._radixDance && !1 === i && a === n.radixPoint && void 0 !== n.digits && (isNaN(n.digits) || parseInt(n.digits) > 0) && p !== t) return {
                                caret: n._radixDance && t === p - 1 ? p + 1 : p
                            };
                            if (!1 === n.__financeInput) if (i) {
                                if (n.digitsOptional) return {
                                    rewritePosition: o.end
                                };
                                if (!n.digitsOptional) {
                                    if (o.begin > p && o.end <= p) return a === n.radixPoint ? {
                                        insert: {
                                            pos: p + 1,
                                            c: "0",
                                            fromIsValid: !0
                                        },
                                        rewritePosition: p
                                    } : {
                                        rewritePosition: p + 1
                                    };
                                    if (o.begin < p) return {
                                        rewritePosition: o.begin - 1
                                    };
                                }
                            } else if (!n.showMaskOnHover && !n.showMaskOnFocus && !n.digitsOptional && n.digits > 0 && "" === this.__valueGet.call(this.el)) return {
                                rewritePosition: p
                            };
                            return {
                                rewritePosition: t
                            };
                        },
                        postValidation: function(e, t, a, i, n, r, o) {
                            if (!1 === i) return i;
                            if (o) return !0;
                            if (null !== n.min || null !== n.max) {
                                var s = n.onUnMask(e.slice().reverse().join(""), void 0, l.extend({}, n, {
                                    unmaskAsNumber: !0
                                }));
                                if (null !== n.min && s < n.min && (s.toString().length > n.min.toString().length || s < 0)) return !1;
                                if (null !== n.max && s > n.max) return !!n.SetMaxOnOverflow && {
                                    refreshFromBuffer: !0,
                                    buffer: c(n.max.toString().replace(".", n.radixPoint).split(""), n.digits, n).reverse()
                                };
                            }
                            return i;
                        },
                        onUnMask: function(e, t, a) {
                            if ("" === t && !0 === a.nullable) return t;
                            var i = e.replace(a.prefix, "");
                            return i = (i = i.replace(a.suffix, "")).replace(new RegExp((0, r.default)(a.groupSeparator), "g"), ""), 
                            "" !== a.placeholder.charAt(0) && (i = i.replace(new RegExp(a.placeholder.charAt(0), "g"), "0")), 
                            a.unmaskAsNumber ? ("" !== a.radixPoint && -1 !== i.indexOf(a.radixPoint) && (i = i.replace(r.default.call(this, a.radixPoint), ".")), 
                            i = (i = i.replace(new RegExp("^" + (0, r.default)(a.negationSymbol.front)), "-")).replace(new RegExp((0, 
                            r.default)(a.negationSymbol.back) + "$"), ""), Number(i)) : i;
                        },
                        isComplete: function(e, t) {
                            var a = (t.numericInput ? e.slice().reverse() : e).join("");
                            return a = (a = (a = (a = (a = a.replace(new RegExp("^" + (0, r.default)(t.negationSymbol.front)), "-")).replace(new RegExp((0, 
                            r.default)(t.negationSymbol.back) + "$"), "")).replace(t.prefix, "")).replace(t.suffix, "")).replace(new RegExp((0, 
                            r.default)(t.groupSeparator) + "([0-9]{3})", "g"), "$1"), "," === t.radixPoint && (a = a.replace((0, 
                            r.default)(t.radixPoint), ".")), isFinite(a);
                        },
                        onBeforeMask: function(e, t) {
                            var a = t.radixPoint || ",";
                            isFinite(t.digits) && (t.digits = parseInt(t.digits)), "number" != typeof e && "number" !== t.inputType || "" === a || (e = e.toString().replace(".", a));
                            var i = "-" === e.charAt(0) || e.charAt(0) === t.negationSymbol.front, n = e.split(a), o = n[0].replace(/[^\-0-9]/g, ""), s = n.length > 1 ? n[1].replace(/[^0-9]/g, "") : "", l = n.length > 1;
                            e = o + ("" !== s ? a + s : s);
                            var u = 0;
                            if ("" !== a && (u = t.digitsOptional ? t.digits < s.length ? t.digits : s.length : t.digits, 
                            "" !== s || !t.digitsOptional)) {
                                var f = Math.pow(10, u || 1);
                                e = e.replace((0, r.default)(a), "."), isNaN(parseFloat(e)) || (e = (t.roundingFN(parseFloat(e) * f) / f).toFixed(u)), 
                                e = e.toString().replace(".", a);
                            }
                            if (0 === t.digits && -1 !== e.indexOf(a) && (e = e.substring(0, e.indexOf(a))), 
                            null !== t.min || null !== t.max) {
                                var d = e.toString().replace(a, ".");
                                null !== t.min && d < t.min ? e = t.min.toString().replace(".", a) : null !== t.max && d > t.max && (e = t.max.toString().replace(".", a));
                            }
                            return i && "-" !== e.charAt(0) && (e = "-" + e), c(e.toString().split(""), u, t, l).join("");
                        },
                        onBeforeWrite: function(e, t, a, i) {
                            function n(e, t) {
                                if (!1 !== i.__financeInput || t) {
                                    var a = e.indexOf(i.radixPoint);
                                    -1 !== a && e.splice(a, 1);
                                }
                                if ("" !== i.groupSeparator) for (;-1 !== (a = e.indexOf(i.groupSeparator)); ) e.splice(a, 1);
                                return e;
                            }
                            var o, s = function(e, t) {
                                var a = new RegExp("(^" + ("" !== t.negationSymbol.front ? (0, r.default)(t.negationSymbol.front) + "?" : "") + (0, 
                                r.default)(t.prefix) + ")(.*)(" + (0, r.default)(t.suffix) + ("" != t.negationSymbol.back ? (0, 
                                r.default)(t.negationSymbol.back) + "?" : "") + "$)").exec(e.slice().reverse().join("")), i = a ? a[2] : "", n = !1;
                                return i && (i = i.split(t.radixPoint.charAt(0))[0], n = new RegExp("^[0" + t.groupSeparator + "]*").exec(i)), 
                                !(!n || !(n[0].length > 1 || n[0].length > 0 && n[0].length < i.length)) && n;
                            }(t, i);
                            if (s) for (var u = t.join("").lastIndexOf(s[0].split("").reverse().join("")) - (s[0] == s.input ? 0 : 1), f = s[0] == s.input ? 1 : 0, d = s[0].length - f; d > 0; d--) delete this.maskset.validPositions[u + d], 
                            delete t[u + d];
                            if (e) switch (e.type) {
                              case "blur":
                              case "checkval":
                                if (null !== i.min) {
                                    var p = i.onUnMask(t.slice().reverse().join(""), void 0, l.extend({}, i, {
                                        unmaskAsNumber: !0
                                    }));
                                    if (null !== i.min && p < i.min) return {
                                        refreshFromBuffer: !0,
                                        buffer: c(i.min.toString().replace(".", i.radixPoint).split(""), i.digits, i).reverse()
                                    };
                                }
                                if (t[t.length - 1] === i.negationSymbol.front) {
                                    var h = new RegExp("(^" + ("" != i.negationSymbol.front ? (0, r.default)(i.negationSymbol.front) + "?" : "") + (0, 
                                    r.default)(i.prefix) + ")(.*)(" + (0, r.default)(i.suffix) + ("" != i.negationSymbol.back ? (0, 
                                    r.default)(i.negationSymbol.back) + "?" : "") + "$)").exec(n(t.slice(), !0).reverse().join(""));
                                    0 == (h ? h[2] : "") && (o = {
                                        refreshFromBuffer: !0,
                                        buffer: [ 0 ]
                                    });
                                } else "" !== i.radixPoint && t[0] === i.radixPoint && (o && o.buffer ? o.buffer.shift() : (t.shift(), 
                                o = {
                                    refreshFromBuffer: !0,
                                    buffer: n(t)
                                }));
                                if (i.enforceDigitsOnBlur) {
                                    var v = (o = o || {}) && o.buffer || t.slice().reverse();
                                    o.refreshFromBuffer = !0, o.buffer = c(v, i.digits, i, !0).reverse();
                                }
                            }
                            return o;
                        },
                        onKeyDown: function(e, t, a, i) {
                            var r, o = l(this);
                            if (e.ctrlKey) switch (e.keyCode) {
                              case n.default.UP:
                                return this.inputmask.__valueSet.call(this, parseFloat(this.inputmask.unmaskedvalue()) + parseInt(i.step)), 
                                o.trigger("setvalue"), !1;

                              case n.default.DOWN:
                                return this.inputmask.__valueSet.call(this, parseFloat(this.inputmask.unmaskedvalue()) - parseInt(i.step)), 
                                o.trigger("setvalue"), !1;
                            }
                            if (!e.shiftKey && (e.keyCode === n.default.DELETE || e.keyCode === n.default.BACKSPACE || e.keyCode === n.default.BACKSPACE_SAFARI) && a.begin !== t.length) {
                                if (t[e.keyCode === n.default.DELETE ? a.begin - 1 : a.end] === i.negationSymbol.front) return r = t.slice().reverse(), 
                                "" !== i.negationSymbol.front && r.shift(), "" !== i.negationSymbol.back && r.pop(), 
                                o.trigger("setvalue", [ r.join(""), a.begin ]), !1;
                                if (!0 === i._radixDance) {
                                    var s = t.indexOf(i.radixPoint);
                                    if (i.digitsOptional) {
                                        if (0 === s) return (r = t.slice().reverse()).pop(), o.trigger("setvalue", [ r.join(""), a.begin >= r.length ? r.length : a.begin ]), 
                                        !1;
                                    } else if (-1 !== s && (a.begin < s || a.end < s || e.keyCode === n.default.DELETE && a.begin === s)) return a.begin !== a.end || e.keyCode !== n.default.BACKSPACE && e.keyCode !== n.default.BACKSPACE_SAFARI || a.begin++, 
                                    (r = t.slice().reverse()).splice(r.length - a.begin, a.begin - a.end + 1), r = c(r, i.digits, i).join(""), 
                                    o.trigger("setvalue", [ r, a.begin >= r.length ? s + 1 : a.begin ]), !1;
                                }
                            }
                        }
                    },
                    currency: {
                        prefix: "",
                        groupSeparator: ",",
                        alias: "numeric",
                        digits: 2,
                        digitsOptional: !1
                    },
                    decimal: {
                        alias: "numeric"
                    },
                    integer: {
                        alias: "numeric",
                        inputmode: "numeric",
                        digits: 0
                    },
                    percentage: {
                        alias: "numeric",
                        min: 0,
                        max: 100,
                        suffix: " %",
                        digits: 0,
                        allowMinus: !1
                    },
                    indianns: {
                        alias: "numeric",
                        _mask: function(e) {
                            return "(" + e.groupSeparator + "99){*|1}(" + e.groupSeparator + "999){1|1}";
                        },
                        groupSeparator: ",",
                        radixPoint: ".",
                        placeholder: "0",
                        digits: 2,
                        digitsOptional: !1
                    }
                });
            },
            9380: function(e, t, a) {
                var i;
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0;
                var n = ((i = a(8741)) && i.__esModule ? i : {
                    default: i
                }).default ? window : {};
                t.default = n;
            },
            7760: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.applyInputValue = c, t.clearOptionalTail = f, t.checkVal = d, t.HandleNativePlaceholder = function(e, t) {
                    var a = e ? e.inputmask : this;
                    if (l.ie) {
                        if (e.inputmask._valueGet() !== t && (e.placeholder !== t || "" === e.placeholder)) {
                            var i = o.getBuffer.call(a).slice(), n = e.inputmask._valueGet();
                            if (n !== t) {
                                var r = o.getLastValidPosition.call(a);
                                -1 === r && n === o.getBufferTemplate.call(a).join("") ? i = [] : -1 !== r && f.call(a, i), 
                                p(e, i);
                            }
                        }
                    } else e.placeholder !== t && (e.placeholder = t, "" === e.placeholder && e.removeAttribute("placeholder"));
                }, t.unmaskedvalue = function(e) {
                    var t = e ? e.inputmask : this, a = t.opts, i = t.maskset;
                    if (e) {
                        if (void 0 === e.inputmask) return e.value;
                        e.inputmask && e.inputmask.refreshValue && c(e, e.inputmask._valueGet(!0));
                    }
                    var n = [], r = i.validPositions;
                    for (var s in r) r[s] && r[s].match && (1 != r[s].match.static || Array.isArray(i.metadata) && !0 !== r[s].generatedInput) && n.push(r[s].input);
                    var l = 0 === n.length ? "" : (t.isRTL ? n.reverse() : n).join("");
                    if ("function" == typeof a.onUnMask) {
                        var u = (t.isRTL ? o.getBuffer.call(t).slice().reverse() : o.getBuffer.call(t)).join("");
                        l = a.onUnMask.call(t, u, l, a);
                    }
                    return l;
                }, t.writeBuffer = p;
                var i, n = (i = a(4528)) && i.__esModule ? i : {
                    default: i
                }, r = a(4713), o = a(8711), s = a(7215), l = a(9845), u = a(6030);
                function c(e, t) {
                    var a = e ? e.inputmask : this, i = a.opts;
                    e.inputmask.refreshValue = !1, "function" == typeof i.onBeforeMask && (t = i.onBeforeMask.call(a, t, i) || t), 
                    d(e, !0, !1, t = t.toString().split("")), a.undoValue = a._valueGet(!0), (i.clearMaskOnLostFocus || i.clearIncomplete) && e.inputmask._valueGet() === o.getBufferTemplate.call(a).join("") && -1 === o.getLastValidPosition.call(a) && e.inputmask._valueSet("");
                }
                function f(e) {
                    e.length = 0;
                    for (var t, a = r.getMaskTemplate.call(this, !0, 0, !0, void 0, !0); void 0 !== (t = a.shift()); ) e.push(t);
                    return e;
                }
                function d(e, t, a, i, n) {
                    var l = e ? e.inputmask : this, c = l.maskset, f = l.opts, d = l.dependencyLib, h = i.slice(), v = "", m = -1, g = void 0, k = f.skipOptionalPartCharacter;
                    f.skipOptionalPartCharacter = "", o.resetMaskSet.call(l), c.tests = {}, m = f.radixPoint ? o.determineNewCaretPosition.call(l, {
                        begin: 0,
                        end: 0
                    }, !1, !1 === f.__financeInput ? "radixFocus" : void 0).begin : 0, c.p = m, l.caretPos = {
                        begin: m
                    };
                    var y = [], b = l.caretPos;
                    if (h.forEach((function(e, t) {
                        if (void 0 !== e) {
                            var i = new d.Event("_checkval");
                            i.which = e.toString().charCodeAt(0), v += e;
                            var n = o.getLastValidPosition.call(l, void 0, !0);
                            !function(e, t) {
                                for (var a = r.getMaskTemplate.call(l, !0, 0).slice(e, o.seekNext.call(l, e, !1, !1)).join("").replace(/'/g, ""), i = a.indexOf(t); i > 0 && " " === a[i - 1]; ) i--;
                                var n = 0 === i && !o.isMask.call(l, e) && (r.getTest.call(l, e).match.nativeDef === t.charAt(0) || !0 === r.getTest.call(l, e).match.static && r.getTest.call(l, e).match.nativeDef === "'" + t.charAt(0) || " " === r.getTest.call(l, e).match.nativeDef && (r.getTest.call(l, e + 1).match.nativeDef === t.charAt(0) || !0 === r.getTest.call(l, e + 1).match.static && r.getTest.call(l, e + 1).match.nativeDef === "'" + t.charAt(0)));
                                if (!n && i > 0 && !o.isMask.call(l, e, !1, !0)) {
                                    var s = o.seekNext.call(l, e);
                                    l.caretPos.begin < s && (l.caretPos = {
                                        begin: s
                                    });
                                }
                                return n;
                            }(m, v) ? (g = u.EventHandlers.keypressEvent.call(l, i, !0, !1, a, l.caretPos.begin)) && (m = l.caretPos.begin + 1, 
                            v = "") : g = u.EventHandlers.keypressEvent.call(l, i, !0, !1, a, n + 1), g ? (void 0 !== g.pos && c.validPositions[g.pos] && !0 === c.validPositions[g.pos].match.static && void 0 === c.validPositions[g.pos].alternation && (y.push(g.pos), 
                            l.isRTL || (g.forwardPosition = g.pos + 1)), p.call(l, void 0, o.getBuffer.call(l), g.forwardPosition, i, !1), 
                            l.caretPos = {
                                begin: g.forwardPosition,
                                end: g.forwardPosition
                            }, b = l.caretPos) : void 0 === c.validPositions[t] && h[t] === r.getPlaceholder.call(l, t) && o.isMask.call(l, t, !0) ? l.caretPos.begin++ : l.caretPos = b;
                        }
                    })), y.length > 0) {
                        var x, P, E = o.seekNext.call(l, -1, void 0, !1);
                        if (!s.isComplete.call(l, o.getBuffer.call(l)) && y.length <= E || s.isComplete.call(l, o.getBuffer.call(l)) && y.length > 0 && y.length !== E && 0 === y[0]) for (var S = E; void 0 !== (x = y.shift()); ) {
                            var _ = new d.Event("_checkval");
                            if ((P = c.validPositions[x]).generatedInput = !0, _.which = P.input.charCodeAt(0), 
                            (g = u.EventHandlers.keypressEvent.call(l, _, !0, !1, a, S)) && void 0 !== g.pos && g.pos !== x && c.validPositions[g.pos] && !0 === c.validPositions[g.pos].match.static) y.push(g.pos); else if (!g) break;
                            S++;
                        }
                    }
                    t && p.call(l, e, o.getBuffer.call(l), g ? g.forwardPosition : l.caretPos.begin, n || new d.Event("checkval"), n && "input" === n.type && l.undoValue !== l._valueGet(!0)), 
                    f.skipOptionalPartCharacter = k;
                }
                function p(e, t, a, i, r) {
                    var l = e ? e.inputmask : this, u = l.opts, c = l.dependencyLib;
                    if (i && "function" == typeof u.onBeforeWrite) {
                        var f = u.onBeforeWrite.call(l, i, t, a, u);
                        if (f) {
                            if (f.refreshFromBuffer) {
                                var d = f.refreshFromBuffer;
                                s.refreshFromBuffer.call(l, !0 === d ? d : d.start, d.end, f.buffer || t), t = o.getBuffer.call(l, !0);
                            }
                            void 0 !== a && (a = void 0 !== f.caret ? f.caret : a);
                        }
                    }
                    if (void 0 !== e && (e.inputmask._valueSet(t.join("")), void 0 === a || void 0 !== i && "blur" === i.type || o.caret.call(l, e, a, void 0, void 0, void 0 !== i && "keydown" === i.type && (i.keyCode === n.default.DELETE || i.keyCode === n.default.BACKSPACE)), 
                    !0 === r)) {
                        var p = c(e), h = e.inputmask._valueGet();
                        e.inputmask.skipInputEvent = !0, p.trigger("input"), setTimeout((function() {
                            h === o.getBufferTemplate.call(l).join("") ? p.trigger("cleared") : !0 === s.isComplete.call(l, t) && p.trigger("complete");
                        }), 0);
                    }
                }
            },
            2394: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = void 0, a(7149), a(3194);
                var i = a(157), n = m(a(4963)), r = m(a(9380)), o = a(2391), s = a(4713), l = a(8711), u = a(7215), c = a(7760), f = a(9716), d = m(a(7392)), p = m(a(3976)), h = m(a(8741));
                function v(e) {
                    return (v = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e;
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    })(e);
                }
                function m(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var g = r.default.document, k = "_inputmask_opts";
                function y(e, t, a) {
                    if (h.default) {
                        if (!(this instanceof y)) return new y(e, t, a);
                        this.dependencyLib = n.default, this.el = void 0, this.events = {}, this.maskset = void 0, 
                        !0 !== a && ("[object Object]" === Object.prototype.toString.call(e) ? t = e : (t = t || {}, 
                        e && (t.alias = e)), this.opts = n.default.extend(!0, {}, this.defaults, t), this.noMasksCache = t && void 0 !== t.definitions, 
                        this.userOptions = t || {}, b(this.opts.alias, t, this.opts)), this.refreshValue = !1, 
                        this.undoValue = void 0, this.$el = void 0, this.skipKeyPressEvent = !1, this.skipInputEvent = !1, 
                        this.validationEvent = !1, this.ignorable = !1, this.maxLength, this.mouseEnter = !1, 
                        this.originalPlaceholder = void 0, this.isComposing = !1;
                    }
                }
                function b(e, t, a) {
                    var i = y.prototype.aliases[e];
                    return i ? (i.alias && b(i.alias, void 0, a), n.default.extend(!0, a, i), n.default.extend(!0, a, t), 
                    !0) : (null === a.mask && (a.mask = e), !1);
                }
                y.prototype = {
                    dataAttribute: "data-inputmask",
                    defaults: p.default,
                    definitions: d.default,
                    aliases: {},
                    masksCache: {},
                    get isRTL() {
                        return this.opts.isRTL || this.opts.numericInput;
                    },
                    mask: function(e) {
                        var t = this;
                        return "string" == typeof e && (e = g.getElementById(e) || g.querySelectorAll(e)), 
                        (e = e.nodeName ? [ e ] : Array.isArray(e) ? e : Array.from(e)).forEach((function(e, a) {
                            var s = n.default.extend(!0, {}, t.opts);
                            if (function(e, t, a, i) {
                                function o(t, n) {
                                    var o = "" === i ? t : i + "-" + t;
                                    null !== (n = void 0 !== n ? n : e.getAttribute(o)) && ("string" == typeof n && (0 === t.indexOf("on") ? n = r.default[n] : "false" === n ? n = !1 : "true" === n && (n = !0)), 
                                    a[t] = n);
                                }
                                if (!0 === t.importDataAttributes) {
                                    var s, l, u, c, f = e.getAttribute(i);
                                    if (f && "" !== f && (f = f.replace(/'/g, '"'), l = JSON.parse("{" + f + "}")), 
                                    l) for (c in u = void 0, l) if ("alias" === c.toLowerCase()) {
                                        u = l[c];
                                        break;
                                    }
                                    for (s in o("alias", u), a.alias && b(a.alias, a, t), t) {
                                        if (l) for (c in u = void 0, l) if (c.toLowerCase() === s.toLowerCase()) {
                                            u = l[c];
                                            break;
                                        }
                                        o(s, u);
                                    }
                                }
                                n.default.extend(!0, t, a), ("rtl" === e.dir || t.rightAlign) && (e.style.textAlign = "right");
                                ("rtl" === e.dir || t.numericInput) && (e.dir = "ltr", e.removeAttribute("dir"), 
                                t.isRTL = !0);
                                return Object.keys(a).length;
                            }(e, s, n.default.extend(!0, {}, t.userOptions), t.dataAttribute)) {
                                var l = (0, o.generateMaskSet)(s, t.noMasksCache);
                                void 0 !== l && (void 0 !== e.inputmask && (e.inputmask.opts.autoUnmask = !0, e.inputmask.remove()), 
                                e.inputmask = new y(void 0, void 0, !0), e.inputmask.opts = s, e.inputmask.noMasksCache = t.noMasksCache, 
                                e.inputmask.userOptions = n.default.extend(!0, {}, t.userOptions), e.inputmask.el = e, 
                                e.inputmask.$el = (0, n.default)(e), e.inputmask.maskset = l, n.default.data(e, k, t.userOptions), 
                                i.mask.call(e.inputmask));
                            }
                        })), e && e[0] && e[0].inputmask || this;
                    },
                    option: function(e, t) {
                        return "string" == typeof e ? this.opts[e] : "object" === v(e) ? (n.default.extend(this.userOptions, e), 
                        this.el && !0 !== t && this.mask(this.el), this) : void 0;
                    },
                    unmaskedvalue: function(e) {
                        if (this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache), 
                        void 0 === this.el || void 0 !== e) {
                            var t = ("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, e, this.opts) || e).split("");
                            c.checkVal.call(this, void 0, !1, !1, t), "function" == typeof this.opts.onBeforeWrite && this.opts.onBeforeWrite.call(this, void 0, l.getBuffer.call(this), 0, this.opts);
                        }
                        return c.unmaskedvalue.call(this, this.el);
                    },
                    remove: function() {
                        if (this.el) {
                            n.default.data(this.el, k, null);
                            var e = this.opts.autoUnmask ? (0, c.unmaskedvalue)(this.el) : this._valueGet(this.opts.autoUnmask);
                            e !== l.getBufferTemplate.call(this).join("") ? this._valueSet(e, this.opts.autoUnmask) : this._valueSet(""), 
                            f.EventRuler.off(this.el), Object.getOwnPropertyDescriptor && Object.getPrototypeOf ? Object.getOwnPropertyDescriptor(Object.getPrototypeOf(this.el), "value") && this.__valueGet && Object.defineProperty(this.el, "value", {
                                get: this.__valueGet,
                                set: this.__valueSet,
                                configurable: !0
                            }) : g.__lookupGetter__ && this.el.__lookupGetter__("value") && this.__valueGet && (this.el.__defineGetter__("value", this.__valueGet), 
                            this.el.__defineSetter__("value", this.__valueSet)), this.el.inputmask = void 0;
                        }
                        return this.el;
                    },
                    getemptymask: function() {
                        return this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache), 
                        l.getBufferTemplate.call(this).join("");
                    },
                    hasMaskedValue: function() {
                        return !this.opts.autoUnmask;
                    },
                    isComplete: function() {
                        return this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache), 
                        u.isComplete.call(this, l.getBuffer.call(this));
                    },
                    getmetadata: function() {
                        if (this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache), 
                        Array.isArray(this.maskset.metadata)) {
                            var e = s.getMaskTemplate.call(this, !0, 0, !1).join("");
                            return this.maskset.metadata.forEach((function(t) {
                                return t.mask !== e || (e = t, !1);
                            })), e;
                        }
                        return this.maskset.metadata;
                    },
                    isValid: function(e) {
                        if (this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache), 
                        e) {
                            var t = ("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, e, this.opts) || e).split("");
                            c.checkVal.call(this, void 0, !0, !1, t);
                        } else e = this.isRTL ? l.getBuffer.call(this).slice().reverse().join("") : l.getBuffer.call(this).join("");
                        for (var a = l.getBuffer.call(this), i = l.determineLastRequiredPosition.call(this), n = a.length - 1; n > i && !l.isMask.call(this, n); n--) ;
                        return a.splice(i, n + 1 - i), u.isComplete.call(this, a) && e === (this.isRTL ? l.getBuffer.call(this).slice().reverse().join("") : l.getBuffer.call(this).join(""));
                    },
                    format: function(e, t) {
                        this.maskset = this.maskset || (0, o.generateMaskSet)(this.opts, this.noMasksCache);
                        var a = ("function" == typeof this.opts.onBeforeMask && this.opts.onBeforeMask.call(this, e, this.opts) || e).split("");
                        c.checkVal.call(this, void 0, !0, !1, a);
                        var i = this.isRTL ? l.getBuffer.call(this).slice().reverse().join("") : l.getBuffer.call(this).join("");
                        return t ? {
                            value: i,
                            metadata: this.getmetadata()
                        } : i;
                    },
                    setValue: function(e) {
                        this.el && (0, n.default)(this.el).trigger("setvalue", [ e ]);
                    },
                    analyseMask: o.analyseMask
                }, y.extendDefaults = function(e) {
                    n.default.extend(!0, y.prototype.defaults, e);
                }, y.extendDefinitions = function(e) {
                    n.default.extend(!0, y.prototype.definitions, e);
                }, y.extendAliases = function(e) {
                    n.default.extend(!0, y.prototype.aliases, e);
                }, y.format = function(e, t, a) {
                    return y(t).format(e, a);
                }, y.unmask = function(e, t) {
                    return y(t).unmaskedvalue(e);
                }, y.isValid = function(e, t) {
                    return y(t).isValid(e);
                }, y.remove = function(e) {
                    "string" == typeof e && (e = g.getElementById(e) || g.querySelectorAll(e)), (e = e.nodeName ? [ e ] : e).forEach((function(e) {
                        e.inputmask && e.inputmask.remove();
                    }));
                }, y.setValue = function(e, t) {
                    "string" == typeof e && (e = g.getElementById(e) || g.querySelectorAll(e)), (e = e.nodeName ? [ e ] : e).forEach((function(e) {
                        e.inputmask ? e.inputmask.setValue(t) : (0, n.default)(e).trigger("setvalue", [ t ]);
                    }));
                }, y.dependencyLib = n.default, r.default.Inputmask = y;
                var x = y;
                t.default = x;
            },
            5296: function(e, t, a) {
                function i(e) {
                    return (i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e;
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    })(e);
                }
                var n = p(a(9380)), r = p(a(2394)), o = p(a(8741));
                function s(e, t) {
                    return !t || "object" !== i(t) && "function" != typeof t ? function(e) {
                        if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                        return e;
                    }(e) : t;
                }
                function l(e) {
                    var t = "function" == typeof Map ? new Map : void 0;
                    return (l = function(e) {
                        if (null === e || (a = e, -1 === Function.toString.call(a).indexOf("[native code]"))) return e;
                        var a;
                        if ("function" != typeof e) throw new TypeError("Super expression must either be null or a function");
                        if (void 0 !== t) {
                            if (t.has(e)) return t.get(e);
                            t.set(e, i);
                        }
                        function i() {
                            return u(e, arguments, d(this).constructor);
                        }
                        return i.prototype = Object.create(e.prototype, {
                            constructor: {
                                value: i,
                                enumerable: !1,
                                writable: !0,
                                configurable: !0
                            }
                        }), f(i, e);
                    })(e);
                }
                function u(e, t, a) {
                    return (u = c() ? Reflect.construct : function(e, t, a) {
                        var i = [ null ];
                        i.push.apply(i, t);
                        var n = new (Function.bind.apply(e, i));
                        return a && f(n, a.prototype), n;
                    }).apply(null, arguments);
                }
                function c() {
                    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                    if (Reflect.construct.sham) return !1;
                    if ("function" == typeof Proxy) return !0;
                    try {
                        return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], (function() {}))), 
                        !0;
                    } catch (e) {
                        return !1;
                    }
                }
                function f(e, t) {
                    return (f = Object.setPrototypeOf || function(e, t) {
                        return e.__proto__ = t, e;
                    })(e, t);
                }
                function d(e) {
                    return (d = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
                        return e.__proto__ || Object.getPrototypeOf(e);
                    })(e);
                }
                function p(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
                var h = n.default.document;
                if (o.default && h && h.head && h.head.attachShadow && n.default.customElements && void 0 === n.default.customElements.get("input-mask")) {
                    var v = function(e) {
                        !function(e, t) {
                            if ("function" != typeof t && null !== t) throw new TypeError("Super expression must either be null or a function");
                            e.prototype = Object.create(t && t.prototype, {
                                constructor: {
                                    value: e,
                                    writable: !0,
                                    configurable: !0
                                }
                            }), t && f(e, t);
                        }(n, e);
                        var t, a, i = (t = n, a = c(), function() {
                            var e, i = d(t);
                            if (a) {
                                var n = d(this).constructor;
                                e = Reflect.construct(i, arguments, n);
                            } else e = i.apply(this, arguments);
                            return s(this, e);
                        });
                        function n() {
                            var e;
                            !function(e, t) {
                                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
                            }(this, n);
                            var t = (e = i.call(this)).getAttributeNames(), a = e.attachShadow({
                                mode: "closed"
                            }), o = h.createElement("input");
                            for (var s in o.type = "text", a.appendChild(o), t) Object.prototype.hasOwnProperty.call(t, s) && o.setAttribute(t[s], e.getAttribute(t[s]));
                            var l = new r.default;
                            return l.dataAttribute = "", l.mask(o), o.inputmask.shadowRoot = a, e;
                        }
                        return n;
                    }(l(HTMLElement));
                    n.default.customElements.define("input-mask", v);
                }
            },
            2391: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.generateMaskSet = function(e, t) {
                    var a;
                    function n(e, a, n) {
                        var r, o, s = !1;
                        if (null !== e && "" !== e || ((s = null !== n.regex) ? e = (e = n.regex).replace(/^(\^)(.*)(\$)$/, "$2") : (s = !0, 
                        e = ".*")), 1 === e.length && !1 === n.greedy && 0 !== n.repeat && (n.placeholder = ""), 
                        n.repeat > 0 || "*" === n.repeat || "+" === n.repeat) {
                            var l = "*" === n.repeat ? 0 : "+" === n.repeat ? 1 : n.repeat;
                            e = n.groupmarker[0] + e + n.groupmarker[1] + n.quantifiermarker[0] + l + "," + n.repeat + n.quantifiermarker[1];
                        }
                        return o = s ? "regex_" + n.regex : n.numericInput ? e.split("").reverse().join("") : e, 
                        !1 !== n.keepStatic && (o = "ks_" + o), void 0 === Inputmask.prototype.masksCache[o] || !0 === t ? (r = {
                            mask: e,
                            maskToken: Inputmask.prototype.analyseMask(e, s, n),
                            validPositions: {},
                            _buffer: void 0,
                            buffer: void 0,
                            tests: {},
                            excludes: {},
                            metadata: a,
                            maskLength: void 0,
                            jitOffset: {}
                        }, !0 !== t && (Inputmask.prototype.masksCache[o] = r, r = i.default.extend(!0, {}, Inputmask.prototype.masksCache[o]))) : r = i.default.extend(!0, {}, Inputmask.prototype.masksCache[o]), 
                        r;
                    }
                    "function" == typeof e.mask && (e.mask = e.mask(e));
                    if (Array.isArray(e.mask)) {
                        if (e.mask.length > 1) {
                            null === e.keepStatic && (e.keepStatic = !0);
                            var r = e.groupmarker[0];
                            return (e.isRTL ? e.mask.reverse() : e.mask).forEach((function(t) {
                                r.length > 1 && (r += e.groupmarker[1] + e.alternatormarker + e.groupmarker[0]), 
                                void 0 !== t.mask && "function" != typeof t.mask ? r += t.mask : r += t;
                            })), n(r += e.groupmarker[1], e.mask, e);
                        }
                        e.mask = e.mask.pop();
                    }
                    null === e.keepStatic && (e.keepStatic = !1);
                    a = e.mask && void 0 !== e.mask.mask && "function" != typeof e.mask.mask ? n(e.mask.mask, e.mask, e) : n(e.mask, e.mask, e);
                    return a;
                }, t.analyseMask = function(e, t, a) {
                    var i, r, o, s, l, u, c = /(?:[?*+]|\{[0-9+*]+(?:,[0-9+*]*)?(?:\|[0-9+*]*)?\})|[^.?*+^${[]()|\\]+|./g, f = /\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g, d = !1, p = new n.default, h = [], v = [], m = !1;
                    function g(e, i, n) {
                        n = void 0 !== n ? n : e.matches.length;
                        var r = e.matches[n - 1];
                        if (t) 0 === i.indexOf("[") || d && /\\d|\\s|\\w]/i.test(i) || "." === i ? e.matches.splice(n++, 0, {
                            fn: new RegExp(i, a.casing ? "i" : ""),
                            static: !1,
                            optionality: !1,
                            newBlockMarker: void 0 === r ? "master" : r.def !== i,
                            casing: null,
                            def: i,
                            placeholder: void 0,
                            nativeDef: i
                        }) : (d && (i = i[i.length - 1]), i.split("").forEach((function(t, i) {
                            r = e.matches[n - 1], e.matches.splice(n++, 0, {
                                fn: /[a-z]/i.test(a.staticDefinitionSymbol || t) ? new RegExp("[" + (a.staticDefinitionSymbol || t) + "]", a.casing ? "i" : "") : null,
                                static: !0,
                                optionality: !1,
                                newBlockMarker: void 0 === r ? "master" : r.def !== t && !0 !== r.static,
                                casing: null,
                                def: a.staticDefinitionSymbol || t,
                                placeholder: void 0 !== a.staticDefinitionSymbol ? t : void 0,
                                nativeDef: (d ? "'" : "") + t
                            });
                        }))), d = !1; else {
                            var o = a.definitions && a.definitions[i] || a.usePrototypeDefinitions && Inputmask.prototype.definitions[i];
                            o && !d ? e.matches.splice(n++, 0, {
                                fn: o.validator ? "string" == typeof o.validator ? new RegExp(o.validator, a.casing ? "i" : "") : new function() {
                                    this.test = o.validator;
                                } : new RegExp("."),
                                static: o.static || !1,
                                optionality: !1,
                                newBlockMarker: void 0 === r ? "master" : r.def !== (o.definitionSymbol || i),
                                casing: o.casing,
                                def: o.definitionSymbol || i,
                                placeholder: o.placeholder,
                                nativeDef: i,
                                generated: o.generated
                            }) : (e.matches.splice(n++, 0, {
                                fn: /[a-z]/i.test(a.staticDefinitionSymbol || i) ? new RegExp("[" + (a.staticDefinitionSymbol || i) + "]", a.casing ? "i" : "") : null,
                                static: !0,
                                optionality: !1,
                                newBlockMarker: void 0 === r ? "master" : r.def !== i && !0 !== r.static,
                                casing: null,
                                def: a.staticDefinitionSymbol || i,
                                placeholder: void 0 !== a.staticDefinitionSymbol ? i : void 0,
                                nativeDef: (d ? "'" : "") + i
                            }), d = !1);
                        }
                    }
                    function k() {
                        if (h.length > 0) {
                            if (g(s = h[h.length - 1], r), s.isAlternator) {
                                l = h.pop();
                                for (var e = 0; e < l.matches.length; e++) l.matches[e].isGroup && (l.matches[e].isGroup = !1);
                                h.length > 0 ? (s = h[h.length - 1]).matches.push(l) : p.matches.push(l);
                            }
                        } else g(p, r);
                    }
                    function y(e) {
                        var t = new n.default(!0);
                        return t.openGroup = !1, t.matches = e, t;
                    }
                    function b() {
                        if ((o = h.pop()).openGroup = !1, void 0 !== o) if (h.length > 0) {
                            if ((s = h[h.length - 1]).matches.push(o), s.isAlternator) {
                                l = h.pop();
                                for (var e = 0; e < l.matches.length; e++) l.matches[e].isGroup = !1, l.matches[e].alternatorGroup = !1;
                                h.length > 0 ? (s = h[h.length - 1]).matches.push(l) : p.matches.push(l);
                            }
                        } else p.matches.push(o); else k();
                    }
                    function x(e) {
                        var t = e.pop();
                        return t.isQuantifier && (t = y([ e.pop(), t ])), t;
                    }
                    t && (a.optionalmarker[0] = void 0, a.optionalmarker[1] = void 0);
                    for (;i = t ? f.exec(e) : c.exec(e); ) {
                        if (r = i[0], t) switch (r.charAt(0)) {
                          case "?":
                            r = "{0,1}";
                            break;

                          case "+":
                          case "*":
                            r = "{" + r + "}";
                            break;

                          case "|":
                            if (0 === h.length) {
                                var P = y(p.matches);
                                P.openGroup = !0, h.push(P), p.matches = [], m = !0;
                            }
                        }
                        if (d) k(); else switch (r.charAt(0)) {
                          case "$":
                          case "^":
                            t || k();
                            break;

                          case "(?=":
                          case "(?!":
                          case "(?<=":
                          case "(?<!":
                            h.push(new n.default(!0));
                            break;

                          case a.escapeChar:
                            d = !0, t && k();
                            break;

                          case a.optionalmarker[1]:
                          case a.groupmarker[1]:
                            b();
                            break;

                          case a.optionalmarker[0]:
                            h.push(new n.default(!1, !0));
                            break;

                          case a.groupmarker[0]:
                            h.push(new n.default(!0));
                            break;

                          case a.quantifiermarker[0]:
                            var E = new n.default(!1, !1, !0), S = (r = r.replace(/[{}]/g, "")).split("|"), _ = S[0].split(","), M = isNaN(_[0]) ? _[0] : parseInt(_[0]), w = 1 === _.length ? M : isNaN(_[1]) ? _[1] : parseInt(_[1]), O = isNaN(S[1]) ? S[1] : parseInt(S[1]);
                            "*" !== M && "+" !== M || (M = "*" === w ? 0 : 1), E.quantifier = {
                                min: M,
                                max: w,
                                jit: O
                            };
                            var T = h.length > 0 ? h[h.length - 1].matches : p.matches;
                            if ((i = T.pop()).isAlternator) {
                                T.push(i), T = i.matches;
                                var C = new n.default(!0), A = T.pop();
                                T.push(C), T = C.matches, i = A;
                            }
                            i.isGroup || (i = y([ i ])), T.push(i), T.push(E);
                            break;

                          case a.alternatormarker:
                            if (h.length > 0) {
                                var D = (s = h[h.length - 1]).matches[s.matches.length - 1];
                                u = s.openGroup && (void 0 === D.matches || !1 === D.isGroup && !1 === D.isAlternator) ? h.pop() : x(s.matches);
                            } else u = x(p.matches);
                            if (u.isAlternator) h.push(u); else if (u.alternatorGroup ? (l = h.pop(), u.alternatorGroup = !1) : l = new n.default(!1, !1, !1, !0), 
                            l.matches.push(u), h.push(l), u.openGroup) {
                                u.openGroup = !1;
                                var B = new n.default(!0);
                                B.alternatorGroup = !0, h.push(B);
                            }
                            break;

                          default:
                            k();
                        }
                    }
                    m && b();
                    for (;h.length > 0; ) o = h.pop(), p.matches.push(o);
                    p.matches.length > 0 && (!function e(i) {
                        i && i.matches && i.matches.forEach((function(n, r) {
                            var o = i.matches[r + 1];
                            (void 0 === o || void 0 === o.matches || !1 === o.isQuantifier) && n && n.isGroup && (n.isGroup = !1, 
                            t || (g(n, a.groupmarker[0], 0), !0 !== n.openGroup && g(n, a.groupmarker[1]))), 
                            e(n);
                        }));
                    }(p), v.push(p));
                    (a.numericInput || a.isRTL) && function e(t) {
                        for (var i in t.matches = t.matches.reverse(), t.matches) if (Object.prototype.hasOwnProperty.call(t.matches, i)) {
                            var n = parseInt(i);
                            if (t.matches[i].isQuantifier && t.matches[n + 1] && t.matches[n + 1].isGroup) {
                                var r = t.matches[i];
                                t.matches.splice(i, 1), t.matches.splice(n + 1, 0, r);
                            }
                            void 0 !== t.matches[i].matches ? t.matches[i] = e(t.matches[i]) : t.matches[i] = ((o = t.matches[i]) === a.optionalmarker[0] ? o = a.optionalmarker[1] : o === a.optionalmarker[1] ? o = a.optionalmarker[0] : o === a.groupmarker[0] ? o = a.groupmarker[1] : o === a.groupmarker[1] && (o = a.groupmarker[0]), 
                            o);
                        }
                        var o;
                        return t;
                    }(v[0]);
                    return v;
                };
                var i = r(a(4963)), n = r(a(9695));
                function r(e) {
                    return e && e.__esModule ? e : {
                        default: e
                    };
                }
            },
            157: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.mask = function() {
                    var e = this, t = this.opts, a = this.el, i = this.dependencyLib;
                    s.EventRuler.off(a);
                    var f = function(t, a) {
                        "textarea" !== t.tagName.toLowerCase() && a.ignorables.push(n.default.ENTER);
                        var l = t.getAttribute("type"), u = "input" === t.tagName.toLowerCase() && a.supportsInputType.includes(l) || t.isContentEditable || "textarea" === t.tagName.toLowerCase();
                        if (!u) if ("input" === t.tagName.toLowerCase()) {
                            var c = document.createElement("input");
                            c.setAttribute("type", l), u = "text" === c.type, c = null;
                        } else u = "partial";
                        return !1 !== u ? function(t) {
                            var n, l;
                            function u() {
                                return this.inputmask ? this.inputmask.opts.autoUnmask ? this.inputmask.unmaskedvalue() : -1 !== r.getLastValidPosition.call(e) || !0 !== a.nullable ? (this.inputmask.shadowRoot || this.ownerDocument).activeElement === this && a.clearMaskOnLostFocus ? (e.isRTL ? o.clearOptionalTail.call(e, r.getBuffer.call(e).slice()).reverse() : o.clearOptionalTail.call(e, r.getBuffer.call(e).slice())).join("") : n.call(this) : "" : n.call(this);
                            }
                            function c(e) {
                                l.call(this, e), this.inputmask && (0, o.applyInputValue)(this, e);
                            }
                            if (!t.inputmask.__valueGet) {
                                if (!0 !== a.noValuePatching) {
                                    if (Object.getOwnPropertyDescriptor) {
                                        var f = Object.getPrototypeOf ? Object.getOwnPropertyDescriptor(Object.getPrototypeOf(t), "value") : void 0;
                                        f && f.get && f.set ? (n = f.get, l = f.set, Object.defineProperty(t, "value", {
                                            get: u,
                                            set: c,
                                            configurable: !0
                                        })) : "input" !== t.tagName.toLowerCase() && (n = function() {
                                            return this.textContent;
                                        }, l = function(e) {
                                            this.textContent = e;
                                        }, Object.defineProperty(t, "value", {
                                            get: u,
                                            set: c,
                                            configurable: !0
                                        }));
                                    } else document.__lookupGetter__ && t.__lookupGetter__("value") && (n = t.__lookupGetter__("value"), 
                                    l = t.__lookupSetter__("value"), t.__defineGetter__("value", u), t.__defineSetter__("value", c));
                                    t.inputmask.__valueGet = n, t.inputmask.__valueSet = l;
                                }
                                t.inputmask._valueGet = function(t) {
                                    return e.isRTL && !0 !== t ? n.call(this.el).split("").reverse().join("") : n.call(this.el);
                                }, t.inputmask._valueSet = function(t, a) {
                                    l.call(this.el, null == t ? "" : !0 !== a && e.isRTL ? t.split("").reverse().join("") : t);
                                }, void 0 === n && (n = function() {
                                    return this.value;
                                }, l = function(e) {
                                    this.value = e;
                                }, function(t) {
                                    if (i.valHooks && (void 0 === i.valHooks[t] || !0 !== i.valHooks[t].inputmaskpatch)) {
                                        var n = i.valHooks[t] && i.valHooks[t].get ? i.valHooks[t].get : function(e) {
                                            return e.value;
                                        }, s = i.valHooks[t] && i.valHooks[t].set ? i.valHooks[t].set : function(e, t) {
                                            return e.value = t, e;
                                        };
                                        i.valHooks[t] = {
                                            get: function(t) {
                                                if (t.inputmask) {
                                                    if (t.inputmask.opts.autoUnmask) return t.inputmask.unmaskedvalue();
                                                    var i = n(t);
                                                    return -1 !== r.getLastValidPosition.call(e, void 0, void 0, t.inputmask.maskset.validPositions) || !0 !== a.nullable ? i : "";
                                                }
                                                return n(t);
                                            },
                                            set: function(e, t) {
                                                var a = s(e, t);
                                                return e.inputmask && (0, o.applyInputValue)(e, t), a;
                                            },
                                            inputmaskpatch: !0
                                        };
                                    }
                                }(t.type), function(t) {
                                    s.EventRuler.on(t, "mouseenter", (function() {
                                        var t = this.inputmask._valueGet(!0);
                                        t !== (e.isRTL ? r.getBuffer.call(e).reverse() : r.getBuffer.call(e)).join("") && (0, 
                                        o.applyInputValue)(this, t);
                                    }));
                                }(t));
                            }
                        }(t) : t.inputmask = void 0, u;
                    }(a, t);
                    if (!1 !== f) {
                        e.originalPlaceholder = a.placeholder, e.maxLength = void 0 !== a ? a.maxLength : void 0, 
                        -1 === e.maxLength && (e.maxLength = void 0), "inputMode" in a && null === a.getAttribute("inputmode") && (a.inputMode = t.inputmode, 
                        a.setAttribute("inputmode", t.inputmode)), !0 === f && (t.showMaskOnFocus = t.showMaskOnFocus && -1 === [ "cc-number", "cc-exp" ].indexOf(a.autocomplete), 
                        l.iphone && (t.insertModeVisual = !1), s.EventRuler.on(a, "submit", c.EventHandlers.submitEvent), 
                        s.EventRuler.on(a, "reset", c.EventHandlers.resetEvent), s.EventRuler.on(a, "blur", c.EventHandlers.blurEvent), 
                        s.EventRuler.on(a, "focus", c.EventHandlers.focusEvent), s.EventRuler.on(a, "invalid", c.EventHandlers.invalidEvent), 
                        s.EventRuler.on(a, "click", c.EventHandlers.clickEvent), s.EventRuler.on(a, "mouseleave", c.EventHandlers.mouseleaveEvent), 
                        s.EventRuler.on(a, "mouseenter", c.EventHandlers.mouseenterEvent), s.EventRuler.on(a, "paste", c.EventHandlers.pasteEvent), 
                        s.EventRuler.on(a, "cut", c.EventHandlers.cutEvent), s.EventRuler.on(a, "complete", t.oncomplete), 
                        s.EventRuler.on(a, "incomplete", t.onincomplete), s.EventRuler.on(a, "cleared", t.oncleared), 
                        !0 !== t.inputEventOnly && (s.EventRuler.on(a, "keydown", c.EventHandlers.keydownEvent), 
                        s.EventRuler.on(a, "keypress", c.EventHandlers.keypressEvent), s.EventRuler.on(a, "keyup", c.EventHandlers.keyupEvent)), 
                        (l.mobile || t.inputEventOnly) && a.removeAttribute("maxLength"), s.EventRuler.on(a, "input", c.EventHandlers.inputFallBackEvent), 
                        s.EventRuler.on(a, "compositionend", c.EventHandlers.compositionendEvent)), s.EventRuler.on(a, "setvalue", c.EventHandlers.setValueEvent), 
                        r.getBufferTemplate.call(e).join(""), e.undoValue = e._valueGet(!0);
                        var d = (a.inputmask.shadowRoot || a.ownerDocument).activeElement;
                        if ("" !== a.inputmask._valueGet(!0) || !1 === t.clearMaskOnLostFocus || d === a) {
                            (0, o.applyInputValue)(a, a.inputmask._valueGet(!0), t);
                            var p = r.getBuffer.call(e).slice();
                            !1 === u.isComplete.call(e, p) && t.clearIncomplete && r.resetMaskSet.call(e), t.clearMaskOnLostFocus && d !== a && (-1 === r.getLastValidPosition.call(e) ? p = [] : o.clearOptionalTail.call(e, p)), 
                            (!1 === t.clearMaskOnLostFocus || t.showMaskOnFocus && d === a || "" !== a.inputmask._valueGet(!0)) && (0, 
                            o.writeBuffer)(a, p), d === a && r.caret.call(e, a, r.seekNext.call(e, r.getLastValidPosition.call(e)));
                        }
                    }
                };
                var i, n = (i = a(4528)) && i.__esModule ? i : {
                    default: i
                }, r = a(8711), o = a(7760), s = a(9716), l = a(9845), u = a(7215), c = a(6030);
            },
            9695: function(e, t) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.default = function(e, t, a, i) {
                    this.matches = [], this.openGroup = e || !1, this.alternatorGroup = !1, this.isGroup = e || !1, 
                    this.isOptional = t || !1, this.isQuantifier = a || !1, this.isAlternator = i || !1, 
                    this.quantifier = {
                        min: 1,
                        max: 1
                    };
                };
            },
            3194: function() {
                Array.prototype.includes || Object.defineProperty(Array.prototype, "includes", {
                    value: function(e, t) {
                        if (null == this) throw new TypeError('"this" is null or not defined');
                        var a = Object(this), i = a.length >>> 0;
                        if (0 === i) return !1;
                        for (var n = 0 | t, r = Math.max(n >= 0 ? n : i - Math.abs(n), 0); r < i; ) {
                            if (a[r] === e) return !0;
                            r++;
                        }
                        return !1;
                    }
                });
            },
            7149: function() {
                function e(t) {
                    return (e = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e;
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    })(t);
                }
                "function" != typeof Object.getPrototypeOf && (Object.getPrototypeOf = "object" === e("test".__proto__) ? function(e) {
                    return e.__proto__;
                } : function(e) {
                    return e.constructor.prototype;
                });
            },
            8711: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.caret = function(e, t, a, i, n) {
                    var r, o = this, s = this.opts;
                    if (void 0 === t) return "selectionStart" in e && "selectionEnd" in e ? (t = e.selectionStart, 
                    a = e.selectionEnd) : window.getSelection ? (r = window.getSelection().getRangeAt(0)).commonAncestorContainer.parentNode !== e && r.commonAncestorContainer !== e || (t = r.startOffset, 
                    a = r.endOffset) : document.selection && document.selection.createRange && (r = document.selection.createRange(), 
                    t = 0 - r.duplicate().moveStart("character", -e.inputmask._valueGet().length), a = t + r.text.length), 
                    {
                        begin: i ? t : u.call(o, t),
                        end: i ? a : u.call(o, a)
                    };
                    if (Array.isArray(t) && (a = o.isRTL ? t[0] : t[1], t = o.isRTL ? t[1] : t[0]), 
                    void 0 !== t.begin && (a = o.isRTL ? t.begin : t.end, t = o.isRTL ? t.end : t.begin), 
                    "number" == typeof t) {
                        t = i ? t : u.call(o, t), a = "number" == typeof (a = i ? a : u.call(o, a)) ? a : t;
                        var l = parseInt(((e.ownerDocument.defaultView || window).getComputedStyle ? (e.ownerDocument.defaultView || window).getComputedStyle(e, null) : e.currentStyle).fontSize) * a;
                        if (e.scrollLeft = l > e.scrollWidth ? l : 0, e.inputmask.caretPos = {
                            begin: t,
                            end: a
                        }, s.insertModeVisual && !1 === s.insertMode && t === a && (n || a++), e === (e.inputmask.shadowRoot || e.ownerDocument).activeElement) if ("setSelectionRange" in e) e.setSelectionRange(t, a); else if (window.getSelection) {
                            if (r = document.createRange(), void 0 === e.firstChild || null === e.firstChild) {
                                var c = document.createTextNode("");
                                e.appendChild(c);
                            }
                            r.setStart(e.firstChild, t < e.inputmask._valueGet().length ? t : e.inputmask._valueGet().length), 
                            r.setEnd(e.firstChild, a < e.inputmask._valueGet().length ? a : e.inputmask._valueGet().length), 
                            r.collapse(!0);
                            var f = window.getSelection();
                            f.removeAllRanges(), f.addRange(r);
                        } else e.createTextRange && ((r = e.createTextRange()).collapse(!0), r.moveEnd("character", a), 
                        r.moveStart("character", t), r.select());
                    }
                }, t.determineLastRequiredPosition = function(e) {
                    var t, a, r = this, s = this.maskset, l = this.dependencyLib, u = i.getMaskTemplate.call(r, !0, o.call(r), !0, !0), c = u.length, f = o.call(r), d = {}, p = s.validPositions[f], h = void 0 !== p ? p.locator.slice() : void 0;
                    for (t = f + 1; t < u.length; t++) a = i.getTestTemplate.call(r, t, h, t - 1), h = a.locator.slice(), 
                    d[t] = l.extend(!0, {}, a);
                    var v = p && void 0 !== p.alternation ? p.locator[p.alternation] : void 0;
                    for (t = c - 1; t > f && (((a = d[t]).match.optionality || a.match.optionalQuantifier && a.match.newBlockMarker || v && (v !== d[t].locator[p.alternation] && 1 != a.match.static || !0 === a.match.static && a.locator[p.alternation] && n.checkAlternationMatch.call(r, a.locator[p.alternation].toString().split(","), v.toString().split(",")) && "" !== i.getTests.call(r, t)[0].def)) && u[t] === i.getPlaceholder.call(r, t, a.match)); t--) c--;
                    return e ? {
                        l: c,
                        def: d[c] ? d[c].match : void 0
                    } : c;
                }, t.determineNewCaretPosition = function(e, t, a) {
                    var n = this, u = this.maskset, c = this.opts;
                    t && (n.isRTL ? e.end = e.begin : e.begin = e.end);
                    if (e.begin === e.end) {
                        switch (a = a || c.positionCaretOnClick) {
                          case "none":
                            break;

                          case "select":
                            e = {
                                begin: 0,
                                end: r.call(n).length
                            };
                            break;

                          case "ignore":
                            e.end = e.begin = l.call(n, o.call(n));
                            break;

                          case "radixFocus":
                            if (function(e) {
                                if ("" !== c.radixPoint && 0 !== c.digits) {
                                    var t = u.validPositions;
                                    if (void 0 === t[e] || t[e].input === i.getPlaceholder.call(n, e)) {
                                        if (e < l.call(n, -1)) return !0;
                                        var a = r.call(n).indexOf(c.radixPoint);
                                        if (-1 !== a) {
                                            for (var o in t) if (t[o] && a < o && t[o].input !== i.getPlaceholder.call(n, o)) return !1;
                                            return !0;
                                        }
                                    }
                                }
                                return !1;
                            }(e.begin)) {
                                var f = r.call(n).join("").indexOf(c.radixPoint);
                                e.end = e.begin = c.numericInput ? l.call(n, f) : f;
                                break;
                            }

                          default:
                            var d = e.begin, p = o.call(n, d, !0), h = l.call(n, -1 !== p || s.call(n, 0) ? p : -1);
                            if (d <= h) e.end = e.begin = s.call(n, d, !1, !0) ? d : l.call(n, d); else {
                                var v = u.validPositions[p], m = i.getTestTemplate.call(n, h, v ? v.match.locator : void 0, v), g = i.getPlaceholder.call(n, h, m.match);
                                if ("" !== g && r.call(n)[h] !== g && !0 !== m.match.optionalQuantifier && !0 !== m.match.newBlockMarker || !s.call(n, h, c.keepStatic, !0) && m.match.def === g) {
                                    var k = l.call(n, h);
                                    (d >= k || d === h) && (h = k);
                                }
                                e.end = e.begin = h;
                            }
                        }
                        return e;
                    }
                }, t.getBuffer = r, t.getBufferTemplate = function() {
                    var e = this.maskset;
                    void 0 === e._buffer && (e._buffer = i.getMaskTemplate.call(this, !1, 1), void 0 === e.buffer && (e.buffer = e._buffer.slice()));
                    return e._buffer;
                }, t.getLastValidPosition = o, t.isMask = s, t.resetMaskSet = function(e) {
                    var t = this.maskset;
                    t.buffer = void 0, !0 !== e && (t.validPositions = {}, t.p = 0);
                }, t.seekNext = l, t.seekPrevious = function(e, t) {
                    var a = this, n = e - 1;
                    if (e <= 0) return 0;
                    for (;n > 0 && (!0 === t && (!0 !== i.getTest.call(a, n).match.newBlockMarker || !s.call(a, n, void 0, !0)) || !0 !== t && !s.call(a, n, void 0, !0)); ) n--;
                    return n;
                }, t.translatePosition = u;
                var i = a(4713), n = a(7215);
                function r(e) {
                    var t = this.maskset;
                    return void 0 !== t.buffer && !0 !== e || (t.buffer = i.getMaskTemplate.call(this, !0, o.call(this), !0), 
                    void 0 === t._buffer && (t._buffer = t.buffer.slice())), t.buffer;
                }
                function o(e, t, a) {
                    var i = this.maskset, n = -1, r = -1, o = a || i.validPositions;
                    for (var s in void 0 === e && (e = -1), o) {
                        var l = parseInt(s);
                        o[l] && (t || !0 !== o[l].generatedInput) && (l <= e && (n = l), l >= e && (r = l));
                    }
                    return -1 === n || n == e ? r : -1 == r || e - n < r - e ? n : r;
                }
                function s(e, t, a) {
                    var n = this, r = this.maskset, o = i.getTestTemplate.call(n, e).match;
                    if ("" === o.def && (o = i.getTest.call(n, e).match), !0 !== o.static) return o.fn;
                    if (!0 === a && void 0 !== r.validPositions[e] && !0 !== r.validPositions[e].generatedInput) return !0;
                    if (!0 !== t && e > -1) {
                        if (a) {
                            var s = i.getTests.call(n, e);
                            return s.length > 1 + ("" === s[s.length - 1].match.def ? 1 : 0);
                        }
                        var l = i.determineTestTemplate.call(n, e, i.getTests.call(n, e)), u = i.getPlaceholder.call(n, e, l.match);
                        return l.match.def !== u;
                    }
                    return !1;
                }
                function l(e, t, a) {
                    var n = this;
                    void 0 === a && (a = !0);
                    for (var r = e + 1; "" !== i.getTest.call(n, r).match.def && (!0 === t && (!0 !== i.getTest.call(n, r).match.newBlockMarker || !s.call(n, r, void 0, !0)) || !0 !== t && !s.call(n, r, void 0, a)); ) r++;
                    return r;
                }
                function u(e) {
                    var t = this.opts, a = this.el;
                    return !this.isRTL || "number" != typeof e || t.greedy && "" === t.placeholder || !a || (e = Math.abs(this._valueGet().length - e)), 
                    e;
                }
            },
            4713: function(e, t) {
                function a(e, t) {
                    var a = (null != e.alternation ? e.mloc[i(e)] : e.locator).join("");
                    if ("" !== a) for (;a.length < t; ) a += "0";
                    return a;
                }
                function i(e) {
                    var t = e.locator[e.alternation];
                    return "string" == typeof t && t.length > 0 && (t = t.split(",")[0]), void 0 !== t ? t.toString() : "";
                }
                function n(e, t, a) {
                    var i = this.opts, n = this.maskset;
                    if (void 0 !== (t = t || s.call(this, e).match).placeholder || !0 === a) return "function" == typeof t.placeholder ? t.placeholder(i) : t.placeholder;
                    if (!0 === t.static) {
                        if (e > -1 && void 0 === n.validPositions[e]) {
                            var r, o = u.call(this, e), l = [];
                            if (o.length > 1 + ("" === o[o.length - 1].match.def ? 1 : 0)) for (var c = 0; c < o.length; c++) if ("" !== o[c].match.def && !0 !== o[c].match.optionality && !0 !== o[c].match.optionalQuantifier && (!0 === o[c].match.static || void 0 === r || !1 !== o[c].match.fn.test(r.match.def, n, e, !0, i)) && (l.push(o[c]), 
                            !0 === o[c].match.static && (r = o[c]), l.length > 1 && /[0-9a-bA-Z]/.test(l[0].match.def))) return i.placeholder.charAt(e % i.placeholder.length);
                        }
                        return t.def;
                    }
                    return i.placeholder.charAt(e % i.placeholder.length);
                }
                function r(e, t, a) {
                    return this.maskset.validPositions[e] || o.call(this, e, u.call(this, e, t ? t.slice() : t, a));
                }
                function o(e, t) {
                    var i = this.opts;
                    e = e > 0 ? e - 1 : 0;
                    for (var n, r, o, l = a(s.call(this, e)), u = 0; u < t.length; u++) {
                        var c = t[u];
                        n = a(c, l.length);
                        var f = Math.abs(n - l);
                        (void 0 === r || "" !== n && f < r || o && !i.greedy && o.match.optionality && "master" === o.match.newBlockMarker && (!c.match.optionality || !c.match.newBlockMarker) || o && o.match.optionalQuantifier && !c.match.optionalQuantifier) && (r = f, 
                        o = c);
                    }
                    return o;
                }
                function s(e, t) {
                    var a = this.maskset;
                    return a.validPositions[e] ? a.validPositions[e] : (t || u.call(this, e))[0];
                }
                function l(e, t, a) {
                    function i(e) {
                        for (var t, a = [], i = -1, n = 0, r = e.length; n < r; n++) if ("-" === e.charAt(n)) for (t = e.charCodeAt(n + 1); ++i < t; ) a.push(String.fromCharCode(i)); else i = e.charCodeAt(n), 
                        a.push(e.charAt(n));
                        return a.join("");
                    }
                    return e.match.def === t.match.nativeDef || !(!(a.regex || e.match.fn instanceof RegExp && t.match.fn instanceof RegExp) || !0 === e.match.static || !0 === t.match.static) && -1 !== i(t.match.fn.toString().replace(/[[\]/]/g, "")).indexOf(i(e.match.fn.toString().replace(/[[\]/]/g, "")));
                }
                function u(e, t, a) {
                    var i, n = this, r = this.dependencyLib, s = this.maskset, u = this.opts, c = this.el, f = s.maskToken, d = t ? a : 0, p = t ? t.slice() : [ 0 ], h = [], v = !1, m = t ? t.join("") : "";
                    function g(t, a, n, r) {
                        function o(n, r, f) {
                            function p(e, t) {
                                var a = 0 === t.matches.indexOf(e);
                                return a || t.matches.every((function(i, n) {
                                    return !0 === i.isQuantifier ? a = p(e, t.matches[n - 1]) : Object.prototype.hasOwnProperty.call(i, "matches") && (a = p(e, i)), 
                                    !a;
                                })), a;
                            }
                            function k(e, t, a) {
                                var i, n;
                                if ((s.tests[e] || s.validPositions[e]) && (s.tests[e] || [ s.validPositions[e] ]).every((function(e, r) {
                                    if (e.mloc[t]) return i = e, !1;
                                    var o = void 0 !== a ? a : e.alternation, s = void 0 !== e.locator[o] ? e.locator[o].toString().indexOf(t) : -1;
                                    return (void 0 === n || s < n) && -1 !== s && (i = e, n = s), !0;
                                })), i) {
                                    var r = i.locator[i.alternation];
                                    return (i.mloc[t] || i.mloc[r] || i.locator).slice((void 0 !== a ? a : i.alternation) + 1);
                                }
                                return void 0 !== a ? k(e, t) : void 0;
                            }
                            function y(e, t) {
                                var a = e.alternation, i = void 0 === t || a === t.alternation && -1 === e.locator[a].toString().indexOf(t.locator[a]);
                                if (!i && a > t.alternation) for (var n = t.alternation; n < a; n++) if (e.locator[n] !== t.locator[n]) {
                                    a = n, i = !0;
                                    break;
                                }
                                if (i) {
                                    e.mloc = e.mloc || {};
                                    var r = e.locator[a];
                                    if (void 0 !== r) {
                                        if ("string" == typeof r && (r = r.split(",")[0]), void 0 === e.mloc[r] && (e.mloc[r] = e.locator.slice()), 
                                        void 0 !== t) {
                                            for (var o in t.mloc) "string" == typeof o && (o = o.split(",")[0]), void 0 === e.mloc[o] && (e.mloc[o] = t.mloc[o]);
                                            e.locator[a] = Object.keys(e.mloc).join(",");
                                        }
                                        return !0;
                                    }
                                    e.alternation = void 0;
                                }
                                return !1;
                            }
                            function b(e, t) {
                                if (e.locator.length !== t.locator.length) return !1;
                                for (var a = e.alternation + 1; a < e.locator.length; a++) if (e.locator[a] !== t.locator[a]) return !1;
                                return !0;
                            }
                            if (d > e + u._maxTestPos) throw "Inputmask: There is probably an error in your mask definition or in the code. Create an issue on github with an example of the mask you are using. " + s.mask;
                            if (d === e && void 0 === n.matches) return h.push({
                                match: n,
                                locator: r.reverse(),
                                cd: m,
                                mloc: {}
                            }), !0;
                            if (void 0 !== n.matches) {
                                if (n.isGroup && f !== n) {
                                    if (n = o(t.matches[t.matches.indexOf(n) + 1], r, f)) return !0;
                                } else if (n.isOptional) {
                                    var x = n, P = h.length;
                                    if (n = g(n, a, r, f)) {
                                        if (h.forEach((function(e, t) {
                                            t >= P && (e.match.optionality = !0);
                                        })), i = h[h.length - 1].match, void 0 !== f || !p(i, x)) return !0;
                                        v = !0, d = e;
                                    }
                                } else if (n.isAlternator) {
                                    var E, S = n, _ = [], M = h.slice(), w = r.length, O = !1, T = a.length > 0 ? a.shift() : -1;
                                    if (-1 === T || "string" == typeof T) {
                                        var C, A = d, D = a.slice(), B = [];
                                        if ("string" == typeof T) B = T.split(","); else for (C = 0; C < S.matches.length; C++) B.push(C.toString());
                                        if (void 0 !== s.excludes[e]) {
                                            for (var j = B.slice(), R = 0, L = s.excludes[e].length; R < L; R++) {
                                                var I = s.excludes[e][R].toString().split(":");
                                                r.length == I[1] && B.splice(B.indexOf(I[0]), 1);
                                            }
                                            0 === B.length && (delete s.excludes[e], B = j);
                                        }
                                        (!0 === u.keepStatic || isFinite(parseInt(u.keepStatic)) && A >= u.keepStatic) && (B = B.slice(0, 1));
                                        for (var F = 0; F < B.length; F++) {
                                            C = parseInt(B[F]), h = [], a = "string" == typeof T && k(d, C, w) || D.slice();
                                            var N = S.matches[C];
                                            if (N && o(N, [ C ].concat(r), f)) n = !0; else if (0 === F && (O = !0), N && N.matches && N.matches.length > S.matches[0].matches.length) break;
                                            E = h.slice(), d = A, h = [];
                                            for (var V = 0; V < E.length; V++) {
                                                var G = E[V], H = !1;
                                                G.match.jit = G.match.jit || O, G.alternation = G.alternation || w, y(G);
                                                for (var K = 0; K < _.length; K++) {
                                                    var U = _[K];
                                                    if ("string" != typeof T || void 0 !== G.alternation && B.includes(G.locator[G.alternation].toString())) {
                                                        if (G.match.nativeDef === U.match.nativeDef) {
                                                            H = !0, y(U, G);
                                                            break;
                                                        }
                                                        if (l(G, U, u)) {
                                                            y(G, U) && (H = !0, _.splice(_.indexOf(U), 0, G));
                                                            break;
                                                        }
                                                        if (l(U, G, u)) {
                                                            y(U, G);
                                                            break;
                                                        }
                                                        if (Q = U, !0 === (W = G).match.static && !0 !== Q.match.static && Q.match.fn.test(W.match.def, s, e, !1, u, !1)) {
                                                            b(G, U) || void 0 !== c.inputmask.userOptions.keepStatic ? y(G, U) && (H = !0, _.splice(_.indexOf(U), 0, G)) : u.keepStatic = !0;
                                                            break;
                                                        }
                                                    }
                                                }
                                                H || _.push(G);
                                            }
                                        }
                                        h = M.concat(_), d = e, v = h.length > 0, n = _.length > 0, a = D.slice();
                                    } else n = o(S.matches[T] || t.matches[T], [ T ].concat(r), f);
                                    if (n) return !0;
                                } else if (n.isQuantifier && f !== t.matches[t.matches.indexOf(n) - 1]) for (var $ = n, z = a.length > 0 ? a.shift() : 0; z < (isNaN($.quantifier.max) ? z + 1 : $.quantifier.max) && d <= e; z++) {
                                    var q = t.matches[t.matches.indexOf($) - 1];
                                    if (n = o(q, [ z ].concat(r), q)) {
                                        if ((i = h[h.length - 1].match).optionalQuantifier = z >= $.quantifier.min, i.jit = (z + 1) * (q.matches.indexOf(i) + 1) > $.quantifier.jit, 
                                        i.optionalQuantifier && p(i, q)) {
                                            v = !0, d = e;
                                            break;
                                        }
                                        return i.jit && (s.jitOffset[e] = q.matches.length - q.matches.indexOf(i)), !0;
                                    }
                                } else if (n = g(n, a, r, f)) return !0;
                            } else d++;
                            var W, Q;
                        }
                        for (var f = a.length > 0 ? a.shift() : 0; f < t.matches.length; f++) if (!0 !== t.matches[f].isQuantifier) {
                            var p = o(t.matches[f], [ f ].concat(n), r);
                            if (p && d === e) return p;
                            if (d > e) break;
                        }
                    }
                    if (e > -1) {
                        if (void 0 === t) {
                            for (var k, y = e - 1; void 0 === (k = s.validPositions[y] || s.tests[y]) && y > -1; ) y--;
                            void 0 !== k && y > -1 && (p = function(e, t) {
                                var a, i = [];
                                return Array.isArray(t) || (t = [ t ]), t.length > 0 && (void 0 === t[0].alternation || !0 === u.keepStatic ? 0 === (i = o.call(n, e, t.slice()).locator.slice()).length && (i = t[0].locator.slice()) : t.forEach((function(e) {
                                    "" !== e.def && (0 === i.length ? (a = e.alternation, i = e.locator.slice()) : e.locator[a] && -1 === i[a].toString().indexOf(e.locator[a]) && (i[a] += "," + e.locator[a]));
                                }))), i;
                            }(y, k), m = p.join(""), d = y);
                        }
                        if (s.tests[e] && s.tests[e][0].cd === m) return s.tests[e];
                        for (var b = p.shift(); b < f.length; b++) {
                            if (g(f[b], p, [ b ]) && d === e || d > e) break;
                        }
                    }
                    return (0 === h.length || v) && h.push({
                        match: {
                            fn: null,
                            static: !0,
                            optionality: !1,
                            casing: null,
                            def: "",
                            placeholder: ""
                        },
                        locator: [],
                        mloc: {},
                        cd: m
                    }), void 0 !== t && s.tests[e] ? r.extend(!0, [], h) : (s.tests[e] = r.extend(!0, [], h), 
                    s.tests[e]);
                }
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.determineTestTemplate = o, t.getDecisionTaker = i, t.getMaskTemplate = function(e, t, a, i, s) {
                    var l = this, c = this.opts, f = this.maskset, d = c.greedy;
                    s && (c.greedy = !1);
                    t = t || 0;
                    var p, h, v, m, g = [], k = 0;
                    do {
                        if (!0 === e && f.validPositions[k]) v = s && !0 === f.validPositions[k].match.optionality && void 0 === f.validPositions[k + 1] && (!0 === f.validPositions[k].generatedInput || f.validPositions[k].input == c.skipOptionalPartCharacter && k > 0) ? o.call(l, k, u.call(l, k, p, k - 1)) : f.validPositions[k], 
                        h = v.match, p = v.locator.slice(), g.push(!0 === a ? v.input : !1 === a ? h.nativeDef : n.call(l, k, h)); else {
                            v = r.call(l, k, p, k - 1), h = v.match, p = v.locator.slice();
                            var y = !0 !== i && (!1 !== c.jitMasking ? c.jitMasking : h.jit);
                            (m = (m && h.static && h.def !== c.groupSeparator && null === h.fn || f.validPositions[k - 1] && h.static && h.def !== c.groupSeparator && null === h.fn) && f.tests[k] && 1 === f.tests[k].length) || !1 === y || void 0 === y || "number" == typeof y && isFinite(y) && y > k ? g.push(!1 === a ? h.nativeDef : n.call(l, k, h)) : m = !1;
                        }
                        k++;
                    } while (!0 !== h.static || "" !== h.def || t > k);
                    "" === g[g.length - 1] && g.pop();
                    !1 === a && void 0 !== f.maskLength || (f.maskLength = k - 1);
                    return c.greedy = d, g;
                }, t.getPlaceholder = n, t.getTest = s, t.getTests = u, t.getTestTemplate = r, t.isSubsetOf = l;
            },
            7215: function(e, t, a) {
                Object.defineProperty(t, "__esModule", {
                    value: !0
                }), t.alternate = l, t.checkAlternationMatch = function(e, t, a) {
                    for (var i, n = this.opts.greedy ? t : t.slice(0, 1), r = !1, o = void 0 !== a ? a.split(",") : [], s = 0; s < o.length; s++) -1 !== (i = e.indexOf(o[s])) && e.splice(i, 1);
                    for (var l = 0; l < e.length; l++) if (n.includes(e[l])) {
                        r = !0;
                        break;
                    }
                    return r;
                }, t.isComplete = c, t.isValid = f, t.refreshFromBuffer = p, t.revalidateMask = v, 
                t.handleRemove = function(e, t, a, i, s) {
                    var u = this, c = this.maskset, f = this.opts;
                    if ((f.numericInput || u.isRTL) && (t === r.default.BACKSPACE ? t = r.default.DELETE : t === r.default.DELETE && (t = r.default.BACKSPACE), 
                    u.isRTL)) {
                        var d = a.end;
                        a.end = a.begin, a.begin = d;
                    }
                    var p, h = o.getLastValidPosition.call(u, void 0, !0);
                    a.end >= o.getBuffer.call(u).length && h >= a.end && (a.end = h + 1);
                    t === r.default.BACKSPACE ? a.end - a.begin < 1 && (a.begin = o.seekPrevious.call(u, a.begin)) : t === r.default.DELETE && a.begin === a.end && (a.end = o.isMask.call(u, a.end, !0, !0) ? a.end + 1 : o.seekNext.call(u, a.end) + 1);
                    if (!1 !== (p = v.call(u, a))) {
                        if (!0 !== i && !1 !== f.keepStatic || null !== f.regex && -1 !== n.getTest.call(u, a.begin).match.def.indexOf("|")) {
                            var m = l.call(u, !0);
                            if (m) {
                                var g = void 0 !== m.caret ? m.caret : m.pos ? o.seekNext.call(u, m.pos.begin ? m.pos.begin : m.pos) : o.getLastValidPosition.call(u, -1, !0);
                                (t !== r.default.DELETE || a.begin > g) && a.begin;
                            }
                        }
                        !0 !== i && (c.p = t === r.default.DELETE ? a.begin + p : a.begin, c.p = o.determineNewCaretPosition.call(u, {
                            begin: c.p,
                            end: c.p
                        }, !1).begin);
                    }
                };
                var i, n = a(4713), r = (i = a(4528)) && i.__esModule ? i : {
                    default: i
                }, o = a(8711), s = a(6030);
                function l(e, t, a, i, r, s) {
                    var u, c, d, p, h, v, m, g, k, y, b, x = this, P = this.dependencyLib, E = this.opts, S = x.maskset, _ = P.extend(!0, {}, S.validPositions), M = P.extend(!0, {}, S.tests), w = !1, O = !1, T = void 0 !== r ? r : o.getLastValidPosition.call(x);
                    if (s && (y = s.begin, b = s.end, s.begin > s.end && (y = s.end, b = s.begin)), 
                    -1 === T && void 0 === r) u = 0, c = (p = n.getTest.call(x, u)).alternation; else for (;T >= 0; T--) if ((d = S.validPositions[T]) && void 0 !== d.alternation) {
                        if (p && p.locator[d.alternation] !== d.locator[d.alternation]) break;
                        u = T, c = S.validPositions[u].alternation, p = d;
                    }
                    if (void 0 !== c) {
                        m = parseInt(u), S.excludes[m] = S.excludes[m] || [], !0 !== e && S.excludes[m].push((0, 
                        n.getDecisionTaker)(p) + ":" + p.alternation);
                        var C = [], A = -1;
                        for (h = m; h < o.getLastValidPosition.call(x, void 0, !0) + 1; h++) -1 === A && e <= h && void 0 !== t && (C.push(t), 
                        A = C.length - 1), (v = S.validPositions[h]) && !0 !== v.generatedInput && (void 0 === s || h < y || h >= b) && C.push(v.input), 
                        delete S.validPositions[h];
                        for (-1 === A && void 0 !== t && (C.push(t), A = C.length - 1); void 0 !== S.excludes[m] && S.excludes[m].length < 10; ) {
                            for (S.tests = {}, o.resetMaskSet.call(x, !0), w = !0, h = 0; h < C.length && (g = w.caret || o.getLastValidPosition.call(x, void 0, !0) + 1, 
                            k = C[h], w = f.call(x, g, k, !1, i, !0)); h++) h === A && (O = w), 1 == e && w && (O = {
                                caretPos: h
                            });
                            if (w) break;
                            if (o.resetMaskSet.call(x), p = n.getTest.call(x, m), S.validPositions = P.extend(!0, {}, _), 
                            S.tests = P.extend(!0, {}, M), !S.excludes[m]) {
                                O = l.call(x, e, t, a, i, m - 1, s);
                                break;
                            }
                            var D = (0, n.getDecisionTaker)(p);
                            if (-1 !== S.excludes[m].indexOf(D + ":" + p.alternation)) {
                                O = l.call(x, e, t, a, i, m - 1, s);
                                break;
                            }
                            for (S.excludes[m].push(D + ":" + p.alternation), h = m; h < o.getLastValidPosition.call(x, void 0, !0) + 1; h++) delete S.validPositions[h];
                        }
                    }
                    return O && !1 === E.keepStatic || delete S.excludes[m], O;
                }
                function u(e, t, a) {
                    var i = this.opts, n = this.maskset;
                    switch (i.casing || t.casing) {
                      case "upper":
                        e = e.toUpperCase();
                        break;

                      case "lower":
                        e = e.toLowerCase();
                        break;

                      case "title":
                        var o = n.validPositions[a - 1];
                        e = 0 === a || o && o.input === String.fromCharCode(r.default.SPACE) ? e.toUpperCase() : e.toLowerCase();
                        break;

                      default:
                        if ("function" == typeof i.casing) {
                            var s = Array.prototype.slice.call(arguments);
                            s.push(n.validPositions), e = i.casing.apply(this, s);
                        }
                    }
                    return e;
                }
                function c(e) {
                    var t = this, a = this.opts, i = this.maskset;
                    if ("function" == typeof a.isComplete) return a.isComplete(e, a);
                    if ("*" !== a.repeat) {
                        var r = !1, s = o.determineLastRequiredPosition.call(t, !0), l = o.seekPrevious.call(t, s.l);
                        if (void 0 === s.def || s.def.newBlockMarker || s.def.optionality || s.def.optionalQuantifier) {
                            r = !0;
                            for (var u = 0; u <= l; u++) {
                                var c = n.getTestTemplate.call(t, u).match;
                                if (!0 !== c.static && void 0 === i.validPositions[u] && !0 !== c.optionality && !0 !== c.optionalQuantifier || !0 === c.static && e[u] !== n.getPlaceholder.call(t, u, c)) {
                                    r = !1;
                                    break;
                                }
                            }
                        }
                        return r;
                    }
                }
                function f(e, t, a, i, r, s, d) {
                    var m = this, g = this.dependencyLib, k = this.opts, y = m.maskset;
                    function b(e) {
                        return m.isRTL ? e.begin - e.end > 1 || e.begin - e.end == 1 : e.end - e.begin > 1 || e.end - e.begin == 1;
                    }
                    a = !0 === a;
                    var x = e;
                    function P(e) {
                        if (void 0 !== e) {
                            if (void 0 !== e.remove && (Array.isArray(e.remove) || (e.remove = [ e.remove ]), 
                            e.remove.sort((function(e, t) {
                                return t.pos - e.pos;
                            })).forEach((function(e) {
                                v.call(m, {
                                    begin: e,
                                    end: e + 1
                                });
                            })), e.remove = void 0), void 0 !== e.insert && (Array.isArray(e.insert) || (e.insert = [ e.insert ]), 
                            e.insert.sort((function(e, t) {
                                return e.pos - t.pos;
                            })).forEach((function(e) {
                                "" !== e.c && f.call(m, e.pos, e.c, void 0 === e.strict || e.strict, void 0 !== e.fromIsValid ? e.fromIsValid : i);
                            })), e.insert = void 0), e.refreshFromBuffer && e.buffer) {
                                var t = e.refreshFromBuffer;
                                p.call(m, !0 === t ? t : t.start, t.end, e.buffer), e.refreshFromBuffer = void 0;
                            }
                            void 0 !== e.rewritePosition && (x = e.rewritePosition, e = !0);
                        }
                        return e;
                    }
                    function E(t, a, r) {
                        var s = !1;
                        return n.getTests.call(m, t).every((function(l, c) {
                            var f = l.match;
                            if (o.getBuffer.call(m, !0), !1 !== (s = (!f.jit || void 0 !== y.validPositions[o.seekPrevious.call(m, t)]) && (null != f.fn ? f.fn.test(a, y, t, r, k, b(e)) : (a === f.def || a === k.skipOptionalPartCharacter) && "" !== f.def && {
                                c: n.getPlaceholder.call(m, t, f, !0) || f.def,
                                pos: t
                            }))) {
                                var d = void 0 !== s.c ? s.c : a, p = t;
                                return d = d === k.skipOptionalPartCharacter && !0 === f.static ? n.getPlaceholder.call(m, t, f, !0) || f.def : d, 
                                !0 !== (s = P(s)) && void 0 !== s.pos && s.pos !== t && (p = s.pos), !0 !== s && void 0 === s.pos && void 0 === s.c ? !1 : (!1 === v.call(m, e, g.extend({}, l, {
                                    input: u.call(m, d, f, p)
                                }), i, p) && (s = !1), !1);
                            }
                            return !0;
                        })), s;
                    }
                    void 0 !== e.begin && (x = m.isRTL ? e.end : e.begin);
                    var S = !0, _ = g.extend(!0, {}, y.validPositions);
                    if (!1 === k.keepStatic && void 0 !== y.excludes[x] && !0 !== r && !0 !== i) for (var M = x; M < (m.isRTL ? e.begin : e.end); M++) void 0 !== y.excludes[M] && (y.excludes[M] = void 0, 
                    delete y.tests[M]);
                    if ("function" == typeof k.preValidation && !0 !== i && !0 !== s && (S = P(S = k.preValidation.call(m, o.getBuffer.call(m), x, t, b(e), k, y, e, a || r))), 
                    !0 === S) {
                        if (S = E(x, t, a), (!a || !0 === i) && !1 === S && !0 !== s) {
                            var w = y.validPositions[x];
                            if (!w || !0 !== w.match.static || w.match.def !== t && t !== k.skipOptionalPartCharacter) {
                                if (k.insertMode || void 0 === y.validPositions[o.seekNext.call(m, x)] || e.end > x) {
                                    var O = !1;
                                    if (y.jitOffset[x] && void 0 === y.validPositions[o.seekNext.call(m, x)] && !1 !== (S = f.call(m, x + y.jitOffset[x], t, !0, !0)) && (!0 !== r && (S.caret = x), 
                                    O = !0), e.end > x && (y.validPositions[x] = void 0), !O && !o.isMask.call(m, x, k.keepStatic && 0 === x)) for (var T = x + 1, C = o.seekNext.call(m, x, !1, 0 !== x); T <= C; T++) if (!1 !== (S = E(T, t, a))) {
                                        S = h.call(m, x, void 0 !== S.pos ? S.pos : T) || S, x = T;
                                        break;
                                    }
                                }
                            } else S = {
                                caret: o.seekNext.call(m, x)
                            };
                        }
                        !1 !== S || !k.keepStatic || !c.call(m, o.getBuffer.call(m)) && 0 !== x || a || !0 === r ? b(e) && y.tests[x] && y.tests[x].length > 1 && k.keepStatic && !a && !0 !== r && (S = l.call(m, !0)) : S = l.call(m, x, t, a, i, void 0, e), 
                        !0 === S && (S = {
                            pos: x
                        });
                    }
                    if ("function" == typeof k.postValidation && !0 !== i && !0 !== s) {
                        var A = k.postValidation.call(m, o.getBuffer.call(m, !0), void 0 !== e.begin ? m.isRTL ? e.end : e.begin : e, t, S, k, y, a, d);
                        void 0 !== A && (S = !0 === A ? S : A);
                    }
                    S && void 0 === S.pos && (S.pos = x), !1 === S || !0 === s ? (o.resetMaskSet.call(m, !0), 
                    y.validPositions = g.extend(!0, {}, _)) : h.call(m, void 0, x, !0);
                    var D = P(S);
                    void 0 !== m.maxLength && (o.getBuffer.call(m).length > m.maxLength && !i && (o.resetMaskSet.call(m, !0), 
                    y.validPositions = g.extend(!0, {}, _), D = !1));
                    return D;
                }
                function d(e, t, a) {
                    for (var i = this.maskset, r = !1, o = n.getTests.call(this, e), s = 0; s < o.length; s++) {
                        if (o[s].match && (o[s].match.nativeDef === t.match[a.shiftPositions ? "def" : "nativeDef"] && (!a.shiftPositions || !t.match.static) || o[s].match.nativeDef === t.match.nativeDef || a.regex && !o[s].match.static && o[s].match.fn.test(t.input))) {
                            r = !0;
                            break;
                        }
                        if (o[s].match && o[s].match.def === t.match.nativeDef) {
                            r = void 0;
                            break;
                        }
                    }
                    return !1 === r && void 0 !== i.jitOffset[e] && (r = d.call(this, e + i.jitOffset[e], t, a)), 
                    r;
                }
                function p(e, t, a) {
                    var i, n, r = this, l = this.maskset, u = this.opts, c = this.dependencyLib, f = u.skipOptionalPartCharacter, d = r.isRTL ? a.slice().reverse() : a;
                    if (u.skipOptionalPartCharacter = "", !0 === e) o.resetMaskSet.call(r), l.tests = {}, 
                    e = 0, t = a.length, n = o.determineNewCaretPosition.call(r, {
                        begin: 0,
                        end: 0
                    }, !1).begin; else {
                        for (i = e; i < t; i++) delete l.validPositions[i];
                        n = e;
                    }
                    var p = new c.Event("keypress");
                    for (i = e; i < t; i++) {
                        p.which = d[i].toString().charCodeAt(0), r.ignorable = !1;
                        var h = s.EventHandlers.keypressEvent.call(r, p, !0, !1, !1, n);
                        !1 !== h && void 0 !== h && (n = h.forwardPosition);
                    }
                    u.skipOptionalPartCharacter = f;
                }
                function h(e, t, a) {
                    var i = this, r = this.maskset, s = this.dependencyLib;
                    if (void 0 === e) for (e = t - 1; e > 0 && !r.validPositions[e]; e--) ;
                    for (var l = e; l < t; l++) {
                        if (void 0 === r.validPositions[l] && !o.isMask.call(i, l, !1)) if (0 == l ? n.getTest.call(i, l) : r.validPositions[l - 1]) {
                            var u = n.getTests.call(i, l).slice();
                            "" === u[u.length - 1].match.def && u.pop();
                            var c, d = n.determineTestTemplate.call(i, l, u);
                            if (d && (!0 !== d.match.jit || "master" === d.match.newBlockMarker && (c = r.validPositions[l + 1]) && !0 === c.match.optionalQuantifier) && ((d = s.extend({}, d, {
                                input: n.getPlaceholder.call(i, l, d.match, !0) || d.match.def
                            })).generatedInput = !0, v.call(i, l, d, !0), !0 !== a)) {
                                var p = r.validPositions[t].input;
                                return r.validPositions[t] = void 0, f.call(i, t, p, !0, !0);
                            }
                        }
                    }
                }
                function v(e, t, a, i) {
                    var r = this, s = this.maskset, l = this.opts, u = this.dependencyLib;
                    function c(e, t, a) {
                        var i = t[e];
                        if (void 0 !== i && !0 === i.match.static && !0 !== i.match.optionality && (void 0 === t[0] || void 0 === t[0].alternation)) {
                            var n = a.begin <= e - 1 ? t[e - 1] && !0 === t[e - 1].match.static && t[e - 1] : t[e - 1], r = a.end > e + 1 ? t[e + 1] && !0 === t[e + 1].match.static && t[e + 1] : t[e + 1];
                            return n && r;
                        }
                        return !1;
                    }
                    var p = 0, h = void 0 !== e.begin ? e.begin : e, v = void 0 !== e.end ? e.end : e, m = !0;
                    if (e.begin > e.end && (h = e.end, v = e.begin), i = void 0 !== i ? i : h, h !== v || l.insertMode && void 0 !== s.validPositions[i] && void 0 === a || void 0 === t) {
                        var g, k = u.extend(!0, {}, s.validPositions), y = o.getLastValidPosition.call(r, void 0, !0);
                        for (s.p = h, g = y; g >= h; g--) delete s.validPositions[g], void 0 === t && delete s.tests[g + 1];
                        var b, x, P = i, E = P;
                        for (t && (s.validPositions[i] = u.extend(!0, {}, t), E++, P++), g = t ? v : v - 1; g <= y; g++) {
                            if (void 0 !== (b = k[g]) && !0 !== b.generatedInput && (g >= v || g >= h && c(g, k, {
                                begin: h,
                                end: v
                            }))) {
                                for (;"" !== n.getTest.call(r, E).match.def; ) {
                                    if (!1 !== (x = d.call(r, E, b, l)) || "+" === b.match.def) {
                                        "+" === b.match.def && o.getBuffer.call(r, !0);
                                        var S = f.call(r, E, b.input, "+" !== b.match.def, !0);
                                        if (m = !1 !== S, P = (S.pos || E) + 1, !m && x) break;
                                    } else m = !1;
                                    if (m) {
                                        void 0 === t && b.match.static && g === e.begin && p++;
                                        break;
                                    }
                                    if (!m && E > s.maskLength) break;
                                    E++;
                                }
                                "" == n.getTest.call(r, E).match.def && (m = !1), E = P;
                            }
                            if (!m) break;
                        }
                        if (!m) return s.validPositions = u.extend(!0, {}, k), o.resetMaskSet.call(r, !0), 
                        !1;
                    } else t && n.getTest.call(r, i).match.cd === t.match.cd && (s.validPositions[i] = u.extend(!0, {}, t));
                    return o.resetMaskSet.call(r, !0), p;
                }
            }
        }, t = {};
        function a(i) {
            var n = t[i];
            if (void 0 !== n) return n.exports;
            var r = t[i] = {
                exports: {}
            };
            return e[i](r, r.exports, a), r.exports;
        }
        var i = {};
        return function() {
            var e, t = i;
            Object.defineProperty(t, "__esModule", {
                value: !0
            }), t.default = void 0, a(3851), a(219), a(207), a(5296);
            var n = ((e = a(2394)) && e.__esModule ? e : {
                default: e
            }).default;
            t.default = n;
        }(), i;
    }();
}));