import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:async';
import 'reportpage.dart';


class Stations extends StatelessWidget {
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


  @override
  _NotifPageState createState() => _NotifPageState();
}


class _NotifPageState extends State<NotifPage> {
  StreamController<List> _streamController = StreamController<List>();
  Timer _timer;
  bool _fromTop = true;
  
  

   



  

 Future getData() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/sfetch.php';
    http.Response response = await http.get(url);
    List data = json.decode(response.body);

  
  

    _streamController.add(data);
  }




    
  @override
  void initState() {
    getData();
  
 
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
               
            Card(
          elevation: 8.0,
        margin: new EdgeInsets.symmetric(horizontal: 10.0, vertical: 6.0),
         child: Container(
        decoration: BoxDecoration(color: Colors.blueGrey[100]),
           child: ListTile(
                contentPadding:
                EdgeInsets.symmetric(horizontal: 20.0, vertical: 10.0),
                leading: Container(
                  padding: EdgeInsets.only(right: 12.0),
                  decoration: new BoxDecoration(
                      border: new Border(
                          right: new BorderSide(width: 1.0, color: Color(0xFF262AAA)))),
                  child:  Image.asset(
                          'assets/pnp.jpg',
                          height: 50.0,
                          fit: BoxFit.cover,
                        ),
                  
                  ),
                    title: Text(document['station_name'],
                      style: TextStyle(
                            fontWeight: FontWeight.bold, color: Color(0xFF262AAA)),
                          ),
                    
                    subtitle:
                 Column (
                    crossAxisAlignment: CrossAxisAlignment.start, 
                    children: <Widget>[
                   
                    Row(
                          children: <Widget>[
                             Icon(Icons.location_on,size: 15),
                
                             Text(
                            document['location_name'],
                          style: TextStyle(
                            fontWeight: FontWeight.bold, color: Colors.black),
                          ),
                        
                      
                          ]
                        ),
                   Row(
                          children: <Widget>[
                             Icon(Icons.phone,size: 15),
                             SizedBox(width: 5),
                             Text(
                            document['station_contactno'],
                          style: TextStyle(
                            fontWeight: FontWeight.bold, color: Colors.black),
                          ),
                        
                      
                          ]
                        ),
                   
                    ]
                 
                  ),
                
                    trailing: 
                    
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
                              height: 500,
                              width: 450,
                            
                               child:Column(
                              children: <Widget>[
                                SizedBox(height:15),
                                
                                  document['image']== "TBD"?
                                  
                                  Container(
                                    
                                      
                                    child:   Column(
                                     children: <Widget>[
                                       SizedBox(height:170),
                                    Icon(
                                        Icons.error,
                                        color: Colors.red,
                                        size: 50.0,
                                      ),
                                      SizedBox(height:20),
                                    Text("No Station Image Avaialable",
                                    style:  TextStyle(color: Colors.black, fontSize:20,decoration: TextDecoration.none,fontWeight: FontWeight.bold),),])
                                     )
                                    :Image.network("http://192.168.18.12/laravelCAPSTONE/public/"+document['image'],
                                             height: 470,
                                             width: 320,
                                              fit:BoxFit.fill
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
                    }
                    ),
                   isThreeLine: true,
                    
         
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
