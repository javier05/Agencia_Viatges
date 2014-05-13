function load_page(url,id_contenidor){
    var xml = $.ajax({ //començo el AJAX i li assigno les propietats
         url: url, // la url
         success: function(xml){// quan tingui èxit
            $(id_contenidor).html("");//esborrar el missatge "loading...""
            load_rss(xml, id_contenidor);//crido la funció que me mostrara les entrades, xml equival a l'arxiu xml de la web que hi hagi en blog.php
   }
 });
}

function load_rss(xml, id_contenidor){
   var limit = xml.getElementsByTagName('item').length; //obtinc la quantitat d'entrades
   var rss = ""; //començo el string
   for (var l=1; l<=5; l++){ // un for desde 1 fins la quantitat de'entrades
//obtinc titol vincle data de publicació i descripció
  var title = xml.getElementsByTagName('title').item(l+1).firstChild.data;
  var link = xml.getElementsByTagName('link').item(l+1).firstChild.data;
  var pubDate = xml.getElementsByTagName('pubDate').item(l- 1).firstChild.data;
  var description = xml.getElementsByTagName('description').item(l+1).firstChild.data;
  var date = pubDate.split("+",1);
  rss = "<data>"+date+"<data><br/><titol><a href=\""+link+"\">"+title+"</a></titol><br/><descripcio>"+description+"</descripcio><hr />";//relleno el string con la información
  $(id_contenidor).append(rss);//l'agrego en el contenidor rss

}

}