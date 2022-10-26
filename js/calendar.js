Calendar=function(a,b,c,d){this.timeout=this.getDateText=this.getDateToolTip=this.getDateStatus=this.currentDateEl=this.activeDiv=null;this.onSelected=c||null;this.onClose=d||null;this.hidden=this.dragging=false;this.minYear=1970;this.maxYear=2050;this.dateFormat=Calendar._TT.DEF_DATE_FORMAT;this.ttDateFormat=Calendar._TT.TT_DATE_FORMAT;this.weekNumbers=this.isPopup=true;this.firstDayOfWeek=typeof a=="number"?a:Calendar._FD;this.showsOtherMonths=false;this.dateStr=b;this.ar_days=null;this.showsTime=
false;this.time24=true;this.yearStep=2;this.hiliteToday=true;this.activeYear=this.hilitedYear=this.activeMonth=this.hilitedMonth=this.yearsCombo=this.monthsCombo=this.firstdayname=this.tbody=this.element=this.table=this.multiple=null;this.dateClicked=false;if(typeof Calendar._SDN=="undefined"){if(typeof Calendar._SDN_len=="undefined")Calendar._SDN_len=3;a=[];for(b=8;b>0;)a[--b]=Calendar._DN[b].substr(0,Calendar._SDN_len);Calendar._SDN=a;if(typeof Calendar._SMN_len=="undefined")Calendar._SMN_len=3;
a=[];for(b=12;b>0;)a[--b]=Calendar._MN[b].substr(0,Calendar._SMN_len);Calendar._SMN=a}};Calendar._C=null;Calendar.is_ie=/msie/i.test(navigator.userAgent)&&!/opera/i.test(navigator.userAgent);Calendar.is_ie5=Calendar.is_ie&&/msie 5\.0/i.test(navigator.userAgent);Calendar.is_opera=/opera/i.test(navigator.userAgent);Calendar.is_khtml=/Konqueror|Safari|KHTML/i.test(navigator.userAgent);
Calendar.getAbsolutePos=function(a){var b=0,c=0,d=/^div$/i.test(a.tagName);if(d&&a.scrollLeft)b=a.scrollLeft;if(d&&a.scrollTop)c=a.scrollTop;b={x:a.offsetLeft-b,y:a.offsetTop-c};if(a.offsetParent){a=this.getAbsolutePos(a.offsetParent);b.x+=a.x;b.y+=a.y}return b};Calendar.isRelated=function(a,b){var c=b.relatedTarget;if(!c){var d=b.type;if(d=="mouseover")c=b.fromElement;else if(d=="mouseout")c=b.toElement}for(;c;){if(c==a)return true;c=c.parentNode}return false};
Calendar.removeClass=function(a,b){if(a&&a.className){for(var c=a.className.split(" "),d=[],e=c.length;e>0;)if(c[--e]!=b)d[d.length]=c[e];a.className=d.join(" ")}};Calendar.addClass=function(a,b){Calendar.removeClass(a,b);a.className+=" "+b};Calendar.getElement=function(a){for(a=Calendar.is_ie?window.event.srcElement:a.currentTarget;a.nodeType!=1||/^div$/i.test(a.tagName);)a=a.parentNode;return a};
Calendar.getTargetElement=function(a){for(a=Calendar.is_ie?window.event.srcElement:a.target;a.nodeType!=1;)a=a.parentNode;return a};Calendar.stopEvent=function(a){a||(a=window.event);if(Calendar.is_ie){a.cancelBubble=true;a.returnValue=false}else{a.preventDefault();a.stopPropagation()}return false};Calendar.addEvent=function(a,b,c){if(a.attachEvent)a.attachEvent("on"+b,c);else if(a.addEventListener)a.addEventListener(b,c,true);else a["on"+b]=c};
Calendar.removeEvent=function(a,b,c){if(a.detachEvent)a.detachEvent("on"+b,c);else if(a.removeEventListener)a.removeEventListener(b,c,true);else a["on"+b]=null};Calendar.createElement=function(a,b){var c=null;c=document.createElementNS?document.createElementNS("http://www.w3.org/1999/xhtml",a):document.createElement(a);typeof b!="undefined"&&b.appendChild(c);return c};
Calendar._add_evs=function(a){with(Calendar){addEvent(a,"mouseover",dayMouseOver);addEvent(a,"mousedown",dayMouseDown);addEvent(a,"mouseout",dayMouseOut);if(is_ie){addEvent(a,"dblclick",dayMouseDblClick);a.setAttribute("unselectable",true)}}};Calendar.findMonth=function(a){if(typeof a.month!="undefined")return a;else if(typeof a.parentNode.month!="undefined")return a.parentNode;return null};
Calendar.findYear=function(a){if(typeof a.year!="undefined")return a;else if(typeof a.parentNode.year!="undefined")return a.parentNode;return null};
Calendar.showMonthsCombo=function(){var a=Calendar._C;if(!a)return false;var b=a.activeDiv,c=a.monthsCombo;a.hilitedMonth&&Calendar.removeClass(a.hilitedMonth,"hilite");a.activeMonth&&Calendar.removeClass(a.activeMonth,"active");var d=a.monthsCombo.getElementsByTagName("div")[a.date.getMonth()];Calendar.addClass(d,"active");a.activeMonth=d;a=c.style;a.display="block";if(b.navtype<0)a.left=b.offsetLeft+"px";else{c=c.offsetWidth;if(typeof c=="undefined")c=50;a.left=b.offsetLeft+b.offsetWidth-c+"px"}a.top=
b.offsetTop+b.offsetHeight+"px"};
Calendar.showYearsCombo=function(a){var b=Calendar._C;if(!b)return false;var c=b.activeDiv,d=b.yearsCombo;b.hilitedYear&&Calendar.removeClass(b.hilitedYear,"hilite");b.activeYear&&Calendar.removeClass(b.activeYear,"active");b.activeYear=null;for(var e=b.date.getFullYear()+(a?1:-1),g=d.firstChild,f=false,j=12;j>0;--j){if(e>=b.minYear&&e<=b.maxYear){g.innerHTML=e;g.year=e;g.style.display="block";f=true}else g.style.display="none";g=g.nextSibling;e+=a?b.yearStep:-b.yearStep}if(f){a=d.style;a.display=
"block";if(c.navtype<0)a.left=c.offsetLeft+"px";else{d=d.offsetWidth;if(typeof d=="undefined")d=50;a.left=c.offsetLeft+c.offsetWidth-d+"px"}a.top=c.offsetTop+c.offsetHeight+"px"}};
Calendar.tableMouseUp=function(a){var b=Calendar._C;if(!b)return false;b.timeout&&clearTimeout(b.timeout);var c=b.activeDiv;if(!c)return false;var d=Calendar.getTargetElement(a);a||(a=window.event);Calendar.removeClass(c,"active");if(d==c||d.parentNode==c)Calendar.cellClick(c,a);var e=Calendar.findMonth(d);c=null;if(e){c=new Date(b.date);if(e.month!=c.getMonth()){c.setMonth(e.month);b.setDate(c);b.dateClicked=false;b.callHandler()}}else if(d=Calendar.findYear(d)){c=new Date(b.date);if(d.year!=c.getFullYear()){c.setFullYear(d.year);
b.setDate(c);b.dateClicked=false;b.callHandler()}}with(Calendar){removeEvent(document,"mouseup",tableMouseUp);removeEvent(document,"mouseover",tableMouseOver);removeEvent(document,"mousemove",tableMouseOver);b._hideCombos();_C=null;return stopEvent(a)}};
Calendar.tableMouseOver=function(a){var b=Calendar._C;if(b){var c=b.activeDiv,d=Calendar.getTargetElement(a);if(d==c||d.parentNode==c){Calendar.addClass(c,"hilite active");Calendar.addClass(c.parentNode,"rowhilite")}else{if(typeof c.navtype=="undefined"||c.navtype!=50&&(c.navtype==0||Math.abs(c.navtype)>2))Calendar.removeClass(c,"active");Calendar.removeClass(c,"hilite");Calendar.removeClass(c.parentNode,"rowhilite")}a||(a=window.event);if(c.navtype==50&&d!=c){var e=Calendar.getAbsolutePos(c),g=c.offsetWidth,
f=a.clientX,j=true;if(f>e.x+g){f=f-e.x-g;j=false}else f=e.x-f;if(f<0)f=0;e=c._range;g=c._current;f=Math.floor(f/10)%e.length;for(var h=e.length;--h>=0;)if(e[h]==g)break;for(;f-- >0;)if(j){if(--h<0)h=e.length-1}else if(++h>=e.length)h=0;c.innerHTML=e[h];b.onUpdateTime()}if(c=Calendar.findMonth(d))if(c.month!=b.date.getMonth()){b.hilitedMonth&&Calendar.removeClass(b.hilitedMonth,"hilite");Calendar.addClass(c,"hilite");b.hilitedMonth=c}else b.hilitedMonth&&Calendar.removeClass(b.hilitedMonth,"hilite");
else{b.hilitedMonth&&Calendar.removeClass(b.hilitedMonth,"hilite");if(d=Calendar.findYear(d))if(d.year!=b.date.getFullYear()){b.hilitedYear&&Calendar.removeClass(b.hilitedYear,"hilite");Calendar.addClass(d,"hilite");b.hilitedYear=d}else b.hilitedYear&&Calendar.removeClass(b.hilitedYear,"hilite");else b.hilitedYear&&Calendar.removeClass(b.hilitedYear,"hilite")}return Calendar.stopEvent(a)}};Calendar.tableMouseDown=function(a){if(Calendar.getTargetElement(a)==Calendar.getElement(a))return Calendar.stopEvent(a)};
Calendar.calDragIt=function(a){var b=Calendar._C;if(!(b&&b.dragging))return false;var c,d;if(Calendar.is_ie){d=window.event.clientY+document.body.scrollTop;c=window.event.clientX+document.body.scrollLeft}else{c=a.pageX;d=a.pageY}b.hideShowCovered();var e=b.element.style;e.left=c-b.xOffs+"px";e.top=d-b.yOffs+"px";return Calendar.stopEvent(a)};
Calendar.calDragEnd=function(a){var b=Calendar._C;if(!b)return false;b.dragging=false;with(Calendar){removeEvent(document,"mousemove",calDragIt);removeEvent(document,"mouseup",calDragEnd);tableMouseUp(a)}b.hideShowCovered()};
Calendar.dayMouseDown=function(a){var b=Calendar.getElement(a);if(b.disabled)return false;var c=b.calendar;c.activeDiv=b;Calendar._C=c;if(b.navtype!=300)with(Calendar){if(b.navtype==50){b._current=b.innerHTML;addEvent(document,"mousemove",tableMouseOver)}else addEvent(document,Calendar.is_ie5?"mousemove":"mouseover",tableMouseOver);addClass(b,"hilite active");addEvent(document,"mouseup",tableMouseUp)}else c.isPopup&&c._dragStart(a);if(b.navtype==-1||b.navtype==1){c.timeout&&clearTimeout(c.timeout);
c.timeout=setTimeout("Calendar.showMonthsCombo()",250)}else if(b.navtype==-2||b.navtype==2){c.timeout&&clearTimeout(c.timeout);c.timeout=setTimeout(b.navtype>0?"Calendar.showYearsCombo(true)":"Calendar.showYearsCombo(false)",250)}else c.timeout=null;return Calendar.stopEvent(a)};Calendar.dayMouseDblClick=function(a){Calendar.cellClick(Calendar.getElement(a),a||window.event);Calendar.is_ie&&document.selection.empty()};
Calendar.dayMouseOver=function(a){var b=Calendar.getElement(a);if(Calendar.isRelated(b,a)||Calendar._C||b.disabled)return false;if(b.ttip){if(b.ttip.substr(0,1)=="_")b.ttip=b.caldate.print(b.calendar.ttDateFormat)+b.ttip.substr(1);b.calendar.tooltips.innerHTML=b.ttip}if(b.navtype!=300){Calendar.addClass(b,"hilite");b.caldate&&Calendar.addClass(b.parentNode,"rowhilite")}return Calendar.stopEvent(a)};
Calendar.dayMouseOut=function(a){with(Calendar){var b=getElement(a);if(isRelated(b,a)||_C||b.disabled)return false;removeClass(b,"hilite");b.caldate&&removeClass(b.parentNode,"rowhilite");if(b.calendar)b.calendar.tooltips.innerHTML=_TT.SEL_DATE;return stopEvent(a)}};
Calendar.cellClick=function(a,b){var c=a.calendar,d=false,e=false,g=null;if(typeof a.navtype=="undefined"){if(c.currentDateEl){Calendar.removeClass(c.currentDateEl,"selected");Calendar.addClass(a,"selected");d=c.currentDateEl==a;if(!d)c.currentDateEl=a}c.date.setDateOnly(a.caldate);g=c.date;var f=!(c.dateClicked=!a.otherMonth);if(!f&&!c.currentDateEl)c._toggleMultipleDate(new Date(g));else e=!a.disabled;f&&c._init(c.firstDayOfWeek,g)}else{if(a.navtype==200){Calendar.removeClass(a,"hilite");c.callCloseHandler();
return}g=new Date(c.date);a.navtype==0&&g.setDateOnly(new Date);c.dateClicked=false;f=g.getFullYear();var j=g.getMonth(),h=function(i){var l=g.getDate(),n=g.getMonthDays(i);l>n&&g.setDate(n);g.setMonth(i)};switch(a.navtype){case 400:Calendar.removeClass(a,"hilite");d=Calendar._TT.ABOUT;if(typeof d!="undefined")d+=c.showsTime?Calendar._TT.ABOUT_TIME:"";else d='Help and about box text is not translated into this language.\nIf you know this language and you feel generous please update\nthe corresponding file in "lang" subdir to match calendar-en.js\nand send it back to <mihai_bazon@yahoo.com> to get it into the distribution  ;-)\n\nThank you!\nhttp://dynarch.com/mishoo/calendar.epl\n';
alert(d);return;case -2:f>c.minYear&&g.setFullYear(f-1);break;case -1:if(j>0)h(j-1);else if(f-- >c.minYear){g.setFullYear(f);h(11)}break;case 1:if(j<11)h(j+1);else if(f<c.maxYear){g.setFullYear(f+1);h(0)}break;case 2:f<c.maxYear&&g.setFullYear(f+1);break;case 100:c.setFirstDayOfWeek(a.fdow);return;case 50:d=a._range;e=a.innerHTML;for(f=d.length;--f>=0;)if(d[f]==e)break;if(b&&b.shiftKey){if(--f<0)f=d.length-1}else if(++f>=d.length)f=0;a.innerHTML=d[f];c.onUpdateTime();return;case 0:if(typeof c.getDateStatus==
"function"&&c.getDateStatus(g,g.getFullYear(),g.getMonth(),g.getDate()))return false}if(g.equalsTo(c.date)){if(a.navtype==0)e=d=true}else{c.setDate(g);e=true}}e&&b&&c.callHandler();if(d){Calendar.removeClass(a,"hilite");b&&c.callCloseHandler()}};
Calendar.prototype.create=function(a){var b=null;if(a){b=a;this.isPopup=false}else{b=document.getElementsByTagName("body")[0];this.isPopup=true}this.date=this.dateStr?new Date(this.dateStr):new Date;this.table=a=Calendar.createElement("table");a.cellSpacing=0;a.cellPadding=0;a.calendar=this;Calendar.addEvent(a,"mousedown",Calendar.tableMouseDown);var c=Calendar.createElement("div");this.element=c;c.className="calendar";if(this.isPopup){c.style.position="absolute";c.style.display="none"}c.appendChild(a);
var d=Calendar.createElement("thead",a),e=null,g=null,f=this;c=function(i,l,n){e=Calendar.createElement("td",g);e.colSpan=l;e.className="button";if(n!=0&&Math.abs(n)<=2)e.className+=" nav";Calendar._add_evs(e);e.calendar=f;e.navtype=n;e.innerHTML="<div unselectable='on'>"+i+"</div>";return e};g=Calendar.createElement("tr",d);var j=6;this.isPopup&&--j;this.weekNumbers&&++j;c("?",1,400).ttip=Calendar._TT.INFO;this.title=c("",j,300);this.title.className="title";if(this.isPopup){this.title.ttip=Calendar._TT.DRAG_TO_MOVE;
this.title.style.cursor="move";c("&#x00d7;",1,200).ttip=Calendar._TT.CLOSE}g=Calendar.createElement("tr",d);g.className="headrow";this._nav_py=c("&#x00ab;",1,-2);this._nav_py.ttip=Calendar._TT.PREV_YEAR;this._nav_pm=c("&#x2039;",1,-1);this._nav_pm.ttip=Calendar._TT.PREV_MONTH;this._nav_now=c(Calendar._TT.TODAY,this.weekNumbers?4:3,0);this._nav_now.ttip=Calendar._TT.GO_TODAY;this._nav_nm=c("&#x203a;",1,1);this._nav_nm.ttip=Calendar._TT.NEXT_MONTH;this._nav_ny=c("&#x00bb;",1,2);this._nav_ny.ttip=Calendar._TT.NEXT_YEAR;
g=Calendar.createElement("tr",d);g.className="daynames";if(this.weekNumbers){e=Calendar.createElement("td",g);e.className="name wn";e.innerHTML=Calendar._TT.WK}for(d=7;d>0;--d){e=Calendar.createElement("td",g);if(!d){e.navtype=100;e.calendar=this;Calendar._add_evs(e)}}this.firstdayname=this.weekNumbers?g.firstChild.nextSibling:g.firstChild;this._displayWeekdays();this.tbody=j=Calendar.createElement("tbody",a);for(d=6;d>0;--d){g=Calendar.createElement("tr",j);if(this.weekNumbers)e=Calendar.createElement("td",
g);for(var h=7;h>0;--h){e=Calendar.createElement("td",g);e.calendar=this;Calendar._add_evs(e)}}if(this.showsTime){g=Calendar.createElement("tr",j);g.className="time";e=Calendar.createElement("td",g);e.className="time";e.colSpan=2;e.innerHTML=Calendar._TT.TIME||"&nbsp;";e=Calendar.createElement("td",g);e.className="time";e.colSpan=this.weekNumbers?4:3;(function(){function i(o,p,s,u){var q=Calendar.createElement("span",e);q.className=o;q.innerHTML=p;q.calendar=f;q.ttip=Calendar._TT.TIME_PART;q.navtype=
50;q._range=[];if(typeof s!="number")q._range=s;else for(o=s;o<=u;++o)q._range[q._range.length]=o<10&&u>=10?"0"+o:""+o;Calendar._add_evs(q);return q}var l=f.date.getHours(),n=f.date.getMinutes(),m=!f.time24,k=l>12;if(m&&k)l-=12;var t=i("hour",l,m?1:0,m?12:23);l=Calendar.createElement("span",e);l.innerHTML=":";l.className="colon";var r=i("minute",n,0,59),v=null;e=Calendar.createElement("td",g);e.className="time";e.colSpan=2;if(m)v=i("ampm",k?"pm":"am",["am","pm"]);else e.innerHTML="&nbsp;";f.onSetTime=
function(){var o,p=this.date.getHours(),s=this.date.getMinutes();if(m){if(o=p>=12)p-=12;if(p==0)p=12;v.innerHTML=o?"pm":"am"}t.innerHTML=p<10?"0"+p:p;r.innerHTML=s<10?"0"+s:s};f.onUpdateTime=function(){var o=this.date,p=parseInt(t.innerHTML,10);if(m)if(/pm/i.test(v.innerHTML)&&p<12)p+=12;else if(/am/i.test(v.innerHTML)&&p==12)p=0;var s=o.getDate(),u=o.getMonth(),q=o.getFullYear();o.setHours(p);o.setMinutes(parseInt(r.innerHTML,10));o.setFullYear(q);o.setMonth(u);o.setDate(s);this.dateClicked=false;
this.callHandler()}})()}else this.onSetTime=this.onUpdateTime=function(){};a=Calendar.createElement("tfoot",a);g=Calendar.createElement("tr",a);g.className="footrow";e=c(Calendar._TT.SEL_DATE,this.weekNumbers?8:7,300);e.className="ttip";if(this.isPopup){e.ttip=Calendar._TT.DRAG_TO_MOVE;e.style.cursor="move"}this.tooltips=e;this.monthsCombo=c=Calendar.createElement("div",this.element);c.className="combo";for(d=0;d<Calendar._MN.length;++d){a=Calendar.createElement("div");a.className=Calendar.is_ie?
"label-IEfix":"label";a.month=d;a.innerHTML=Calendar._SMN[d];c.appendChild(a)}this.yearsCombo=c=Calendar.createElement("div",this.element);c.className="combo";for(d=12;d>0;--d){a=Calendar.createElement("div");a.className=Calendar.is_ie?"label-IEfix":"label";c.appendChild(a)}this._init(this.firstDayOfWeek,this.date);b.appendChild(this.element)};
Calendar._keyEvent=function(a){var b=window._dynarch_popupCalendar;if(!b||b.multiple)return false;Calendar.is_ie&&(a=window.event);var c=Calendar.is_ie||a.type=="keypress",d=a.keyCode;if(a.ctrlKey)switch(d){case 37:c&&Calendar.cellClick(b._nav_pm);break;case 38:c&&Calendar.cellClick(b._nav_py);break;case 39:c&&Calendar.cellClick(b._nav_nm);break;case 40:c&&Calendar.cellClick(b._nav_ny);break;default:return false}else switch(d){case 32:Calendar.cellClick(b._nav_now);break;case 27:c&&b.callCloseHandler();
break;case 37:case 38:case 39:case 40:if(c){var e,g,f,j,h;c=d==37||d==38;h=d==37||d==39?1:7;var i=function(){j=b.currentDateEl;var m=j.pos;e=m&15;g=m>>4;f=b.ar_days[g][e]};i();for(var l=function(){var m=new Date(b.date);m.setDate(m.getDate()-h);b.setDate(m)},n=function(){var m=new Date(b.date);m.setDate(m.getDate()+h);b.setDate(m)};;){switch(d){case 37:if(--e>=0)f=b.ar_days[g][e];else{e=6;d=38;continue}break;case 38:if(--g>=0)f=b.ar_days[g][e];else{l();i()}break;case 39:if(++e<7)f=b.ar_days[g][e];
else{e=0;d=40;continue}break;case 40:if(++g<b.ar_days.length)f=b.ar_days[g][e];else{n();i()}}break}if(f)if(f.disabled)c?l():n();else Calendar.cellClick(f)}break;case 13:c&&Calendar.cellClick(b.currentDateEl,a);break;default:return false}return Calendar.stopEvent(a)};
Calendar.prototype._init=function(a,b){var c=new Date,d=c.getFullYear(),e=c.getMonth();c=c.getDate();this.table.style.visibility="hidden";var g=b.getFullYear();if(g<this.minYear){g=this.minYear;b.setFullYear(g)}else if(g>this.maxYear){g=this.maxYear;b.setFullYear(g)}this.firstDayOfWeek=a;this.date=new Date(b);var f=b.getMonth(),j=b.getDate();b.getMonthDays();b.setDate(1);var h=(b.getDay()-this.firstDayOfWeek)%7;if(h<0)h+=7;b.setDate(-h);b.setDate(b.getDate()+1);h=this.tbody.firstChild;for(var i=this.ar_days=
[],l=Calendar._TT.WEEKEND,n=this.multiple?this.datesCells={}:null,m=0;m<6;++m,h=h.nextSibling){var k=h.firstChild;if(this.weekNumbers){k.className="day wn";k.innerHTML=b.getWeekNumber();k=k.nextSibling}h.className="daysrow";for(var t=false,r,v=i[m]=[],o=0;o<7;++o,k=k.nextSibling,b.setDate(r+1)){r=b.getDate();var p=b.getDay();k.className="day";k.pos=m<<4|o;v[o]=k;var s=b.getMonth()==f;if(s){k.otherMonth=false;t=true}else if(this.showsOtherMonths){k.className+=" othermonth";k.otherMonth=true}else{k.className=
"emptycell";k.innerHTML="&nbsp;";k.disabled=true;continue}k.disabled=false;k.innerHTML=this.getDateText?this.getDateText(b,r):r;if(n)n[b.print("%Y%m%d")]=k;if(this.getDateStatus){var u=this.getDateStatus(b,g,f,r);if(this.getDateToolTip){var q=this.getDateToolTip(b,g,f,r);if(q)k.title=q}if(u===true){k.className+=" disabled";k.disabled=true}else{if(/disabled/i.test(u))k.disabled=true;k.className+=" "+u}}if(!k.disabled){k.caldate=new Date(b);k.ttip="_";if(!this.multiple&&s&&r==j&&this.hiliteToday){k.className+=
" selected";this.currentDateEl=k}if(b.getFullYear()==d&&b.getMonth()==e&&r==c){k.className+=" today";k.ttip+=Calendar._TT.PART_TODAY}if(l.indexOf(p.toString())!=-1)k.className+=k.otherMonth?" oweekend":" weekend"}}if(!(t||this.showsOtherMonths))h.className="emptyrow"}this.title.innerHTML=Calendar._MN[f]+", "+g;this.onSetTime();this.table.style.visibility="visible";this._initMultipleDates()};
Calendar.prototype._initMultipleDates=function(){if(this.multiple)for(var a in this.multiple){var b=this.datesCells[a];if(this.multiple[a])if(b)b.className+=" selected"}};Calendar.prototype._toggleMultipleDate=function(a){if(this.multiple){var b=a.print("%Y%m%d"),c=this.datesCells[b];if(c)if(this.multiple[b]){Calendar.removeClass(c,"selected");delete this.multiple[b]}else{Calendar.addClass(c,"selected");this.multiple[b]=a}}};
Calendar.prototype.setDateToolTipHandler=function(a){this.getDateToolTip=a};Calendar.prototype.setDate=function(a){a.equalsTo(this.date)||this._init(this.firstDayOfWeek,a)};Calendar.prototype.refresh=function(){this._init(this.firstDayOfWeek,this.date)};Calendar.prototype.setFirstDayOfWeek=function(a){this._init(a,this.date);this._displayWeekdays()};Calendar.prototype.setDateStatusHandler=Calendar.prototype.setDisabledHandler=function(a){this.getDateStatus=a};
Calendar.prototype.setRange=function(a,b){this.minYear=a;this.maxYear=b};Calendar.prototype.callHandler=function(){this.onSelected&&this.onSelected(this,this.date.print(this.dateFormat))};Calendar.prototype.callCloseHandler=function(){this.onClose&&this.onClose(this);this.hideShowCovered()};Calendar.prototype.destroy=function(){this.element.parentNode.removeChild(this.element);Calendar._C=null;window._dynarch_popupCalendar=null};
Calendar.prototype.reparent=function(a){var b=this.element;b.parentNode.removeChild(b);a.appendChild(b)};Calendar._checkCalendar=function(a){var b=window._dynarch_popupCalendar;if(!b)return false;for(var c=Calendar.is_ie?Calendar.getElement(a):Calendar.getTargetElement(a);c!=null&&c!=b.element;c=c.parentNode);if(c==null){window._dynarch_popupCalendar.callCloseHandler();return Calendar.stopEvent(a)}};
Calendar.prototype.show=function(){for(var a=this.table.getElementsByTagName("tr"),b=a.length;b>0;){var c=a[--b];Calendar.removeClass(c,"rowhilite");c=c.getElementsByTagName("td");for(var d=c.length;d>0;){var e=c[--d];Calendar.removeClass(e,"hilite");Calendar.removeClass(e,"active")}}this.element.style.display="block";this.hidden=false;if(this.isPopup){window._dynarch_popupCalendar=this;Calendar.addEvent(document,"keydown",Calendar._keyEvent);Calendar.addEvent(document,"keypress",Calendar._keyEvent);
Calendar.addEvent(document,"mousedown",Calendar._checkCalendar)}this.hideShowCovered()};Calendar.prototype.hide=function(){if(this.isPopup){Calendar.removeEvent(document,"keydown",Calendar._keyEvent);Calendar.removeEvent(document,"keypress",Calendar._keyEvent);Calendar.removeEvent(document,"mousedown",Calendar._checkCalendar)}this.element.style.display="none";this.hidden=true;this.hideShowCovered()};Calendar.prototype.showAt=function(a,b){var c=this.element.style;c.left=a+"px";c.top=b+"px";this.show()};
Calendar.prototype.showAtElement=function(a,b){var c=this,d=Calendar.getAbsolutePos(a);if(!b||typeof b!="string"){this.showAt(d.x,d.y+a.offsetHeight);return true}this.element.style.display="block";Calendar.continuation_for_the_fucking_khtml_browser=function(){var e=c.element.offsetWidth,g=c.element.offsetHeight;c.element.style.display="none";var f=b.substr(0,1),j="l";if(b.length>1)j=b.substr(1,1);switch(f){case "T":d.y-=g;break;case "B":d.y+=a.offsetHeight;break;case "C":d.y+=(a.offsetHeight-g)/2;
break;case "t":d.y+=a.offsetHeight-g}switch(j){case "L":d.x-=e;break;case "R":d.x+=a.offsetWidth;break;case "C":d.x+=(a.offsetWidth-e)/2;break;case "l":d.x+=a.offsetWidth-e}d.width=e;d.height=g+40;c.monthsCombo.style.display="none";if(d.x<0)d.x=0;if(d.y<0)d.y=0;e=document.createElement("div");g=e.style;g.position="absolute";g.right=g.bottom=g.width=g.height="0px";document.body.appendChild(e);g=Calendar.getAbsolutePos(e);document.body.removeChild(e);if(Calendar.is_ie){g.y+=document.body.scrollTop;
g.x+=document.body.scrollLeft}else{g.y+=window.scrollY;g.x+=window.scrollX}e=d.x+d.width-g.x;if(e>0)d.x-=e;e=d.y+d.height-g.y;if(e>0)d.y-=e;c.showAt(d.x,d.y)};Calendar.is_khtml?setTimeout("Calendar.continuation_for_the_fucking_khtml_browser()",10):Calendar.continuation_for_the_fucking_khtml_browser()};Calendar.prototype.setDateFormat=function(a){this.dateFormat=a};Calendar.prototype.setTtDateFormat=function(a){this.ttDateFormat=a};
Calendar.prototype.parseDate=function(a,b){if(!b)b=this.dateFormat;this.setDate(Date.parseDate(a,b))};
Calendar.prototype.hideShowCovered=function(){function a(t){var r=t.style.visibility;r||(r=document.defaultView&&typeof document.defaultView.getComputedStyle=="function"?Calendar.is_khtml?"":document.defaultView.getComputedStyle(t,"").getPropertyValue("visibility"):t.currentStyle?t.currentStyle.visibility:"");return r}if(Calendar.is_ie||Calendar.is_opera){var b=["applet","iframe","select"],c=this.element,d=Calendar.getAbsolutePos(c),e=d.x,g=c.offsetWidth+e,f=d.y;c=c.offsetHeight+f;for(var j=b.length;j>
0;)for(var h=document.getElementsByTagName(b[--j]),i=null,l=h.length;l>0;){i=h[--l];d=Calendar.getAbsolutePos(i);var n=d.x,m=i.offsetWidth+n;d=d.y;var k=i.offsetHeight+d;if(this.hidden||n>g||m<e||d>c||k<f){if(!i.__msh_save_visibility)i.__msh_save_visibility=a(i);i.style.visibility=i.__msh_save_visibility}else{if(!i.__msh_save_visibility)i.__msh_save_visibility=a(i);i.style.visibility="hidden"}}}};
Calendar.prototype._displayWeekdays=function(){for(var a=this.firstDayOfWeek,b=this.firstdayname,c=Calendar._TT.WEEKEND,d=0;d<7;++d){b.className="day name";var e=(d+a)%7;if(d){b.ttip=Calendar._TT.DAY_FIRST.replace("%s",Calendar._DN[e]);b.navtype=100;b.calendar=this;b.fdow=e;Calendar._add_evs(b)}c.indexOf(e.toString())!=-1&&Calendar.addClass(b,"weekend");b.innerHTML=Calendar._SDN[(d+a)%7];b=b.nextSibling}};
Calendar.prototype._hideCombos=function(){this.monthsCombo.style.display="none";this.yearsCombo.style.display="none"};
Calendar.prototype._dragStart=function(a){if(!this.dragging){this.dragging=true;var b;if(Calendar.is_ie){b=window.event.clientY+document.body.scrollTop;a=window.event.clientX+document.body.scrollLeft}else{b=a.clientY+window.scrollY;a=a.clientX+window.scrollX}var c=this.element.style;this.xOffs=a-parseInt(c.left);this.yOffs=b-parseInt(c.top);with(Calendar){addEvent(document,"mousemove",calDragIt);addEvent(document,"mouseup",calDragEnd)}}};Date._MD=[31,28,31,30,31,30,31,31,30,31,30,31];
Date.SECOND=1E3;Date.MINUTE=60*Date.SECOND;Date.HOUR=60*Date.MINUTE;Date.DAY=24*Date.HOUR;Date.WEEK=7*Date.DAY;
Date.parseDate=function(a,b){var c=new Date,d=0,e=-1,g=0,f=a.split(/\W+/),j=b.match(/%./g),h=0,i=0,l=0,n=0;for(h=0;h<f.length;++h)if(f[h])switch(j[h]){case "%d":case "%e":g=parseInt(f[h],10);break;case "%m":e=parseInt(f[h],10)-1;break;case "%Y":case "%y":d=parseInt(f[h],10);d<100&&(d+=d>29?1900:2E3);break;case "%b":case "%B":for(i=0;i<12;++i)if(Calendar._MN[i].substr(0,f[h].length).toLowerCase()==f[h].toLowerCase()){e=i;break}break;case "%H":case "%I":case "%k":case "%l":l=parseInt(f[h],10);break;
case "%P":case "%p":if(/pm/i.test(f[h])&&l<12)l+=12;else if(/am/i.test(f[h])&&l>=12)l-=12;break;case "%M":n=parseInt(f[h],10)}if(isNaN(d))d=c.getFullYear();if(isNaN(e))e=c.getMonth();if(isNaN(g))g=c.getDate();if(isNaN(l))l=c.getHours();if(isNaN(n))n=c.getMinutes();if(d!=0&&e!=-1&&g!=0)return new Date(d,e,g,l,n,0);d=0;e=-1;for(h=g=0;h<f.length;++h)if(f[h].search(/[a-zA-Z]+/)!=-1){j=-1;for(i=0;i<12;++i)if(Calendar._MN[i].substr(0,f[h].length).toLowerCase()==f[h].toLowerCase()){j=i;break}if(j!=-1){if(e!=
-1)g=e+1;e=j}}else if(parseInt(f[h],10)<=12&&e==-1)e=f[h]-1;else if(parseInt(f[h],10)>31&&d==0){d=parseInt(f[h],10);d<100&&(d+=d>29?1900:2E3)}else if(g==0)g=f[h];if(d==0)d=c.getFullYear();if(e!=-1&&g!=0)return new Date(d,e,g,l,n,0);return c};Date.prototype.getMonthDays=function(a){var b=this.getFullYear();if(typeof a=="undefined")a=this.getMonth();return 0==b%4&&(0!=b%100||0==b%400)&&a==1?29:Date._MD[a]};
Date.prototype.getDayOfYear=function(){var a=new Date(this.getFullYear(),this.getMonth(),this.getDate(),0,0,0),b=new Date(this.getFullYear(),0,0,0,0,0);return Math.floor((a-b)/Date.DAY)};Date.prototype.getWeekNumber=function(){var a=new Date(this.getFullYear(),this.getMonth(),this.getDate(),0,0,0),b=a.getDay();a.setDate(a.getDate()-(b+6)%7+3);b=a.valueOf();a.setMonth(0);a.setDate(4);return Math.round((b-a.valueOf())/6048E5)+1};
Date.prototype.equalsTo=function(a){return this.getFullYear()==a.getFullYear()&&this.getMonth()==a.getMonth()&&this.getDate()==a.getDate()&&this.getHours()==a.getHours()&&this.getMinutes()==a.getMinutes()};Date.prototype.setDateOnly=function(a){a=new Date(a);this.setDate(1);this.setFullYear(a.getFullYear());this.setMonth(a.getMonth());this.setDate(a.getDate())};
Date.prototype.print=function(a){var b=this.getMonth(),c=this.getDate(),d=this.getFullYear(),e=this.getWeekNumber(),g=this.getDay(),f={},j=this.getHours(),h=j>=12,i=h?j-12:j,l=this.getDayOfYear();if(i==0)i=12;var n=this.getMinutes(),m=this.getSeconds();f["%a"]=Calendar._SDN[g];f["%A"]=Calendar._DN[g];f["%b"]=Calendar._SMN[b];f["%B"]=Calendar._MN[b];f["%C"]=1+Math.floor(d/100);f["%d"]=c<10?"0"+c:c;f["%e"]=c;f["%H"]=j<10?"0"+j:j;f["%I"]=i<10?"0"+i:i;f["%j"]=l<100?l<10?"00"+l:"0"+l:l;f["%k"]=j;f["%l"]=
i;f["%m"]=b<9?"0"+(1+b):1+b;f["%M"]=n<10?"0"+n:n;f["%n"]="\n";f["%p"]=h?"PM":"AM";f["%P"]=h?"pm":"am";f["%s"]=Math.floor(this.getTime()/1E3);f["%S"]=m<10?"0"+m:m;f["%t"]="\t";f["%U"]=f["%W"]=f["%V"]=e<10?"0"+e:e;f["%u"]=g+1;f["%w"]=g;f["%y"]=(""+d).substr(2,2);f["%Y"]=d;f["%%"]="%";b=/%./g;if(!Calendar.is_ie5&&!Calendar.is_khtml)return a.replace(b,function(k){return f[k]||k});c=a.match(b);for(d=0;d<c.length;d++)if(e=f[c[d]]){b=RegExp(c[d],"g");a=a.replace(b,e)}return a};
Date.prototype.__msh_oldSetFullYear=Date.prototype.setFullYear;Date.prototype.setFullYear=function(a){var b=new Date(this);b.__msh_oldSetFullYear(a);b.getMonth()!=this.getMonth()&&this.setDate(28);this.__msh_oldSetFullYear(a)};window._dynarch_popupCalendar=null;

Calendar._DN=new Array("\u0412\u043e\u0441\u043a\u0440\u0435\u0441\u0435\u043d\u044c\u0435","\u041f\u043e\u043d\u0435\u0434\u0435\u043b\u044c\u043d\u0438\u043a","\u0412\u0442\u043e\u0440\u043d\u0438\u043a","\u0421\u0440\u0435\u0434\u0430","\u0427\u0435\u0442\u0432\u0435\u0440\u0433","\u041f\u044f\u0442\u043d\u0438\u0446\u0430","\u0421\u0443\u0431\u0431\u043e\u0442\u0430","\u0412\u043e\u0441\u043a\u0440\u0435\u0441\u0435\u043d\u044c\u0435");
Calendar._SDN=new Array("\u0412\u0441","\u041f\u043d","\u0412\u0442","\u0421\u0440","\u0427\u0442","\u041f\u0442","\u0421\u0431","\u0412\u0441");Calendar._FD=1;
Calendar._MN=new Array("\u042f\u043d\u0432\u0430\u0440\u044c","\u0424\u0435\u0432\u0440\u0430\u043b\u044c","\u041c\u0430\u0440\u0442","\u0410\u043f\u0440\u0435\u043b\u044c","\u041c\u0430\u0439","\u0418\u044e\u043d\u044c","\u0418\u044e\u043b\u044c","\u0410\u0432\u0433\u0443\u0441\u0442","\u0421\u0435\u043d\u0442\u044f\u0431\u0440\u044c","\u041e\u043a\u0442\u044f\u0431\u0440\u044c","\u041d\u043e\u044f\u0431\u0440\u044c","\u0414\u0435\u043a\u0430\u0431\u0440\u044c");
Calendar._SMN=new Array("\u042f\u043d\u0432","\u0424\u0435\u0432","\u041c\u0430\u0440","\u0410\u043f\u0440","\u041c\u0430\u0439","\u0418\u044e\u043d","\u0418\u044e\u043b","\u0410\u0432\u0433","\u0421\u0435\u043d","\u041e\u043a\u0442","\u041d\u043e\u044f","\u0414\u0435\u043a");Calendar._TT={};Calendar._TT.INFO="\u041e \u043a\u0430\u043b\u0435\u043d\u0434\u0430\u0440\u0435";
Calendar._TT.ABOUT="DHTML Date/Time Selector\n(c) dynarch.com 2002-2005 / Author: Mihai Bazon\nFor latest version visit: http://www.dynarch.com/projects/calendar/\nDistributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details.\n\nDate selection:\n- Use the \u00ab, \u00bb buttons to select year\n- Use the "+String.fromCharCode(8249)+", "+String.fromCharCode(8250)+" buttons to select month\n- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT.ABOUT_TIME="\n\nTime selection:\n- Click on any of the time parts to increase it\n- or Shift-click to decrease it\n- or click and drag for faster selection.";Calendar._TT.PREV_YEAR="\u041f\u0440\u0435\u0434\u044b\u0434\u0443\u0449\u0438\u0439 \u0433\u043e\u0434";Calendar._TT.PREV_MONTH="\u041f\u0440\u0435\u0434\u044b\u0434\u0443\u0449\u0438\u0439 \u043c\u0435\u0441\u044f\u0446";Calendar._TT.GO_TODAY="\u0422\u0435\u043a\u0443\u0449\u0430\u044f \u0434\u0430\u0442\u0430";
Calendar._TT.NEXT_MONTH="\u0421\u043b\u0435\u0434\u0443\u044e\u0449\u0438\u0439 \u043c\u0435\u0441\u044f\u0446";Calendar._TT.NEXT_YEAR="\u0421\u043b\u0435\u0434\u0443\u044e\u0449\u0438\u0439 \u0433\u043e\u0434";Calendar._TT.SEL_DATE="\u0412\u044b\u0431\u0435\u0440\u0435\u0442\u0435 \u0434\u0430\u0442\u0443";Calendar._TT.DRAG_TO_MOVE="\u0443\u0434\u0435\u0440\u0436\u0438\u0432\u0430\u0439\u0442\u0435 \u0434\u043b\u044f \u043f\u0435\u0440\u0435\u043c\u0435\u0449\u0435\u043d\u0438\u044f";
Calendar._TT.PART_TODAY=" (\u0441\u0435\u0439\u0447\u0430\u0441)";Calendar._TT.DAY_FIRST="\u0421\u043d\u0430\u0447\u0430\u043b\u0430 %s";Calendar._TT.WEEKEND="0,6";Calendar._TT.CLOSE="\u0417\u0430\u043a\u0440\u044b\u0442\u044c";Calendar._TT.TODAY="\u0421\u0435\u0439\u0447\u0430\u0441";Calendar._TT.TIME_PART="\u041d\u0430\u0436\u043c\u0438\u0442\u0435 \u0434\u043b\u044f \u0441\u043c\u0435\u043d\u044b \u0437\u043d\u0430\u0447\u0435\u043d\u0438\u044f";Calendar._TT.DEF_DATE_FORMAT="%Y-%m-%d";
Calendar._TT.TT_DATE_FORMAT="%a, %b %e";Calendar._TT.WK="wk";Calendar._TT.TIME="\u0412\u0440\u0435\u043c\u044f:";

Calendar.setup=function(a){function b(e,c){if(typeof a[e]=="undefined")a[e]=c}function j(e){var c=e.params,g=e.dateClicked||c.electric;if(g&&c.inputField){c.inputField.value=e.date.print(c.ifFormat);typeof c.inputField.onchange=="function"&&c.inputField.onchange()}if(g&&c.displayArea)c.displayArea.innerHTML=e.date.print(c.daFormat);g&&typeof c.onUpdate=="function"&&c.onUpdate(e);g&&c.flat&&typeof c.flatCallback=="function"&&c.flatCallback(e);g&&c.singleClick&&e.dateClicked&&e.callCloseHandler()}b("inputField",
null);b("displayArea",null);b("button",null);b("eventName","click");b("ifFormat","%Y/%m/%d");b("daFormat","%Y/%m/%d");b("singleClick",true);b("disableFunc",null);b("dateStatusFunc",a.disableFunc);b("dateText",null);b("firstDay",null);b("align","Br");b("range",[1900,2999]);b("weekNumbers",true);b("flat",null);b("flatCallback",null);b("onSelect",null);b("onClose",null);b("onUpdate",null);b("date",null);b("showsTime",false);b("timeFormat","24");b("electric",true);b("step",2);b("position",null);b("cache",
false);b("showOthers",false);b("multiple",null);var h=["inputField","displayArea","button"];for(var i in h)if(typeof a[h[i]]=="string")a[h[i]]=document.getElementById(a[h[i]]);if(a.flat!=null){if(typeof a.flat=="string")a.flat=document.getElementById(a.flat);if(!a.flat){alert("Calendar.setup:\n  Flat specified but can't find parent.");
return false}var f=new Calendar(a.firstDay,a.date,a.onSelect||j);f.showsOtherMonths=a.showOthers;f.showsTime=a.showsTime;f.time24=a.timeFormat=="24";f.params=a;f.weekNumbers=a.weekNumbers;f.setRange(a.range[0],a.range[1]);f.setDateStatusHandler(a.dateStatusFunc);f.getDateText=a.dateText;a.ifFormat&&f.setDateFormat(a.ifFormat);a.inputField&&typeof a.inputField.value=="string"&&f.parseDate(a.inputField.value);f.create(a.flat);f.show();return false}(a.button||a.displayArea||a.inputField)["on"+a.eventName]=
function(){var e=a.inputField||a.displayArea,c=a.inputField?a.ifFormat:a.daFormat,g=false,d=window.calendar;if(e)a.date=Date.parseDate(e.value||e.innerHTML,c);if(d&&a.cache){a.date&&d.setDate(a.date);d.hide()}else{window.calendar=d=new Calendar(a.firstDay,a.date,a.onSelect||j,a.onClose||function(l){l.hide()});d.showsTime=a.showsTime;d.time24=a.timeFormat=="24";d.weekNumbers=a.weekNumbers;g=true}if(a.multiple){d.multiple={};for(e=a.multiple.length;--e>=0;){var k=a.multiple[e],m=k.print("%Y%m%d");d.multiple[m]=
k}}d.showsOtherMonths=a.showOthers;d.yearStep=a.step;d.setRange(a.range[0],a.range[1]);d.params=a;d.setDateStatusHandler(a.dateStatusFunc);d.getDateText=a.dateText;d.setDateFormat(c);g&&d.create();d.refresh();a.position?d.showAt(a.position[0],a.position[1]):d.showAtElement(a.button||a.displayArea||a.inputField,a.align);return false};return f};
