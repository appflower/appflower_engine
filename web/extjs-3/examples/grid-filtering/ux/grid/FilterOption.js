/**
 * Ext.ux.FilterOption
 *
 * @author: Prakash Paudel
 *
 * Add the filter option to each filters, relavent to the type of filter.
*/

Ext.ns("Ext.ux");
Ext.ux.FilterOption = function(filter,name){
    var options = {
        "string":[{
            name: "Match any",
            value: "any"
        },{
            name: "Match exact",
            value: "exact"
        },{
            name: "Starts with",
            value: "starts"
        },{
            name: "Ends with",
            value: "ends"
        },{
            name: "Not contains",
            value: "nc"
        }],
        "list":[{
            name: "Containing any of selected",
            value: "or"
        },{
            name: "Containing all of selected",
            value: "and"
        }]
    };
    //filter.menu.add(new Ext.menu.Separator());
    var ch = true;
    for(var i in options){
        if(i == name){            
            Ext.each(options[i],function(item){
                var chItem = new Ext.menu.CheckItem({
                    text: item.name,
                    hideOnClick: false,
                    group: "filter-options",
                    listeners: {
                        checkChange: function(checkItem,checked){
                            filter.setFilterOptions(item.value);
                            filter.fireUpdate();
                        }/*,
                        render: function(ci){
                            if(ch){
                                //ci.setChecked(true);
                                ch = false;
                            }
                        }*/
                    }
                });
                
                filter.menu.add(chItem);
                
            });
        }
    }    
}
