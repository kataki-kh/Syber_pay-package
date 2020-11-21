<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <style>
         #container2 {
width: 960px;
position: relative;
margin: auto;
line-height: 1.4em;
text-align: center;
    font-size: larger;
    color:red;

}
.message{
text-align: center;
    font-size: larger;
    color:red;
}
.video-div{
	width:100%;
	
}
.video{
	width:100%;
	display: block;
}


@media only screen and (max-width: 479px){
    #container2 {
    width: 90%;
        position: fixed;
    text-align: center;
    font-size: larger;
    
    color:red;
    
    }
}
        </style>
    </head>
    <body>

      <div id="container2">
      <div class="message">	{{$message}}</div>
      

 <video class="video" controls autoplay="true" loop>
 	
  <source src='{{$url}}' type="video/mp4">
  
 
</video>
    
</div>
       
    </body>
</html>
