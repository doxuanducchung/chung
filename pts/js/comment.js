
var ie45,ns6,ns4,dom;
if (navigator.appName=="Microsoft Internet Explorer") ie45=parseInt(navigator.appVersion)>=4;
else if (navigator.appName=="Netscape"){  ns6=parseInt(navigator.appVersion)>=5;  ns4=parseInt(navigator.appVersion)<5;}
dom=ie45 || ns6;
function showhide(id) {
         elb = document.all ? document.all[id] :   dom ? document.getElementById(id) :   document.layers[id];
         els = dom ? elb.style : elb;
         if (dom){
             if (els.display == "none") {
                 els.display = "";
                 window.status="M? form ph?n h?i";
             } else {
                 els.display = "none";
                 window.status="Ðóng form ph?n h?i";
             }
         } else if (ns4){
             if (els.display == "show") {
                 els.display = "hide";
                 window.status="M? form ph?n h?i";
             } else {
                 els.display = "show";
                 window.status="Ðóng form ph?n h?i";
             }
         }
}
