
function objGet(x) {
	if (typeof x != 'string') return x;
	else if (Boolean(document.getElementById)) return document.getElementById(x);
	else if (Boolean(document.all)) return eval('document.all.'+x);
	else return null;
}

function objSetStyle (obj,prop,val) {
	var o = objGet(obj);
	if (o && o.style) {
		eval ('o.style.'+prop+'="'+val+'"');
		return true;
		}
	else return false;
}

function objDisplay (obj,on,type) {
	if (on && !type) type = 'block';
	return objSetStyle(obj,'display',(on) ? type:'none');
}

function getFormValue(name) { return objGet(name).value; }
function setFormValue(name,val) { objGet(name).value = val; }

function dec2hex(n) {
	var s = n.toString(16);
	if (s.length<2) s = '0'+s;
	return s.toUpperCase();
}
function hex2dec(n) {
	return parseInt(n,16);
}

function col2Gray(r,g,b) {
	var lum = Math.round( r*0.299 + g*0.587 + b*0.114 );
	return dec2hex(lum)+dec2hex(lum)+dec2hex(lum);
}


// Color object

var varPresets = new Array();

varPresets['default'] = new Array( 	-1,-1, 		1,-0.7, 	0.25,1, 	0.5,1 /**/ , 		0.15,1, 	-0.25,0.5, 	0.5,0.5, 	0.7,0.7);
varPresets['pastel'] = new Array( 	0.5,-0.9, 	0.5,0.5,	0.1,0.9, 0.75,0.75 /**/,	0.3,1, 	-0.15,0.6, 	0.5,0.6, 	0.6,0.6 );
varPresets['soft'] = new Array( 		0.3,-0.8, 	0.3,0.5,	0.1,0.9, 0.5,0.75 /**/, 	0.2,1, 	-0.15,0.5, 	0.35,0.5, 	0.55,0.7 );
varPresets['hard'] = new Array( 		1,-1, 		1,-0.6,	0.1,1, 	0.6,1 /**/, 		0.4,1, 	-0.5,0.45, 	0.7,0.5, 	0.9,0.7 );
varPresets['light'] = new Array( 	0.25,1, 		0.5,0.75, 0.1,1, 	0.15,1 /**/, 		0.05,1, 	-0.25,0.5, 	0.35,0.5, 	0.3,0.9 );
varPresets['pale'] = new Array( 		0.1,-0.85, 	0.1,0.5, 0.1,1, 	0.1,0.75 /**/, 	0.05,1, 	-0.25,0.5, 	0.2,0.5, 	0.3,0.7 );
//varPresets['work'] = new Array();

function Color(H) {
	this.S = new Array();
	this.V = new Array();
	this.setBaseColor(H);
}

Color.prototype.setBaseColor = function (H) {
	this.moveHue(H);
}

Color.prototype.moveHue = function (H) {

	function avrg(a,b,k) { return a + Math.round((b-a)*k);	}

	this.H = H;
	var hue = Math.round(this.H) % 360;
	var d = hue%15 + (this.H-Math.floor(this.H));
	var k = d/15;
	var d1 = hue - Math.floor(d);
	var c1 = colWheel[d1];
	d1 = (d1+15)%360;
	var c2 = colWheel[d1];
	this.baseR = avrg(c1[0],c2[0],k);
	this.baseG = avrg(c1[1],c2[1],k);
	this.baseB = avrg(c1[2],c2[2],k);
	this.baseS = avrg(c1[4],c2[4],k)/100;
	this.baseV = avrg(c1[5],c2[5],k)/100;
}

Color.prototype.setVariant = function (varNr, S, V) {
	this.S[varNr] = S;
	this.V[varNr] = V;
}

Color.prototype.getS = function (varNr) {
	var S = (this.S[varNr]<0) ? -this.S[varNr] * this.baseS : this.S[varNr];
	if (S>1) S = 1; if (S<0) S = 0;
	return S;
}

Color.prototype.getV = function (varNr) {
	var V = (this.V[varNr]<0) ? -this.V[varNr] * this.baseV : this.V[varNr];
	if (V>1) V = 1; if (V<0) V = 0;
	return V;
}

Color.prototype.setVariantPreset = function (preset) {
	var i,p = varPresets[preset];
	if (!p) p = varPresets[usedPreset];
	for (i=0;i<8;i++) this.setVariant(i,p[2*i],p[2*i+1]);
	usedPreset = preset;
}

Color.prototype.getHex = function(webColors,CBMode,varNr) {
	var r,g,b;
	var max = Math.max(Math.max(this.baseR,this.baseG),this.baseB);
	var min = Math.min(Math.min(this.baseR,this.baseG),this.baseB);
	var V = (varNr<0) ? this.baseV : this.getV(varNr);
	var S = (varNr<0) ? this.baseS : this.getS(varNr);
	var v = V*255;
	var k = (max>0) ? v/max : 0;
	r = Math.round(v-(v-this.baseR*k)*S); if (r>255) r = 255;
	g = Math.round(v-(v-this.baseG*k)*S); if (g>255) g = 255;
	b = Math.round(v-(v-this.baseB*k)*S); if (b>255) b = 255;
	if (webColors) {
		r = Math.round(r/51) * 51;
		g = Math.round(g/51) * 51;
		b = Math.round(b/51) * 51;
	}
	if (CBMode) {
		if (CBMode==7) return col2Gray(r,g,b);
		else return getColorBlindColor(r,g,b,CBMode);
	}
	else return dec2hex(r)+dec2hex(g)+dec2hex(b);
}

Color.prototype.rotate = function (angle) {
	var nh = (this.H + angle) % 360;
	this.setBaseColor(nh);
}


// Variables
var colWheel = new Array(12);
colWheel['0']   = new Array(255,0,0,	0, 100, 100);
colWheel['15']  = new Array(255,51,0,	15, 100, 100);
colWheel['30']  = new Array(255,102,0,	30, 100, 100);
colWheel['45']  = new Array(255,128,0,	45, 100, 100);
colWheel['60']  = new Array(255,153,0,	60, 100, 100);
colWheel['75']  = new Array(255,178,0,	75, 100, 100);
colWheel['90']  = new Array(255,204,0,	90, 100, 100);
colWheel['105'] = new Array(255,229,0,	105, 100, 100);
colWheel['120'] = new Array(255,255,0,	120, 100, 100);
colWheel['135'] = new Array(204,255,0,	135, 100, 100);
colWheel['150'] = new Array(153,255,0,	150, 100, 100);
colWheel['165'] = new Array(51,255,0,	165, 100, 100);
colWheel['180'] = new Array(0,204,0,	180, 100, 80);
colWheel['195'] = new Array(0,178,102,	195, 100, 70);
colWheel['210'] = new Array(0,153,153,	210, 100, 60);
colWheel['225'] = new Array(0,102,178,	225, 100, 70);
colWheel['240'] = new Array(0,51,204,	240, 100, 80);
colWheel['255'] = new Array(25,25,178,	255, 100, 70);
colWheel['270'] = new Array(51,0,153,	270, 100, 60);
colWheel['285'] = new Array(64,0,153,	285, 100, 60);
colWheel['300'] = new Array(102,0,153,	300, 100, 60);
colWheel['315'] = new Array(153,0,153,	315, 100, 60);
colWheel['330'] = new Array(204,0,153,	330, 100, 80);
colWheel['345'] = new Array(229,0,102,	345, 100, 90);

var usedColors = 2;
var schemeNames = new Array('mono','compl','triad','tetrad','analog');
var usedScheme = 'compl';
var sliderVal = 0.5;
var col = new Array(8);
for (var i=0;i<8;i++) col[i] = new Color(60);
var bussy = false;
var webSnap = false;
var colorblindMode = 0;
// 0 = normal
// 1 = protanopie - 1%
// 2 = deuteranopie - 1%
// 3 = tritanopie - 0,01%
// 4 = protanomalie - 1%
// 5 = deuteranomalie - 5%
// 6 = tritanomalie - 0%
// 7 = monochroma - 1%
// 8 = part monochroma - 1%
var usedPreset = 'default';
var RGBinput = '';
var SchemeType = 'light';
var SavedStyles = {};
// Setting colors

function setMainColor(H, moveonly) {
	col[0].setBaseColor(H);
	createScheme(!moveonly);
}

function setVar(colNr,varNr,S,V) {
	var o;
	if (col[colNr].S[varNr]!=S || col[colNr].V[varNr]!=V) {
		col[colNr].setVariant(varNr,S,V);
		if (usedPreset) {
			o = objGet('preset-'+usedPreset);
			if (o) o.className = 'btn';
			usedPreset = '';
			}
		}
	drawSample();
	}


function createScheme(setDefaults) {
	H = col[0].H;
	if (usedScheme=='mono') {
		usedColors=1;
	}else if (usedScheme=='compl') {
		usedColors=2;
		col[1].setBaseColor(H); col[1].rotate(180);
	}else if (usedScheme=='triad') {
		usedColors=3;
		var dif = 60 * sliderVal;
		col[1].setBaseColor(H); col[1].rotate(180-dif);
		col[2].setBaseColor(H); col[2].rotate(180+dif);
	}else if (usedScheme=='tetrad') {
		usedColors=4;
		var dif = 90 * sliderVal;
		col[1].setBaseColor(H); col[1].rotate(180);
		col[2].setBaseColor(H); col[2].rotate(180+dif);
		col[3].setBaseColor(H); col[3].rotate(dif);
	}else if (usedScheme=='analog') {
		usedColors=3;
		var dif = 60 * sliderVal;
		var compl = objGet('analogCompl').checked;
		col[1].setBaseColor(H); col[1].rotate(dif);
		col[2].setBaseColor(H); col[2].rotate(360-dif);
		col[3].setBaseColor(H); col[3].rotate(180);
		usedColors = (compl) ? 4 : 3;
	}
	
	if (setDefaults) {
		for (var i=0; i<8; i++) col[i].setVariantPreset(usedPreset);
	}
	drawSample();
	}


// Drawings
function drawSample(){
	var return_color = {
		main_bg: col[0].getHex(webSnap,colorblindMode,0),
		input_color: col[0].getHex(webSnap,colorblindMode,1),
		header_bg: col[0].getHex(webSnap,colorblindMode,(SchemeType == 'light') ? 4 : 5),
		link_color: col[0].getHex(webSnap,colorblindMode,7),
		status_color: col[1].getHex(webSnap,colorblindMode,0),
		footer_bg: col[0].getHex(webSnap,colorblindMode,(SchemeType == 'light') ? 3 : 6)
	}	
	
	objGet('maincolorhue').innerHTML = col[0].H + '°';
	$('#manual_h').val(col[0].H);
	objDisplay('colsamplevars',false);
	objDisplay('colsamplevarsswitch',false);
	objDisplay('cbmodeswitch',true);
	
	drawRing(0,1,col[0].H);
	drawRing(1,1,col[1].H);
	
	writePgSample(return_color);
/*	
	$('#all_examples').html('');
	for (j=0;j<8;j++) {
		$('#all_examples').append('<div class="example">'+j+'</div>');
	}
	$('#all_examples').append('<br><br><br>');
	for (i=0;i<2;i++) {
		for (j=0;j<8;j++) {
			c = '#' + col[i].getHex(webSnap,0,j);
			$('#all_examples').append('<div class="example" style="background: '+c+'">&nbsp;</div>');
		}
		$('#all_examples').append('<br><br><br>');
	}
	$('#all_examples').append(getAllSetsExample());
	*/
}

function getAllSetsExample(){
	current_preset = usedPreset;
	vari = '';
	for (m in varPresets) {
		for (var i=0;i<8;i++) col[0].setVariantPreset(m);

		for (j=0;j<8;j++) {
			c = '#' + col[0].getHex(webSnap,0,j);
			vari += '<div class="example" style="background: '+c+'">&nbsp;</div>';
		}
		vari += ' - '+m+'<br><br><br>';
	}
	for (var i=0;i<8;i++) col[0].setVariantPreset(usedPreset);
	return vari;
}

function drawVar(colNr,varNr) {
	var o, ccode;
	var S = Math.round(col[colNr].getS(varNr)*20)/20;
	var V = Math.round(col[colNr].getV(varNr)*20)/20;
	var c = new Color(col[colNr].H);
	var buff = '';
	for (var i=20; i>=0; i--) {
		c.S[0] = i/20;
		for (var j=20; j>=0; j--) {
			c.V[0] = j/20;
			ccode = c.getHex(webSnap,0,0);
			buff += '<div id="vbx-'+i+'-'+j+'" onclick="setVar('+colNr+','+varNr+','+c.S[0]+','+c.V[0]+')" ';
			buff += 'title="S='+Math.round(c.S[0]*100)+' %, V='+Math.round(c.V[0]*100)+' % --- #' + ccode + '" ';
			buff += 'class="colvar" style="top:'+((20-i)*14)+'px; left:'+((20-j)*14)+'px; ';
			if (c.S[0]==S && c.V[0]==V) buff += 'background: red url(\'ring-w.gif\') center no-repeat; ';
			buff += 'background-color:#'+ccode;
			buff += '"><'+'/div>';
			}
		}

	objGet('colsamplevars').innerHTML = buff;
	
	objDisplay('cbmodeswitch',false);
	//objDisplay('websnapswitch',false);
	objDisplay('colsample',false);
	objDisplay('colsamplevars',true);
	objDisplay('colsamplevarsswitch',true);
	}

function drawRing(n,on,angle) {
	var x,y,o = objGet('pointer'+n);
	var r = (angle-90)/360 * 2*Math.PI;
	if (on) {
		x = Math.round( 115 + 45*Math.cos(r) ) - 3;
		y = Math.round( 115 + 45*Math.sin(r) ) - 2;
		objSetStyle(o,'left',x+'px');
		objSetStyle(o,'top',y+'px');
	}
	objDisplay(o,on);
}


// Action handling
var dX, dY, eX, eY, eH, eW;
	
function getEvent(e) {
	if (!e) e = window.event;
	eX = (e.pageX) ? e.pageX : (e.clientX + objGet('wheelarea').scrollLeft);
	eY = (e.pageY) ? e.pageY : (e.clientY + objGet('wheelarea').scrollTop);
}

function moveHue(e) {
	var a,x,y,h;
	getEvent(e);
	x = eX - dX - eW/2;
	y = eY - dY - eH/2;

	h = Math.round(((Math.atan2(-x,y) * 180/Math.PI) + 180) % 360);
	r = Math.sqrt(x*x + y*y);
	if (r>50) {
		h = (Math.floor((h-7.5)/15 + 1) * 15) % 360;
		setMainColor(h,false);
	} else {
		setMainColor(h,true);
	}
	
}

function showHueInfo(e) {
	var a,x,y,h;
	getEvent(e);
	x = eX - dX - eW/2;
	y = eY - dY - eH/2;
	h = Math.round(((Math.atan2(-x,y) * 180/Math.PI) + 180) % 360);
	r = Math.sqrt(x*x + y*y);
	if (r>50) h = (Math.floor((h-7.5)/15 + 1) * 15) % 360;
	window.status = 'Hue: '+ h + '°';
}

function showSliderInfo(e) {
	var min = 48;
	var max = 188;
	getEvent(e);
	var x = eX - dX;
	x = (x-min)/(max-min);
	if (x<0) x = 0; if (x>1) x = 1;
	window.status = 'Value: '+ Math.round(x*100) + ' %';
}

function moveSlider(e) {
	var min = 48;
	var max = 188;
	getEvent(e);
	var x = eX - dX;
	x = (x-min)/(max-min);
	if (x<0) x = 0; if (x>1) x = 1;
	objSetStyle('pointer-slider','left', Math.round(min+(max-min)*x)-3 + 'px' )
	sliderVal = x;
	createScheme(false);
}

function setSlider(x) {
	var min = 48;
	var max = 188;
	if (x<0) x = 0; if (x>1) x = 1;
	objSetStyle('pointer-slider','left', Math.round(min+(max-min)*x)-3 + 'px' )
	sliderVal = x;
}

function shiftVar(n) {
	var S = col[n].S[0];
	var V = col[n].V[0];
	for (var i=0;i<3;i++) {
		col[n].S[i] = col[n].S[i+1];
		col[n].V[i] = col[n].V[i+1];
		}
	col[n].S[3] = S;
	col[n].V[3] = V;
	drawSample();
}

function selectScheme(name) {
	usedScheme = name;
	for (var i=0; i<5; i++) {
		objGet('previmg-'+schemeNames[i]).src = 'prev_' + schemeNames[i] + ((name==schemeNames[i])?'-on':'') + '.gif';
	}

	createScheme(false);
}

function switchPreset(preset) {
	var o;
	if (usedPreset) {
		o = objGet('preset-'+usedPreset);
		if (o) o.className = 'btn';
		}
	usedPreset = preset;
	o = objGet('preset-'+usedPreset);
	if (o) o.className = 'btnon';
	for (var i=0;i<4;i++) col[i].setVariantPreset(preset);
	drawSample();
}

function switchSchemeType(val){
	SchemeType = val;
	drawSample();	
}

function switchWebSnap(on) {
	webSnap = on;
	drawSample();
	}

function switchBlindlessMode() {
	colorblindMode = getFormValue('cbmodeswitcher');
	drawSample();
	}

function searchRGB(hex) {
	RGBinput = hex;
	if (!hex.match(/^[0-9a-fA-F]{6}$/)) {
		return;
	}
	
	function RGB2HSV(r,g,b) {
		// rgb = [0,1];
		var d, min, max, H, S, V;
		min = Math.min(r,Math.min(g,b));
		max = Math.max(r,Math.max(g,b));
		d = max-min;
		V = max;
		if (d>0) S = d/max;
		else return [0,0,V];
		// grey
		if (r==max) H = (g-b)/d;
		else if (g==max) H = 2 + (b-r)/d;
		else H = 4 + (r-g)/d;
		H *= 60;
		if (H<0) H += 360;
		return [H,S,V];
	}

	var i, c, r, g, b, hsv, hsv1, H0, H1, H2, i1, i2, H, S, V;
	r = hex2dec(hex.substr(0,2));
	g = hex2dec(hex.substr(2,2));
	b = hex2dec(hex.substr(4,2));
	hsv = RGB2HSV(r/255,g/255,b/255);
//	alert(hsv[0]+' '+hsv[1]+' '+hsv[2]);
	H = Math.round(hsv[0]);
/*	H1 = 0; H2 = 1000;
	for (i=0; i<360; i+=15) {
		c = colWheel[i];
		hsv1 = RGB2HSV(c[0]/255,c[1]/255,c[2]/255);
		H = hsv1[0];
		if (H>=H1 && H<=H0) { H1 = H; i1 = i }
		if (H<=H2 && H>=H0) { H2 = H; i2 = i }
	}
	if (H2==0 || H2>360) { H2 = 360; i2 = 360 }
	
	k = (H2!=H1) ? (H0-H1)/(H2-H1) : 0;
	H = Math.round(i1 + k*(i2-i1));*/

	if (H>=360) H -= 360; if (H<0) H += 360;
	S = hsv[1];
	V = hsv[2];
	setMainColor(H,true);
}

// drag & drop by dgx  (thanks by pixy)

var dragging = false;

function beginDragSlider(e) {
	dragging = true;
	dragSlider(e);
	}

function dragSlider(e) {
	showSliderInfo(e);
	if (dragging) moveSlider(e);
	}

function beginDragWheel(e) {
	dragging = true;
	dragWheel(e);
	}

function dragWheel(e) {
	showHueInfo(e);
	if (dragging) moveHue(e);
	}

function endDrag() { dragging = false }


// INIT
$(function(){
	dX = $('#wheelarea').offset().left;
	dY = $('#wheelarea').offset().top;
	eH = $('#wheelarea').height();
	eW = $('#wheelarea').width();
	
	Init();
});

function Init() {
	objGet('wheelarea').onclick = moveHue;

	switchPreset(usedPreset);

	$('#sample_preset').bind('change', function(){
		switchPreset($(this).val());	
	});
	$('#sample_scheme_type').bind('change', function(){
		switchSchemeType($(this).val());	
	});
//	$('#manual_color').bind('blur', function(){
//		searchRGB($(this).val());
//	});
	$('#manual_h').bind('keyup', function(){
		var h = parseInt($(this).val());
		if (h){
			while (h < 0) h = h + 360;
			while (h > 360) h = h - 360;
			setMainColor(h,true);
		}
	});
	
	var st = $('#scheme_type').val();
	$('#sample_scheme_type option[value="'+st+'"]').prop('selected', true);
	switchSchemeType(st);	
	searchRGB(main_bg);

	return true;
}
        
function writePgSample(ret){
	var color;

	for(m in schemeSettings){
		if(schemeSettings[m].type != 'color') continue;

		if(schemeSettings[m].autochange == 'yes'){
			color = ret[m];
		}else if(schemeSettings[m].autochange == 'no'){
			color = (SchemeType == 'light') ? schemeSettings[m].light_default : schemeSettings[m].dark_default;
		}else{
			color = ret[schemeSettings[m].autochange];
		}
		$('#sample_'+m+' div.v').html('<div></div>');
		$('#sample_'+m+' div.v > div').css('background-color', '#'+color).text('#'+color);
		SavedStyles[m] = color;
	}
	
}

function apply(){
	var st = $('#sample_scheme_type').val();
	$('#scheme_type option[value="'+st+'"]').prop('selected', true);
	for(m in SavedStyles){
		$('#'+m).val(SavedStyles[m]);
		$('#'+m).trigger('focus').trigger('blur');
	}
}
