/*
* Mootools Simple Modal
* Version 1.0
* Copyright (c) 2011 Marco Dell'Anna - http://www.plasm.it
*/
window.addEvent("domready", function (e) {
    /* Modal Image */
	container = $('contenido');
	container.addEvent('click:relay(.xcss_accion)', function(e){
    //$$(".xcss_accion").addEvent("click", function (e) {
        e.stop();
		
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
                "url": this.get('href') +"/"+ Math.random(),
                "onRequestComplete": function () { }
            }
        });
    });
});

