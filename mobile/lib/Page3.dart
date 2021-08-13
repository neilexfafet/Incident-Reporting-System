import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'changepassword.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:fluttertoast/fluttertoast.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';




class AccountPage extends StatefulWidget {

  
  @override
  MapScreenState createState() => MapScreenState();
}


class MapScreenState extends State<AccountPage>
    with SingleTickerProviderStateMixin {
 StreamController<List> _streamController = StreamController<List>();
   final _key = new GlobalKey<FormState>();


  bool _status = true;
  final FocusNode myFocusNode = FocusNode();
 
 TextEditingController _controlleremail;
 TextEditingController _controllerfname;
 TextEditingController _controllerlname;
 TextEditingController _controllermobile;
 TextEditingController _controlleraddress;
 TextEditingController _controllergender;

 DateTime selectedDate = DateTime.now();

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
  


  String email = "", id = "", fname= "", lname="", address="",mobile="",date="",gender="";
 // String email2 = "", fname2= "", lname2="", address2="",mobile2="";
  getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
       id = preferences.getString('id');
       email = preferences.getString('email');
       fname = preferences.getString('fname');
       lname = preferences.getString('lname');
       address = preferences.getString('address');
       mobile = preferences.getString('mobile');
       date = preferences.getString('date');
        gender = preferences.getString('gender');
       _controlleremail= new TextEditingController(text: email);
       _controllerfname= new TextEditingController(text: fname);
       _controllerlname= new TextEditingController(text: lname);
       _controllermobile= new TextEditingController(text: mobile);
      _controlleraddress= new TextEditingController(text: address);
       _controllergender= new TextEditingController(text: gender);
    
    });
  }
 
   Future getData() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/userfetch.php';
    http.Response response = await http.get(url);
    List data = json.decode(response.body);

    _streamController.add(data);
  }


  File _image;
  final picker = ImagePicker();
 
   Future choiceImage()async{
    var pickedImage = await picker.getImage(source: ImageSource.gallery);
    setState(() {
      _image = File(pickedImage.path);
      
    });
  }

   Future uploadImage()async{
    final uri = Uri.parse("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/api_veri2.php");
    var request = http.MultipartRequest('POST',uri);
    var pic = await http.MultipartFile.fromPath("image", _image.path);
    request.files.add(pic);
    request.fields['email'] = email;
    var response = await request.send();
  
    if (response.statusCode == 200) {
      print('Image Uploded');
      Fluttertoast.showToast(
        msg: ("Image has been updated successfully."),
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 2,
        backgroundColor: Colors.green,
        textColor: Colors.white);
    }else{
      print('Image Not Uploded');
    } 
    if (this.mounted) {
    setState(() {
   //   _image = null;
    });
   }
  }  



  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    getPref();
    getData();
  

  }

  check() {
  final form = _key.currentState;
    if (form.validate()) {
      form.save();
       edit();
    }
    
  }

  

  edit() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/edit.php", body: {
      'id': id,
      'email': _controlleremail.text,    
      'fname': _controllerfname.text,
      'lname': _controllerlname.text,
      'mobile': _controllermobile.text,
      'address': _controlleraddress.text,
      'birthday': "${selectedDate.toLocal()}".split(' ')[0],
    });

    print(id);
    print(_controlleremail.text);
    print(_controllerfname.text);
    print(_controllerlname.text);
    print(_controllermobile.text);
    print(_controlleraddress.text);
    print("${selectedDate.toLocal()}".split(' ')[0]);
   

   

   final data = jsonDecode(response.body);  
    int value = data['value'];
    String message = data['message'];
    if (value == 1) {
      setState(() {
    //  Navigator.pop(context);
      });
     print(message);
     editToast(message);
    } else {
   //   print("fail");
    print(message);
    editToast2(message);
    }
}
  
  editToast(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 3,
        backgroundColor: Colors.green,
        textColor: Colors.white);
  }

  editToast2(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 3,
        backgroundColor: Colors.red,
        textColor: Colors.white);
  }



  @override
  Widget build(BuildContext context) {
    return new Scaffold(
      appBar: AppBar( 
        backgroundColor: Color(0xFF262AAA),
          centerTitle: true,
        title: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Text('CDO',
            style: TextStyle(color: Colors.lightBlue,fontWeight: FontWeight.w700),
            ),
            SizedBox(width: 1.3),
            Text(
              'REPORT',
              style: TextStyle(color: Colors.white,fontWeight: FontWeight.w700),
            ),
          ],
        ),
        elevation: 0,
      ),
        body:     
         StreamBuilder<List>(
        stream: _streamController.stream,
        builder:(BuildContext context, AsyncSnapshot snapshot) {
       
       if (snapshot.hasData)
        return new Container(
        color: Colors.white,
        child: Form(
          key: _key,
       child: new ListView(
        children: <Widget>[
           
       for (Map document in snapshot.data)
         if (id==document['id']) 
          Column(
            children: <Widget>[
              new Container(
                height: 195.0,
                color: Colors.white,
                child: new Column(
                  children: <Widget>[
                  
                    Padding(
                      padding: EdgeInsets.only(top: 50.0),
                      child: new Stack(fit: StackFit.loose, children: <Widget>[
                        new Row(
                          crossAxisAlignment: CrossAxisAlignment.center,
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: <Widget>[
                            new Container(
                                width: 140.0,
                                height: 140.0,
                                decoration: new BoxDecoration(
                                  shape: BoxShape.circle,
                                  border: Border.all(color: Color(0xFF262AAA),width: 2 ),
                                  image: 
                                  new DecorationImage(
                                    image:
                                    
                        
                                    document['image'] == "TBD" ?new ExactAssetImage(
                                        'assets/images/default_user.png') :
                                         NetworkImage("http://192.168.18.12/laravelCAPSTONE/public/"+document['image'],),
                                    fit: BoxFit.cover,
                                  ),
                                )),
                          ],
                        ),
                        
                        Padding(
                            padding: EdgeInsets.only(top: 90.0, right: 100.0),
                            child: new Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: <Widget>[
                                new CircleAvatar(
                                  backgroundColor: Color(0xFF262AAA),
                                  radius: 25.0,
                                  child: new  IconButton(
                                          onPressed: () {
                                        choiceImage();  
                                   
                                          },
                                          icon: Icon(Icons.camera_alt),
                                          color: Colors.white,
                                        ),
                                      
                                )
                              ],
                            )),
                      ]),
                    )
                  ],
                ),
              ),
        

              _image == null
            ? Padding(
                padding: EdgeInsets.only(top: 0.0),
             child: RaisedButton(
          child: new Text('Save Image'),
          textColor: Colors.white,
          color: Colors.green,
          onPressed:null,
          shape: new RoundedRectangleBorder(
                    borderRadius: new BorderRadius.circular(20.0)),
        ),
              ):
              Padding(
                padding: EdgeInsets.only(top: 0.0),
             child: RaisedButton(
          child: new Text('Save Image'),
          textColor: Colors.white,
          color: Colors.green,
          onPressed: (){
            uploadImage();
          },
          shape: new RoundedRectangleBorder(
                    borderRadius: new BorderRadius.circular(20.0)),
        ),
              ),
                
   
              new Container(
                color: Color(0xffFFFFFF),
                child: Padding(
                  padding: EdgeInsets.only(bottom: 25.0),
                  child: new Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: <Widget>[
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 25.0),
                          child: new Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                  new Text(
                                    'Personal Information',
                                    style: TextStyle(
                                        fontSize: 18.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ],
                              ),
                              new Column(
                                mainAxisAlignment: MainAxisAlignment.end,
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                  _status ? _getEditIcon() : new Container(),
                                ],
                              )
                            ],
                          )),
                          Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 5.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                   GestureDetector(
                                onTap: () {
                                   Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (context) => ChangePass()),
                                  );
                                  
                                  
                                },
                                child: Text(
                                  "Change Password",
                                  style: TextStyle(
                                      fontWeight: FontWeight.bold, fontStyle: FontStyle.italic, color: Colors.cyan),
                                ),
                              ),
                                 
                                ],
                              ),
                            ],
                          )),
                          
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 35.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                  new Text(
                                    'Email ID',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ],
                              ),
                            ],
                          )),
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 2.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Flexible(
                                child: new TextField(
                                  
                           
                               controller: _controlleremail,
                                  enabled: !_status,
                                  autofocus: !_status,

                                ),
                              ),
                            ],
                          )),
                    
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 25.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                              Expanded(
                                child: Container(
                                  child: new Text(
                                    'Firstname',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ),
                                flex: 2,
                              ),
                              Expanded(
                                child: Container(
                                  child: new Text(
                                    'Lastname',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ),
                                flex: 2,
                              ),
                            ],
                          )),
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 2.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                              Flexible(
                                child: Padding(
                                  padding: EdgeInsets.only(right: 10.0),
                                  child: new TextField(   
                                   
                                     controller: _controllerfname,
                                    enabled: !_status,
                                  ),
                                ),
                                flex: 2,
                              ),
                              Flexible(
                                child: new TextField(
                          
                               controller: _controllerlname,
                                  enabled: !_status,
                                ),
                                flex: 2,
                              ),
                            ],
                          )),
                           Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 25.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                  new Text(
                                    'Address',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ],
                              ),
                            ],
                          )),
                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 2.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            children: <Widget>[
                              new Flexible(
                                child: new TextField(
                         
                               controller: _controlleraddress,
                                  enabled: !_status,
                                ),
                              ),
                            ],
                          )),
                            Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 25.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                              Expanded(
                                child: Container(
                                  child: new Text(
                                    'Mobile',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ),
                                flex: 2,
                              ),
                              Expanded(
                                child: Container(
                                  child: new Text(
                                    'Gender',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ),
                                flex: 2,
                              ),
                            ],
                          )),
                             Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 2.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                              Flexible(
                                child: Padding(
                                  padding: EdgeInsets.only(right: 10.0),
                                  child: new TextField(   
                                   
                                   controller: _controllermobile,
                                    enabled: !_status,
                                  ),
                                ),
                                flex: 2,
                              ),
                              Flexible(
                                child: new TextField(
                          
                              controller: _controllergender,
                                  enabled: !_status,
                                ),
                                flex: 2,
                              ),
                            ],
                          )),
                           Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 25.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                              Expanded(
                                child: Container(
                                  child: new Text(
                                    'Date of Birth',
                                    style: TextStyle(
                                        fontSize: 16.0,
                                        fontWeight: FontWeight.bold),
                                  ),
                                ),
                                flex: 2,
                              ),
                            
                            ],
                          )),

                      Padding(
                          padding: EdgeInsets.only(
                              left: 25.0, right: 25.0, top: 2.0),
                          child: new Row(
                            mainAxisSize: MainAxisSize.max,
                            mainAxisAlignment: MainAxisAlignment.start,
                            children: <Widget>[
                          
                             IconButton(
                            icon: Icon(Icons.calendar_today),
                            color: Colors.cyan,
                            onPressed: () => _selectDate(context),
                            ),
                              SizedBox(width: 5.0,),
                             Text(date,
                              style: TextStyle(color: Colors.cyan),),
                          SizedBox(width: 0.0,),
                        
                            ],
                          )),
                      !_status ? _getActionButtons() : new Container(),
                    ],
                  ),
                ),
              )
            ],
          ),
        ],
      ),
    ),
    );

    return new Center(child: new CircularProgressIndicator());

   }
  
    ),

    );
    

  }

  @override
  void dispose() {
 
    myFocusNode.dispose();
    super.dispose();
  }

  Widget _getActionButtons() {
    return Padding(
      padding: EdgeInsets.only(left: 25.0, right: 25.0, top: 45.0),
      child: new Row(
        mainAxisSize: MainAxisSize.max,
        mainAxisAlignment: MainAxisAlignment.start,
        children: <Widget>[
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(right: 10.0),
              child: Container(
                  child: new RaisedButton(
                child: new Text("Save"),
                textColor: Colors.white,
                color: Colors.green,
                onPressed: () {
                  setState(() {
                    _status = true;
                    FocusScope.of(context).requestFocus(new FocusNode());
                     edit();
                  });
                },
                shape: new RoundedRectangleBorder(
                    borderRadius: new BorderRadius.circular(20.0)),
              )),
            ),
            flex: 2,
          ),
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(left: 10.0),
              child: Container(
                  child: new RaisedButton(
                child: new Text("Cancel"),
                textColor: Colors.white,
                color: Colors.red,
                onPressed: () {
                  setState(() {
                    _status = true;
                //    FocusScope.of(context).requestFocus(new FocusNode());
                  });
                },
                shape: new RoundedRectangleBorder(
                    borderRadius: new BorderRadius.circular(20.0)),
              )),
            ),
            flex: 2,
          ),
        ],
      ),
    );
  }

  Widget _getEditIcon() {
    return new GestureDetector(
      child: new CircleAvatar(
        backgroundColor: Color(0xFF262AAA),
        radius: 14.0,
        child: new Icon(
          Icons.edit,
          color: Colors.white,
          size: 16.0,
        ),
      ),
      onTap: () {
        setState(() {
          _status = false;
         
        });
      },
    );
  }
}
