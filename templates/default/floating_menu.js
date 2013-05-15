<!--

window.onerror = null;
var topMargin = 100;
var slideTime = 1200;
var ns6 = (!document.all && document.getElementById);
var ie4 = (document.all);
var ns4 = (document.layers);


function layerObject(id,left) {
		this.obj = document.getElementById(id).style;
		this.obj.left = '350px';

		return this.obj;
}


function layerSetup() {
	floatLyr = new layerObject('floating_layer', pageWidth * .5);
	window.setInterval("main()", 10)
}



function floatObject() {
		if (document.documentElement && document.documentElement.clientHeight) {
			findHt = document.documentElement.clientHeight;
		}
		else if (document.body) {
			findHt = document.body.clientHeight;
		}
}
 

function main() {

		if (document.documentElement && document.documentElement.scrollTop) {
			this.scrollTop = document.documentElement.scrollTop;
			this.currentY = document.documentElement.scrollTop;
		}
		else if (document.body) {
			this.scrollTop = document.body.scrollTop;
			this.currentY = document.body.scrollTop;
		}

	mainTrigger();
}




function mainTrigger() {


	var newTargetY = this.scrollTop + this.topMargin;
	if ( this.currentY != newTargetY ) {
		if ( newTargetY != this.targetY ) {
			this.targetY = newTargetY;
			floatStart();
		}
	animator();
   }

}


function floatStart() {

	var now = new Date();
	this.A = this.targetY - this.currentY;
	this.B = Math.PI / ( 2 * this.slideTime );
	this.C = now.getTime();

	if (Math.abs(this.A) > this.findHt) {
		this.D = this.A > 0 ? this.targetY - this.findHt : this.targetY + this.findHt;
		this.A = this.A > 0 ? this.findHt : -this.findHt;
	}
	else {
		this.D = this.currentY;
	}
}


function animator() {

	var now = new Date();
	var newY = this.A * Math.sin( this.B * ( now.getTime() - this.C ) ) + this.D;
	newY = Math.round(newY);

	if (( this.A > 0 && newY > this.currentY ) || ( this.A < 0 && newY < this.currentY )) {
		document.getElementById('floating_layer').style.top = newY + "px"
	}
}



function load_floating_layer() {
		if (document.documentElement && document.documentElement.clientWidth) {
			pageWidth = document.documentElement.clientWidth;
			pageHeight = document.documentElement.clientHeight;
		}
		else if (document.body) {
			pageWidth = document.body.clientWidth;
			pageHeight = document.body.clientHeight;
		}

		layerSetup();
		floatObject();
}


function toggle_floating_layer(v) {

	if(v==1) {
		document.getElementById('floating_layer').style.visibility='hidden';
	} else {
		if(document.getElementById('floating_layer').style.visibility=='hidden') {
			document.getElementById('floating_layer').style.visibility='visible';
		} else {
			document.getElementById('floating_layer').style.visibility='hidden';
		}
	}
}



//-->