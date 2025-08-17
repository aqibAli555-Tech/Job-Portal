<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
    </head>
    <style>
        *{
    margin: 0;
    padding: 0;
    font-family: 'poppins', sans-serif;
    box-sizing: border-box;

}
.container{
    width: 100vw;
    height: 100vh;
    background-image: url(https://i.postimg.cc/bvVb53Lq/background.png);
    background-position: center;
    background-size: cover;
    padding: 0 8%;

}
.logo{
    width: 120px;
    padding: 20px 0;
    cursor: pointer;

}
.content{
    top: 50%;
    position: absolute;
    transform: translateY(-50%);
    color: #fff;

}
.content h1{
    font-size: 64px;
    font-weight: 600;
}
.content h1 span{
    color:#ff3753;
}
.content button{
    background: transparent;
    border: 2px solid #fff;
    outline: none;
    padding: 12px 25px;
    color: #fff;
    display: flex;
    align-items: center;
    margin-top: 30px;
    cursor: pointer;

}
.content button img{
    width: 15px;
    margin-left: 10px;
}
.launch-time{
    display: flex;
}
.launch-time div{
    flex-basis: 200px;

}
.launch-time div p{
    font-size: 60px;
    margin-bottom: -4px;

}
.rocket{
    width: 250px;
    position: absolute;
    right: 10%;
    bottom: 0;
    animation: rocket 4s linear infinite;

}
@keyframes rocket{
    0%{
        bottom: 0;
        opacity: 0;
    }
    100%{
        bottom: 105%;
        opacity: 1;
    }
}
    </style>
    <body>
        <div class="container">
         
            <div class="content">
                <p>Website Is Under Maintenance</p>
                <h1>We're <span>Launching</span> Soon</h1>
                <div class="launch-time">
                    <div>
                        <p id="days">00</p>
                        <span>Days</span>
                    </div>
                    <div>
                        <p id="hours">00</p>
                        <span>Hours</span>
                    </div>
                    <div>
                        <p id="minutes">00</p>
                        <span>Minutes</span>
                    </div>
                    <div>
                        <p id="seconds">00</p>
                        <span>Seconds</span>
                    </div>
                </div>
             
            </div>
            <img src="https://i.postimg.cc/PfwZ6bDk/rocket.png" class="rocket">
        </div>
    </body>
</html>
<script>
    /*the complete project is in the following link:
https://github.com/vkive/comming-sooon.git
Follow me on Codepen
*/    
var tomorrow = new Date();
var countDownDate =new Date(tomorrow.setDate(tomorrow.getDate()+2)).getTime();
            var x= setInterval(function(){
                
                var now = new Date().getTime();
                var distance = countDownDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("days").innerHTML = days;
                document.getElementById("hours").innerHTML = hours;
                document.getElementById("minutes").innerHTML = minutes;
                document.getElementById("seconds").innerHTML = seconds;

                if(distance < 0){
                    clearInterval(x);
                    document.getElementById("days").innerHTML = "00";
                    document.getElementById("hours").innerHTML = "00";
                    document.getElementById("minutes").innerHTML = "00";
                    document.getElementById("seconds").innerHTML = "00";
                    
                }
                
            },1000);
</script>