import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'dart:async';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:flutter_google_places/flutter_google_places.dart';
import 'package:geocoder/geocoder.dart';
import 'package:google_maps_webservice/places.dart';


import 'reportpage.dart';

//AIzaSyCVc8zI-MqjcX_GxhIetGwiPIrOdl_MpX8
const kGoogleApiKey = "AIzaSyC_mXiFwyOVwWe_QMKPXw5gMPh3SbvVWjI";

GoogleMapsPlaces _places = GoogleMapsPlaces(apiKey: kGoogleApiKey);
 
class MapsDemo extends StatefulWidget {
  MapsDemo({this.plat, this.plong, this.pname, this.pstreet}) : super();


   String plat;
   String plong;
   String pname;
   String pstreet;
  
 
  final String title = "Select Location";
 
  @override
  MapsDemoState createState() => MapsDemoState();
}
 
class MapsDemoState extends State<MapsDemo> {

  
  Completer<GoogleMapController> _controller = Completer();

  LatLng _center;
  LatLng _lastMapPosition;
   Set<Marker> _markers = {
  };

  MapType _currentMapType = MapType.normal;

  
 
 /* String _name="";
  String _street="";
 

  getPlace() async{
 
  List<Placemark> newPlace =
  await placemarkFromCoordinates(double.tryParse(widget.plat),double.tryParse(widget.plong));
  Placemark placeMark = newPlace[0];
      _name = placeMark.name;
     _street = placeMark.thoroughfare;

  print("$_name,$_street");
  }*/


  @override
void initState(){
  super.initState();
 // getPlace();
  _center =  LatLng(double.tryParse(widget.plat), double.tryParse(widget.plong));
  _lastMapPosition= LatLng(double.tryParse(widget.plat), double.tryParse(widget.plong));
   _markers.add(

        Marker(
          markerId: MarkerId(_center.toString()),
          position: _center,
          infoWindow: InfoWindow(
            title: 'You are here!',  
          ),
          icon: BitmapDescriptor.defaultMarker,
          draggable: true,
           onDragEnd: ((newPosition) {
            print(newPosition.latitude);
            print(newPosition.longitude);
             setState(() {
            widget.plat = newPosition.latitude.toString();
            widget.plong = newPosition.longitude.toString();
                  });
          }),
         
        ),
        
      );

    

}


 
  static final CameraPosition _position1 = CameraPosition(
    bearing: 192.833,
    target: LatLng(45.531563, -122.677433),
    tilt: 59.440,
    zoom: 11.0,
  );
 
  Future<void> _goToPosition1() async {
    final GoogleMapController controller = await _controller.future;
    controller.animateCamera(CameraUpdate.newCameraPosition(_position1));
  }
 
  _onMapCreated(GoogleMapController controller) {
    _controller.complete(controller);
  }
 
  _onCameraMove(CameraPosition position) {
    _lastMapPosition = position.target;
  }
 
  _onMapTypeButtonPressed() {
    
    setState(() {
      _currentMapType = _currentMapType == MapType.normal
          ? MapType.satellite
          : MapType.normal;
    });
  }
 
  _onAddMarkerButtonPressed() {
    setState(() {
      _markers.add(
        Marker(
          markerId: MarkerId(_lastMapPosition.toString()),
          position: _lastMapPosition,
          infoWindow: InfoWindow(
            title: 'This is a Title',
            snippet: 'This is a snippet',
          ),
          icon: BitmapDescriptor.defaultMarker,
        ),
      );
    });
  }
   
    

  



  Widget button(Function function, IconData icon, String heroTag) {
    return FloatingActionButton(
    
      onPressed: function,
      heroTag: heroTag,
      materialTapTargetSize: MaterialTapTargetSize.padded,
      backgroundColor: Color(0xFF262AAA),
      child: Icon(
        icon,
        size: 36.0,
      
      ),
    );
  }
  Widget button1(IconData icon, String heroTag) {
    return FloatingActionButton(
      onPressed: 
       () async {
            // show input autocomplete with selected mode
            // then get the Prediction selected
            Prediction p = await PlacesAutocomplete.show(
                context: context, apiKey: kGoogleApiKey);
            displayPrediction(p);
          },
      heroTag: heroTag,
      materialTapTargetSize: MaterialTapTargetSize.padded,
      backgroundColor: Color(0xFF262AAA),
      child: Icon(
        icon,
        size: 36.0,
      ),
    );
  }

  
  Future<Null> displayPrediction(Prediction p) async {
    if (p != null) {
      PlacesDetailsResponse detail =
      await _places.getDetailsByPlaceId(p.placeId); 

      var placeId = p.placeId;
      double lat = detail.result.geometry.location.lat;
      double lng = detail.result.geometry.location.lng;

      var address = await Geocoder.local.findAddressesFromQuery(p.description);

      print(lat);
      print(lng);
      Fluttertoast.showToast(
        msg: "Location Changed",
        toastLength: Toast.LENGTH_LONG,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 5,
        backgroundColor: Colors.green,
        textColor: Colors.white);
        
     
       setState(() {
         widget.plat = lat.toString();
         widget.plong = lng.toString();
        
       }
       );
    
    }
  }

 
  @override
  Widget build(BuildContext context) {

    
    return MaterialApp(
     debugShowCheckedModeBanner: false,   
      home: Scaffold(
        appBar: AppBar(
            centerTitle: true,
              leading: IconButton(icon: Icon(Icons.arrow_back),
              color: Colors.white,
              onPressed: (){
                  //Navigator.pop(context,true);
                     Navigator.push(
                context,
                MaterialPageRoute(
              builder: (context) => ReportPage(lat: widget.plat, long: widget.plong)),
                     );
              }),
          title: Text(widget.title,
          style: TextStyle(
          color: Colors.white, fontWeight: FontWeight.w700, fontSize: 20,)),
          backgroundColor: Color(0xFF262AAA),
        ),
        body: Stack(
          children: <Widget>[
            GoogleMap(
              onMapCreated: _onMapCreated,
              initialCameraPosition: CameraPosition(
                target: _center,
           
                zoom: 11.0,
              ),
              mapType: _currentMapType,
              markers: _markers,
              onCameraMove: _onCameraMove,
            ),

    
            Padding(
              padding: EdgeInsets.all(16.0),
              child: Align(
                alignment: Alignment.topLeft,
                child: Column(
                  children: <Widget>[

                    button1(Icons.location_searching, 'b4'),
                    SizedBox(
                      height: 16.0,
                    ),
                    button(_onMapTypeButtonPressed, Icons.map,'b2'),
                    SizedBox(
                      height: 16.0,
                    ),
                
                    
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
