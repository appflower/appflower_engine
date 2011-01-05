/**
* @author: radu
*/

Ext.ns('ux');

Ext.ux.GridColorView = function(config){
    Ext.apply(this, config);
    Ext.ux.GridColorView.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.GridColorView, Ext.grid.GridView, {
	
	initTemplates : function(){
        var ts = this.templates || {};
      
        ts.row = new Ext.Template(
                    '<div class="x-grid3-row {alt}" style="{tstyle}"><table class="x-grid3-row-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle} {color}">',
                    '<tbody><tr>{cells}</tr>',
                    (this.enableRowBody ? '<tr class="x-grid3-row-body-tr" style="{bodyStyle}"><td colspan="{cols}" class="x-grid3-body-cell" tabIndex="0" hidefocus="on"><div class="x-grid3-row-body">{body}</div></td></tr>' : ''),
                    '</tbody></table></div>'
                    );
                    
         ts.cell = new Ext.Template(
                    '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
                    '<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr} style="{cell_color}">{value}</div>',
                    '</td>'
                    );
        
        this.templates = ts;
		Ext.ux.GridColorView.superclass.initTemplates.call(this);
    },
   
    doRender : function(cs, rs, ds, startRow, colCount, stripe){
    	var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount-1;
        var tstyle = 'width:'+this.getTotalWidth()+';';
        // buffers
        var buf = [], cb, c, p = {}, rp = {tstyle: tstyle}, r;
        //console.log(rs);
        for(var j = 0, len = rs.length; j < len; j++){
            r = rs[j]; cb = [];
            
            var rowIndex = (j+startRow);
            for(var i = 0; i < colCount; i++){
                c = cs[i];
                p.id = c.id;
                p.css = i === 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
                p.attr = p.cellAttr = "";
                p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
                p.style = c.style;
                if(Ext.isEmpty(p.value)){
                    p.value = "&#160;";
                }
                if(this.markDirty && r.dirty && typeof r.modified[c.name] !== 'undefined'){
                    p.css += ' x-grid3-dirty-cell';
                }
                
                //radu - start
                p.cell_color='';
                if(r.data['_cell_color']&&r.data['_cell_color'][c.name])
                {
                	p.cell_color='background-color:'+r.data['_cell_color'][c.name]+';';
                }
                //radu - end
                
                cb[cb.length] = ct.apply(p);
            }
            var alt = [];
            if(stripe && ((rowIndex+1) % 2 === 0)){
                alt[0] = "x-grid3-row-alt";
            }
            if(r.dirty){
                alt[1] = " x-grid3-dirty-row";
            }
            rp.cols = colCount;
            if(this.getRowClass){
                alt[2] = this.getRowClass(r, rowIndex, rp, ds);
            }
            rp.alt = alt.join(" ");
            rp.cells = cb.join("");
            
            //added by radu
		    if(r.data['_color']!='')
		    {
		    	rp.color='background-color:'+r.data['_color']+';';
		    }
		    
		    //added by radu
		    if(this.grid.select&&r.data['_selected'])
		    {
		    	var sm=this.grid.getSelectionModel();
		    	sm.selectRow(ds.indexOf(r),true);
		    } 
            
            buf[buf.length] =  rt.apply(rp);
        }
        return buf.join("");
    }
});