import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:jiffy/jiffy.dart';
import 'dart:convert';
import 'dart:async';
import 'package:shared_preferences/shared_preferences.dart';

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

class Notif extends StatelessWidget {
  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Server',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: NotifPage(title: 'Notif'),
    );
  }
}

class NotifPage extends StatefulWidget {
  NotifPage({Key key, this.title}) : super(key: key);
  final String title;
  static const IconData local_police_sharp = IconData(0xeda1, fontFamily: 'MaterialIcons');
  

  @override
  _NotifPageState createState() => _NotifPageState();
}


class _NotifPageState extends State<NotifPage> {
  StreamController<List> _streamController = StreamController<List>();
  Timer _timer;
  String id;
  bool _fromTop = true;

   getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
       id = preferences.getString('id');
  
    });
 
 
  }



  

 Future getData() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/fetch3.php';
    http.Response response = await http.get(url);
    List data = json.decode(response.body);

    
    _streamController.add(data);
  }

  showAlertDialog(BuildContext context) {
         showDialog(
          context: context,
          barrierDismissible: false,
          builder: (context) {
            return StatefulBuilder(
              builder: (context, setState) {
                return AlertDialog(
                shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(15.0)),
                  title: Row(
                    children: <Widget>[
                      Text("Confirm Deletion"
                      ),
                    ],
                  ),
                  content: Text("Are you sure you want to remove this notification?"),
                  actions: 
                  <Widget>[
                     FlatButton(
                      onPressed: () 
                       {
                       sendData();
                        Navigator.pop(context);
                       },
                      child: 
                      Row(
                          children: <Widget>[
                            
                            Icon(Icons.delete,color: Colors.greenAccent),
                            SizedBox(width:5),
                            Text("Delete",
                            style: TextStyle(color: Colors.greenAccent),)
                          ]
                        ),
                    ),
                    FlatButton(
                      onPressed: () => Navigator.pop(context),
                      child: 
                        Row(
                          children: <Widget>[
                            
                            Icon(Icons.cancel,color: Colors.redAccent),
                            SizedBox(width:5),
                            Text("Cancel",
                             style: TextStyle(color: Colors.redAccent),)
                          ]
                        ),
                    ),
                  ],
                );
              },
            );
          },
        );

      }

   String reportId;
    sendData() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/notif4.php", body: {
      
      "id": id,
      "reportId": reportId,
      "read": "deleted",
   
    });
  }

               



  @override
  void initState() {
    getData();
     getPref();
 
    //Check the server every 5 seconds
    _timer = Timer.periodic(Duration(seconds: 5), (timer) => getData());

    super.initState();
  }

  @override
  void dispose() {
    //cancel the timer
    if (_timer.isActive) _timer.cancel();

    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
      SizeConfig().init(context); 
   
    return Scaffold(
     
      body: 
      StreamBuilder<List>(
        stream: _streamController.stream,
        builder: 
        (BuildContext context, AsyncSnapshot snapshot) {
       
       
         if (snapshot.hasData)
        
            return ListView(
             padding: EdgeInsets.only(top: 2.0),
              children: [
                for (Map document in snapshot.data)
                if (id==document['sendto_id']) 
            Card(
          elevation: 8.0,
        margin: new EdgeInsets.symmetric(horizontal: 10.0, vertical: 6.0),
         child: Container(
        decoration: BoxDecoration(color: Colors.blueGrey[100]),
           child: ListTile(
                contentPadding:
                EdgeInsets.symmetric(horizontal: 2, vertical: 10.0),
                leading: Container(
                  padding: EdgeInsets.all(3),
                  decoration: new BoxDecoration(
                      border: new Border(
                          right: new BorderSide(width: 1.0, color: Color(0xFF262AAA)))),
                  child:  Image.asset(
                          'assets/pnp.jpg',
                          height: 50.0,
                          fit: BoxFit.cover,
                        ),
                  
                  ),
                    title: Text("Announcement",
                     style: TextStyle(
                            fontWeight: FontWeight.bold, color: Color(0xFF262AAA)),
                          ),
                  
                    subtitle:  
                      Column (
                    crossAxisAlignment: CrossAxisAlignment.start, 
                    children: <Widget>[
                   
                    Text("PNP posted new announcement",
                     style: TextStyle(
                            fontWeight: FontWeight.bold, color: Colors.black),
                          
                    ),
                      
                    
                    Text(Jiffy(document['created_at']).fromNow()),
                    ]
                      ),
                   isThreeLine: true,
                   trailing:   Wrap(
                                 spacing: 0,
                              
                                      children: <Widget>[
                                    
                                      
                                        IconButton(icon: Icon(Icons.visibility,size: 25.0),onPressed: () {
                                   
                                
                                  showGeneralDialog(
                                    barrierLabel: "Label",
                                    barrierDismissible: true,
                                    barrierColor: Colors.black.withOpacity(0.5),
                                    transitionDuration: Duration(milliseconds: 700),
                                    context: context,
                                    pageBuilder: (context, anim1, anim2) {
                                      return Align(
                                        alignment: _fromTop ? Alignment.topCenter : Alignment.bottomCenter,
                                        child: Container(
                                         height: 600,
                                         width: 550,
                                         
                                         child:   Column(


                                            children: <Widget>[

                                     document['image']!=null?SizedBox(height:10):SizedBox(height:50), 
                                            Row(
                                    
                                            children: <Widget>[
                                          SizedBox(width:105), 
                                         document['image']!="TBD"?Container():Image.asset('assets/1123.png',
                                           height: 100.0,
  
                                          fit: BoxFit.cover,
                                          )
                                                        
                                            ]
                                          ),

                           
                                            Row(
                                        
                                            children: <Widget>[
                                         document['image']!="TBD"?SizedBox(height:60):SizedBox(width:85,height:100), 
                                        document['image']!="TBD"?Container():Text("ANNOUNCEMENT",
                                     style: TextStyle(color: Colors.black, fontSize:30,decoration: TextDecoration.none,fontWeight: FontWeight.bold)
                                          ),
                                                        
                                            ]
                                          ),

                                         document['image']!="TBD"?SizedBox(height:0):SizedBox(height:20), 
                                            Row(
                
                                            children: <Widget>[
                                        
                                             SizedBox(width:20),  
                                        
                                              SizedBox(width:10),
                                               document['subject']=="TBD"?Container(): RichText(
                                                text: TextSpan(
                                                  text: 'Title:  ',
                                                  style:  TextStyle(color: Colors.black, fontSize:20,decoration: TextDecoration.none,fontWeight: FontWeight.bold),
                                                  children: <TextSpan>[
                                                   document['subject']=="TBD"?Container(): TextSpan(text: document['subject'], style: TextStyle(fontSize: 13,color: Colors.black,decoration: TextDecoration.none)),
                                                    
                                                  ],
                                                ),
                                              ),
                                            
                                            ]
                                          ),

                                              
                                       
                                      
                                        
                                            Row(
      
                                            children: <Widget>[
                                              SizedBox(width:30),
                                           document['message']=="TBD"?Container(): /*RichText(
                                          
                                            text: TextSpan(
                                              text: 'Content:  ',
                                              style:  TextStyle(color: Colors.black, fontSize:20,decoration: TextDecoration.none,fontWeight: FontWeight.bold),
                                              children: <TextSpan>[
                                              document['message']=="TBD"?Container(): TextSpan(text: document['message'], style: TextStyle(fontSize: 15)),
                                                
                                              ],
                                            ),
                                          ),*/
                                          Text('Content:',
                                           style:  TextStyle(color: Colors.black, fontSize:20,decoration: TextDecoration.none,fontWeight: FontWeight.bold),),
                                           document['message']=="TBD"?Container():Expanded(child:Padding(padding:const EdgeInsets.all(10.0),child: Text(document['message'],maxLines: 10, overflow: TextOverflow.ellipsis,textAlign: TextAlign.start, style: TextStyle(fontSize: 13,color: Colors.black,decoration: TextDecoration.none)))),
                                            ]
                                          ),

                                       document['image']!="TBD"?SizedBox(height:0):SizedBox(height:190),
                                          Row(
                                            children: <Widget>[
                                             SizedBox(width:20), 
                                              SizedBox(width:10),
                                            document['image']!="TBD"?Container():RichText(
                                            text: TextSpan(
                                              text: 'Date Posted:  ',
                                              style:  TextStyle(color: Colors.black, fontSize:20,decoration: TextDecoration.none,fontWeight: FontWeight.bold),
                                              children: <TextSpan>[
                                                TextSpan(text: document['created_at'], style: TextStyle(fontSize: 15)),
                                                
                                              ],
                                            ),
                                          ),
                                            
                                            ]
                                          ),
                                         
                                         document['image']=="TBD"?Container():
                                           Row(
                                            children: <Widget>[
                                         document['message']=="TBD" && document['subject']=="TBD"?SizedBox(width: 35):
                                           SizedBox(width:37),
                                           Image.network("http://192.168.18.12/laravelCAPSTONE/public/"+document['image'],
                                             height: 470,
                                             width: 320,
                                              fit:BoxFit.fill
                                            ),
                                            
                                    
                                            ]
                                            
                                          ),
                                       
                                          
                                            ],
                                          ),
                                    
                                          
                                          margin: EdgeInsets.only(top: 50, left: 12, right: 12, bottom: 50),
                                          decoration: BoxDecoration(
                                            color: Colors.white,
                                            borderRadius: BorderRadius.circular(40),
                                          ),
                                        ),
                                      );
                                    },
                                    transitionBuilder: (context, anim1, anim2, child) {
                                      return SlideTransition(
                                        position: Tween(begin: Offset(0, _fromTop ? -1 : 1), end: Offset(0, 0)).animate(anim1),
                                        child: child,
                                      );
                                    },
                                  );    
                          },),
                                        IconButton(icon: Icon(Icons.delete,size: 25.0),onPressed: () {
                                      showAlertDialog(context);
                                          setState(() {
                                          reportId =document['notif_id'];
                                          });
                                          print(reportId);
                                       },),
                                   
                                      ],
                                    
                              ),
        
                  ),
         ),
            ),
              ],
            );
         
         
            return new Center(child: new CircularProgressIndicator());
          
         
        },
      ),
      
    );
    
  }
}
