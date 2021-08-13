import 'dart:ffi';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';
import 'package:path/path.dart';
import 'package:dio/dio.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'main.dart';
import 'maps.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geocoding/geocoding.dart';

import 'sharedloginregister.dart';

class SizeConfig {
  static MediaQueryData _mediaQueryData;
  static double screenWidth;
  static double screenHeight;
  static double blockSizeHorizontal;
  static double blockSizeVertical;

  void init(BuildContext context) {
    _mediaQueryData = MediaQuery.of(context);
    screenWidth = _mediaQueryData.size.width;
    screenHeight = _mediaQueryData.size.height;
    blockSizeHorizontal = screenWidth / 100;
    blockSizeVertical = screenHeight / 100;
  }
}



class ReportPage extends StatefulWidget{
  ReportPage({this.lat, this.long}) : super();
   
   String lat;
   String long;
  
    
  @override
  ReportPageState createState() => ReportPageState();
}





class ReportPageState extends State<ReportPage> {


  final desController = TextEditingController();
   
   
   @override
  void dispose() {
    // Clean up the controller when the widget is disposed.
    desController.dispose();
    super.dispose();
  }

  
  String id = "";
 // String email2 = "", fname2= "", lname2="", address2="",mobile2="";
  getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
       id = preferences.getString('id');
   
    });

    
  }


  final GlobalKey<ScaffoldState> _scaffoldstate = new GlobalKey<ScaffoldState>();
  final _key = new GlobalKey<FormState>();
  File selectedfile;
  List<File> files;
  Response response;
  String progress;
  Dio dio = new Dio();

  check() {
    final form = _key.currentState;
    if (form.validate()) {

      form.save();
      uploadFile();
    
    }
  }
  

  selectFile() async {
   /*  selectedfile = await FilePicker.getFile(
          type: FileType.ANY,
        
     );*/
    files = await FilePicker.getMultiFile(type: FileType.ANY,);
   
     setState((){}); 
     //update the UI so that file name is shown
  }   
    uploadFile() async {
     String uploadurl = "http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/report.php";
     //dont use http://localhost , because emulator don't get that address
     //insted use your local IP address or use live URL
     //hit "ipconfig" in windows or "ip a" in linux to get you local IP

     FormData formdata = FormData.fromMap({
          "id": id,
          "type": _mySelection,
          "des": desController.text,
     //widget.lat!=null&&widget.long!=null?"location":_name2+" "+_street2:"location":_name+" "+_street,
         //"location":_name+" "+_street,

         "location":widget.lat!=null&&widget.long!=null?_name2+" "+_street2+" "+locality2+" "+administrativeArea2+" ":_name+" "+_street+" "+locality+" "+administrativeArea+" ",
          "lat": lat,
          "long": long,
          "incident_date":  "${selectedDate.toLocal()}".split(' ')[0],
          "time": time.format(this.context),
          "file": files==null?null:
          /*await MultipartFile.fromFile(
                 files.path,   
                 filename: basename(files.path)
           ),*/
           [
          for (var file in files)
            {await MultipartFile.fromFile(file.path, filename: basename(file.path))}
             .toList()
            
           ]
           
   

      });
    

      response = await dio.post(uploadurl, 
          data: formdata,
         );


     final data = jsonDecode(response.toString()); 
        int value = data['value'];
        String message = data['message'];
        if (value == 1) {
          setState(() {
          Navigator.push(this.context, new MaterialPageRoute(
                    builder: (context) => Login()
                    ));
          });
          print(message);
          sendToast(message);
        } else {
         
          print(message);
          sendToast2(message);
        }
      
   /*   if(response.statusCode == 200){
            print(response.toString());
      final data = jsonDecode(response.toString()); 
            String message = data['message'];
              setState(() {
               Navigator.push(this.context, new MaterialPageRoute(
                    builder: (context) => Login()
                    ));
            sendToast(message);
            });
      }else{ 
          print("Error during connection to server.");
      }*/
  }
 sendreport() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/report2.php", body: {
      "type": _mySelection,
      "description": desController.text,
     
    });

    final data = jsonDecode(response.body);  
    int value = data['value'];
    String message = data['message'];
    if (value == 1) {
      setState(() {
       Navigator.pop(this.context,true);
      });
      print(message);
      sendToast(message);
    } else {
      print("fail");
      print(message);
      sendToast2(message);
    }
    
  }

  sendToast(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_LONG,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 5,
        backgroundColor: Colors.green,
        textColor: Colors.white);
  }

   sendToast2(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_LONG,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 5,
        backgroundColor: Colors.red,
        textColor: Colors.white);
  }
  

  DateTime selectedDate = DateTime.now();
  TimeOfDay time = TimeOfDay.now();
  

  Future<void> _selectDate(BuildContext context) async {
    final DateTime picked = await showDatePicker(
        context: context,
        initialDate: selectedDate,
        firstDate: DateTime(1910, 8),
        lastDate: DateTime(2100));
    if (picked != null && picked != selectedDate)
      setState(() {
        selectedDate = picked;
      });
  }


  Future<void> _pickTime(BuildContext context) async {
   TimeOfDay t = await showTimePicker(
      context: context,
      initialTime: time
    );
    if(t != null && t != time)
      setState(() {
        time = t;
      });
  }



 
  String _mySelection;

  final String url = "http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/fetch.php";

  List data = List(); 

  
   
  
 

  Future<String> getSWData() async {
    var res = await http
        .get(Uri.encodeFull(url), headers: {"Accept": "application/json"});
    var resBody = json.decode(res.body);

    setState(() {
      data = resBody;

  
    });
   
  }


          

   showAlertDialog(BuildContext context) {

          // set up the button
          Widget okButton = FlatButton(
            child: Text("OK"),
            onPressed: () {

            },
          );

          // set up the AlertDialog
          AlertDialog alert = AlertDialog(
            
            title: Text("My title"),
            content:  Text(''),
        //    (_mySelection==1)?Text('sad'):(_mySelection==2)? Text('sad'):(_mySelection==3)? Text('sad'): Text('sad'),
              

           
            actions: [
              okButton,
            ],
          );

          // show the dialog

        /* showDialog(
            context: context,
            builder: (BuildContext context) {
              return alert;
            },
          ); */
         showDialog(
          context: context,
          builder: (context) {
             
            return StatefulBuilder(
              builder: (context, setState) {
                return AlertDialog(
                  title: Row(
                    children: <Widget>[
                      Icon(Icons.warning),
                      SizedBox(width: 10.0,),
                      Text("Incident Description"
                      ),
                    ],
                  ),
                  content:   
            
         
             
      (_mySelection==data[0]['type'])?Text(data[0]['description']):(_mySelection==data[1]['type'])? Text(data[1]['description']):(_mySelection==data[2]['type'])? Text(data[2]['description']):(_mySelection==data[3]['type'])? Text(data[3]['description']):
      (_mySelection==data[4]['type'])?Text(data[4]['description']): (_mySelection==data[5]['type'])?Text(data[5]['description']):(_mySelection==data[6]['type'])?Text(data[6]['description']):(_mySelection==data[7]['type'])?Text(data[7]['description']):(_mySelection==data[8]['type'])?Text(data[8]['description']):(_mySelection==data[9]['type'])?Text(data[9]['description']):(_mySelection==data[10]['type'])?Text(data[10]['description']):(_mySelection==data[11]['type'])?Text(data[11]['description']):Text('Is this the exact incident?'),
                  actions: 
                  <Widget>[
                    FlatButton(
                      onPressed: () => Navigator.pop(context),
                      child: Text("Confirm"),
                    ),
                  ],
                );
              },
            );
          },
        );

        }



  String lat;
  String long;

  String _name="";
  String _street="";
  String locality="";
  String administrativeArea="";
  

  getCurrentLocation() async{
   Position position = await Geolocator.getCurrentPosition(desiredAccuracy: LocationAccuracy.high);
    
  
   setState((){
     lat = '${position.latitude.toString()}';
    long = '${position.longitude.toString()}';
  
   
   });
  List<Placemark> newPlace =
  await placemarkFromCoordinates(double.tryParse(lat),double.tryParse(long));
  Placemark placeMark = newPlace[0];
   _name = placeMark.name;
  _street = placeMark.thoroughfare;
   locality = placeMark.locality;
   administrativeArea = placeMark.administrativeArea;
   
   
  
   return("$_name, $locality,$_street, $administrativeArea");
  }



  String _name2="";
  String _street2="";
   String locality2="";
  String administrativeArea2="";
  
  getCurrentLocation2() async{
  List<Placemark> newPlace =
  await placemarkFromCoordinates(double.tryParse(widget.lat),double.tryParse(widget.long));
  Placemark placeMark = newPlace[0];
   _name2 = placeMark.name;
  _street2 = placeMark.thoroughfare;
    locality2 = placeMark.locality;
   administrativeArea2 = placeMark.administrativeArea;
  
  
    setState(() {
     lat = widget.lat;
     long = widget.long;
    });

    return("$_name2,$_street2");
  }




    

  @override
  void initState() {
    super.initState();
    this.getSWData();
       getPref();
     // getCurrentLocation();
     // getCurrentLocation2();
   widget.lat==null&&widget.long==null? getCurrentLocation():getCurrentLocation2();
   
  
  }

  




  @override
  Widget build(BuildContext context) {
  SizeConfig().init(context); 
   
          Widget textSection = Container(
            color: Color(0xFF262AAA),
            padding: const EdgeInsets.all(10.0),
            child: Text(
              "If you file a false police report, there's a very good chance that you could be held liable for defamation, intentional infliction of emotional distress, or other damages directly resulting from your actions.",
              style: TextStyle(fontStyle: FontStyle.italic,fontSize: 15,color: Colors.white),
              softWrap: true,
            ),
          );

            Widget iconSec = Container(
                decoration: BoxDecoration(
                    image: DecorationImage(image: AssetImage("pnp.jpg"), fit: BoxFit.cover, colorFilter: new ColorFilter.mode(Colors.black.withOpacity(0.9), BlendMode.dstATop),),
                    ),
              child: Align(
              alignment: Alignment(-0.96, -0.96),
              child: Padding(
                padding: const EdgeInsets.only(top:15),
              child: Icon(
              Icons.warning,
              color: Colors.white,
              size: 50.0,
            ),
            ),
            ),
            );
              
      Widget titleSec= Container (
                padding: const EdgeInsets.all(30.0),
                color: Colors.white,
                child: new Container(
                  child: new Center(
                    child: new Column(
                     children : [
                       new Padding(padding: EdgeInsets.only(top: 20.0)),
                       new Text('INCIDENT SUMMARY',
                       style: new TextStyle( decoration: TextDecoration.underline,color: Colors.black, fontSize: 30.0, fontWeight: FontWeight.w700,),),
                       new Padding(padding: EdgeInsets.only(top: 50.0)),
                     ]
                  ),
                ),
                ),
                );



        Widget dropdownSec = Container(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              children: <Widget>[
                Text("Type of Incident:",
                  style: new TextStyle(color: Colors.black, fontSize: 20.0, fontWeight: FontWeight.w300,),
                ),
                SizedBox(
                  height: 5.0,
                ),     
                DropdownButton(
                   isExpanded: true,
                   items: data.map((item) {
                    return new DropdownMenuItem(
                      child: new Text(item['type']),
                      value: item['type'].toString(),
                    );
                  }).toList(),
                  onChanged: (newVal) {
                    setState(() {
                      _mySelection = newVal;
                       showAlertDialog(context);
                    });
                  },
                
                  value: _mySelection,
                ),      
              ],
          ),
        );
          
        

        
        Widget title2= Container (
                padding: const EdgeInsets.all(20.0),
                color: Colors.white,
                child: new Container(
                  child: new Center(
                    child: new Column(
                     children : [
                       new Padding(padding: EdgeInsets.only(top: 0.0)),
                       new Text('Outline of the Incident:',
                       style: new TextStyle(color: Colors.black, fontSize: 20.0, fontWeight: FontWeight.w300,),),
                
                     ]
                  ),
                ),
                ),
                );


          Widget datetitle= Container (
                padding: const EdgeInsets.all(20.0),
                color: Colors.white,
                child: new Container(
                  child: new Center(
                    child: new Column(
                     children : [
                       new Padding(padding: EdgeInsets.only(top: 0.0)),
                       new Text('When did this incident happened?',
                       style: new TextStyle(color: Colors.black, fontSize: 17.0, fontWeight: FontWeight.w300,),),
                  
                     ]
                  ),
                ),
                ),
                );
        Widget datebutton = 
            Hero(
            tag: 'dddddd',
          child: Container (
          padding: const EdgeInsets.only(top:0),
            child: Row(
            mainAxisAlignment: MainAxisAlignment.center, 
            mainAxisSize: MainAxisSize.min,
            children: <Widget>[
               IconButton(
                      icon: Icon(Icons.calendar_today),
                      color: Colors.cyan,
                      onPressed: () => _selectDate(context),
                        ),                     
                      Text(("${selectedDate.toLocal()}".split(' ')[0]),
                          style: TextStyle(color: Colors.cyan,fontSize: 10.0,),),
                          SizedBox(width: 10.0,),
                    IconButton(
                      icon: Icon(Icons.timer),
                      color: Colors.cyan,
                      onPressed: () => _pickTime(context),
                        ),  
                     Text(time.format(context),
                      style: TextStyle(color: Colors.cyan,fontSize: 10.0,),), 
                   
            ]),
            
            ));
            
     
     Widget textSec =
     Form(
      key: _key,
     child: Container (
     padding: const EdgeInsets.only(top:0),
     margin:EdgeInsets.symmetric(horizontal: 20.0),
      child: TextFormField(
      validator: (e) {
          if (e.isEmpty) {
                                  
                              
           return "This field cannot be empty.";
             }
             },
       controller: desController,
        keyboardType: TextInputType.text,
          maxLines: 8,
          decoration: InputDecoration(
            border: OutlineInputBorder(
              borderRadius:
                      BorderRadius.all(new Radius.circular(25.0))
            ),
            hintText: 'Enter description'
          ),
        ),
     )
     );

     Widget title3= Container (
                padding: const EdgeInsets.all(20.0),
                color: Colors.white,
                child: new Container(
                  child: new Center(
                    child: new Column(
                     children : [
                       new Padding(padding: EdgeInsets.only(top: 0.0)),
                     RichText(
                    text: TextSpan(
                      text: 'Attach Evidence (Image/Video)  ',
                      style: TextStyle(color: Colors.black,fontSize: 20.0, fontWeight: FontWeight.w300,),
                      children: <TextSpan>[
                        
                         TextSpan(text: ':',style:TextStyle(color: Colors.black)),
                      ],
                    ),
                  )
                     ]
                  ),
                ),
                ),
                );
           
          
          Widget button1 = 
            Hero(
            tag: 'button1',
          child: Container (
            
          padding: const EdgeInsets.only(top:0),
            child: Row(
            mainAxisAlignment: MainAxisAlignment.center, 
            mainAxisSize: MainAxisSize.min,
            children: <Widget>[
            RaisedButton(
                  onPressed: selectFile,
                  child: const Text('Pick Evidence   ', style: TextStyle(fontSize: 15),textAlign: TextAlign.center),
                ),
              SizedBox(width: 10),
               files == null ? Text("No file attached",style: TextStyle(color: Colors.red),): Text("File Attached",style: TextStyle(color: Colors.green),),
            ]),
            ));
  
          Widget button2 = 
          Hero(
            tag: 'button2',
            child: Container(      
            padding: const EdgeInsets.only(top:40),
            child: Row( 
            mainAxisAlignment: MainAxisAlignment.center, 
            mainAxisSize: MainAxisSize.min,
            children: <Widget>[ 
              RaisedButton.icon(
                padding: const EdgeInsets.all(12.0),
                  onPressed: (){
           // uploadFile();
            check();
          
                  },
                  shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.all(Radius.circular(20.0))),
                  label: Text('Send Report', 
                        style: TextStyle(color: Colors.white, fontSize:20),),
                  icon: Icon(Icons.send, color:Colors.white,), 
                  textColor: Colors.black,
                  splashColor: Colors.cyan,
                  color: Color(0xFF262AAA),)
          
            ])));

 
        return WillPopScope(
          onWillPop: ()=> Future.value(false),
          child: Scaffold(
           key: _scaffoldstate,
            appBar: AppBar(
            centerTitle: false,
            backgroundColor: Colors.white,
              leading: IconButton(icon: Icon(Icons.arrow_back),
              color: Color(0xFF262AAA),
              onPressed: (){
              
                Navigator.push(context, new MaterialPageRoute(
                    builder: (context) => Login()
                    ));
                   
              }),
          title: Text("Location:", style: TextStyle(
          color: Colors.black, fontWeight: FontWeight.w700, fontSize: 2.5 * SizeConfig.blockSizeVertical,
          )),
        
          actions: 
          <Widget>[
           
            FlatButton(
            textColor: Color(0xFF262AAA),
              onPressed: () {
             Navigator.push(
                context,
                MaterialPageRoute(
              builder: (context) => MapsDemo(plat: lat, plong: long)),
                     );
              },
             
             child: Row(
                    children: <Widget>[
                      
                      Icon(Icons.location_on, size: 20,color: Colors.cyan,),
                    widget.lat==null&&widget.long==null? Text(_name+" "+_street, style: TextStyle(
                      fontWeight: FontWeight.w300,  fontSize: 1.5 * SizeConfig.blockSizeVertical,
                      color: Color(0xFF262AAA),
                      )): Text(_name2+" "+_street2, style: TextStyle(
                      fontWeight: FontWeight.w300,  fontSize: 1.5 * SizeConfig.blockSizeVertical,
                      color: Color(0xFF262AAA),
                      )), 
      
                    ]
                  ),
           
         
              shape: CircleBorder(side: BorderSide(color: Colors.transparent)),
            ),
           
            ],
          
          
          ),
      body: ListView(
          children: [

            iconSec,
            textSection,
            titleSec,
            dropdownSec,
            datetitle,
            datebutton,
            title2,
            textSec,
            title3,
            button1,
            button2,
         
          
          ],
        ),

        
      ),
    );
  }


}
