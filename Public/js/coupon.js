/**
 * Created by AA on 2016/10/15.
 */


$(function(){
   function run(){
       $('.main').append('<ul></ul>');
       $.ajax({
           url:"data/products.json",
           type:"get",
           dataType:"json",
           success:function(data){
               var html="<li>" +
                   "<div class='pic'>" +
                   "<img src='"+data.img+"'>" +
                   "</div>" +
                   "<h3>"+data.title+"</h3>"+
                   "<div class='text'>" +
                   "<h5>限时秒杀价：" +
                   "<span><b>￥</b>"+data.price+"</span>" +
                   "</h5>" +
                   "</div>"+
                   "</li>";
               $('ul').html(html);
           },
           error:function(xml,status){
               $('ul').html(xml.responseText);
               console.log(status)
           }
       });
   }
    run();
    //$('.main').append('<ul></ul>');
    //var html="<li>" +
    //                "<div class='pic'>" +
    //                    "<img src=''>" +
    //                "</div>" +
    //                "<h3></h3>"+
    //                "<div class='text'>" +
    //                    "<h5>限时秒杀价：" +
    //                        "<span><b>￥</b></span>" +
    //                    "</h5>" +
    //                "</div>"+
    //         "</li>";
    //
    //$('ul').html(html);

})