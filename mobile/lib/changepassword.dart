import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:fluttertoast/fluttertoast.dart';
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



class ChangePass extends StatefulWidget {
  ChangePass({Key key}) : super(key: key);
  

  @override
  _ChangePassState createState() => _ChangePassState();
}

class _ChangePassState extends State<ChangePass> {
 final _key = new GlobalKey<FormState>();
 
String password, npassword,cnpassword;
String  email;
final TextEditingController _pass = TextEditingController();
final TextEditingController _confirmPass = TextEditingController();




check() {
   final form = _key.currentState;
    if (form.validate()) {
      form.save();
       changepass();
    }
  }


changepass() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/changepass.php", body: {
      "email": email,
      "password": password,
      "npassword": _pass.text,
      "cnpassword": _confirmPass.text,
    });

   

   final data = jsonDecode(response.body);  
    int value = data['value'];
    String message = data['message'];
    if (value == 1) {
      setState(() {
      Navigator.pop(context);
      });
      print(message);
      changeToast(message);
    } else {
      print("fail");
      print(message);
      changeToast2(message);
    }
}
  
  changeToast(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 2,
        backgroundColor: Colors.green,
        textColor: Colors.white);
  }

   changeToast2(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_LONG,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 5,
        backgroundColor: Colors.red,
        textColor: Colors.white);
  }

  getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
       email = preferences.getString('email');
  
     
    });
    print(email);
 
  }

  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    getPref();
  }

  @override
  Widget build(BuildContext context) {
     SizeConfig().init(context); 
   
    return Scaffold(
      resizeToAvoidBottomPadding: true,
      appBar: AppBar(
        title: Text('Change Password'),
       backgroundColor: Color(0xFF262AAA),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
         
          Container(
             padding: EdgeInsets.only(top: 10, left: 20.0, right: 20.0),
          child: Form(
          key: _key,
            child: Column( 
              children: <Widget>[
                  new Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                     children: <Widget>[
                  new Text(
                      'Current Password',
                        style: TextStyle(
                        fontSize: 2.5 * SizeConfig.blockSizeVertical,
                         fontWeight: FontWeight.bold),
                        ),
                     ]
                  ),
                TextFormField(
                  obscureText: true,
                    validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill out the field";
                            }
                    
                           },
                   onSaved: (e) => password = e,
                  decoration: InputDecoration(
                      labelText: 'Enter Current Password',
                      labelStyle: TextStyle(
                          fontWeight: FontWeight.bold, color: Colors.grey),
                      focusedBorder: UnderlineInputBorder(
                          borderSide: BorderSide(color: Colors.red))),
                ),
                SizedBox(height: 10.0),
                new Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                     children: <Widget>[
                  new Text(
                      'New Password',
                        style: TextStyle(
                       fontSize: 2.5 * SizeConfig.blockSizeVertical,
                         fontWeight: FontWeight.bold),
                        ),
                     ]
                  ),
                TextFormField(
           //         key: _key,
                    validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill out the field";
                            }
                            
                          if (e.length < 8)
                          return 'Password must be at least 6 characters';
                        else
                          return null;
                           },
               //   onSaved: (e) => npassword = e,
                 controller: _pass,
                  decoration: InputDecoration(
                      labelText: 'Enter New Password',
                      labelStyle: TextStyle(
                          fontWeight: FontWeight.bold, color: Colors.grey),
                      focusedBorder: UnderlineInputBorder(
                          borderSide: BorderSide(color: Colors.red))),
                  obscureText: true,
                ),
                SizedBox(height: 10.0),
                new Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                     children: <Widget>[
                  new Text(
                      'Confirm New Password',
                        style: TextStyle(
                        fontSize: 2.5 * SizeConfig.blockSizeVertical,
                         fontWeight: FontWeight.bold),
                        ),
                     ]
                  ),
                TextFormField(
                    validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill out the field";
                            }
                         
                          if (e.length < 8)
                          return 'Password must be at least 6 characters';
                          if (e != _pass.text)
                          return 'Password does not match';
                        else
                          return null;
                           },
              //    onSaved: (e) => cnpassword = e,
                controller:  _confirmPass,
                  decoration: InputDecoration(
                      labelText: 'Confirm New Password',
                      labelStyle: TextStyle(
                          fontWeight: FontWeight.bold, color: Colors.grey),
                      focusedBorder: UnderlineInputBorder(
                          borderSide: BorderSide(color: Colors.red))),
                            obscureText: true,
                ),
                  
                SizedBox(height: 10.0),
                Container(
                  height: 40.0,
                  child: Material(
                    borderRadius: BorderRadius.circular(20.0),
                    shadowColor: Color(0xFF262AAA),
                    color: Color(0xFF262AAA),
                    elevation: 7.0,
                    child: GestureDetector(
                        onTap: () {
                          check();
                        },
                        child: Center(
                            child: Text(
                          'Change Password',
                          style: TextStyle(
                              fontWeight: FontWeight.bold, color: Colors.white),
                        ))),
                  ),
                ),
            
              ],
            ),
          ),
     
         
          ),],
      ),
    );
  }
}