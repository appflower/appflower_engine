/**
Captcha input field
Verifying human involvament in form processing
@author: Prakash Paudel
*/
Ext.ux.Captcha = Ext.extend(Ext.form.TextField,  {
    
    initComponent: function(){
        Ext.ux.Captcha.superclass.initComponent.call(this);          
    },
	
    onRender: function(ct, position){
        Ext.ux.Captcha.superclass.onRender.call(this, ct, position);
        randId = 'captcha_image_'+(Math.floor(Math.random()*10000000));
        src = this.src;
        captchaDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px; margin:0px; margin-bottom:2px;float:left; padding:0px'});
        captchaImgDiv = Ext.DomHelper.insertFirst(captchaDiv, {tag: 'div', style:'margin:0px;float:left; padding:0px'});
        reloadImgDiv = Ext.DomHelper.insertFirst(captchaDiv, {tag: 'div', html:'<div style="color:#888; padding-bottom:2px;">Can\'t see image? <a href="#" onclick="document.getElementById(randId).src=document.getElementById(randId).src+\'#\'" style="color:#0000ff">Reload</a></div>',  style:'margin:0px;float:right; padding:0px'});
        captchaImg = Ext.DomHelper.append(captchaImgDiv, {tag: 'img', id:randId, src:this.src, width:this.width,style:this.imgStyle});  
        if(!this.input){
        	this.el.applyStyles({display:'none'});
        }
    }
});

Ext.reg("captcha", Ext.ux.Captcha);