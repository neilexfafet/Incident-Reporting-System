import 'package:flutter/material.dart';
import 'article.dart';

//import 'app_bar.dart';


class ArticleScreen extends StatelessWidget {
  final Article article;

  const ArticleScreen({Key key, @required this.article}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
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
      body: Container(
        child: SingleChildScrollView(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Align(
              alignment: Alignment.center,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: <Widget>[
                  ClipRRect(
                    borderRadius: BorderRadius.circular(42),
                    child: Image.network(
                      article.imgUrl,
                      fit: BoxFit.cover,
                      height: 250,
                    ),
                  ),
                  SizedBox(height: 15),
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        Text(
                          '${article.publishedAt}',
                          style: TextStyle(
                              color: Colors.black87,
                              fontWeight: FontWeight.w400),
                        ),
                        SizedBox(height: 20),
                        Text(
                          '${article.title}',
                          textAlign: TextAlign.justify,
                          style: TextStyle(
                              fontSize: 22, fontWeight: FontWeight.w800),
                        ),
                        SizedBox(height: 20),
                        Text(
                          '${article.author}',
                          style: TextStyle(
                              color: Colors.blue,
                              fontSize: 17,
                              fontWeight: FontWeight.w500),
                        ),
                        SizedBox(height: 20),
                        Text(
                          '${article.description}',
                          textAlign: TextAlign.justify,
                          style: TextStyle(
                              fontSize: 18, fontWeight: FontWeight.w500),
                        ),
                        SizedBox(height: 32),
                     
                    
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
