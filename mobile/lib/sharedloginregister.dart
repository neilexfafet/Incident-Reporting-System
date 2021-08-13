import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:intl/intl.dart';

import 'Constants.dart';
import 'Home.dart';
import 'Page2.dart';
import 'Page3.dart';
import 'notificationpage.dart';
import 'notificationpage2.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

/*class SizeConfig {
  static MediaQueryData _mediaQueryData;
  static double screenWidth;
  static double screenHeight;
  static double blockSizeHorizontal;
  static double blockSizeVertical;
  static double _safeAreaHorizontal;
  static double _safeAreaVertical;
  static double safeBlockHorizontal;
  static double safeBlockVertical;

  void init(BuildContext context){
    _mediaQueryData = MediaQuery.of(context);
    screenWidth = _mediaQueryData.size.width;
    screenHeight = _mediaQueryData.size.height;
    blockSizeHorizontal = screenWidth/100;
    blockSizeVertical = screenHeight/100;
    _safeAreaHorizontal = _mediaQueryData.padding.left +
        _mediaQueryData.padding.right;
    _safeAreaVertical = _mediaQueryData.padding.top +
        _mediaQueryData.padding.bottom;
    safeBlockHorizontal = (screenWidth - _safeAreaHorizontal)/100;
    safeBlockVertical = (screenHeight - _safeAreaVertical)/100;
  }
}
*/


class Login extends StatefulWidget {
  @override
  _LoginState createState() => _LoginState();
}

enum LoginStatus { notSignIn, signIn }

class _LoginState extends State<Login>  with TickerProviderStateMixin {
  LoginStatus _loginStatus = LoginStatus.notSignIn;
  String email, password;
  final _key = new GlobalKey<FormState>();

  bool _secureText = true;
  bool _fromTop = true;
 

  showHide() {
    setState(() {
      _secureText = !_secureText;
    });
  }

  check() {
    final form = _key.currentState;
    if (form.validate()) {

      form.save();
      login();
    
    }
  }

  login() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/api_verification.php", body: {
      "flag": 1.toString(),
      "email": email,
      "password": password,
    });

    final data = jsonDecode(response.body);
    int value = data['value'];
    String message = data['message'];
    String fnameAPI = data['fname'];
    String lnameAPI = data['lname'];
    String emailAPI = data['email'];
    String addressAPI = data['address'];
    String mobileAPI = data['mobile'];
    String dateAPI = data['date'];
    String genderAPI = data['gender'];
    String id = data['id'];

    if (value == 1) {
         
      setState(() {
      
        _loginStatus = LoginStatus.signIn;
        savePref(value, fnameAPI,lnameAPI, emailAPI,addressAPI,mobileAPI,dateAPI,genderAPI,id);
      });
      
    
      print(message);
      loginToast(message);
    } 
    else if (value == 4) {  
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
                              height: 300,
                              width: 350,
                             
                            
                               child:Column(
                              children: <Widget>[
                                SizedBox(height:30),
                                SizedBox(width:105),
                                Icon(Icons.error_outline_sharp,color:Colors.red,size:60),
                                SizedBox(height:20),
                               SizedBox(width:30),
                                Text("Account blocked!",
                              style: TextStyle(color: Colors.black, fontSize:15,decoration: TextDecoration.none,fontWeight: FontWeight.bold)),
                                 SizedBox(height:50),
                               Padding(
                                 padding: EdgeInsets.fromLTRB(20, 20, 20, 20),
                                  child:Text("Your account has been blocked due to a report made was Fraud.",
                              style: TextStyle(color: Colors.black, fontSize:12,decoration: TextDecoration.none),
                              textAlign: TextAlign.center,
                              ),
                               ),
                               ],

                               ),
                              margin: EdgeInsets.only(top: 50, left: 12, right: 12, bottom: 50),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(25),
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
                    print(message);
                  
                  }
                  else {
                  
         
                    print(message);
                    loginToast2(message);
                  }
                }

  loginToast(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 2,
        backgroundColor: Colors.green,
        textColor: Colors.white);
  }

 loginToast2(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 2,
        backgroundColor: Colors.red,
        textColor: Colors.white);
  }
  savePref(int value,  String fname,String lname, String email, String address,String mobile,String date,String gender,  String id) async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
      preferences.setInt("value", value);
      preferences.setString("fname", fname);
      preferences.setString("lname", lname);
      preferences.setString("email", email);
      preferences.setString("address", address);
      preferences.setString("mobile", mobile);
      preferences.setString("date", date);
      preferences.setString("gender", gender);
      preferences.setString("id", id);
      preferences.commit();
   
      
    });
  }

  var value;

  getPref() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
      value = preferences.getInt("value");
      _loginStatus = value == 1 ? LoginStatus.signIn : LoginStatus.notSignIn;
      
    });
   
  }

  signOut() async {
    SharedPreferences preferences = await SharedPreferences.getInstance();
    setState(() {
      preferences.setInt("value", null);
      preferences.setString("fname", null);
      preferences.setString("lname", null);
      preferences.setString("email", null);
      preferences.setString("address", null);
      preferences.setString("mobile", null);    
      preferences.setString("date", null);   
      preferences.setString("id", null);
      preferences.commit();
      _loginStatus = LoginStatus.notSignIn;
    });
  }

 // MediaQueryData queryData;
  
  @override
  void initState() {
    //TODO: implement initState
    super.initState();
    getPref();
  }

  @override
  Widget build(BuildContext context) {
  // SizeConfig().init(context);
  
    switch (_loginStatus) {
      case LoginStatus.notSignIn:
        return  Scaffold(
         
          body: 
          Container(
      height: MediaQuery.of(context).size.height*1,
       width:  MediaQuery.of(context).size.width*1,
        
            decoration: new BoxDecoration(
                gradient: new LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                   colors: [Color(0xFF262AAA), Colors.white],
                )),
          
          child:Center(
            child: ListView(
              shrinkWrap: true,
              padding: EdgeInsets.all(15.0),
              children: <Widget>[
                Center(
               
                    child: Form(
                      key: _key,
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: <Widget>[
                          Image.asset('assets/1123.png',
                                           height: 100.0,
                         fit: BoxFit.cover,
                         ),
                         SizedBox(height:10),
                         Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: <Widget>[
                          Text('CDO',
                          style: TextStyle(color: Colors.lightBlue,fontWeight: FontWeight.w700,fontSize: 35),
                          ),
                          SizedBox(width: 1.3),
                          Text(
                            'REPORT',
                            style: TextStyle(color: Colors.white,fontWeight: FontWeight.w700,fontSize: 35),
                          ),
                        ],
                      ),
                          SizedBox(
                            height: 10),
                          SizedBox(
                            height: 20,
                            child: Text(
                              "Sign in to your account",
                              style: TextStyle(
                                  color: Colors.black, fontSize: 18.0,fontWeight: FontWeight.w500),
                            ),
                          ),
                          SizedBox(
                            height: 25,
                          ),

                          //card for Email TextFormField
                          Card(
                            elevation: 6.0,
                            child: TextFormField(
                              validator: (e) {
                                if (e.isEmpty) {
                                  
                            
                                  return "This field cannot be empty.";
                                }
                             
                              },
                              onSaved: (e) => email = e,
                              style: TextStyle(
                                color: Colors.black,
                                fontSize: 16,
                                fontWeight: FontWeight.w300,
                              ),
                              decoration: InputDecoration(
                                  prefixIcon: Padding(
                                    padding:
                                        EdgeInsets.only(left: 20, right: 15),
                                    child:
                                        Icon(Icons.person, color: Colors.black),
                                  ),
                                  contentPadding: EdgeInsets.all(18),
                                  labelText: "Email"),
                            ),
                          ),

                          // Card for password TextFormField
                          Card(
                            elevation: 6.0,
                            child: TextFormField(
                              validator: (e) {
                                if (e.isEmpty) {
                                  
                              
                                   return "This field cannot be empty.";
                                }
                              },
                              obscureText: _secureText,
                              onSaved: (e) => password = e,
                              style: TextStyle(
                                color: Colors.black,
                                fontSize: 16,
                                fontWeight: FontWeight.w300,
                              ),
                              decoration: InputDecoration(
                                labelText: "Password",
                                prefixIcon: Padding(
                                  padding: EdgeInsets.only(left: 20, right: 15),
                                  child: Icon(Icons.phonelink_lock,
                                      color: Colors.black),
                                ),
                                suffixIcon: IconButton(
                                  onPressed: showHide,
                                  icon: Icon(_secureText
                                      ? Icons.visibility_off
                                      : Icons.visibility),
                                ),
                                contentPadding: EdgeInsets.all(18),
                              ),
                            ),
                          ),

                          SizedBox(
                            height: 15,
                          ),
                           new Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: <Widget>[
                              Text(
                                "Forgot your password?",
                                style: TextStyle(fontWeight: FontWeight.w400,fontSize: 14.0,color: Colors.black),
                              ),
                              SizedBox(
                                width: 5,
                              ),
                              GestureDetector(
                                onTap: () {
                                  print("Routing");
                                },
                                child: Text(
                                  "Recover",
                                  style: TextStyle(
                                      fontWeight: FontWeight.w600, color: Color(0xFF262AAA)),
                                ),
                              )
                            ],
                          ),
                          Padding(
                            padding: EdgeInsets.all(14.0),
                          ),

                           SizedBox(
                            height: 40,
                          ),
                      
                          new Row(
                          
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: <Widget>[
                             
                            
                             ButtonTheme(
                              minWidth:MediaQuery.of(context).size.width * 0.9,

                              height: 36.0,
                             child: new RaisedButton(
                                    shape: RoundedRectangleBorder(
                                        borderRadius:
                                            BorderRadius.circular(35.0)),
                                   padding: const EdgeInsets.all(15.0),
                                    child: Text(
                                      "SIGN IN",
                                      style: TextStyle(fontSize: 18.0),
                                    ),
                                    textColor: Colors.white,
                                    color: Color(0xFF262AAA),
                                
                                    splashColor: Colors.white,
                                    onPressed: () {
                                      check();
                                    }),
                             ),
                                      
                             
                              SizedBox(
                                height: 44.0,
                              ),
                            ],
                          ),
                          

                          SizedBox(
                                height: 24.0,
                              ),
                         

                           new Row(
                            mainAxisAlignment: MainAxisAlignment.center,

                            children: <Widget>[
                              Text(
                                "Don't have an account?",
                                style: TextStyle(fontWeight: FontWeight.w400,fontSize: 14,color: Colors.black ),
                              ),
                              SizedBox(
                                width: 5,
                              ),
                              GestureDetector(
                                onTap: () {
                      
                                  Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                            builder: (context) => Register()),
                                      );
                                },
                                child: Text(
                                  "Sign up",
                                  style: TextStyle(
                                      fontWeight: FontWeight.w800, color: Color(0xFF262AAA), fontSize: 14),
                                ),
                              )
                            ],

                           ),
                          


                        ],
                      ),
                    ),
                  ),
               
              ],
            ),
          ),
        ),

        );
        break;

      case LoginStatus.signIn:
        return MainMenu(signOut);
       
        break;
    }
  }
}

class Register extends StatefulWidget {
  @override
  _RegisterState createState() => _RegisterState();
}

class _RegisterState extends State<Register> {

  
  bool checkBoxValue = false;
  String fname,mname,lname, email, mobile, pass,password,address;
  final TextEditingController _pass = TextEditingController();
  final TextEditingController _confirmPass = TextEditingController();
  final _key = new GlobalKey<FormState>();

  bool _secureText = true;
  File _image;

  final picker = ImagePicker();

  String _currentSelectedValue;

  var _currencies = [
    "Male",
    "Female",
  ];


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
    }else{
      print('Image Not Uploded');
    } 
    if (this.mounted) {
    setState(() {
    
    });
   }
  }  
  
  
  showHide() {
    setState(() {
      _secureText = !_secureText;
    });
  }

  check() {
    final form = _key.currentState;
    if (form.validate()) {
      form.save();
      save();
  
    }
  }

  save() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/api_verification.php", body: {
      "flag": 2.toString(),
      "fname": fname,
      "mname": mname,
      "lname": lname,
      "address": address,
      "email": email,
      "mobile": mobile,
      "password": _confirmPass.text,
      "birthday": _date.text,
      "gender": _currentSelectedValue,
    });
  

  final data = jsonDecode(response.body);
  
    int value = data['value'];
    String message = data['message'];
    if (value == 1) {
      setState(() {
        Navigator.pop(context);
      });
      print(message);
      registerToast(message);
    } else if (value == 2) {
      print(message);
      registerToast2(message);
    } else {
      print(message);
      registerToast2(message);
    }
  }

  registerToast(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 1,
        backgroundColor: Colors.green,
        textColor: Colors.white);
  }

   registerToast2(String toast) {
    return Fluttertoast.showToast(
        msg: toast,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIos: 1,
        backgroundColor: Colors.red,
        textColor: Colors.white);
  }



 DateTime selectedDate = DateTime.now();
TextEditingController _date = new TextEditingController();

Future<Null> _selectDate(BuildContext context) async {
   DateFormat formatter = DateFormat('yyyy-MM-dd');
  final DateTime picked = await showDatePicker(
      context: context,
      initialDate: selectedDate,
      firstDate: DateTime(1901, 1),
      lastDate: DateTime.now());
  if (picked != null && picked != selectedDate)
    setState(() {
      selectedDate = picked;
      _date.value = TextEditingValue(text: formatter.format(picked));
    });
}




  @override
  Widget build(BuildContext context) {
    return Scaffold(
     // backgroundColor: Colors.cyan,
      body: 
       Container(
            decoration: new BoxDecoration(
                gradient: new LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                   colors: [Color(0xFF262AAA), Colors.white],
                )),
      child: Center(
        child: ListView(
          shrinkWrap: true,
          padding: EdgeInsets.all(15.0),
          children: <Widget>[
            Center(
            
                child: Form(
                  key: _key,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: <Widget>[
                      
                      SizedBox(
                        height: 30,
                      ),
                         Image.asset('assets/1123.png',
                                           height: 100.0,
                         fit: BoxFit.cover,
                         ),
                     
                      SizedBox(
                        height: 5,
                      ),
                         Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: <Widget>[
                          Text('CDO',
                          style: TextStyle(color: Colors.lightBlue,fontWeight: FontWeight.w700,fontSize: 35),
                          ),
                          SizedBox(width: 1.3),
                          Text(
                            'REPORT',
                            style: TextStyle(color: Colors.white,fontWeight: FontWeight.w700,fontSize: 35),
                          ),
                        ],
                      ),
                      SizedBox(height: 5),
                       Text(
                          "Make an account",
                          style: TextStyle(
                                  color: Colors.black, fontSize: 18.0,fontWeight: FontWeight.w500),
                        ),
                          SizedBox(
                        height: 25,
                      ),

                      //card for Fullname TextFormField
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill-out the field";
                            }
                            if (e != '${e[0].toUpperCase()}${e.substring(1)}')
                          return 'Firstname should be in uppercase';
                        else
                          return null;
                          },
                          onSaved: (e) => fname = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.person, color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "First Name"),
                        ),
                      ),
                         Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill-out the field";
                            }
                             if (e != '${e[0].toUpperCase()}${e.substring(1)}')
                          return 'Middlename should be in uppercase';
                        else
                          return null;
                          },
                          onSaved: (e) => mname = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.person, color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Middle Name"),
                        ),
                      ),
                         Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill-out the field";
                            }
                             if (e != '${e[0].toUpperCase()}${e.substring(1)}')
                          return 'Lastname should be in uppercase';
                        else
                          return null;
                          },
                          onSaved: (e) => lname = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.person, color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Last Name"),
                        ),
                      ),

                     

                      //card for Mobile TextFormField
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please insert Mobile Number";
                            }
                            if (e.length != 11)
                          return 'Mobile Number must be of 11 digit';
                        else
                          return null;
                          },
                          onSaved: (e) => mobile = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                            prefixIcon: Padding(
                              padding: EdgeInsets.only(left: 20, right: 15),
                              child: Icon(Icons.phone, color: Colors.black),
                            ),
                            contentPadding: EdgeInsets.all(18),
                            labelText: "Mobile",
                          ),
                          keyboardType: TextInputType.number,
                        ),
                      ),

                      
                    
                     
                     
                         
                      
                       //card for Email TextFormField
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please insert Address";
                            }
                           
                          },
                          onSaved: (e) => address = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.place, color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Address"),
                        ),
                      ),
                       //card for Email TextFormField
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                          validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill-out the field";
                            }
                            Pattern pattern =
                                r'^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$';
                            RegExp regex = new RegExp(pattern);
                            if (!regex.hasMatch(e))
                              return 'Please enter a valid Email Address';
                            else
                              return null;
                          },
                          onSaved: (e) => email = e,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                          decoration: InputDecoration(
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.email, color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Email"),
                        ),
                      ),

                      //card for Password TextFormField
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                           validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill out the field";
                            }
                             if (e.length < 6)
                          return 'Password must be at least 6 characters';
                        else
                          return null;
                       
                           },
                          obscureText: _secureText,
                      //    onSaved: (e) => pass = e,
                          controller: _pass,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                           decoration: InputDecoration(
                              suffixIcon: IconButton(
                                onPressed: showHide,
                                icon: Icon(_secureText
                                    ? Icons.visibility_off
                                    : Icons.visibility),
                              ),
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.phonelink_lock,
                                    color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Password"),
                        ),
                      ),
                      Card(
                        elevation: 6.0,
                        child: TextFormField(
                           validator: (e) {
                            if (e.isEmpty) {
                              return "Please fill out the field";
                            }
                          if (e.length < 6)
                          return 'Password must be at least 6 characters';
                        
                         if (e != _pass.text)
                          return 'Password does not match';
                        else
                          return null;
                           },
                          obscureText: _secureText,
                  
                          controller:  _confirmPass,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16,
                            fontWeight: FontWeight.w300,
                          ),
                           decoration: InputDecoration(
                              suffixIcon: IconButton(
                                onPressed: showHide,
                                icon: Icon(_secureText
                                    ? Icons.visibility_off
                                    : Icons.visibility),
                              ),
                              prefixIcon: Padding(
                                padding: EdgeInsets.only(left: 20, right: 15),
                                child: Icon(Icons.phonelink_lock,
                                    color: Colors.black),
                              ),
                              contentPadding: EdgeInsets.all(18),
                              labelText: "Confirm Password"),
                        ),
                      ),


                     
               
                    Container(
                      child:Card(
                        elevation: 6.0,
                      child:GestureDetector(
                                onTap: () => _selectDate(context),
                                child: AbsorbPointer(
                                  child: TextFormField(
                                  validator: (e) {
                                      if (e.isEmpty) {
                                        return "Please select a date";
                                      }
                                  },
                                    controller: _date,
                                    keyboardType: TextInputType.datetime,
                                    decoration: InputDecoration(
                                     labelText: "Birthday",
                                      prefixIcon:  Padding(
                                      padding: EdgeInsets.only(left: 20, right: 15),
                                     child: Icon(
                                        Icons.calendar_today_outlined,
                                        color: Colors.black,
                                      ),
                                      ),
                                      contentPadding: EdgeInsets.all(18),
                                    ),
                                  ),
                                ),
                              ),
                    ),
                    ),
                    
                


                     Container(
                      child:Card(
                        elevation: 6.0,
                     child: FormField<String>(
                       
                      builder: (FormFieldState<String> state) {
                      
                      return InputDecorator(
                        
                        decoration: InputDecoration(
                          
                            prefixIcon:  Padding(
                                      padding: EdgeInsets.only(left: 20, right: 15),
                                     child: Icon(
                                        Icons.face,
                                        color: Colors.black,
                                      ),
                                      ),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(5.0))),
                        isEmpty: _currentSelectedValue == '',
                        child: DropdownButtonHideUnderline(
                          child: DropdownButton<String>(
                            value: _currentSelectedValue,
                            isDense: true,
                            onChanged: (String newValue) {
                              setState(() {
                                _currentSelectedValue = newValue;
                                state.didChange(newValue);
                              });
                            },
                            items: _currencies.map((String value) {
                              return DropdownMenuItem<String>(
                                value: value,
                                child: Text(value),
                              );
                            }).toList(),
                          ),
                        ),
                      );
                    },
                  )
                      ),
                  ),
                   
                      Padding(    
                        padding: EdgeInsets.all(12.0),
                      ),
                   
                      new Container(
                      
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: <Widget>[
                          Checkbox(
                              activeColor: Colors.blue[200],
                              value: checkBoxValue,
                              onChanged: (bool newValue) {
                                setState(() {
                                  checkBoxValue = newValue;
                                });
                              }),
                          Text(
                            "I accept all terms and conditions",
                            style: TextStyle(fontWeight: FontWeight.w400, fontSize:15,color: Colors.black),
                          ),
                        ],
                      ),
                    ),
                   

                      new Row(
                        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                        children: <Widget>[
                          ButtonTheme(
                              minWidth:MediaQuery.of(context).size.width * 0.9,
                              height: 36.0,
                            child: RaisedButton(
                                shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(15.0)),
                                child: Text(
                                  "Register",
                                  style: TextStyle(fontSize: 18.0),
                                ),
                                textColor: Colors.white,
                                splashColor: Colors.white,
                                color: Color(0xFF262AAA),
                                onPressed: () {
                                check();
                           
                                }),
                          ),
                          
                        ],
                      ),
                        SizedBox(
                            height: 10,
                     ),

                       new Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: <Widget>[
                              Text(
                                "Already have an account?",
                                style: TextStyle(fontWeight: FontWeight.w400,fontSize: 14.0,color: Colors.black),
                              ),
                              SizedBox(
                                width: 5,
                              ),
                              GestureDetector(
                                onTap: () {
                                    Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (context) => Login()),
                                  );
                                  
                                },
                                child: Text(
                                  "Sign in",
                                  style: TextStyle(
                                      fontWeight: FontWeight.w600, color: Color(0xFF262AAA)),
                                ),
                              )
                            ],
                          ),


                    ],
                  ),
                ),
              ),
         
          ],
        ),
      ),
    ),
    );
  }
}



 



class MainMenu extends StatefulWidget {

  
  final VoidCallback signOut;

  MainMenu(this.signOut);
  

  @override
  _MainMenuState createState() => _MainMenuState();
}

class _MainMenuState extends State<MainMenu> {

  int index = 0;
 

  List<Widget> list = [
   
    HomeScreen(),
    Stations(),
    AccountPage(),
    Notif(),
    Notif2(),
 
    
  ];

  signOut() {
    setState(() {
      widget.signOut();
    });
  }

  int currentIndex = 0;
  String selectedIndex = 'TAB: 0';


 
  TabController tabController;
 void choiceAction(String choice){
      if(choice == Constants.Settings){
       Navigator.push(context, new MaterialPageRoute(
                    builder: (context) => AccountPage()));
    }else if(choice == Constants.SignOut){
      
      showAlertDialog(context);
    }
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
                      Text("Signing out"
                      ),
                    ],
                  ),
                  content: Text("Are you sure you want to log out?"),
                  actions: 
                  <Widget>[
                     FlatButton(
                      onPressed: () 
                       {
                         signOut();
                        Navigator.pop(context);
                       },
                      child: 
                      Row(
                          children: <Widget>[
                            
                            Icon(Icons.exit_to_app,color: Colors.greenAccent),
                            SizedBox(width:5),
                            Text("Sign Out",
                           style: TextStyle(color: Colors.greenAccent),
                            )
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
                             style: TextStyle(color: Colors.redAccent),
                            )
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
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: Scaffold(
        appBar: AppBar(
          actions: <Widget>[
    
          PopupMenuButton<String>(
             icon: Icon(Icons.settings),
            onSelected: choiceAction,
            itemBuilder: (BuildContext context){
              return Constants.choices.map((String choice){
                return PopupMenuItem<String>(
                 
                  
                  value: choice,
                  child: 
                  Row(
                
                
                      children: <Widget>[
                        
              choice=="Account Settings"?Icon(Icons.settings, size:18):Icon(Icons.exit_to_app, size:18), 
                        Text("      "+choice),
   
                      ]
                    ),
                   
                 // Text(choice),
                );
              }).toList();
            },
          )
          
        ],
          backgroundColor: Color(0xFF262AAA),
           iconTheme: IconThemeData(color: Colors.lightBlue),
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
        body: list[index],
        drawer:  MyDrawer(onTap: (lol, i) {
          setState(() {
            index = i;
            Navigator.pop(lol);
          });
        }),
      ),
      
    );
    
  }
}

class MyDrawer extends StatefulWidget {
    
  final Function onTap;
  final VoidCallback signOut;
 

    MyDrawer(
    {this.onTap,this.signOut
    });



    @override
  _MyDrawerState createState() => _MyDrawerState();
}

    
 class _MyDrawerState extends State<MyDrawer> { 
   int counter = 0;
   int asd = 0;
  
  signOut() {
    setState(() {
      widget.signOut();
    });
  }


   String email = "", id = "", fname= "", lname="", address="",mobile="",date="";
   String read="";
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

    
    });
    print(id);
    print(email);
    print(fname);
    print(lname);
    print(address);
    print(mobile);
    print(date);

  }

 

  Future getData() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/fetch2.php';
    http.Response response = await http.get(url);
    List data = json.decode(response.body);
      
    if (this.mounted) { 
      setState(() {
      data.forEach((item){
      if(item["sendto_id"] == id)
      counter++;
      });
      });
    }
  
    
    
  }



  Future getData2() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/ufetch2.php';
    http.Response response = await http.get(url);
    List data2 = json.decode(response.body);
     
    if (this.mounted) { // check whether the state object is in tree
      setState(() {
      data2.forEach((item){
      if(item["sendto_id"] == id)
      asd++;
      });
      });
    }
     

  }



 

  
  
  sendData() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/notif.php", body: {
      
      "id": id,
      "read": "read",
   
    });
  }

  sendData2() async {
    final response = await http
        .post("http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/notif2.php", body: {
      
      "id": id,
      "read": "read",
   
    });
  }

  
 StreamController<List> _streamController = StreamController<List>();
 Future user() async {
    var url = 'http://192.168.18.12/laravelCAPSTONE/public/FlutterUpload-master/userfetch.php';
    http.Response response = await http.get(url);
    List udata = json.decode(response.body);

    
    _streamController.add(udata);
  }



  
    @override
  void initState() {
    // TODO: implement initState
    super.initState();
    getPref();
    getData();
    getData2();
    user();
  }

  @override
  Widget build(BuildContext context) {

  
    
     return SizedBox(
       
      width: MediaQuery
          .of(context)
          .size
          .width * 0.7,
      child: Drawer(
        child: Container(
          height:100,
          color: Colors.white,
          child: ListView(
            padding: EdgeInsets.all(0),
            children: <Widget>[
             
        //    for (Map document in snapshot.data)
          //    if (id==document['id']) 
              UserAccountsDrawerHeader(
                decoration: BoxDecoration(
                color: Colors.white,
                 image: DecorationImage(
                  image: AssetImage("assets/pnp.jpg"),
                     fit: BoxFit.cover,
                      colorFilter: new ColorFilter.mode(Colors.black.withOpacity(0.8), BlendMode.dstATop)),
               ),
                accountEmail: Text(email),
                accountName: Text(fname+" "+lname,
                style: TextStyle(color: Colors.white,fontWeight: FontWeight.w700, fontSize: 25),
                ),
         
              ),
             
              ListTile(
                selected: true,
                leading: Icon(Icons.event, color: Colors.cyan,size: 30.0),
                title: Text("News", 
               
                style: TextStyle(color: Colors.black,fontWeight: FontWeight.w500, fontSize: 18),
                ),
                onTap: () => widget.onTap(context, 0),
                trailing:
               Icon(Icons.keyboard_arrow_right, color: Colors.cyan, size: 30.0),
                
              ),
              ListTile(
                leading:  Container(
                height: 20,        
                width: 40,    
                
               child: new Stack(
          
                children: <Widget>[
                  
                Icon(Icons.announcement,color: Colors.cyan, size: 25.0),
                counter != 0 ? new Positioned(
              //      right: 11,
                    left:22,
                    top: 0,
                 child: new Container(
                      padding: EdgeInsets.all(2),
                      decoration: new BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      constraints: BoxConstraints(
                        minWidth: 14,
                        minHeight: 14,
                      ),
                      child: Text(
                        '$counter',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 8,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    )
                  ) : new Container()
                ],
            
                ),  
                ),
          //   Icon(Icons.notifications,color: Colors.cyan, size: 30.0),
              
              title: new GestureDetector(
                onTap: () => {
                  widget.onTap(context, 3),
                  sendData(),
                  setState(() {
                      counter = 0;
                    }),
                },
                child: Text("Announcements",
                style: TextStyle(color: Colors.black,fontWeight: FontWeight.w500, fontSize: 18),
                ),
              ),
              trailing:
               Icon(Icons.keyboard_arrow_right, color: Colors.cyan, size: 30.0),
              ),
              ListTile(
                leading: Icon(Icons.place,color: Colors.cyan, size: 30.0),
                title: Text("Stations",
                style: TextStyle(color: Colors.black,fontWeight: FontWeight.w500, fontSize: 18),
                ),
                onTap: () => widget.onTap(context, 1),
                 trailing:
               Icon(Icons.keyboard_arrow_right, color: Colors.cyan, size: 30.0),
              ),
              
                ListTile(
                leading:  Container(
                height: 20,        
                width: 40,    
                
               child: new Stack(
          
                children: <Widget>[
                  
                Icon(Icons.notifications_active,color: Colors.cyan, size: 28.0),
                asd != 0 ? new Positioned(
              //      right: 11,
                    left:22,
                    top: 0,
                 child: new Container(
                      padding: EdgeInsets.all(2),
                      decoration: new BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      constraints: BoxConstraints(
                        minWidth: 14,
                        minHeight: 14,
                      ),
                      child: Text(
                        '$asd',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 8,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    )
                  ) : new Container()
                ],
            
                ),  
                ),
       
              title: new GestureDetector(
                onTap: () => {
                  widget.onTap(context, 4),
                  sendData2(),
                  setState(() {
                      counter = 0;
                    }),
               
                },
                child: Text("Notifications",
                style: TextStyle(color: Colors.black,fontWeight: FontWeight.w500, fontSize: 18),
                ),
              ),
                  trailing:
               Icon(Icons.keyboard_arrow_right, color: Colors.cyan, size: 30.0),
         
              ),
      
            ],
          ),
        ),
      ),
    );
         
     
     
  }

}


