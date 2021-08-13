import 'package:flutter/material.dart';
import 'reportpage.dart';
import './news/article.dart';
import './news/news.dart';
import './news/article_screen.dart';
import './news/news_tile.dart';
import 'package:connectivity/connectivity.dart';

class HomeScreen extends StatefulWidget {
  
  @override
  _HomeScreenState createState() => _HomeScreenState();
}


class _HomeScreenState extends State<HomeScreen> {


 
  List<Article> articles = List<Article>();
  List<Article> filteredArticles = List<Article>();
  bool _isLoading;
  
  /*check() async{
   var connectivityResult = await (Connectivity().checkConnectivity());
    if (connectivityResult == ConnectivityResult.mobile) {
    
     print("data ni");
     getNews();
    
    } else if (connectivityResult == ConnectivityResult.wifi) {
     
     print("wifi ni");
     getNews();
    
    }
    else if ( connectivityResult == ConnectivityResult.none) {
      print("walay internet");

    }
  }*/

  @override
  void initState() {
    super.initState();
    getNews();
   _isLoading = true;
    // check();
  }

   
Future<void> getNews() async {
    News news = News();
    await news.getNews();
    articles = news.news;
    setState(() {
      _isLoading = false;
      filteredArticles = articles;
    });
    


  }

   

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      
    body: _isLoading
          ? Center(
              child: CircularProgressIndicator(),
            )
          : Container(
              child: SingleChildScrollView(
                child: Column(
            
                  children: <Widget>[
                    new Padding(padding: new EdgeInsets.all(0.0),),
                   
                    Container(
                      margin: const EdgeInsets.symmetric(
                          vertical: 20, horizontal: 12),
        
                      child: TextField(
                        onChanged: (article) {
                          setState(() {
                            filteredArticles = articles
                                .where(
                                  (element) =>
                                      element.title.toLowerCase().contains(
                                            article.toLowerCase(),
                                          ),
                                )
                                .toList();
                          });
                        },
                        decoration: InputDecoration(
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(
                              color: Colors.black,
                            ),
                            borderRadius: BorderRadius.circular(32),
                          ),
                          prefixIcon: Icon(
                            Icons.search,
                            color: Colors.black,
                          ),
                          border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(32),
                              borderSide: BorderSide(color: Colors.black)),
                        ),
                      ),
                    ),

                   

                    

                    
                
              
                    Container(
                      child: ListView.builder(
                          shrinkWrap: true,
                          physics: ClampingScrollPhysics(),
                          itemCount: filteredArticles.length,
                          itemBuilder: (context, i) {
                            return Padding(
                              padding: const EdgeInsets.all(12),
                              child: GestureDetector(
                                onTap: () => Navigator.of(context).push(
                                  MaterialPageRoute(
                                    builder: (context) => ArticleScreen(
                                      article: filteredArticles[i],
                                    ),
                                  ),
                                ),
                                child: NewsTile(
                                  imgUrl: filteredArticles[i].imgUrl ?? "",
                                  title: filteredArticles[i].title ?? "",
                                  description:
                                      filteredArticles[i].description ?? "",
                                ),
                              ),
                            );
                          }),
                    ),
                    
                  ],
                ),
              ),
            ),

           floatingActionButton: Container(
              height: 100.0,
              width: 80.0,
              child: FittedBox(
                child: FloatingActionButton(
                 heroTag: null,
                  backgroundColor: Color(0xFF262AAA),
                  child:Icon(Icons.chat, color: Colors.white),
                  onPressed: () {
                    Navigator.push(context, new MaterialPageRoute(
                    builder: (context) => ReportPage()
                    ));
                }),
              ),
            ),
            

        
    );
  }
}
