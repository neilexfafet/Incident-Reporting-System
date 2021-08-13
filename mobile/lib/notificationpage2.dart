import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:async';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:jiffy/jiffy.dart';

class Notif2 extends StatelessWidget {
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
  String id;
  String fdate="";
  
  

   getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
       id = preferences.getString('id');
  
    });
 
 
  }


  
 String reportId;
 Future getData() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/ufetch.php';
    http.Response response = await http.get(url);
    List data = json.decode(response.body);

      
   
    _streamController.add(data);
  }


  sendData() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/notif3.php", body: {
      
      "id": id,
     "reportId": reportId,
      "read": "deleted",
      
    });
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
                    title: Text("Report Update",
                      style: TextStyle(
                            fontWeight: FontWeight.bold, color: Color(0xFF262AAA)),
                          ),
                    
                  subtitle:
                 Column (
                    crossAxisAlignment: CrossAxisAlignment.start, 
                    children: <Widget>[
                   
                  
                         
                             Text(
                            "Your report about"+ " " + document['type'] +" " +"been responded by" +" "+ document['station_name'] +"" ,
                          style: TextStyle(
                            fontWeight: FontWeight.bold, color: Colors.black),
                          ),
                        
                      
                          
                        
                     
                      Text(Jiffy(document['created_at']).fromNow()),
                    ]
  
                  ),
                    
                     isThreeLine: true,
                     
                    trailing:IconButton(icon: Icon(Icons.delete,size: 25.0),onPressed: () {
                   showAlertDialog(context);
                    setState(() {
                       reportId = document['id'];
                    });
                    print(reportId);
                    },),
                  
                    
         
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
