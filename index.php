<form method = "POST" action = "">
  latitude : <input type = "text" name = "lat"><BR>
  longitude : <input type = "text" name = "long"><BR>
  Title : <input type = "text" name = "title"><BR>
  Description : <input type = "text" name = "des"><BR>
  Zoom : <input type = "text" name = "zoom"><BR>
  <input type = "submit" name = "locate" value = "locate">
 </form>
 
 <?php
 $conn=mysqli_connect('localhost','root','','map');
 if(!$conn){
 
 	echo "connection failed";
 }
if(isset($_POST['locate'])){
  $Title=$_POST['title'];
  $Description=$_POST['des'];
	$latitude=$_POST['lat'];
	$longitude=$_POST['long'];
	$zoom=$_POST['zoom'];
    //get variable all form variables
    $location = "https://www.google.com/maps/place/MMF+Systems+(India)+Pvt.+Ltd./@".$latitude.",".$longitude.",".$zoom."z/data=!4m12!1m6!3m5!1s0x0:0x1c2e35caf2b1e50!2sMMF+Systems+(India)+Pvt.+Ltd.!8m2!3d18.5587549!4d73.7752235!3m4!1s0x0:0x1c2e35caf2b1e50!8m2!3d18.5587549!4d73.7752235";
  //$location =  "https://www.openstreetmap.org/node/16174445#map=11/18.5210/73.8769";
    
    header('$location');
    $insert=mysqli_query($conn,"INSERT INTO register(title,description,latitude,longitude,zoom) VALUES('$Title','$Description','$latitude','$longitude','$zoom')");
    if($insert)
    {
    	echo "correct location";
    }
    else{
    	echo "incorrect location";
    }
}



?>

<html>
  <head><title>OpenLayers Marker Popups</title></head>
  <body>
  <div id="mapdiv"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.11/lib/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
    projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

    var lonLat = new OpenLayers.LonLat(73.856255,18.516726).transform(epsg4326, projectTo);


    var zoom=9;
    map.setCenter (lonLat, zoom);

    var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

    // Define markers as "features" of the vector layer:
    var feature = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point( 73.7733456, 18.5588404 ).transform(epsg4326, projectTo),
            {description:'Contact: +910000000000'} ,
            {externalGraphic: 'img/marker.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
        );
    vectorLayer.addFeatures(feature);

    var feature = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point( 73.7736456, 18.5688404 ).transform(epsg4326, projectTo),
            {description:'Contact: +9107892222'} ,
            {externalGraphic: 'img_marker.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
        );
    vectorLayer.addFeatures(feature);

   


    map.addLayer(vectorLayer);


    //Add a selector control to the vectorLayer with popup functions
    var controls = {
      selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
    };

    function createPopup(feature) {
      feature.popup = new OpenLayers.Popup.FramedCloud("pop",
          feature.geometry.getBounds().getCenterLonLat(),
          null,
          '<div class="markerContent">'+feature.attributes.description+'</div>',
          null,
          true,
          function() { controls['selector'].unselectAll(); }
      );
      //feature.popup.closeOnMove = true;
      map.addPopup(feature.popup);
    }

    function destroyPopup(feature) {
      feature.popup.destroy();
      feature.popup = null;
    }

    map.addControl(controls['selector']);
    controls['selector'].activate();

  </script>
  <div id="explanation">Popup bubbles appearing when you click a marker. The marker content is set within a feature attribute</div>
</body></html>



