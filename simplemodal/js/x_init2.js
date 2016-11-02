/*
* Mootools Simple Modal
* Version 1.0
* Copyright (c) 2011 Marco Dell'Anna - http://www.plasm.it
*/
window.addEvent("domready", function (e) {
    /* Modal Image */
	container = $('tb_resultado');
	container.addEvent('click:relay(.xcss_accion)', function(e){
    //$$(".xcss_accion").addEvent("click", function (e) {
        e.stop();
		var cuarto_param = '';
		if(this.get('xope_facturada') != null){
			cuarto_param = this.get('xope_facturada');
		}else if(this.get('xcop_codigo') != null){
			cuarto_param = this.get('xcop_codigo');
		}else{
			cuarto_param = this.parentNode.parentNode.rowIndex;
		}
		/*if(this.get('xope_facturada') == null){ cuarto_param = this.parentNode.parentNode.rowIndex;
		}else{ cuarto_param = this.get('xope_facturada');
		}*/
		
        var SM = new SimpleModal({
            "offsetTop": 100,
            "width": 380,
            "isoffsetTop": true,
            "onAppend": function () {
                $("simple-modal").fade("hide").fade("in")
            }
        });
        SM.show({
            "model": "modal-ajax",
            "title": this.get('xtitulo'),
			"nota": "",
            "param": {
                "url": this.get('href') + this.get('xope_codigo') +"/"+ cuarto_param +"/"+ Math.random(),
                "onRequestComplete": function () { }
            }
        });
    });
});

